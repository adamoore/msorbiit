<?php include 'header.php'; ?>
<?php
session_start();
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Tarkista käyttäjätunnus ja salasana (esimerkki kovakoodattu)
    if ($username == 'admin' && $password == 'adminpassword') {
        $_SESSION['admin'] = true;
        header('Location: kalenteri.php');
        exit();
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

    <!-- Alla oleva admin form otettu kalenteri.php:stä -->
    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == true): ?>
    <form action="kalenteri.php" method="post">
        <label for="date">Päivämäärä:</label>
        <input type="date" id="date" name="date" required>
        <label for="tapahtumat">Tapahtumat:</label>
        <input type="text" id="tapahtumat" name="tapahtumat">
        <label for="vapaat_ajat">Vapaita aikoja:</label>
        <input type="text" id="vapaat_ajat" name="vapaat_ajat">
        <button type="submit">Lisää</button>
    </form>
    <?php else: ?>

        <!--Jos admin haluaa lisätä tapahtuman tai vapaan ajan?
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $date = $_POST['date'];
            $tapahtumat = $_POST['tapahtumat'];
            $vapaat_ajat = $_POST['vapaat_ajat'];

            if (!empty($tapahtumat)) {
                $sql = "INSERT INTO tapahtumat (paivamaara, tapahtumat) VALUES ('$paivamaara', '$tapahtumat')";
                echo $sql;
                exit;
                if ($conn->query($sql) === TRUE) {
                    echo "Tapahtuma lisätty onnistuneesti";
                } else {
                    echo "Virhe: " . $sql . "<br>" . $conn->error;
                }
            }

            if (!empty($vapaat_ajat)) {
                $sql = "INSERT INTO vapaat_ajat (paivamaara, vapaat_ajat) VALUES ('$date', '$vapaat_ajat')";
                if ($conn->query($sql) === TRUE) {
                    echo "Vapaa aika lisätty onnistuneesti";
                } else {
                    echo "Virhe: " . $sql . "<br>" . $conn->error;
                }
            }
        }*/-->

</body>
</html> 