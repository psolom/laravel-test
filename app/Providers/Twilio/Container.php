<?php

namespace App\Providers\Twilio;

class Container
{
    public $config;

    /**
     * Container constructor.
     * @param array $settings
     */
    public function __construct($settings)
    {
        $this->config = $settings;
    }

    /**
     * @return \Services_Twilio
     */
    public function client()
    {
        return $this->init()->getClient();
    }

    /**
     * @return \Lookups_Services_Twilio
     */
    public function lookups()
    {
        return $this->init()->getLookups();
    }

    /**
     * @return Twilio
     */
    public function init()
    {
        return new Twilio(
            $this->config['sid'],
            $this->config['token'],
            $this->config['from']
        );
    }

    /**
     * Overrides default "from" phone number
     * @param string $phone
     * @return Twilio
     */
    public function from($phone)
    {
        $this->config['from'] = $phone;
        return $this->init();
    }
}