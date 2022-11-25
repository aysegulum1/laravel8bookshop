<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Form extends Controller
{
    public function eskiform(){

return view('form');

    }
    public function yeniform(Request $request){

        return $request->metin;
        
            }
}
