<?php

namespace App\Providers\Twilio;

use Services_Twilio;
use Lookups_Services_Twilio;

class Twilio
{
    /**
     * @var string
     */
    public $sid;

    /**
     * @var string
     */
    public $token;

    /**
     * @var string
     * Default Twilio phone number
     */
    public $from;

    /**
     * @var Services_Twilio
     * Twilio client instance
     */
    public $client;

    /**
     * @var Lookups_Services_Twilio
     * Twilio lookups instance
     */
    public $lookups;


    /**
     * Twilio constructor.
     * @param string $sid
     * @param string $token
     * @param string $from
     */
    public function __construct($sid, $token, $from)
    {
        $this->sid = $sid;
        $this->token = $token;
        $this->from = $from;
    }

    /**
     * Makes a call to target phone
     * @param string $target
     * @param string|null $twimlUrl
     * @return \Services_Twilio_Rest_Call
     */
    public function makeCall($target, $twimlUrl = null)
    {
        if(!$twimlUrl) {
            // test url to read default TwiML when a call connects (hold music)
            $twimlUrl = 'http://twimlets.com/holdmusic?Bucket=com.twilio.music.ambient';
        }
        $call = $this->getClient()->account->calls->create(
            $this->from,
            $target,
            $twimlUrl
        );

        return $call;
    }

    /**
     * Sends a SMS to target phone
     * @param string $target
     * @param string $text
     * @return \Services_Twilio_Rest_Message
     */
    public function sendSMS($target, $text)
    {
        $message = $this->getClient()->account->messages->sendMessage(
            $this->from,
            $target,
            $text
        );

        return $message;
    }

    /**
     * Returns Twilio's client instance
     * @return Services_Twilio
     */
    public function getClient()
    {
        if($this->client === null) {
            $this->client = new Services_Twilio($this->sid, $this->token);
        }
        return $this->client;
    }

    /**
     * Returns Twilio's client instance
     * @return Lookups_Services_Twilio
     */
    public function getLookups()
    {
        if($this->lookups === null) {
            $this->lookups = new Lookups_Services_Twilio($this->sid, $this->token);
        }
        return $this->lookups;
    }

    /**
     * Returns list of Twilio available countries
     * @return string
     */
    public function getAvailableCountries()
    {
        $client = $this->getClient();
        $uri = '/'. $client->getVersion() . '/Accounts/' . $this->sid . '/AvailablePhoneNumbers.json';

        try {
            $numbers = $client->retrieveData($uri);
            return $numbers->countries;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


}