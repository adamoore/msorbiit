<?php
// Sisällytä tietokannan kirjautumistiedot
//include 'tunnukset.php';

// Tarkista, onko lomake lähetetty
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Alusta virhelista
    $errors = [];

    // Tarkista pakolliset kentät
    $pakolliset = ['etunimi', 'sukunimi', 'email', 'password', 'password2'];
    foreach ($pakolliset as $kentta) {
        if (empty($_POST[$kentta])) {
            $errors[$kentta] = "Kenttä on pakollinen";
        }
    }

    // Tarkista, että salasanat täsmäävät
    if ($_POST['password'] !== $_POST['password2']) {
        $errors['password2'] = "Salasanat eivät täsmää";
    }

    // Jos ei ole virheitä, tallenna tiedot tietokantaan ja lähetä sähköposti
    if (empty($errors)) {
        // Tallenna tiedot tietokantaan
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Yhteys epäonnistui: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO users (etunimi, sukunimi, email, salasana) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $_POST['etunimi'], $_POST['sukunimi'], $_POST['email'], password_hash($_POST['password'], PASSWORD_DEFAULT));
        $stmt->execute();
        $stmt->close();

        include 'asetukset.php';

        // Lähetä vahvistusviesti
        $token = bin2hex(random_bytes(16)); // Luo satunnainen token
        $to = $_POST['email'];
        $subject = "Vahvista sähköpostiosoitteesi";
        $message = "Hei " . $_POST['etunimi'] . ",\n\nKiitos rekisteröitymisestä! Vahvista sähköpostiosoitteesi klikkaamalla alla olevaa linkkiä:\n\n";
        $message .= "http://yourwebsite.com/vahvista.php?email=" . urlencode($to) . "&token=" . urlencode($token);

        $mail->send();
        $success = "Vahvistusviesti on lähetetty sähköpostiisi.";
		//} catch (Exception $e) {
		$errors['mail'] = "Vahvistusviestin lähettäminen epäonnistui. Mailer Error: {$mail->ErrorInfo}";
        }

        $conn->close();
    }
?>