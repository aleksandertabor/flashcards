<?php

namespace App;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class User extends Authenticatable implements HasMedia
{
    use Sluggable, HasMediaTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        static::updating(function ($user) {
            if ($user->isDirty('password')) {
                $user->password = bcrypt($user->password);
            }
        });

        parent::boot();
    }

    public function sluggable()
    {
        return [
            'username' => [
                'unique' => true,
                'separator' => '',
                'onUpdate' => false,
            ],
        ];
    }

    public function getRouteKeyName()
    {
        return 'username';
    }

    public function registerMediaCollections()
    {
        $this
            ->addMediaCollection('main')
            ->acceptsMimeTypes(['image/png', 'image/jpeg', 'image/webp'])
            ->singleFile();
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->diffForHumans();
    }

    public function getImageAttribute()
    {
        $url = '';

        $media = $this->media->first();

        if ($media) {
            $url = $media->getFullUrl();
        }

        return $url;
    }

    public function setUsernameAttribute($value)
    {
        $this->attributes['username'] = strtolower($value);
    }

    public function scopeNewest($query)
    {
        return $query->latest();
    }

    public function cards()
    {
        return $this->hasManyThrough('App\Card', 'App\Deck');
    }

    public function publishedCards()
    {
        return $this->cards()->published();
    }

    public function decks()
    {
        return $this->hasMany('App\Deck')->withoutGlobalScopes();
    }

    public function publishedDecks()
    {
        return $this->hasMany('App\Deck');
    }
}
