<div class="py-1 bg-primary">
    	<div class="container">
    		<div class="row no-gutters d-flex align-items-start align-items-center px-md-0">
	    		<div class="col-lg-12 d-block">
		    		<div class="row d-flex">
		    			<div class="col-md pr-4 d-flex topper align-items-center">
					    	<div class="icon mr-2 d-flex justify-content-center align-items-center"><span class="icon-phone2"></span></div>
							    <span class="text">+62 812 0000 0000</span>
					    </div>
					    <div class="col-md pr-4 d-flex topper align-items-center">
					    	<div class="icon mr-2 d-flex justify-content-center align-items-center"><span class="icon-paper-plane"></span></div>
							    <span class="text">hello@exflorekbb.co.id</span>
					    </div>
					    <div class="col-md-5 pr-4 d-flex topper align-items-center text-lg-right">
						    <span class="text">Layanan 24 jam untuk pemesanan wisata</span>
					    </div>
				    </div>
			    </div>
		    </div>
		  </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
	    <div class="container">
	      <a class="navbar-brand" href="{{ route('home') }}">Exflore KBB</a>
	      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
	        <span class="oi oi-menu"></span> Menu
	      </button>

	      <div class="collapse navbar-collapse" id="ftco-nav">
	        <ul class="navbar-nav ml-auto">
	          <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}"><a href="{{ route('home') }}" class="nav-link">Beranda</a></li>
	          <li class="nav-item {{ request()->routeIs('shop', 'product.single') ? 'active' : '' }}"><a href="{{ route('shop') }}" class="nav-link">Paket Wisata</a></li>
	          <li class="nav-item dropdown {{ request()->routeIs('destinations', 'accommodations', 'transportations', 'culinaries') ? 'active' : '' }}">
              <a class="nav-link dropdown-toggle" href="#" id="dropdown-services" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Layanan</a>
              <div class="dropdown-menu" aria-labelledby="dropdown-services">
              	<a class="dropdown-item" href="{{ route('destinations') }}">Tiket Destinasi</a>
              	<a class="dropdown-item" href="{{ route('accommodations') }}">Penginapan</a>
                <a class="dropdown-item" href="{{ route('transportations') }}">Transportasi</a>
								<a class="dropdown-item" href="{{ route('culinaries') }}">Kuliner</a>
              </div>
            </li>
	          <li class="nav-item {{ request()->routeIs('about', 'contact') ? 'active' : '' }}"><a href="{{ route('about') }}#contact-section" class="nav-link">Tentang & Kontak</a></li>
	          <li class="nav-item cta cta-colored {{ request()->routeIs('cart') ? 'active' : '' }}"><a href="{{ route('cart') }}" class="nav-link"><span class="icon-shopping_cart"></span>Keranjang</a></li>
	          <li class="nav-item cta ml-lg-2 {{ request()->routeIs('trip-builder', 'destinations', 'accommodations', 'transportations', 'culinaries', 'shop') ? 'active' : '' }}"><a href="{{ route('trip-builder') }}" class="nav-link">Mulai Booking</a></li>
	        </ul>
	      </div>
	    </div>
	  </nav>
    <!-- END nav -->
