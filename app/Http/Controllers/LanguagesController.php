<?php

namespace App\Http\Controllers;

use App\Api\GoogleTranslationApi;
use Illuminate\Http\Request;

class LanguagesController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $translator = new GoogleTranslationApi();

        $languages = $translator->languages();

        return response()->json(['success' => ['translation' => $languages]], 200);
    }
}
