<!DOCTYPE html>

<html lang="hu">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="R&R Vendégház Weboldala" />
    <meta name="author" content="Racskó Balázs" />
    <meta name="keywords" content="nyaralás, balaton, magyarország, vendégház, kikapcsolódás, túrázás, strand, balatonkenese, R&R"/>
    <title>R&R Vendégház</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="{{url('kepek/favicon.jpg')}}" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" crossorigin="anonymous"></script>
    <script async src="https://kit.fontawesome.com/6cc05e1e8e.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet"
        type="text/css" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="/css/basic.css" rel="stylesheet" />

    <!-- Extra css if needed-->
    {{ $extracss ?? '' }}

</head>

<body>
    <!-- Navigation-->
    eddigi navbar:
    <nav class="navbar navbar-expand-xxl py-0 bg-secondary text-uppercase fixed-top" id="mainNav">

            <div class="container">
                <a class="navbar-brand" href="{{ url('fooldal#rolunk') }}">R&R Vendégház</a>
                <button class="navbar-toggler text-uppercase font-weight-bold bg-primary text-white rounded" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive"
                    aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fas fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item mx-0 "><a class="nav-link py-1 px-0 px-lg-1 rounded"
                                href="{{ url('fooldal#rolunk') }}">Rólunk</a></li>
                        <li class="nav-item mx-0 "><a class="nav-link py-1 px-0 px-lg-1 rounded"
                                href="{{ url('fooldal#galeria') }}">Galéria</a></li>
                        <li class="nav-item mx-0 "><a class="nav-link py-1 px-0 px-lg-1 rounded"
                                href="{{ url('arak#arak') }}">Árak/Feltételek</a></li>
                        <li class="nav-item mx-0 "><a class="nav-link py-1 px-0 px-lg-1 rounded"
                                href="{{ url('foglalas') }}">Foglalás</a></li>
                        <li class="nav-item mx-0 "><a class="nav-link py-1 px-0 px-lg-1 rounded"
                                href="{{ url('adatvedelem#adatvedelem') }}">Adatvédelem</a></li>
                        <li class="nav-item mx-0 "><a class="nav-link py-1 px-0 px-lg-1 rounded"
                                href="{{ url('hazirend#hazirend') }}">Házirend</a></li>
                        <li class="nav-item mx-0 "><a class="nav-link py-1 px-0 px-lg-1 rounded"
                                href="{{ url('programok#programok') }}">Programajánló</a></li>
                        <li class="nav-item mx-0 "><a class="nav-link py-1 px-0 px-lg-1 rounded"
                                href="{{ url('kapcsolat#kapcsolat') }}">Kapcsolat</a></li>
						<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle mx-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">English</a>
    <div class="dropdown-menu mx-0">
		<a class="dropdown-item" href="{{ url('main') }}">About Us</a>
		<a class="dropdown-item" href="{{ url('prices') }}">Pricing</a>
		<a class="dropdown-item" href="{{ url('booking') }}">Booking</a>
        <a class="dropdown-item" href="{{ url('rules') }}">Rules</a>
    </div>
