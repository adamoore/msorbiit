<?php 
include 'header.php'; 
include 'db.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $paivamaara = $_POST['paivamaara'];
    $aika = $_POST['aika'];

    $sql = "INSERT INTO varaukset (kayttajatunnus, paivamaara, aika) VALUES ('$kayttajatunnus', '$paivamaara', '$aika')";

    if ($conn->query($sql) === TRUE) {
        // Lähetä sähköpostivahvistus
        $to = $_SESSION['email'];
        $subject = "Varausvahvistus";
        $message = "Varaus on tehty onnistuneesti päivämäärälle $paivamaara klo $aika.";
        $headers = "From: msorbiit1981@gmail.com";

        mail($to, $subject, $message, $headers);

        echo "Varaus tallennettu onnistuneesti ja vahvistus lähetetty sähköpostiin.";
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
    <title>Varaa aika</title>
</head>
<body>
    <h1>Varaa aika tatuointiin!</h1>
    <form action="varaus.php" method="post">
        <label for="paivamaara">Päivämäärä:</label>
        <input type="date" id="paivamaara" name="paivamaara" required><br>
        
        <label for="aika">Aika:</label>
        <input type="time" id="aika" name="aika" required><br>
        
        <input type="submit" value="Varaa" name="painike">
    </form>
</body>
</html>