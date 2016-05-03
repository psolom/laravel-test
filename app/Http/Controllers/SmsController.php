<?php namespace App\Http\Controllers;

use App\SmsLog;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;
use Twilio;

class SmsController extends BaseController
{
    /**
     * Handle incoming call
     * @return View
     */
    public function incoming()
    {
        $targetNumber = config('twilio.config')['target'];
        $messageBody = $_REQUEST['Body'];

        $model = new SmsLog();
        $model->message_sid = $_REQUEST['SmsMessageSid'];
        $model->phone_from = $_REQUEST['From'];
        $model->phone_to = $_REQUEST['To'];
        $model->phone_target = $targetNumber;
        $model->body = $messageBody;
        $model->data = serialize($_REQUEST);
        $model->save();

        // transferring SMS from Twilio phone number to target one
        Twilio::from($_REQUEST['To'])->sendSMS($targetNumber, $messageBody);
    }

}