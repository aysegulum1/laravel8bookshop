@extends('anasayfa')
@section('title') Kitaplar    @endsection
@section('govde') 
<section class="page-section" id="kitap">
    <div class="container">
                <h2 class="text-center mt-1">Yeni Kitap Ekle</h2>
                <hr class="divider my-8" />
                    <div class="col-lg-12col-md-6 text-left">   
                    <form action="{{route('kitaplar.store')}}" method="GET" class="form-horizontal" >
			{{csrf_field()}}

		

					<div class="control-group">
						<label class="control-label">Kitap adı</label>
						<div class="controls">
							<input type="text" class="span11" name="kitapadi"/>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Kategori</label>
						<div class="controls">
							<input type="text" class="span11" name="kategori"/>
						</div>
					</div>
                    <div class="control-group">
						<label class="control-label">fiyat</label>
						<div class="controls">
							<input type="text" class="span11" name="fiyat"/>
						</div>
					</div>
          <div></div>
					<div class="form-actions">
						<button type="submit" class="btn btn-success" id="gonder">kitap Ekle</button>
					</div>
					</form>

        </div>
    </div>            
     </section>
        @endsection