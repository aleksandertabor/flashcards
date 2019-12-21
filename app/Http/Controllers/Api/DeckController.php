<?php

namespace App\Http\Controllers\Api;

use App\Deck;
use App\Http\Controllers\Controller;
use App\Http\Resources\DeckCollection;
use App\Http\Resources\DeckEditResource;
use App\Http\Resources\DeckShowResource;
use Barryvdh\Debugbar\Facade as Debugbar;
use Illuminate\Http\Request;

class DeckController extends Controller
{
    public function index(Request $request)
    {

        //! filters to do
        Debugbar::info($request);
        Debugbar::info($request->page);
        Debugbar::info($request->decksType);

        $decks = Deck::withCount(['cards']);

        return new DeckCollection(
            $decks->public()->paginate(12)
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
