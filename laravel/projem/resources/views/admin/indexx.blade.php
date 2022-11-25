@extends('anasayfa')
@section('title') kullanıcılar    @endsection
@section('govde') 
<section class="page-section" id="kitaplar">
    <div class="container">
    <div style="float:right;margin:15px  0 5px 0;"><a href="{{route('kullanicilar.create')}}" class="btn btn-success">kullanıcı ekle</a></div>
<div style="clear:both;"></div>
                <h2 class="text-center mt-1">KULLANICILAR</h2>
                <hr class="divider my-8" />
                    <div class="col-lg-12col-md-6 text-left">   
                    <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>Sıra no:</th>
                  <th>Ad ve soyad:</th>
                  <th>E-posta:</th>
                  <th>Durum:</th>
                  

                </tr>
              </thead>
              <tbody>

              @if(count($users) > 0)
            @foreach($users as $user)
                <tr class="gradeX">
                <tr id="{{$user->user_id}}">
                  <td>{{$loop->iteration}}</td>
                  <td>{{$user->name}}</td>
                  <td>{{$user->email}}</td>
                  <td>
                  @if($user->is_active == 1)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-danger">Pasif</span>
                        @endif
                  </td>

                  <td class="center"><a href="{{route('kullanicilar.edit',$user->id)}}" class="btn btn-success btn-mini"> Düzenle</a></td>
                  <form action="{{route('kullanicilar.sil',$user->id)}}" method="GET">
                    {{csrf_field()}}
                    {{method_field('GET')}}
                  <td class="center">
                    <button type="submit" class="btn btn-danger btn-mini">sil</button>
                  </td>
                  </form>
                </tr>
                @endforeach
        @else
             <tr>
                <td colspan="5">
                    <p class="text-center">Herhangi bir kullanıcı bulunamadı.</p>
                </td>
            </tr>

               @endif
              </tbody>
            </table>

        </div>
    </div>      
        </div>
        @endsection