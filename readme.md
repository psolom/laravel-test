## Installation

1) Create and fill `.env` file.
1.1) Setup your MySQL server credentials 
1.2) Populate actual credentials of you Twilio account to string prefixed with TWILIO_

2) Execute `composer update` 
  
3) Execute `php artisan migrate` to create 2 MySQL log tables for calls and SMS

## Controllers 

app/Http/Controllers/SiteController - main controller to handle requests to the main page

app/Http/Controllers/CallController - handle Twilio's incoming calls

app/Http/Controllers/SmsController - handle Twilio's incoming sms

## Models

app/CallLog.php - calls log model

app/SmsLog.php - sms log model

## Twilio component

app/Providers/Twilio - folder, contains Twilio component files: main class, service provider and facade
    
## Commands

app/Console/Commands/Notify.php - console command to send SMS 18 minutes late after call end (cover bonus tasks)

## Tests

tests/SiteTest.php - covers general requests for SiteController
