<!DOCTYPE html>

<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>American Pro</title>
	<!--Google font-->
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans:100,300,400,600,600i,700" rel="stylesheet">
	<!--favicon-->
	<link rel="shortcut icon" type="image/x-icon" href="/assets/images/favicon.png">
	<!-- <link rel="icon" href="/assets/images/favicon.png"> -->
	<!--stylesheet-->
	<link rel="stylesheet" href="/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/css/owl.carousel.min.css">
	<link rel="stylesheet" href="/assets/css/fontello.css">
	<link rel="stylesheet" href="/assets/css/animate.css">
	<link rel="stylesheet" href="/assets/css/style.css">
	<link rel="stylesheet" href="/assets/css/responsive.css">
</head>

<body>
	<!--===========================
        Start PreLoader
===========================-->
	<div class="preloader">
		<div class="loader">
			<span></span>
		</div>
		<!--end .loader-->
	</div>
	<!--end .preloader-->
	<!--===========================
        End PreLoader
===========================-->

	<!--===========================
        Start Header
===========================-->


	<header class="header_area">
		<div class="container">
			<div class="row">
				<div class="col-md-3 col-sm-4 col-xs-12">
					<div class="header_social">
						<ul class="hd_social_icons">
                        @foreach($socials as $item)
							<li>
								<a href="{{url(''.$item->value)}}" target="_blank">
									<i class="{{$item->icon}}"></i>
								</a>
							</li>
                            @endforeach
							
							<div class="f2_line fl"></div>
							<a href="http://instagram.com/americanpro_hvac" target="_blank">
								<div class="inst fl"></div>
							</a>
							<div class="f2_line fl"></div>
							<a href="https://www.linkedin.com/in/americanpro" target="_blank">
								<div class="linkedin fl"></div>
							</a>
						</ul>

					</div>
				</div>
				<div class="col-md-9 col-sm-8 col-xs-12">
					<div class="header_contact text-right">
						<ul class=" search-header">
							<form name="ser" action="view/search/viewSearch.php" method="get">
								<input type="text" class="search keywords" id="word" placeholder="Search By Keywords" name="word" onfocus="ser_focaseword()">
								<button class="sub" type="submit">
									<i class="fa fa-search keyword"></i>
								</button>
							</form>
							<form name="serCat" action="view/search/viewSearchCatogery.php" method="get">
								<input type="text" class="search serach-cat" id="catogry" placeholder="Search By Catogry" name="catogry" onfocus="ser_focasecatogery()">
								<button type="submit" class="sub">
									<i class="fa fa-search cats"></i>
								</button>
							</form>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</header>
	<!--===========================
        End Header
===========================-->

	<!--===========================
        Start Main Menu
===========================-->
	<div class="main_menu_area">
		<div class="container">
			<div class="row">
				<div class="col-md-2 col-sm-12">
					<button type="button" class="navbar-toggles">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a href="{{url('/')}}" class="logo">
						<img src="/assets/images/logo.png" alt="Logo">
					</a>
				</div>
				<!--end .col-md-2-->
				<div class="col-md-10 col-sm-9 collapse_responsive">
					<div class="collapse navbar-collapse remove_padding" id="myNavbar">
						<ul class="nav navbar-nav text-center">
							@foreach($menus as $m) @if(isset($m->sons))
							<li>

								<a href="{{url(''.$m->url)}}">{{$m->title_en}} <i class="fa fa-angle-down"></i></a>

								<ul class="dropdown-menu dropdown-menu-responsive">
									@foreach($m->sons as $s)

									<li>
										<a href="{{url(''.$s->url)}}">{{$s->title_en}}</a>
									</li>

									@endforeach

								</ul>
							</li>

							@endif @if(!isset($m->sons))

							<li>
								<a href="{{url(''.$m->url)}}">{{$m->title_en}}</a>
							</li>
							@endif @endforeach
							<li class="header_right_btn">
								<a class="btn-yellow" href="#">Become A Dealer</a>
							</li>
						</ul>
					</div>
					<!--end .collapse-->
				</div>
				<!--end .col-md-10-->
			</div>
			<!--end .row-->
		</div>
		<!--end .container-->
	</div>
	<!--end .main_menu_area-->
	<!--===========================
        End Main Menu
===========================-->
	@yield('content')

	<!--===========================
        Start Footer
