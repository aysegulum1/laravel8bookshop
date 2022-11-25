<?php

namespace App\Http\Controllers;
use App\Models\Iletisimmodel;

use Illuminate\Http\Request;

class Iletisim extends Controller
{
    public function index()
    {
        return view('iletisim');
    }
    public function eklenme(Request $table)
    {
        $adsoyad=$table->adsoyad;
        $telefon=$table->telefon;
        $mail=$table->mail;
        $metin=$table->metin;
        $updated_at=$table->updated_at;
        $created_at=$table->created_at;
        Iletisimmodel::create([
            "adsoyad"=>$adsoyad,
            "telefon"=>$telefon,
            "mail"=>$mail,
            "metin"=>$metin,
            "updated_at"=>$updated_at,
            "created_at"=>$created_at,
        ]);
    }
}
