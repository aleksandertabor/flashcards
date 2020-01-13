<?php

namespace App\Http\Controllers;

use App\Facades\TranslationFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DetectionController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phrase' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }

        $detection = TranslationFacade::detect($request->phrase);

        return response()->json(['success' => ['detection' => $detection]], 200);
    }
}
