<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $call_sid
 * @property string $phone_from
 * @property string $phone_to
 * @property string $phone_target
 * @property string $time_end
 * @property string $data
 */
class CallLog extends Model
{
    protected $table = 'call_log';
}