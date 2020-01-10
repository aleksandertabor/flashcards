<?php

namespace App;

use App\Deck;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Card extends Model implements HasMedia
{
    use HasMediaTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['deck_id', 'question', 'answer', 'example_question', 'example_answer'];

    public function registerMediaCollections()
    {
        $this
            ->addMediaCollection('main')
            ->singleFile();
    }

    public function deck()
    {
        return $this->belongsTo('App\Card');
    }

    public function scopePublished($query)
    {
        $query->whereHas('deck', function (Builder $query) {
            $query->where('visibility', "=", key(Deck::PUBLIC_VISIBILITY));
        });
    }
}
