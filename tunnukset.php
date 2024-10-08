<?php
session_start();
include 'db.php';

$admin_mail = 'msorbiit1981@gmail.com';
$username_mailtrap = 'msorbiit1981@gmail.com';
$password_mailtrap = 'peruna88';
 
 // Käyttäjän rekisteröinti
if (isset($_POST['rekisterointi'])) {
    $user = $_POST['kayttajatunnus'];
    $pass = password_hash($_POST['salasana'], PASSWORD_BCRYPT);
 
    $sql = "INSERT INTO users (kayttajatunnus, salasana) VALUES ('$user', '$pass')";
 
    if ($conn->query($sql) === TRUE) {
        echo "Rekisteröinti onnistui!";
    } else {
        echo "Virhe: " . $sql . "<br>" . $conn->error;
    }
}
// Käyttäjän kirjautuminen
if (isset($_POST['kirjaudu'])) {
    $user = $_POST['kayttajatunnus'];
    $pass = $_POST['salasana'];
 
    $sql = "SELECT * FROM users WHERE kayttajatunnus='$user'";
    $result = $conn->query($sql);
 
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($pass, $row['salasana'])) {
            $_SESSION['kayttajatunnus'] = $user;
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
        Käyttäjänimi: <input type="text" name="kayttajatunnus" required><br>
        Salasana: <input type="salasana" name="salasana" required><br>
        <input type="submit" name="painike" value="Rekisteröidy">
    </form>
 
    <h2>Kirjaudu sisään</h2>
    <form method="post" action="">
        Käyttäjänimi: <input type="text" name="kayttajatunnus" required><br>
        Salasana: <input type="salasana" name="salasana" required><br>
        <input type="submit" name="painike" value="Kirjaudu sisään">
    </form>
 
    <h2>Kirjaudu ulos</h2>
    <form method="get" action="">
        <input type="submit" name="logout" value="Kirjaudu ulos">
    </form>
</body>
</html>