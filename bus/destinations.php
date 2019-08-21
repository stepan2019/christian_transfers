<?php
$host = "localhost";
$username = "brgambal_eu";
$password = "brgambal20!^";
$dbname = "brgambal_eu";
// Create connection
$conn = new mysqli($host, $username, $password,$dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


/*$host = "localhost";
$username = "root";
$password = "";
$dbname = "ccc";
// Create connection
$conn = new mysqli($host, $username, $password,$dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} */

$sql = "select * from aacompaniesname";
$result = mysqli_query($conn,$sql);
$page = $result->fetch_all();

$id = $page[0][0];
$name = $page[0][1];

function citiesCard($cityid, $cityname, $station, $conn){
	$one = '<article class="one-fourth">
		<div class="description">
			<div>
				<h3><a href="destination-single.html">'.$cityname.'</a></h3>';
	$k=0;
	if($station){
		
		foreach($station as $st){
			$sql2 = "select * from aastations where id='".$st[1]."'";
			$result2 = mysqli_query($conn,$sql2);
			$page2 = $result2->fetch_all();		
			if($k==4){
				break;
			}
			if(strlen($page2[0][1])<33){
				$one = $one.'<p><a href="#">'.$page2[0][1].'</a></p>';
				$k++;
			}
		}
	}
	$one =	$one.'</div>
			<a href="destination-single.html" class="more">See all</a>
		</div>
	</article>';
	if($k == 4){
		echo $one;
	}

}

?>
<!DOCTYPE html>
<html>
  <head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="keywords" content="Transfers - Private Transport and Car Hire HTML Template" />
	<meta name="description" content="Transfers - Private Transport and Car Hire HTML Template">
	<meta name="author" content="themeenergy.com">

	<title>Transfers - Destinations</title>

	<link rel="stylesheet" href="css/theme-pink.css" />
	<link rel="stylesheet" href="css/style.css" />
	<link rel="stylesheet" href="css/animate.css" />
	<link rel="stylesheet" href="css/icons.css" />
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Raleway:400,500,600,700|Montserrat:400,700">
	<link rel="shortcut icon" href="images/favicon.ico">
	<script src="https://use.fontawesome.com/e808bf9397.js"></script>
	
		<meta name="language" content="en">
		<meta http-equiv="content-language" content="ll-cc">
		<meta name="google-site-verification" content="Cw5reSoF0ZiKm_Xk6FMIrj5i-Ls_UzBiRR5QCaCKho4" />
		<meta name="msvalidate.01" content="20A862EDE06E9DCD7AC835A4D8CBBC71" />
		<meta name="yandex-verification" content="9712271bd60c8344" />
		<meta name="baidu-site-verification" content="" />

		<meta name="description" content="{$description}" />
		<meta name="keywords" content="airport transfers, minibus rentals, christian transfers" />
		<meta name="robots" content="index, follow, all" />
		<meta name="revisit-after" content="7days" />
		<link rel="cannonical" href="https://www.christiantransfers.eu" />
		
		<link rel="stylesheet" href="/css/theme-pink.css" />
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
		<link rel="stylesheet" href="/css/jquery-ui.theme.css" />
		<link rel="stylesheet" href="/css/style.css" />
		<link rel="stylesheet" href="/css/animate.css" />
		<link rel="stylesheet" href="/css/icons.css" />
		<link rel="stylesheet" href="/css/gallery.css" />
		<link href='https://fonts.googleapis.com/css?family=Raleway:400,500,600,700|Montserrat:400,700' rel='stylesheet'
			type='text/css'>
		<link rel="shortcut icon" href="images/favicon.ico" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
  </head>

  <body>
	<body class="home">
		<!-- Preloader -->
		<!--		<div class="preloader">
				<div id="followingBallsG">
					<div id="followingBallsG_1" class="followingBallsG"></div>
					<div id="followingBallsG_2" class="followingBallsG"></div>
					<div id="followingBallsG_3" class="followingBallsG"></div>
					<div id="followingBallsG_4" class="followingBallsG"></div>
				</div>
			</div>-->
		<!-- //Preloader -->
	
		<!-- Header -->
		<header class="header" role="banner">
			<div class="wrap">
				<!-- Logo -->
				<div class="logo">
					<a href="/" title="Christian Transfers - Airport Transfers"><img src="/images/logo1.png" width="180"
							height="85" alt="Christian Transfers logo" /></a>
				</div>
				<!-- //Logo -->
	
				<!-- Main Nav -->
				<nav role="navigation" class="main-nav">
					<ul>
						<li class="active"><a href="/" title="">Home</a></li>
						<li><a href="/airport-transfers" title="Airport Transfers">Transfers</a></li>
						<li><a href="/minibus" title="Minibus rentals">Minibus</a></li>
						<li><a href="/coaches" title="Coaches rentals">Coaches</a></li>
						<li><a href="/timetable" title="Timetable">Timetable</a></li>
						<li><a href="/contact" title="Contact">Contact</a>
							<ul>
								<li><a href="/tailor-made" title="Tailor mode">Tailor made</a></li>
							</ul>
						</li>
					</ul>
				</nav>
				<!-- //Main Nav -->
			</div>
		</header>
		<!-- //Header -->
	
		<main class="main" role="main">
			<!-- Intro -->
			<div class="intro">
				<div class="wrap">
					<div class="textwidget">
						<h1 class="wow fadeInDown">Need an Airport Transfer?</h1>
						<h2 class="wow fadeInUp">You've come to the right place.</h2>
						<div class="actions">
							<a href="https://www.christiantransfers.eu/#services" title="Our services" class="btn large white wow fadeInLeft anchor">Our
								services</a>
							<a href="https://www.christiantransfers.eu/#booking" title="Make a booking" class="btn large color wow fadeInRight anchor">Make a
								booking</a>
						</div>
					</div>
				</div>
			</div>
			<!-- //Intro -->
			<!-- Search -->
			<!-- <div class="advanced-search color" id="booking"> -->
			</div>
			<!-- //Search -->
	<!-- Main -->

	<main class="main" role="main">
		
		<div class="wrap">
			<div class="row">
				<!--- Content -->
				<div class="full-width content">
					
					<!-- Tabs -->
					<!-- <nav class="tabs six grey">
						<ul>
							<li><a href="#tab1" title="Africa">Africa</a></li>
							<li><a href="#tab2" title="Asia">Asia</a></li>
							<li><a href="#tab3" title="Australia">Australia</a></li>
							<li><a href="#tab4" title="Europe">Europe</a></li>
							<li><a href="#tab5" title="North America">North America</a></li>
							<li><a href="#tab6" title="South America">South America</a></li>
						</ul>
					</nav> -->
					<!-- //Tabs -->
				<br />
				<br />
					<!-- TabContent -->
					<div class="tab-content" id="tab1">
						<div class="row">
							<!-- Item -->
							<?php
								foreach($page as $city){
									$cityid = $city[0];
									$cityname = $city[1];
									$sql1 = "select * from aacompanystations where companyID='$cityid'";
									$result1 = mysqli_query($conn,$sql1);
									$page1 = $result1->fetch_all();
									if(sizeof($page1) > 3 && strlen($city[1]) < 18){								
										citiesCard($city[0], $city[1], $page1, $conn);
									}
								}
							?>
							<!-- //Item -->

							

							<!-- Pager -->
							<!-- <div class="pager">		
								<a href="#">1</a>
								<a href="#" class="current color">2</a>
								<a href="#">3</a>
								<a href="#">4</a>
								<a href="#">5</a>
							</div> -->
							<!-- //Pager -->
						</div>
					</div>
					<!-- //TabContent -->
				</div>
				<!--- //Content -->
			</div>
		</div>
	</main>
	<!-- //Main -->

	

	<!-- Footer -->
	<footer class="footer black" role="contentinfo">
		<div class="wrap">
			<div class="row">
				<!-- Column -->
				<article class="one-half">
					<h6>About us</h6>
					<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy.</p>
				</article>
				<!-- //Column -->
				
				<!-- Column -->
				<article class="one-fourth">
					<h6>Need help?</h6>
					<p>Contact us via phone or email:</p>
					<p class="contact-data"><span class="icon icon-themeenergy_call"></span> +1 555 555 555</p>
					<p class="contact-data"><span class="icon icon-themeenergy_mail-2"></span> <a href="mailto:help@transfers.com">help@transfers.com</a></p>
				</article>
				<!-- //Column -->
				
				<!-- Column -->
				<article class="one-fourth">
					<h6>Follow us</h6>
					<ul class="social">
						<li><a href="#" title="facebook"><i class="fa fa-fw fa-facebook"></i></a></li>
						<li><a href="#" title="twitter"><i class="fa fa-fw fa-twitter"></i></a></li>
						<li><a href="#" title="gplus"><i class="fa fa-fw fa-google-plus"></i></a></li>
						<li><a href="#" title="linkedin"><i class="fa fa-fw fa-linkedin"></i></a></li>
						<li><a href="#" title="pinterest"><i class="fa fa-fw fa-pinterest-p"></i></a></li>
						<li><a href="#" title="vimeo"><i class="fa fa-fw fa-vimeo"></i></a></li>
					</ul>
				</article>
				<!-- //Column -->
			</div>
			
			<div class="copy">
				<p>Copyright 2016, Themeenergy. All rights reserved. </p>
				
				<nav role="navigation" class="foot-nav">
					<ul>
						<li><a href="#" title="Home">Home</a></li>
						<li><a href="#" title="Blog">Blog</a></li>
						<li><a href="#" title="About us">About us</a></li>
						<li><a href="#" title="Contact us">Contact us</a></li>
						<li><a href="#" title="Terms of use">Terms of use</a></li>
						<li><a href="#" title="Help">Help</a></li>
						<li><a href="#" title="For partners">For partners</a></li>
					</ul>
				</nav>
			</div>
		</div>
	</footer>
	<!-- //Footer -->

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="js/jquery.uniform.min.js"></script>
	<script src="js/jquery.slicknav.min.js"></script>
	<script src="js/wow.min.js"></script>
	<script src="js/scripts.js"></script>
  </body>
</html>