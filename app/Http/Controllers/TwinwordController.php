<?php

namespace App\Http\Controllers;

use App\Facades\ExampleFacade;
use Illuminate\Http\Request;

class TwinwordController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $sentenceTwinword = ExampleFacade::example('kot', 'pl', 'zh');

        return response()->json(['success' => ['images' => $sentenceTwinword]], 200);
    }
}
