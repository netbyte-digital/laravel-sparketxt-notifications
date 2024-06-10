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

    public function send($from,$to,$message)
    {
        ray($this);
        ray($to . " : ".$message);
        $send = Http::dd()->withHeaders([
            'Authorization' => 'Basic '.base64_encode($this->apiKey.':'.$this->apiSecret)
        ])
            ->withBody(
            '{
                "messages": [
                    {
                        "content": "'.$message.'",
                        "destination_number": "'.$to.'",
                        "format": "SMS"
                    },
                ]
            }',
                "application/json")
            ->post($this->sendEndpoint);
        ray($send);
        if($send->badRequest()) {
            throw SparketxtNotification::badRequestResponse();
        }
        if($send->forbidden()) {
            throw SparketxtNotification::authorisationFailedResponse();
        }
        if($send->failed()) {
            throw SparketxtNotification::serviceUnknownResponse();
        }

        $response = json_decode($send,true);



    }
}
