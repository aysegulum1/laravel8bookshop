@extends('anasayfa')
@section('title') Kategoriler    @endsection
@section('govde') 
<section class="page-section" id="kategori">
    <div class="container">
    <div style="float:right;margin:15px  0 5px 0;"><a href="{{route('kategoriler.create')}}" class="btn btn-success">kategori ekle</a></div>
<div style="clear:both;"></div>
                <h2 class="text-center mt-1">KATEGORİLER</h2>
                <hr class="divider my-8" />
                    <div class="col-lg-12col-md-6 text-left">   
                    <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>Kategori adı</th>
                  <th>Kategori metin</th>

                  <th>düzenle</th>
                  <th>sil</th>
                </tr>
              </thead>
              <tbody>

              @foreach($kategoriler as $kategori)
                <tr class="gradeX">
                  <td>{{$kategori->kategoriadi}}</td>
                  <td>{{$kategori->metin}}</td>
                  <td class="center"><a href="{{route('kategoriler.edit',$kategori->id)}}" class="btn btn-success btn-mini"> Düzenle</a></td>
                  <form action="{{route('kategoriler.sil',$kategori->id)}}" method="GET">
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
     </section>
        @endsection