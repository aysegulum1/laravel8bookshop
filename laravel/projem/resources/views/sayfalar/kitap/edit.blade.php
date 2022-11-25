@extends('anasayfa')
@section('title') KİTAPLAR    @endsection
@section('govde') 
<section class="page-section" id="kitap">
    <div class="container">
                <h2 class="text-center mt-1">KİTAP DÜZENLEME:{{$kitaplar->kitapadi}} </h2>
                <hr class="divider my-8" />
                    <div class="col-lg-12col-md-6 text-left">   
                    <form action="{{route('kitaplar.update',$kitaplar->id)}}" method="GET" class="form-horizontal" >
						{{csrf_field()}}
					<div class="control-group">
						<label class="control-label">Kitap adı:</label>
						<div class="controls">
							<input type="text" class="span11" name="kitapadi" value="{{$kitaplar->kitapadi}}"/>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Kategorisi:</label>
						<div class="controls">
							<input type="text" class="span11" name="kategori" value="{{$kitaplar->kategori}}"/>
						</div>
					</div>
                    <div class="control-group">
						<label class="control-label">fiyat:</label>
						<div class="controls">
							<input type="text" class="span11" name="fiyat" value="{{$kitaplar->fiyat}}"/>
						</div>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn btn-success">kitap Düzenle</button>
					</div>
					</form>

        </div>
    </div>            
     </section>
        @endsection