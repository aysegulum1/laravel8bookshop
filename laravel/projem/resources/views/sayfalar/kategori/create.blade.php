@extends('anasayfa')
@section('title') Kategoriler    @endsection
@section('govde') 
<section class="page-section" id="kategori">
    <div class="container">
                <h2 class="text-center mt-1">Yeni Kategori Ekle</h2>
                <hr class="divider my-8" />
                    <div class="col-lg-12col-md-6 text-left">   
                    <form action="{{route('kategoriler.store')}}" method="GET" class="form-horizontal" >
			{{csrf_field()}}

		

					<div class="control-group">
						<label class="control-label">Kategori Başlık</label>
						<div class="controls">
							<input type="text" class="span11" name="kategoriadi"/>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Kategori metin kısmı</label>
						<div class="controls">
							<input type="text" class="span11" name="metin"/>
						</div>
					</div>
          <div></div>
					<div class="form-actions">
						<button type="submit" class="btn btn-success" id="gonder">Kategori Ekle</button>
					</div>
					</form>

        </div>
    </div>            
     </section>
        @endsection