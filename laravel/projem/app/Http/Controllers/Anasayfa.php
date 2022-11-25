<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Anasayfa extends Controller
{
  // public function site(){

  //   $data["yazi1"]="aaaaaaaaaaaaaaaa";
  //   $data["yazi2"]="bbbbbbbbbbbbbbb";
  //   return view('anasayfa',$data);
  // }





  public function home(){
    return view('sayfalar.home');
  }
  public function kategoriler(){
    return view('sayfalar.kategoriler');
  }
  public function kitaplar(){
    return view('sayfalar.kitaplar');
  }
  public function sepet(){
    return view('sayfalar.sepet');
  }

}
