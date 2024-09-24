<?php include 'header.php'; ?>
<?php
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
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];

    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";

    if ($conn->query($sql) === TRUE) {
        echo "Rekisteröinti onnistui";
    } else {
        echo "Virhe: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekisteröidyt</title>
</head>
<body>
    <h1>Rekisteröidy</h1>
    <form action="register.php" method="post">
        <label for="username">Käyttäjänimi:</label>
        <input type="text" id="username" name="username" required><br>
        
        <label for="password">Salasana:</label>
        <input type="password" id="password" name="password" required><br>
        
        <label for="email">Sähköposti:</label>
        <input type="email" id="email" name="email" required><br>
        
        <input type="submit" value="Rekisteröidy">
    </form>
</body>
</html>