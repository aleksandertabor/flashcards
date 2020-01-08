<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Deck extends Model
{
    use Sluggable, Searchable;

    public const PUBLIC_VISIBILITY = 'public';
    public const UNLISTED_VISIBILITY = 'unlisted';
    public const PRIVATE_VISIBILITY = 'private';

    public static function visibilities(): array
    {
        return [self::PUBLIC_VISIBILITY, self::UNLISTED_VISIBILITY, self::PRIVATE_VISIBILITY];
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('visibility', self::PUBLIC_VISIBILITY);
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function cards()
    {
        return $this->hasMany('App\Card');
    }

    public function sourceLanguage()
    {
        return $this->hasOne('App\Language', 'id', 'lang_source_id');
    }

    public function targetLanguage()
    {
        return $this->hasOne('App\Language', 'id', 'lang_target_id');
    }
}
