<?php

namespace NotificationChannels\Sparketxt;

use Illuminate\Support\Facades\Http;
use NotificationChannels\Sparketxt\Exceptions\SparketxtNotification;

class SparketxtClient
{

    protected string $sendEndpoint = 'https://api.etxtservice.co.nz/v1/messages';
    protected $apiKey;
    protected $apiSecret;
    protected $callbackUrl;
    protected bool $deliveryReport;

    public function __construct()
    {
        $this->apiKey = config('services.sparketxt.api_key');
        $this->apiSecret = config('services.sparketxt.api_secret');
        $this->callbackUrl = config('services.sparketxt.callback_url') ?: null;
        $this->deliveryReport = $this->callbackUrl != null;

        return $this;
    }

    public function send($to,$message)
    {
        $send = Http::withHeaders([
            'Authorization' => 'Basic '.base64_encode($this->apiKey.':'.$this->apiSecret)
        ])
            ->withBody('{
                "messages": [
                    {
                    "callback_url": "'.$this->callbackUrl.'",
                    "content": "'.$message.'",
                    "delivery_report": "'.$this->deliveryReport.'",
                    "destination_number": "'.$to.'",
                    "format": "SMS"
                    },
                ]
            }')
            ->post($this->sendEndpoint);

        if($send->badRequest()) {
            throw SparketxtNotification::badRequestResponse();
        }
        if($send->forbidden()) {
            throw SparketxtNotification::authorisationFailedResponse();
        }
        if($send->failed()) {
            ray($send);
            throw SparketxtNotification::serviceUnknownResponse();
        }

        $response = json_decode($send,true);



    }
}
