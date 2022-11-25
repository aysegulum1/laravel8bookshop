<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class KategorilerController extends Controller
{
    public function index()
    {
        $kategoriler=Kategori::all();
        return view('sayfalar.kategori.index',compact('kategoriler'));
        
    }
   
    public function create()
    {

        $kategoriler = Kategori::all();
        return view('sayfalar.kategori.create',compact('kategoriler'));
    }

    
    public function store(Request $request)
    {
        $this->validate(request(),array(
            'kategoriadi'=>'required',
            'metin'=>'required',
        ));


        $kategoriler=new Kategori();
        $kategoriler->kategoriadi=request('kategoriadi');
        $kategoriler->metin=request('metin');
      
        $kategoriler->save();
        
            if ($kategoriler->save()) {
                return redirect()->route('kategoriler');
            }else{
                return "error";
            }

            return redirect()->route('kategoriler');

    }

   
    public function show($id)
    {
      
    }

   
    public function edit($id)
    {
        $kategoriler=Kategori::find($id);
        $tumkategoriler=Kategori::all();
        return view('sayfalar.kategori.edit',compact('kategoriler','tumkategoriler'));
    }

    
    public function update(Request $request, $id)
    {
        $this->validate(request(),array(
            'kategoriadi'=>'required',
            'metin'=>'required',
           
        ));
        

        $kategoriler=Kategori::find(1);
        $kategoriler->kategoriadi=request('kategoriadi');
        $kategoriler->metin=request('metin');
        $kategoriler->save();


        if($kategoriler){
            return redirect()->route('kategoriler');
        }
        else{
            return "hata";
        }
        return redirect()->route('kategoriler');
    }

    
    public function destroy($id)
    {
    
        Kategori::destroy($id);
        return redirect()->route('kategoriler');
    }
}
