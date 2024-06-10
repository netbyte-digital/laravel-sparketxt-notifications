<?php

namespace NotificationChannels\Sparketxt\Exceptions;

class SparketxtNotification extends \Exception
{
    public static function serviceUnknownResponse()
    {
        return new static("Unknown resposne from Spark eTxt API");
    }

    public static function badRequestResponse()
    {
        return new static("Request was invalid and failed to parse correctly.");
    }

    public static function authorisationFailedResponse()
    {
        return new static("Invalid authentication credentials");
    }
}
