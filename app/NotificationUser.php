<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\WebPush\HasPushSubscriptions;

class NotificationUser extends Model
{
    use Notifiable, HasPushSubscriptions;
}
