<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class PushRegistered extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage())
            ->title('Thank you!')
            ->icon('/images/icons/icon-96x96.png')
            ->body('We will inform you about new decks.')
            ->action('See all decks', 'explore')
            ->action('Not now', 'close')
            ->badge('/images/icons/icon-96x96.png')
            ->data(['action_url' => 'search']);
    }
}
