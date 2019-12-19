<?php

namespace App\Http\Controllers\Api;

use App\Deck;
use App\Http\Controllers\Controller;
use App\Http\Resources\DeckEditResource;
use App\Http\Resources\DeckIndexResource;
use App\Http\Resources\DeckShowResource;

class DeckController extends Controller
{
    public function index()
    {
        return DeckIndexResource::collection(
            Deck::withCount(['cards'])->public()->get()
        );
    }

    public function show(Deck $deck)
    {
        return new DeckShowResource($deck->load('cards'));
    }

    public function edit()
    {
        //! only for logged in users
        return DeckEditResource::collection(
            Deck::withCount(['cards'])->get()
        );
    }

}
