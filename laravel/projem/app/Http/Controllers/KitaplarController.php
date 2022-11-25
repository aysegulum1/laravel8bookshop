<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kitap;

class KitaplarController extends Controller
{
    public function index()
    {
        $kitaplar=Kitap::all();
        return view('sayfalar.kitap.index',compact('kitaplar'));
        
    }
    public function update(Request $request, $id)
    {
        $this->validate(request(),array(
            'kitapadi'=>'required',
            'kategori'=>'required',
            'fiyat'=>'required',
           
        ));
        $kitaplar=Kitap::find(1);
        $kitaplar->kitapadi=request('kitapadi');
        $kitaplar->kategori=request('kategori');
        $kitaplar->fiyat=request('fiyat');

        $kitaplar->save();
        if($kitaplar){
            return redirect()->route('kitaplar');
        }
        else{
            return "hata";
        }
        return redirect()->route('kitaplar');
    }
    public function destroy($id)
    {
    
        Kitap::destroy($id);
        return redirect()->route('kitaplar');
    }

    public function create()
    {

        $kitaplar = Kitap::all();
        return view('sayfalar.kitap.create',compact('kitaplar'));
    }

    public function store(Request $request)
    {
        $this->validate(request(),array(
            'kitapadi'=>'required',
            'kategori'=>'required',
            'fiyat'=>'required',
        ));


        $kitaplar=new Kitap();
        $kitaplar->kitapadi=request('kitapadi');
        $kitaplar->kategori=request('kategori');
        $kitaplar->fiyat=request('fiyat');
      
        $kitaplar->save();
        
            if ($kitaplar->save()) {
                return redirect()->route('kitaplar');
            }else{
                return "error";
            }

            return redirect()->route('kitaplar');
    }
    public function edit($id)
    {
        $kitaplar=Kitap::find($id);
        $tumkitaplar=Kitap::all();
        return view('sayfalar.kitap.edit',compact('kitaplar','tumkitaplar'));
    }

   

}
