<?php
$servername = "127.0.0.1"; // Korvaa tämä palvelimen IP-osoitteella
$username = "root"; // Korvaa tämä luomallasi käyttäjänimellä
$password = ""; // Korvaa tämä luomallasi salasanalla
$dbname = "kalenteri"; // Korvaa tämä tietokannan nimellä
$port = 3306; // Portti, oletuksena 3306

// Luo yhteys
$conn = new mysqli($servername, $username, $password, $dbname);

// Tarkista yhteys
if ($conn->connect_error) {
    die("Yhteys epäonnistui: " . $conn->connect_error);
}
echo "Yhteys onnistui";
?>