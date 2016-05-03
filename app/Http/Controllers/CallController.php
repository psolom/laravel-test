<?php namespace App\Http\Controllers;

use App\CallLog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;
use Twilio;
use Services_Twilio_Twiml;

class CallController extends BaseController
{
    /**
     * Handle incoming call
     * @return View
     */
    public function incoming()
    {
        header("content-type: text/xml");
        $targetNumber = config('twilio.config')['target'];

        $model = new CallLog();
        $model->call_sid = $_REQUEST['CallSid'];
        $model->phone_from = $_REQUEST['From'];
        $model->phone_to = $_REQUEST['To'];
        $model->phone_target = $targetNumber;
        $model->data = serialize($_REQUEST);
        $model->save();

        // performing TwiML response to make a call to the target number
        $response = new Services_Twilio_Twiml();
        $response->dial($targetNumber);
        print $response;
        die();
    }

    /**
     * Handle after incoming call is completed
     * @return View
     */
    public function completed()
    {
        $model = CallLog::where('call_sid', '=', $_REQUEST['CallSid'])->first();
        if($model !== null) {
            $model->time_end = date('Y-m-d H:i:s');
            $model->save();
        }
    }

}