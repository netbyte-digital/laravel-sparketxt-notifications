<?php

namespace NotificationChannels\Sparketxt;

use NotificationChannels\Sparketxt\Exceptions\CouldNotSendNotification;
use Illuminate\Notifications\Notification;

class SparketxtChannel
{

    /**
     * @var SparketxtClient
     */
    private $client;

    public function __construct(SparketxtClient $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\Sparketxt\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        /* Confirm the toSparketxt method exists before continuing */
        if (!method_exists($notification, 'toSparketxt')) {
            throw CouldNotSendNotification::methodDoesNotExist();
        }

        $message = $notification->toSparketxt($notifiable, $notification);

        /* Check notification uses correct class for this API */
        if (!$message instanceof SparketxtMessage) {
            throw CouldNotSendNotification::incorrectMessageClass();
        }

        $this->client->send(
            $message->from,
            $notifiable->routeNotificationFor('sparketxt'),
            $message->content
        );
    }
}
