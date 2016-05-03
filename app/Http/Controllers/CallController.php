<?php namespace App\Http\Controllers;

use App\CallLog;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;
use Twilio;
use Services_Twilio_Twiml;

class CallController extends BaseController
{
    /**
     * Handle incoming call
     * @param Request $request
     * @return View
     */
    public function incoming(Request $request)
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
    }

    /**
     * Handle after incoming call is completed
     * @param Request $request
     * @return View
     */
    public function completed(Request $request)
    {
        $model = CallLog::where('call_sid', '=', $_REQUEST['CallSid'])->first();
        if($model !== null) {
            $model->time_end = date('Y-m-d H:i:s');
            $model->save();
        }
    }

}