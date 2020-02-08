<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class DeckPublished extends Notification
{
    use Queueable;

    private $deck;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($deck)
    {
        $this->deck = $deck;
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
        return (new WebPushMessage)
        ->title('🃏 New Deck was published!')
        ->dir('ltr')
        ->lang('en-GB')
        ->icon('/images/icons/icon-96x96.png')
        ->image($this->deck->getFirstMediaUrl('main'))
        ->body("User {$this->deck->user->username} has published deck - {$this->deck->title}")
        ->action('🃏 View deck', 'explore')
        ->action('⌚ Maybe later', 'close')
        ->requireInteraction(true)
        ->badge('/images/icons/icon-96x96.png')
        ->data(['action_url' => 'deck/'.$this->deck->slug]);
    }
}
