@extends('anasayfa')
@section('title') Kategoriler    @endsection
@section('govde') 

<header class="masthead">
            <div class="container h-100">
                <div class="row h-100 align-items-center justify-content-center text-center">
                    <div class="col-lg-10 align-self-end">
                       
                        <hr class="divider my-4" /> <h1 class="text-uppercase text-white font-weight-bold">Online Kitapçı'm HEP BURADA!</h1>
                    </div>
                    <div class="col-lg-8 align-self-baseline">
                        <p class="text-white-75 font-weight-light mb-5">Online Kitapçı'm olarak binlerce kitabı sizin için buluşturuyoruz.</p>
                        <a class="btn btn-primary btn-xl js-scroll-trigger" href="{{route('kategoriler')}}">KATEGORİLERİMİZ</a>
                    </div>
                </div>
            </div>
        </header>
        @endsection