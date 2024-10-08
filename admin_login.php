<?php
include 'db.php';
include 'header.php';
 
session_start();
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
 
   
    // Tarkista käyttäjätunnus ja salasana (esimerkki kovakoodattu)
    $stmt = $yhteys->prepare("SELECT u.id, u.salasana, r.value FROM users u
                              JOIN roles r ON u.id = r.id
                              WHERE u.kayttajatunnus = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
 
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['salasana'])) {
            if ($row['value'] == 3) {
                $_SESSION['admin'] = true;
                header('Location: kasittelija_login.php');
                exit();
            } else {
                $error = "Sinulla ei ole oikeuksia kirjautua sisään.";
            }
        } else {
            $error = "Väärä käyttäjätunnus tai salasana.";
        }
    } else {
        $error = "Väärä käyttäjätunnus tai salasana.";
    }
}
?>
 
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>Admin Kirjautuminen</title>
</head>
<body>
    <h1>Admin Kirjautuminen</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form action="admin_login.php" method="post">
        <label for="username">Käyttäjätunnus:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Salasana:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit" name="login">Kirjaudu</button>
    </form>
    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == true): ?>
    <h2>Lisää tapahtuma tai vapaa aika</h2>
    <form action="admin_login.php" method="post">
        <label for="date">Päivämäärä:</label>
        <input type="date" id="date" name="date" required>
        <label for="tapahtumat">Tapahtumat:</label>
        <input type="text" id="tapahtumat" name="tapahtumat">
        <label for="vapaat_ajat">Vapaita aikoja:</label>
        <input type="text" id="vapaat_ajat" name="vapaat_ajat">
        <button type="submit">Lisää</button>
    </form>
    <?php endif: ?>
<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $date = $_POST['date'];
        $tapahtumat = $_POST['tapahtumat'];
        $vapaat_ajat = $_POST['vapaat_ajat'];
 
        if (!empty($tapahtumat)) {
            $stmt = $yhteys->prepare("INSERT INTO tapahtumat (paivamaara, tapahtumat) VALUES (?, ?)");
            $stmt->bind_param("ss", $date, $tapahtumat);
            if ($stmt->execute()) {
                echo "Tapahtuma lisätty onnistuneesti";
            } else {
                echo "Virhe: " . $stmt->error;
            }
            $stmt->close();
        }
 
        if (!empty($vapaat_ajat)) {
            $stmt = $yhteys->prepare("INSERT INTO vapaat_ajat (paivamaara, vapaat_ajat) VALUES (?, ?)");
            $stmt->bind_param("ss", $date, $vapaat_ajat);
            if ($stmt->execute()) {
                echo "Vapaa aika lisätty onnistuneesti";
            } else {
                echo "Virhe: " . $stmt->error;
            }
            $stmt->close();
        }
    }
    ?>
</body>
</html>