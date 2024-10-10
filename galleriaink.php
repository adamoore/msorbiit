<!DOCTYPE html>
<html lang="fi">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Galleria</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="rinksite.css">
	<?php include 'rinkheader.php'; ?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<style>
		.carousel-inner > .item > img {
			width: auto;
			height: 80vh;
			border-radius: 50px;
			border: 5px solid #72004e;
			margin: auto;
			background-color: #c165d8;
		    }
        a {
            color: #daf544;
            text-decoration: none;
            font-size: 16px;
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
	</ol>

	<!-- Wrapper for slides -->
	<div class="carousel-inner">
		<div class="item active">
			<img src="msorbiitkuvat/rink.jpg" alt="Annika ja laiva">
		</div>

		<div class="item">
			<img src="msorbiitkuvat/tatska1.jpeg" alt="Tatutointi 1">
		</div>

		<div class="item">
			<img src="msorbiitkuvat/tatska2.jpeg" alt="Tatuointi 2">
		</div>

		<div class="item">
			<img src="msorbiitkuvat/tatska3.jpeg" alt="Tatuointi 3">
		</div>

		<div class="item">
			<img src="msorbiitkuvat/annika.jpeg" alt="Annika Redsven">
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
</body>
</html>
<?php include 'footer.php'; ?>