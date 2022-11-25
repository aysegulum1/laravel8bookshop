@extends('anasayfa')
@section('title') kullanıcılar    @endsection
@section('govde') 
<section class="page-section" id="kullanicilar">
    <div class="container">
                <h2 class="text-center mt-1">KULLANICI DÜZENLE</h2>
                <hr class="divider my-8" />
                    <div class="table-responsive">   
                    <form action="{{route('kullanicilar.store')}}" method="GET" class="form-horizontal" autocomplete="off" novalidate >
			{{csrf_field()}}

		<div >
    <input type="hidden" name="user_id" value="{{$user->user_id}}">
					<div class="control-group">
						<label class="control-label">ad soyad:</label>
						<div class="controls">
							<input type="text" class="span11" label="Ad Soyad" placeholder="Ad soyad giriniz" field="name" value="{{$user->name}}"/>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">e mail giriniz:</label>
						<div class="controls">
							<input class="span11" label="Eposta giriniz" placeholder="Eposta giriniz" field="email" type="email"
                             value="{{$user->email}}"/>
						</div>
					</div>
            
          <div class="control-group">
						<label class="control-label">yetkili kullanıcı</label>
						<div class="controls">
            <checkbox field="is_admin" label="Yetkili Kullanıcı" checked="{{$user->is_admin == 1}}"/>
						</div>
					</div>
          <div class="control-group">
						<label class="control-label">aktif</label>
						<div class="controls">
            <checkbox field="is_active" label="Aktif Kullanıcı" checked="{{$user->is_active == 1}}"/>
						</div>
					</div>

          </div>

					<div class="form-actions">
						<button type="submit" class="btn btn-success" id="gonder">kullanıcı ekle</button>
					</div>
					</form>

        </div>
    </div>      
        </div>
        @endsection