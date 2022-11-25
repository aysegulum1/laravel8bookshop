@extends('anasayfa')
@section('title') kitaplar    @endsection
@section('govde') 
<section class="page-section" id="kitaplar">
    <div class="container">
    <div style="float:right;margin:15px  0 5px 0;"><a href="{{route('kitaplar.create')}}" class="btn btn-success">kitap ekle</a></div>
<div style="clear:both;"></div>
                <h2 class="text-center mt-1">KİTAPLAR</h2>
                <hr class="divider my-8" />
                    <div class="col-lg-12col-md-6 text-left">   
                    <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>Kitap adı:</th>
                  <th>Kitap Kategorisi</th>
                  <th>Kitap fiyatı:</th>
                 

                  <th>düzenle</th>
                  <th>sil</th>
                </tr>
              </thead>
              <tbody>

              @foreach($kitaplar as $kitap)
                <tr class="gradeX">
                  <td>{{$kitap->kitapadi}}</td>
                  <td>{{$kitap->kategori}}</td>
                  <td>{{$kitap->fiyat}}</td>
                  <td class="center"><a href="{{route('kitaplar.edit',$kitap->id)}}" class="btn btn-success btn-mini"> Düzenle</a></td>
                  <form action="{{route('kitapsil',$kitap->id)}}" method="GET">
                    {{csrf_field()}}
                    {{method_field('GET')}}
                  <td class="center">
                    <button type="submit" class="btn btn-danger btn-mini">sil</button>
                  </td>
                  </form>
                </tr>
               @endforeach
              </tbody>
            </table>

        </div>
    </div>      
        </div>
        @endsection