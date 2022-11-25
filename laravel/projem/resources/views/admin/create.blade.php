@extends('anasayfa')
@section('title') kullanıcılar    @endsection
@section('govde') 
<section class="page-section" id="kullanicilar">
    <div class="container">
                <h2 class="text-center mt-1">YENİ KULLANICI EKLE</h2>
                <hr class="divider my-8" />
                    <div class="table-responsive">   
                    <form action="{{route('kullanicilar.store')}}" method="GET" class="form-horizontal" autocomplete="off" novalidate >
			{{csrf_field()}}

		<div >

					<div class="control-group">
						<label class="control-label">ad soyad:</label>
						<div class="controls">
							<input type="text" class="span11" name="name" value="name"/>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">e mail giriniz:</label>
						<div class="controls">
							<input type="email" class="span11" name="email" value="email"/>
						</div>
					</div>
          <div class="control-group">
						<label class="control-label">şifre</label>
						<div class="controls">
							<input type="text" class="span11" name="name" value="name"/>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">şifre tekrar:</label>
						<div class="controls">
							<input type="email" class="span11" name="email" value="email"/>
						</div>
					</div>
          <div class="control-group">
						<label class="control-label">yetkili kullanıcı</label>
						<div class="controls">
							<input type="checkbox" class="span11" name="is_admin" value="1" />
						</div>
					</div>
          <div class="control-group">
						<label class="control-label">aktif</label>
						<div class="controls">
							<input type="checkbox" class="span11" name="is_active" value="1"/>
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