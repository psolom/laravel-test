<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Twilio;

class SiteController extends BaseController
{
    /**
     * Main page
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $cookie = Cookie::get('country');

        if($cookie) {
            $content = $this->getPhone($cookie);
        } else {
            $content = View::make('site._countries_form');
        }

        return view('site.index', [
            'content' => $content,
            'countries' => json_encode($cookie ? null : $this->getCountriesList()),
        ]);
    }

    /**
     * Looking for phone number in selected country
     * @return mixed
     */
    public function phone()
    {
        $countryCode = Input::get('countryCode');
        if($countryCode) {
            // validate country code
            $availableCountries = $this->getCountriesList();
            if(isset($availableCountries[$countryCode])) {
                Cookie::queue('country', $countryCode, 60*60*24*365);
                return $this->getPhone($countryCode);
            }
        }
        return View::make('site._error_country');
    }

    /**
     * Drop cookie with selected country
     * @param Request $request
     * @return $this
     */
    public function change(Request $request)
    {
        return redirect('/site/index')->withCookie(Cookie::forget('country'));
    }

    /**
     * Retrieves existing phone number or purchases new one
     * @param $countryCode
     * @return mixed
     */
    private function getPhone($countryCode)
    {
        $phoneNumber = false;
        $client = Twilio::client();
        $lookups = Twilio::lookups();

        // looking for existent phone number
        $numbers = $client->account->incoming_phone_numbers;
        foreach($numbers as $number) {
            $lookup = $lookups->phone_numbers->get($number->phone_number);
            if($lookup->country_code === $countryCode) {
                $phoneNumber = $number->phone_number;
                break;
            }
        }

        // buy new number if not found
        if($phoneNumber === false) {
            $availableNumbers = $client->account->available_phone_numbers->getList($countryCode, 'Local', [
                //'SmsEnabled' => 'True',
                'VoiceEnabled' => 'True',
            ]);
            try {
                $first = $availableNumbers->available_phone_numbers[0]->phone_number;
                $newNumber = $client->account->incoming_phone_numbers->create([
                    'PhoneNumber' => $first,
                    'VoiceUrl' => url('call/incoming'),
                    'StatusCallback' => url('call/completed'),
                    'SmsUrl' => url('sms/incoming'),
                    'VoiceMethod' => 'POST',
                    'StatusCallbackMethod' => 'POST',
                    'SmsMethod' => 'POST',
                ]);
//                $newNumber->update(array(
//                    "VoiceUrl" => url('call/incoming'),
//                    "SmsUrl" => url('sms/incoming'),
//                    "VoiceMethod" => 'POST',
//                    "SmsMethod" => 'POST',
//                ));
                $phoneNumber = $newNumber->phone_number;
            } catch(\Exception $e) {
                Log::error($e->getMessage());
                return View::make('site._error');
            }
        }

        return View::make('site._phone', [
            'number' => $phoneNumber,
        ]);
    }

    /**
     * Retrieves a list of Twilio's available countries
     * @return array
     */
    private function getCountriesList()
    {
        $list = [];
        $countries = Twilio::init()->getAvailableCountries();

        foreach($countries as $item) {
            $list[$item->country_code] = $item->country;
        }
        ksort($list);
        return $list;
    }
}