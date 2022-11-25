@extends('anasayfa')
@section('title') Kategoriler    @endsection
@section('govde') 
<section class="page-section" id="kategori">
    <div class="container">
                <h2 class="text-center mt-1">KATEGORİ DÜZENLEME:{{$kategoriler->kategoriadi}} </h2>
                <hr class="divider my-8" />
                    <div class="col-lg-12col-md-6 text-left">   
                    <form action="{{route('kategoriler.update',$kategoriler->id)}}" method="GET" class="form-horizontal" >
						{{csrf_field()}}
					<div class="control-group">
						<label class="control-label">Kategori Başlık</label>
						<div class="controls">
							<input type="text" class="span11" name="kategoriadi" value="{{$kategoriler->kategoriadi}}"/>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Kategori metin</label>
						<div class="controls">
							<input type="text" class="span11" name="metin" value="{{$kategoriler->metin}}"/>
						</div>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn btn-success">Kategori Düzenle</button>
					</div>
					</form>

        </div>
    </div>            
     </section>
        @endsection