<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bilgiler;

class Modeldb extends Controller
{
    public function liste(){
        $bilgi=Bilgiler::where("id",10)->first();
        echo $bilgi->metin;
    }
    public function ekle(){
        Bilgiler::create(["metin"=>"ee",]);
        
    }
    public function guncelle(){
        Bilgiler::whereId(8)->update(["metin"=>"ee",]);
        
    }
    public function sil(){
        Bilgiler::whereId(8)->delete();
        
    }
}
