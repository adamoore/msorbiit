<!DOCTYPE html>
<html lang="fi">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Galleria</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="site.css">
	<?php include 'header.php'; ?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<style>
		.carousel-inner > .item > img {
			width: auto;
			height: 150vh;
			border-radius: 50px;
			border: 5px solid #133667;
			margin: auto;
			background-color: #496E4B;
		}
		
		nav a:hover {
    	color: #133667;
    	text-decoration: none;
		}

		/* Aktiivisen sivun korostus */
		nav .active a {
    	background-color: #A7DDAC;
    	color: #133667;
    	text-decoration: none;
		}

		nav a:active {
    	background-color: #A7DDAC;
    	text-decoration: none;
		}

		a {
    	color: #FFFC99;
    	text-decoration: none;
		}

		footer {
    	text-align: center;
    	background: linear-gradient(to top right,#081a18,#3d9754);
		color: #FFFC99;
		}
	</style>
</head>
<body>

<div id="myCarousel" class="carousel slide" data-ride="carousel">
	<!-- Indicators -->
	<ol class="carousel-indicators">
		<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
		<li data-target="#myCarousel" data-slide-to="1"></li>
		<li data-target="#myCarousel" data-slide-to="2"></li>
		<li data-target="#myCarousel" data-slide-to="3"></li>
		<li data-target="#myCarousel" data-slide-to="4"></li>
		<li data-target="#myCarousel" data-slide-to="5"></li>
		<li data-target="#myCarousel" data-slide-to="6"></li>
		<li data-target="#myCarousel" data-slide-to="7"></li>
		<li data-target="#myCarousel" data-slide-to="8"></li>
	</ol>

	<!-- Wrapper for slides -->
	<div class="carousel-inner">
		<div class="item active">
			<img src="msorbiitkuvat/laiva1edit.jpg" alt="Laiva 1">
		</div>

		<div class="item">
			<img src="msorbiitkuvat/laiva2edit.jpg" alt="Laiva 2">
		</div>

		<div class="item">
			<img src="msorbiitkuvat/laiva3edit.jpg" alt="Laiva 3">
		</div>

		<div class="item">
			<img src="msorbiitkuvat/laiva4.jpg" alt="Laiva 4">
		</div>

		<div class="item">
			<img src="msorbiitkuvat/laiva5.jpg" alt="Laiva 5">
		</div>

		<div class="item">
			<img src="msorbiitkuvat/laiva6.jpg" alt="Laiva 6">
		</div>

		<div class="item">
			<img src="msorbiitkuvat/laiva7.jpg" alt="Laiva 7">
		</div>

		<div class="item">
			<img src="msorbiitkuvat/ohjaamo.jpg" alt="Laivan ohjaamo">
		</div>

		<div class="item">
			<img src="msorbiitkuvat/poster.jpg" alt="Posteri">
		</div>
	</div>

	<!-- Left and right controls -->
	<a class="left carousel-control" href="#myCarousel" data-slide="prev" style="height:100%">
		<span class="glyphicon glyphicon-chevron-left"></span>
		<span class="sr-only">Previous</span>
	</a>
	<a class="right carousel-control" href="#myCarousel" data-slide="next" style="height:100%">
		<span class="glyphicon glyphicon-chevron-right"></span>
		<span class="sr-only">Next</span>
	</a>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
