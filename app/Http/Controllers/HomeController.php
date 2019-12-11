<?php

namespace App\Http\Controllers;

use App\Api\GoogleTranslationApi;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $translator = new GoogleTranslationApi();
        $source = 'en';
        $target = 'pl';
        $word = 'Wlazł kotek na płotek.';
        $languages = [
            'source' => $source,
            'target' => $target,
        ];
        dd($translator->detect($word));
        return 'test';
    }
}
