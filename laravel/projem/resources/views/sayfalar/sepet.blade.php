@extends('anasayfa')
@section('title') Sepet İşlemleri   @endsection
@section('govde') 
<section class="page-section" id="sepet">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center">
                        <h2 class="mt-0">Sepete Eklediğiniz Ürünler</h2>
                        <hr class="divider my-4" />
                        <p class="text-muted mb-5"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 ml-auto text-center mb-5 mb-lg-0">
                        <i class="fas fa-credit-card fa-3x mb-3 text-muted"></i>
                        <div>Kart işlemleri</div>
                    </div>
                    <div class="col-lg-4 mr-auto text-center">
                        <i class="fas fa-paypal fa-3x mb-3 text-muted"></i>
                        <div>Kart işlemleri</div>
                    </div>
                </div>
            </div>
        </section>
        @endsection