<?php

namespace NotificationChannels\Sparketxt\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function methodDoesNotExist()
    {
        return new static('The toSparketxt method does not exist in your notification class.');
    }

    public static function incorrectMessageClass()
    {
        return new static('Your notification is incorrectly formatted or needs to use an instance of the SparketxtMessage class.');
    }
}
