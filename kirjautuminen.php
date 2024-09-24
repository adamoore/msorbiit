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
    <form action="login.php" method="post">
        <label for="username">Käyttäjänimi/Username:</label>
        <input type="text" id="username" name="username" required><br>
        
        <label for="password">Salasana:</label>
        <input type="password" id="password" name="password" required><br>
        
        <input type="submit" value="Kirjaudu">
    </form>
</body>
</html>