<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, Sluggable;

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

    public function findForPassport($username)
    {
        return $this->where('username', $username)->first() ?? $this->where('email', $username)->first();
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

    public function setUsernameAttribute($value)
    {
        $this->attributes['username'] = strtolower($value);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function cards()
    {
        return $this->hasManyThrough('App\Card', 'App\Deck');
    }

    public function decks()
    {
        return $this->hasMany('App\Deck');
    }

    public function userDecks()
    {
        return $this->hasMany('App\Deck')->withoutGlobalScopes();
    }

    public function publishedCards()
    {
        return $this->cards()->published();
    }
}
