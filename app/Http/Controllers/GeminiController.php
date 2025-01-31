<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeminiController extends Controller
{
    public function index(Request $request)
    {
        return view('index');
    }

    /**
    * post
    *
    * @param  Request  $request
    */
    public function post(Request $request)
    {
    
    }
}