===========================-->
	<footer class="footer_area">
		<div class="footer_contact text-center">
			<div class="container">
				<div class="col-md-4 col-sm-4">
					<div class="footer_contact_width text-left">
						<p>
							<i class="icon-placeholder"></i> {{__('Address')}}
							<span>{{$info->address}}</span>
						</p>
					</div>
					<!--end .footer_contact_width-->
				</div>
				<!--end .col-md-4-->
				<div class="col-md-4 col-sm-4">
					<div class="footer_contact_width">
						<p>
							<i class="icon-contact"></i> {{__('Email')}}
							<span>
								<a href="#">{{$info->email}}</a>
							</span>
						</p>
					</div>
					<!--end .footer_contact_width-->
				</div>
				<!--end .col-md-4-->
				<div class="col-md-4 col-sm-4">
					<div class="footer_contact_width text-right">
						<p>
							<i class="icon-clock"></i> {{__('Open Hours')}}
							<span>
								<a href="">{{$info->open_hours}}</a>
							</span>
						</p>
					</div>
					<!--end .footer_contact_width-->
				</div>
				<!--end .col-md-4-->
			</div>
			<!--end .container-->
		</div>
		<!--end .footer_contact-->
		<div class="footer_content section_padding">
			<div class="container">
				<div class="row">
					<div class="col-md-4 col-sm-6">
						<div class="footer_textwidget textwidget">
							<h2>{{__('About American Pro')}}</h2>
							<p>
								{{$info->about_footer}}
							</p>
							<h4>{{__('Get a Quote')}}</h4>
							<span class="number">{{$info->phone}}</span>
						</div>
						<!--end .footer_textwidget .textwidget-->
					</div>
					<!--end .col-md-4-->
					<div class="col-md-2 col-sm-3">
						<h2>{{__('Quick Links')}}</h2>
						<ul class="footer_link">
                            @foreach($footer_links as $key=>$item)
                            @if($key%2==0)
							<li>
								<a href="{{url(''.$item->link)}}">{{$item->name}}</a>
                            </li>
                            @endif
                            
                            @endforeach
							
						</ul>
						<!--end .footer_link-->
					</div>
					<!--end .col-md-2-->
					<div class="col-md-2 col-sm-3">
						<ul class="footer_link extra_mt">
							<!-- <li><a href="">Careers</a></li> -->
                            @foreach($footer_links as $key=>$item)
                            @if($key%2!=0)
							<li>
								<a href="{{url(''.$item->link)}}">{{$item->name}}</a>
                            </li>
                            @endif
                            
                            @endforeach
						</ul>
						<!--end .footer_link-->
					</div>
					<!--end .col-md-2-->
					<div class="col-md-4 col-sm-6">
						<div class="newslatter">
							<h2>{{__('Request a Catalogue')}}</h2>
							<input type="EMAIL" placeholder="Enter your email">
							<button class="btn-yellow" value="SUBMIT NOW">REQUEST</button>
						</div>
						<!--end .newslatter-->
					</div>
					<!--end .col-md-4-->
				</div>
				<!--end .row-->
				<div class="copyright_area">
					<div class="row">
						<div class="col-md-8 col-sm-6 copyright_text">
							<p>&copy; {{__('copyright 2019 by')}}
								<a href="#">
									<span class="voila">Voila </span>
								</a>
							</p>
						</div>
						<!--end .col-md-8-->
						<div class="col-md-4 col-sm-6 copyright_social text-right">
							<ul>
								
                            @foreach($socials as $item)
							<li>
								<a href="{{url(''.$item->value)}}" target="_blank">
									<i class="{{$item->icon}}"></i>
								</a>
							</li>
                            @endforeach
							</ul>
						</div>
						<!--end .col-md-4-->
					</div>
					<!--end .row-->
				</div>
				<!--end .copyright_area-->
			</div>
			<!--end .container-->
		</div>
		<!--end .footer_content-->
		<!-- <img src="/assets/images/shape/footer-shape.png" class="footer_shape" alt="footer shape"> -->
	</footer>
	<!--end .footer_area-->
	<!--===========================
        End Footer
===========================-->


	<script src="/assets/js/jquery.min.js"></script>
	<script src="/assets/js/bootstrap.min.js"></script>
	<script src="/assets/js/owl.carousel.min.js"></script>
	<script src="/assets/js/jquery.magnific-popup.min.js"></script>
	<script src="/assets/js/waypoint.js"></script>
	<script src="/assets/js/jquery.counterup.min.js"></script>
	<script src="/assets/js/custom-map.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA_Agsvf36du-7l_mp8iu1a-rXoKcWfs2I"></script>
	<script src="/assets/js/custom.js"></script>
</body>

</html>