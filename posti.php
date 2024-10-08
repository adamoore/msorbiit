<?php
// Tarkista, onko lomake lähetetty
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Alusta virhelista
	$errors = [];

	// Tarkista pakolliset kentät
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
		$conn = new mysqli("localhost", "root", "", "kalenteri");
		if ($conn->connect_error) {
			die("Yhteys epäonnistui: " . $conn->connect_error);
		}

		$stmt = $conn->prepare("INSERT INTO users (etunimi, sukunimi, email, salasana) VALUES (?, ?, ?, ?)");
		$stmt->bind_param("ssss", $_POST['etunimi'], $_POST['sukunimi'], $_POST['email'], password_hash($_POST['salasana'], PASSWORD_DEFAULT));
		$stmt->execute();
		$stmt->close();

		// Lähetä vahvistusviesti seuraava on verification.php
		$to = $_POST['email'];
		$subject = "Vahvista sähköpostiosoitteesi";
		$message = "Hei " . $_POST['etunimi'] . ",\n\nKiitos rekisteröitymisestä! Vahvista sähköpostiosoitteesi klikkaamalla alla olevaa linkkiä:\n\n";
		$message .= "http://yourwebsite.com/vahvista.php?email=" . urlencode($to) . "&token=" . urlencode($token);
		$headers = "From: no-reply@yourwebsite.com";

		if (mail($to, $subject, $message, $headers)) {
			echo "Vahvistusviesti on lähetetty sähköpostiisi.";
		} else {
			echo "Vahvistusviestin lähettäminen epäonnistui.";
		}

		$conn->close();

		// Aseta onnistumisviesti
		$success = "success";
	}
}
?>