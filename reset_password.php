<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Tarkista, onko sähköpostiosoite olemassa tietokannassa
    $sql = "SELECT kayttajatunnus FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Lähetä sähköposti salasanan palautuslinkillä
        $reset_link = "http://yourwebsite.com/reset_password_confirm.php?email=" . urlencode($email) . "&token=" . urlencode($token);
        $subject = "Salasanan palautus";
        $message = "Klikkaa alla olevaa linkkiä palauttaaksesi salasanasi:\n\n" . $reset_link;
        $headers = "From: no-reply@yourwebsite.com";

        if (mail($email, $subject, $message, $headers)) {
            echo "Palautuslinkki on lähetetty sähköpostiisi.";
        } else {
            echo "Sähköpostin lähettäminen epäonnistui.";
        }
    } else {
        echo "Sähköpostiosoitetta ei löydy.";
    }
}
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salasanan palautus</title>
    <link rel="stylesheet" href="rekisteroityminen.css">
</head>
<body>
    <h1>Salasanan palautus</h1>
    <form action="reset_password.php" method="post">
        <label for="email">Sähköpostiosoite:</label>
        <input type="email" id="email" name="email" required><br>
        <button><a href="verification.php">Lähetä palautuslinkki!</a></button>
    </form>
</body>
</html>