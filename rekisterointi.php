<?php

session_start();
include 'db.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Yhteys epäonnistui: " . $conn->connect_error);
}

$success = false;
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];

    $sql = "INSERT INTO users (kayttajatunnus, salasana, email) VALUES ('$kayttajatunnus', '$salasana', '$email')";

    if ($conn->query($sql) === TRUE) {
        $success = true;
        $message = "Rekisteröinti onnistui";
    } else {
        $message = "Virhe: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

