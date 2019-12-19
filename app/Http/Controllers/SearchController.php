<?php

namespace App\Http\Controllers;

use App\Deck;
use App\Http\Resources\DeckIndexResource;

class SearchController extends Controller
{
    public function index()
    {
        $data = request()->validate([
            'query' => 'required',
        ]);

        // $decks = Deck::search($data['query'])->get();
        $deck = Deck::withCount(['cards'])->public();
        $decks = Deck::search($data['query'])->constrain($deck)->get();

        return DeckIndexResource::collection(
            $decks
        );
    }
}
