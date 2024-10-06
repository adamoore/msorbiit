<?php
session_start();
include 'db.php';
// Tietokantayhteys
$servername = "Hasse";
$username = "root";
$password = "";
$dbname = "kalenteri";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Yhteys epäonnistui: " . $conn->connect_error);
}

// Käyttäjän rekisteröinti
if (isset($_POST['rekisterointi'])) {
    $user = $_POST['username'];
    $pass = password_hash($_POST['salasana'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (username, password) VALUES ('$user', '$pass')";

    if ($conn->query($sql) === TRUE) {
        echo "Rekisteröinti onnistui!";
    } else {
        echo "Virhe: " . $sql . "<br>" . $conn->error;
    }
}

// Käyttäjän kirjautuminen
if (isset($_POST['kirjaudu'])) {
    $user = $_POST['username'];
    $pass = $_POST['salasana'];

    $sql = "SELECT * FROM users WHERE username='$user'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($pass, $row['salasana'])) {
            $_SESSION['username'] = $user;
            echo "Kirjautuminen onnistui!";
        } else {
            echo "Väärä salasana!";
        }
    } else {
        echo "Käyttäjää ei löydy!";
    }
}

// Käyttäjän uloskirjautuminen
if (isset($_GET['kirjaudu_ulos'])) {
    session_destroy();
    echo "Olet kirjautunut ulos!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Käyttäjän tunnistautuminen</title>
</head>
<body>
    <h2>Rekisteröidy</h2>
    <form method="post" action="">
        Käyttäjänimi: <input type="text" name="username" required><br>
        Salasana: <input type="password" name="password" required><br>
        <input type="submit" name="register" value="Rekisteröidy">
    </form>

    <h2>Kirjaudu sisään</h2>
    <form method="post" action="">
        Käyttäjänimi: <input type="text" name="username" required><br>
        Salasana: <input type="password" name="password" required><br>
        <input type="submit" name="login" value="Kirjaudu sisään">
    </form>

    <h2>Kirjaudu ulos</h2>
    <form method="get" action="">
        <input type="submit" name="logout" value="Kirjaudu ulos">
    </form>
</body>
</html>