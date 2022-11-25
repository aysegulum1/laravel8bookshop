<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class Veritabani extends Controller
{
    public function ekle()
    {
DB::table("bilgiler")->insert([
    "metin"=>"aaaaaaaaaaaaaaa"
]);
    }
    public function guncelle()
    {
DB::table("bilgiler")->where("id",5)->update([
    "metin"=>"bu örneksssssssssssssssssssssss"
]);
    }
    public function sil()
    {
DB::table("bilgiler")->where("id",7)->delete();
    }

    public function bilgi()
    {
$veriler=DB::table("bilgiler")->where("id",2)->first();

echo $veriler->metin;
    }
}
