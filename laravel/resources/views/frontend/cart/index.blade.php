<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>SEPETİM</title>
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
       
        <!-- kapak-->
        <header >
            <div class="container h-100 w-100">
                <div class="row h-50 align-items-center justify-content-center text-center">
               
                                <a class="nav-link js-scroll-trigger align-items-right" aria-current="page" href="/">Anasayfa</a>
                          
                               
                               
                <h5>Sepetim</h5>
            @if(count($cart->details) > 0)
                <table class="table">
                    <thead>
                    <th>Fotoğraf</th>
                    <th>Ürün</th>
                    <th>Adet</th>
                    <th>Fiyat</th>
                    <th>İşlemler</th>
                    </thead>
                    <tbody>
                    @foreach($cart->details as $detail)
                        <tr>
                            <td>
                                <img src="{{asset('/storage/products/'.$detail->product->images[0]->image_url)}}"
                                     alt="{{$detail->product->images[0]->alt}}" width="100">
                            </td>
                            <td>{{ $detail->product->name }}</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>{{ $detail->product->price }}</td>
                            <td>
                                <a href="/sepetim/sil/{{$detail->cart_detail_id}}">Sepetten Sil</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <a href="/satin-al" class="btn btn-success float-end">Satın Al</a>
            @else
                <p class="text-danger text-center">Sepetinizde ürün bulunamadı.</p>
            @endif
                    
                </div>
            </div>
        </header>
      
       
       
        <!-- Footer-->
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

