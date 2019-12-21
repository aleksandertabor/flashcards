<?php

namespace App\Http\Controllers;

use App\Deck;
use App\Http\Resources\DeckCollection;
use Barryvdh\Debugbar\Facade as Debugbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchDeckController extends Controller
{
    public function index(Request $request)
    {
        Debugbar::info($request);
        Debugbar::info($request->page);
        Debugbar::info($request->decksType);

        $decks = Deck::withCount(['cards'])->public();

        Debugbar::info($decks);

        if ($request->filled('query')) {
            Debugbar::info('jest query');
            $query = $request->query('query');
            $constraints = $decks;
            $decks = Deck::search($query)->constrain($constraints);
        } else {
            $query = '';
        }

        if ($request->filled('decks_type')) {
            $decksType = $request->query('decks_type');
            if ($decksType === 'latest') {
                $decks->orderBy('created_at', 'desc');

            }
            if ($decksType === 'oldest') {
                $decks->orderBy('created_at', 'asc');
            }

            if ($decksType === 'random') {
                $decks->orderBy(DB::raw('RAND()'));
            }

            if ($decksType === 'cards') {
                $decks->orderBy('cards_count', 'desc');
            }

        }

        Debugbar::info($decks);

        // $decks = Deck::constrain($decks);

        return new DeckCollection(
            $decks->paginate(12)->appends(
                [
                    'query' => $query,
                    'decks_type' => $decksType,
                ]
            )
        );
    }
}
