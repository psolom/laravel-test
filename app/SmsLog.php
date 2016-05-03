<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $message_sid
 * @property string $phone_from
 * @property string $phone_to
 * @property string $phone_target
 * @property string $body
 * @property string $data
 */
class SmsLog extends Model
{
    protected $table = 'sms_log';
}