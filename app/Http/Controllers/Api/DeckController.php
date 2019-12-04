<?php

namespace App\Http\Controllers\Api;

use App\Deck;
use App\Http\Controllers\Controller;
use App\Http\Resources\DeckIndexResource;
use App\Http\Resources\DeckShowResource;

class DeckController extends Controller
{
    public function index()
    {
        return DeckIndexResource::collection(
            Deck::withCount(['cards'])->get()
        );
    }

    public function show($id)
    {
        return new DeckShowResource(Deck::findOrFail($id));
    }
}
