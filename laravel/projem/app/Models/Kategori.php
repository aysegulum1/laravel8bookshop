<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $primaryKey = "id";

    protected $table='kategoriler';
    protected $guarded=[];

    public function kitaplar(){
        return $this->hasMany('App\Models\Kitap','kategori');
    }
   
}
