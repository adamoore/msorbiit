<?php 
include "asetukset.php";
include "connect.php";
include "rememberme.php";
$loggedIn = secure_page();
$title = 'Profiili';
$css = 'profiili.css';
include "header.php"; 
?>
<div class="container">
<!-- Kuva ja perustiedot TÄÄ ON JUKAN FILE-->
<img src="https://cdn.pixabay.com/photo/2019/07/02/03/10/highland-cattle-4311375_1280.jpg" alt="Profiilikuva" class="profile-image">
<div class="info-section">
    <div class="info-title">Nimi:</div>
    <div>$kayttajatunnus</div>
</div>

<!-- Yhteystiedot -->
<div class="info-section">
    <div class="info-title">Yhteystiedot:</div>
    <div>Email: $email</div>
 <!--   <div>Puhelin: </div> -->

</div>
<!-- alla oleva varmistaa että site.css ladataan ensi ja vasta sitten profiili.css -->
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="site.css">
    <link rel="stylesheet" href="<?php echo $css; ?>">
</head>
<body>
<?php include "footer.php"; ?>