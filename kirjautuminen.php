<?php
session_start();
include 'db.php';
include 'header.php';
 
if (isset($_SESSION['user_id'])) {
    header("Location: kasittelija_login.php");
    exit();
}
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kayttajatunnus = $_POST['username'];
    $salasana = $_POST['password'];
 
    // Valmisteleva lause SQL-injektion estämiseksi
    $stmt = $conn->prepare("SELECT u.id, u.kayttajatunnus, u.salasana, r.value FROM users u
                            JOIN roles r ON u.id = r.id
                            WHERE u.kayttajatunnus = ?");
    $stmt->bind_param("s", $kayttajatunnus);
    $stmt->execute();
    $result = $stmt->get_result();
 
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($salasana, $row['salasana'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = $row['value'];
            header("Location: kasittelija_login.php");
            exit();
        } else {
            echo "Virheellinen salasana";
        }
    } else {
        echo "Käyttäjänimiä ei löydy";
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirjaudu</title>
</head>
<body>
    <h1>Kirjaudu</h1>
    <form action="kirjautuminen.php" method="post">
        <label for="username">Käyttäjätunnus:</label>
        <input type="text" id="username" name="username" required><br>
        <p>Käyttäjätunnuksesi on sähköpostiosoitteesi. Jos sinulla ei ole käyttäjätunnusta, rekisteröidythän ensin.</p>
        <label for="password">Salasana:</label>
        <input type="password" id="password" name="password" required><br>
        <p>Salasanasi on se, jonka annoit rekisteröityessäsi.</p>
        <input type="submit" value="Kirjaudu" class="nappi"> <br>
        <input type = "submit" value="rekisteroidy" class="nappi"> <a href="rekisterointi.php" value="Rekisteröidy" class="nappi">Rekisteröidy</a>
        <a href="reset_password.php">Unohditko salasanasi?</a>
    </form>
</body>
</html>