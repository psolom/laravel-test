<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\CallLog;
use Twilio;

class Notify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS to user';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $notificationsSent = 0;
        $sql = "SELECT l.*, TIMESTAMPDIFF(MINUTE, created_at, time_end) as duration
            FROM call_log as l
            JOIN (
                SELECT phone_from, MIN(id) AS id
                FROM call_log GROUP BY phone_from
            ) AS t USING (id)
            WHERE l.sms_sent = :state
              AND TIMESTAMPDIFF(MINUTE, created_at, NOW()) >= :timeout
            ";

        $results = DB::select($sql, ['state' => 0, 'timeout' => 18]);
        foreach($results as $item) {
            try {
                // sending SMS message
                $message = $this->getMessage($item->duration);
                Twilio::from($item->phone_to)->sendSMS($item->phone_from, $message);

                // update sms notification state
                $model = CallLog::find($item->id);
                $model->sms_sent = 1;
                $model->save();

                $notificationsSent++;
            } catch(\Exception $e) {
                Log::error($e->getMessage());
            }
        }

        $this->comment(PHP_EOL . "Notifications sent: {$notificationsSent}" . PHP_EOL);
    }

    /**
     * Retrieve message for SMS message
     * @param int $duration
     * @return string
     */
    protected function getMessage($duration)
    {
        if($duration >= 2) {
            return 'Thank you for the conversation we had on the phone just now.';
        } else {
            return 'Thank you for your call.';
        }
    }
}
