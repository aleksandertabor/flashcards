<?php

namespace App\Http\Resources;

use App\Deck;
use Illuminate\Http\Resources\Json\JsonResource;

class DeckEditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'lang_source' => $this->lang_source,
            'lang_target' => $this->lang_target,
            'slug' => $this->slug,
            'visibility' => $this->visibility,
            'visibilitity_options' => Deck::visibilities(),
            'cards_count' => $this->cards_count,
        ];

    }
}
