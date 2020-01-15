<?php

namespace App\Http\Controllers;

use App\Facades\ImageFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WikipediaController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'phrase' => ['required', 'string'],
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 401);
        // }

        $imageWikipedia = ImageFacade::random('cat', 'pl');

        return response()->json(['success' => ['images' => $imageWikipedia]], 200);
    }
}
