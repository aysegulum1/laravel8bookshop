<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static function categoryList()
    {
        return Category::where('parent_id','=',0)->with('children')->get();
    }

    public function categoryproducts($id,$slug){
        $datalist=Product::where('category_id',$id)->get();
        $data=Category::find($id);

        return view('home.category_products',['data'=>$data,'datalist'=>$datalist]);

    }

    public static function getsetting()
    {
        return Setting::first();
    }

    public function index(){
        $setting=Setting::first();
        $slider=Product::select('id','title','image','slug','price','category_id')->where('status','True')->get();

        $data=[
            'setting'=>$setting,
            'slider'=>$slider,
            'page'=>'home'

        ];
        return view('home.index',$data);
    }

    public function login(){
        return view('admin.login');
    }
    public function logincheck(Request $request)
    {
        if($request->isMethod('post'))
        {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                return redirect()->intended('admin');
            }

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }
        else
        {
            return view('admin.login');
        }
    }
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