</li>
                        @if (Auth::check())
                            @if(Auth::user()->hasRole('user'))

                            <li class="nav-item mx-0 dropdown">
                                <a href="#" class="nav-link py-1 px-0 px-lg-1 rounded dropdown-toggle" data-bs-toggle="dropdown">Kezelőpult</a>
                                <div class="dropdown-menu">
                                    <a href="{{ url('dashboard/myprofile') }}" class="dropdown-item">Profil</a>
                                    <a href="{{ url('dashboard/adatmodositas') }}" class="dropdown-item">Adatok Módosítása</a>
                                    <a href="{{ url('dashboard/vendegkonyv') }}" class="dropdown-item">Vendégkönyv</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a class="dropdown-item" href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Kijelentkezés</a>
                                    </form>
                                </div>
                            </li>

                          @endif
                          @if (Auth::user()->hasRole('admin'))
                          <li class="nav-item mx-0 dropdown">
                            <a href="#" class="nav-link py-1 px-0 px-lg-1 rounded dropdown-toggle nav-link" data-bs-toggle="dropdown">Kezelőpult</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a href="{{ url('dashboard/adminadatmodositas') }}" class="dropdown-item nav-item">Adatok Módosítása</a>
                                <a href="{{ url('dashboard/foglalasok') }}" class="dropdown-item nav-item">Foglalások Megtekintése</a>
                                <a href="{{ url('dashboard/felhasznalok') }}"class="dropdown-item nav-item">Felhasználók megtekintése</a>
                                <a href="{{ url('dashboard/kepfeltoltes') }}" class="dropdown-item nav-item">Képfeltöltés</a>
                                <a href="{{ url('dashboard/ar') }}"class="dropdown-item nav-item">Ár módosítása</a>
                                <a href="{{ url('dashboard/oldalakSzerkesztese') }}"class="dropdown-item nav-item">Oldalak módosítása</a>
                                <a href="{{ url('dashboard/kepmodositas') }}"class="dropdown-item nav-item">Főoldali képek módosítása</a>
                                <a href="{{ url('dashboard/programmodositas') }}"class="dropdown-item nav-item">Programok módosítása</a>
                                <a href="{{ url('dashboard/vendegkonyv_lista') }}" class="dropdown-item">Vendégkönyv</a>
                                                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a class="dropdown-item nav-item" href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Kijelentkezés</a>
                                </form>
                            </div>
                        </li>
                          @endif

                        @endif
                        @if (!Auth::check())
                            <li class="nav-item mx-0 "><a class="nav-link py-1 px-0 px-lg-1 rounded"
                                    href="{{ url('login') }}">Bejelentkezés</a></li>
                            <li class="nav-item mx-0 "><a class="nav-link py-1 px-0 px-lg-1 rounded"
                                    href="{{ url('register') }}">Regisztráció</a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

    <!-- Masthead-->
    <header class="masthead bg-primary text-white text-center">
        <div class="container d-flex align-items-center flex-column">
            <!-- Masthead Avatar Image-->
            <img src="/kepek/header.jpg" width="100%" height="100%" alt="..." />
    </header>
    <!-- About Section-->
    <section class="page-section mb-0" id={{ $anchor }}>
        <div class="container">
            <!--Heading-->
            <h3 class="page-section-heading text-center text-uppercase ">{{ $sectionHeading }}</h3>
            <!-- Icon Divider-->
            <div class="divider-custom ">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                <div class="divider-custom-line"></div>
            </div>
            <!--Content-->

            {{ $content }}

    </section>

    <!-- Footer-->
    <footer class="footer text-center">
        <div class="container">
            <div class="row">
                <!-- Footer Location-->
                <div class="col-lg-12 mb-5 mb-lg-0">
                    <h4 class="text-uppercase mb-4">Cím</h4>
                    <p class="lead mb-0">
                        8174 Balatonkenese
                        <br />
                        Óvoda utca 40.
                        <br />
                        info@randrvendeghaz.hu
                    </p>

                </div>
                <!-- Footer Social Icons-->
                <div class="text-center">
                    <a href="https://www.facebook.com/RandRvendeghaz"><i
                                class="fab fa-fw fa-facebook-f"></i></a>
                </div>
                <div class="text-center"><img src="kepek/szepkartya.png" alt="Szépkártya Logók" height="150px" width="300px"></div>
            </div>
        </div>
    </footer>
    <!-- Copyright Section-->
    <div class="copyright py-4 text-center text-white">
        <div class="container"><small>Copyright &copy; Racskó Balázs</small>
        <br>
        <small>A borítóképet köszönjük a <a href="https://www.bacse.hu/">Bakonyi Csillagászati Egyesület</a>-nek.</small></div>
    </div>

    <!--JS-->
    <!--<script src="/js/navbar.js"></script>-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous">
    </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Extra js if needed-->
    {{ $extrascripts ?? '' }}

</body>

</html>
