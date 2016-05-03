<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCallLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('call_sid')->index();
            $table->string('phone_from');
            $table->string('phone_to');
            $table->string('phone_target');
            $table->dateTime('time_end')->nullable()->default(null);
            $table->tinyInteger('sms_sent')->default('0');
            $table->text('data');
            $table->timestamps();
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('call_log');
    }
}
