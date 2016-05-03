<?php

return [
    /**
     * Twilio account SID
     */
    'sid' => env('TWILIO_SID', ''),
    /**
     * Twilio account token
     */
    'token' => env('TWILIO_TOKEN', ''),
    /**
     * Default phone for call/SMS from Twilio account
     */
    'from' => env('TWILIO_FROM', ''),
    /**
     * Your target incoming phone number to get calls
     */
    'target' => env('TWILIO_TARGET', ''),
];