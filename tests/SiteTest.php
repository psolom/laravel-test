<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Providers\Twilio\Facade as Twilio;

class SiteTest extends TestCase
{
    use WithoutMiddleware;

    public $countriesPurchased = [];
    public $countriesAvailable = [];

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        $client = Twilio::client();
        $lookups = Twilio::lookups();
        $numbers = $client->account->incoming_phone_numbers;
        foreach($numbers as $number) {
            $lookup = $lookups->phone_numbers->get($number->phone_number);
            $this->countriesPurchased[] = $lookup->country_code;
        }

        $countries = Twilio::init()->getAvailableCountries();
        foreach($countries as $item) {
            $this->countriesAvailable[] = $item->country_code;
        }
    }

    /**
     * Main page
     */
    public function testMainPage()
    {
        $this->visit('/')->see('Contact us');
    }

    /**
     * If any phone Twilio's number is purchased
     */
    public function testIfAnyPhonePurchased()
    {
        $this->assertNotEmpty($this->countriesPurchased);
    }

    /**
     * Main path with cookie of a country where phone number was purchased
     * Cookie encryption is turned off for testing environment
     * @see App\Http\Kernel::__construct
     */
    public function testRealCountryCookie()
    {
        // test with a purchased phone number in the specified country
        $this->makeRequest('GET', '/', [], [
            'country' => $this->countriesPurchased[0],
        ])->see('Call this number to contact us');
    }

    /**
     * Main path with cookie of a country where there is no phone number
     * Cookie encryption is turned off for testing environment
     * @see App\Http\Kernel::__construct
     */
    public function testWrongCountryCookie()
    {
        $diff = array_diff($this->countriesAvailable, $this->countriesPurchased);

        // test with a absent phone number in the specified country
        $this->makeRequest('GET', '/', [], [
            'country' => $diff[0],
        ])->see('An error has occurred');
    }
}
