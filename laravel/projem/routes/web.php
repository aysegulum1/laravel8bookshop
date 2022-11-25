<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Anasayfa;
use App\Http\Controllers\Form;
use App\Http\Controllers\Veritabani;
use App\Http\Controllers\Modeldb;
use App\Http\Controllers\Iletisim;
use App\Http\Controllers\Backend\UserController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategorilerController;
use App\Http\Controllers\KitaplarController;

// Route::get('/', function () {
//     return view('welcome');
// });








Route::get("/", [Anasayfa::class,'home'])->name('home');


Route::get("/user", [UserController::class,'index'])->name('kullanicilar');
Route::get("user/update/{id}", [UserController::class,'update'])->name('kullanicilar.update');
Route::get("user/edit/{id}", [UserController::class,'edit'])->name('kullanicilar.edit');
Route::get("user/create", [UserController::class,'create'])->name('kullanicilar.create');
Route::get("user/store", [UserController::class,'store'])->name('kullanicilar.store');
Route::get("user/{id}", [UserController::class,'destory'])->name('kullanicilar.sil');

Route::get("/kategoriler", [KategorilerController::class,'index'])->name('kategoriler');
Route::get('kategoriler/update/{id}', [KategorilerController::class,'update'])->name('kategoriler.update');
Route::get('kategoriler/edit/{id}',  [KategorilerController::class,'edit'])->name('kategoriler.edit');
Route::get('kategoriler/create', [KategorilerController::class,'create'])->name('kategoriler.create');
Route::get('kategoriler/store', [KategorilerController::class,'store'])->name('kategoriler.store');
Route::get('kategorisil/{id}', [KategorilerController::class,'destroy'])->name('kategoriler.sil');

Route::get("/kitaplar", [KitaplarController::class,'index'])->name('kitaplar');
Route::get('kitaplar/update/{id}', [KitaplarController::class,'update'])->name('kitaplar.update');
Route::get('kitaplar/edit/{id}',  [KitaplarController::class,'edit'])->name('kitaplar.edit');
Route::get('kitaplar/create', [KitaplarController::class,'create'])->name('kitaplar.create');
Route::get('kitaplar/store', [KitaplarController::class,'store'])->name('kitaplar.store');
Route::get('kitapsil/{id}', [KitaplarController::class,'destroy'])->name('kitapsil');

Route::get("/sepet", [Anasayfa::class,'sepet'])->name('sepet');



Route::get('/admin/login',[HomeController::class,'login'])->name('admin_login');
Route::post('/admin/logincheck',[HomeController::class,'logincheck'])->name('admin_logincheck');
Route::get('/admin/logout',[HomeController::class,'logout'])->name('admin_logout');
Route::get('/logout',[HomeController::class,'logout'])->name('logout');