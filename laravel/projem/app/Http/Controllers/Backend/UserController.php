<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $users = User::all();
        return view('admin.index',["users"=>$users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
        $users = User::all();
        return view('admin.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate(request(),array(
          
            'name'=>'required',
            'email'=>'required',
            'password'=>'required',
            'is_admin'=>'required',
            'is_active'=>'required',
        ));

        $is_admin=$is_admin=="on" ? 1:0;
        $is_active=$is_active=="on" ? 1:0;

        $user=new Kategori();
        $user->name=request('name');
        $user->email=request('email');
        $user->password=request('password');
        $user->is_admin=request('is_admin',0);
        $user->is_active=request('is_active',0);
        $user->save();
        
            
            return Redirect::to('/user');

        
    }

  
    public function show($id)
    {
        
    }

    
    public function edit($id)
    {
        $user=User::find($id);
        return view('admin.edit',['user'=>$user]);

    }

    
    public function update(Request $request, $id)
    {
       
  
        $this->validate(request(),array(
          
            'name'=>'required',
            'email'=>'required',
           
            'is_admin'=>'required',
            'is_active'=>'required',
        ));

        $is_admin=$is_admin=="on" ? 1:0;
        $is_active=$is_active=="on" ? 1:0;

        $user=Kategori::find();
        $user->name=request('name');
        $user->email=request('email');
       
        $user->is_admin=request('is_admin');
        $user->is_active=request('is_active');
        $user->save();
        
            
            return Redirect::to('/user');

    }
 
    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('kullanicilar');
        
    }
}
