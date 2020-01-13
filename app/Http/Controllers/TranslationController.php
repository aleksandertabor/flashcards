<?php

namespace App\Http\Controllers;

use App\Facades\TranslationFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TranslationController extends Controller
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
            'phrase' => ['required', 'string', 'max:300'],
            //! need to add validation for languages code 'pl', 'en'
            'source' => ['required', 'string'],
            'target' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }

        //! need to add the class instance to singleton
        $translation = TranslationFacade::translate($request->phrase, ['source' => $request->source, 'target' => $request->target]);

        return response()->json(['success' => ['translation' => $translation['text']]], 200);
    }
}
