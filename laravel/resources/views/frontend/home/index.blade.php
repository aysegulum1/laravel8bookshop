<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Kitap dünyam</title>
        <!-- Favicon-->
        <link rel="stylesheet" href="http://127.0.0.1:8000/css/app.css">
        <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    />
    <!-- Google Fonts Roboto -->
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap"
    />
    <!-- MDB -->
    <link rel="stylesheet" href="css/mdb.min.css" />
        <link rel="icon" type="image/x-icon" href="https://iconarchive.com/download/i87032/graphicloads/colorful-long-shadow/Book.ico" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
        <!-- Third party plugin CSS-->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="https://tasarim.phpturkiye.net/css/styles.css" rel="stylesheet" />
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
            <div class="container">
               @auth()
                <a class="navbar-brand  js-scroll-trigger" href="/panel">{{Auth::user()->name}}</a>
                @endauth
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto my-2 my-lg-0">
                      
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="/">Anasayfa</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#kategori">Kategoriler</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#kitap">Kitaplar</a></li>
                       
                        @auth()

                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="/sepetim">Sepetim</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="/cikis">Çıkış</a></li>
                        @else
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="/giris">Giriş Yap</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="/uye-ol">Üye ol</a></li>
                        @endauth
                       
                    </ul>
                </div>
                <ul class="navbar-nav ml-auto my-2 my-lg-0">
                    
                </ul>
            </div>
        </nav>
        <!-- kapak-->
        <header class="masthead">
            <div class="container h-100">
                <div class="row h-100 align-items-center justify-content-center text-center">
                    <div class="col-lg-10 align-self-end">
                       
                        <hr class="divider my-4" /> <h1 class="text-uppercase text-white font-weight-bold">Online Kitapçı'm HEP BURADA!</h1>
                    </div>
                    <div class="col-lg-8 align-self-baseline">
                        <p class="text-white-75 font-weight-light mb-5">Online Kitapçı'm olarak binlerce kitabı sizin için buluşturuyoruz.</p>
                        <a class="btn btn-primary btn-xl js-scroll-trigger" href="#kategori">KATEGORİLER</a>
                    </div>
                </div>
            </div>
        </header>
      
        <!-- kategoriler kısmı-->
        <section class="page-section" id="kategori">
            <div class="container">
                <h2 class="text-center mt-0">KATEGORİLER</h2>
                <hr class="divider my-4" />
                <div class="row">
                    @if(count($categories) > 0)
                    @foreach($categories as $category)
                    <div class="col-sm-2 align-items-center justify-content-center text-center">
                      <i class="fas fa-4x fa-gem text-primary mb-4"></i>
                       <div class="list-group">
                        <a  href="/kategori/{{$category->slug}}" class="list-group-item list-group-item-action mb-4">{{$category->name}}</a>
                        <p class="text-muted mb-0">{{$category->metin}}</p>
                       </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </section>
        <!-- kitaplar-->
        <div id="kitap">
        <h2 class="text-center mt-0"><a href="/">TÜM KİTAPLAR </a></h2>
           
           <hr class="divider my-4" />
            <div >
                
          
                <div>
                @if(count($products) > 0)
                <div class="row">
                    @foreach($products as $product)
                    <div class="card" style="width: 18rem;">
                        @if(isset($product->images[0]->image_url))
                 <img src="{{asset('/storage/products/'.$product->images[0]->image_url)}}"
                 class="card-img" alt="{{$product->images[0]->alt}} ">
        @else
        <img src="{{asset('/storage/products/default-image.jpg')}}"
                 alt="fdsffds"
                 class="img-thumbnail"
                 width="80">
        @endif
            <div class="card-body">
                <h3 class="card-title">{{$product->name}}</h3>
               
                <h6 class="card-title">Fiyat: {{$product->price}}TL</h6>
                <a href="/sepetim/ekle/{{$product->product_id}}" class="btn btn-primary">Sepete Ekle</a>
            </div>
                        </div>
                    @endforeach
                </div>
            @endif
               </div>
            </div>
        </div>
       
        
        <footer class="bg-light py-5">
            <div class="container"><div class="small text-center text-muted">Copyright © 2022</div></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script type="text/javascript" src="js/mdb.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Third party plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
        <!-- Core theme JS-->
        <script src="https://tasarim.phpturkiye.net/js/scripts.js"></script>
    </body>
</html>