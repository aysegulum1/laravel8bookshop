<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Giriş yap</title>
      
        <link rel="stylesheet" href="http://127.0.0.1:8000/css/app.css">
        <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    />
   
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap"
    />


    <link rel="stylesheet" href="css/mdb.min.css" />
        <link rel="icon" type="image/x-icon" href="https://iconarchive.com/download/i87032/graphicloads/colorful-long-shadow/Book.ico" />
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
<nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
            <div class="container">
                <a class="navbar-brand  js-scroll-trigger" href="/">ONLİNE KİTAPÇI'M</a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto my-2 my-lg-0">
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="/">Anasayfa</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="/uye-ol">Üye ol</a></li
                    </ul>
                </div>
            </div>
        </nav>

        <header class="masthead">
            <div class="container h-100 w-50 align-items-center">
                <div class="row h-100   justify-content-center text-center">
                <form method="POST" action="">
                    @csrf
                    <h1 class="text-white-75 font-weight-light mb-5">Giriş Yapın</h1>

                    <div class="form-group mt-2">
                        <x-input label="Eposta giriniz" placeholder="Eposta giriniz" field="email" type="email"/>
                    </div>

                    <div class="form-group mt-2">
                        <x-input label="Şifre Giriniz" placeholder="Şifre giriniz" field="password" type="password"/>
                    </div>

                    <div class="form-group  mb-3 mt-2">
                        <x-checkbox field="remember-me" label="Beni Hatırla"/>
                    </div>
                    <button class="btn btn-primary btn-xl js-scroll-trigger" type="submit">Giriş</button>
                </form>
                   
                </div>
            </div>
        </header>
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