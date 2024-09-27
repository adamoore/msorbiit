<?php include 'header.php'; ?>
<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kalenteri";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Yhteys epäonnistui: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id, password FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            header("Location: kalenteri.php");
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
        <input type="submit" value="Rekisteröidy" class="nappi">
        <a href="">Unohditko salasanasi?</a>
    </form>
</body>
</html>