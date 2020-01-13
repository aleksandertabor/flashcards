<?php

namespace App;

use App\Scopes\PublishedScope;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Deck extends Model implements HasMedia
{
    use Sluggable, Searchable, HasMediaTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'title', 'title', 'description', 'lang_source_id', 'lang_target_id', 'visibility', 'slug'];
    protected $with = ['media'];
    public const PUBLIC_VISIBILITY = ['public' => 'anybody can see'];
    public const UNLISTED_VISIBILITY = ['unlisted' => 'only with link'];
    public const PRIVATE_VISIBILITY = ['private' => 'only you'];

    public static function visibilities() : array
    {
        return [self::PUBLIC_VISIBILITY, self::UNLISTED_VISIBILITY, self::PRIVATE_VISIBILITY];
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new PublishedScope);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    public function registerMediaCollections()
    {
        $this
            ->addMediaCollection('main')
            ->singleFile();
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
        return $query->where('visibility', '=', key(self::PUBLIC_VISIBILITY));
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
