<?php
 
$tietokanta = "kalenteri";
$title = 'Rekisteröityminen';
$kentat = ['etunimi','sukunimi','email','salasana','salasana2'];
$kentat_suomi = ['etunimi','sukunimi','email','salasana','salasana'];
$pakolliset = ['etunimi','sukunimi','email','salasana','salasana2'];
$kentat_tiedosto = ['image'];
$css = 'rekisteroityminen.css';
 
include "db.php";
include "debuggeri.php";
include "virheilmoitukset.php";
include "posti.php";
include "rekisterointi.php";
 
// Initialize variables to avoid undefined variable errors
$etunimi = '';
$sukunimi = '';
$email = '';
$password = '';
$password2 = '';
$puhelin = '';
$image = null;
$errors = [];
$success = '';
$display = '';
$message = '';
 
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Perform validation and processing here
    $etunimi = $_POST['etunimi'] ?? '';
    $sukunimi = $_POST['sukunimi'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    $puhelin = $_POST['puhelin'] ?? '';
    $image = $_FILES['image'] ?? null;
 
// Tarkista, että salasanat täsmäävät
       if ($salasana !== $salasana2) {
       $errors['salasana2'] = "Salasanat eivät täsmää";
       }

// Jos ei ole virheitä, tallenna tiedot tietokantaan ja lähetä sähköposti
   if (empty($errors)) {
       // Tallenna tiedot tietokantaan
       $conn = new mysqli("localhost", "root", "", "kalenteri");
       if ($conn->connect_error) {
           die("Yhteys epäonnistui: " . $conn->connect_error);
       }

       //$hashed_password = password_hash($salasana, PASSWORD_DEFAULT);
       $stmt = $conn->prepare("INSERT INTO users (etunimi, sukunimi, email, salasana) VALUES (?, ?, ?, ?)");
       $stmt->bind_param("ssss", $etunimi, $sukunimi, $email, $password);
       $stmt->execute();
       $stmt->close();

       // Luo token
       $token = bin2hex(random_bytes(16));

    // Add your validation logic here
    if (empty($etunimi)) {
        $errors['etunimi'] = 'Etunimi on pakollinen.';
    }
    if (empty($sukunimi)) {
        $errors['sukunimi'] = 'Sukunimi on pakollinen.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Virheellinen sähköpostiosoite.';
    }
    if (empty($salasana)) {
        $errors['salasana'] = 'Salasana on pakollinen.';
    } elseif (strlen($salasana) < 12) {
        $errors['salasana'] = 'Salasanan on oltava vähintään 12 merkkiä pitkä.';
    }
    if ($salasana !== $salasana2) {
        $errors['salasana2'] = 'Salasanat eivät täsmää.';
    }
    if (empty($puhelin)) {
        $errors['puhelin'] = 'Puhelinnumero on pakollinen.';
    }
 
    // If no errors, set success message
    if (empty($errors)) {
        $success = 'success';
        $display = 'show';
        $message = 'Rekisteröinti onnistui!';
    }
}}
?>
 
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $title; ?></title>
<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="rekisteroityminen.css" rel="stylesheet">
</head>
<body>
<form method="post" class="mb-3 needs-validation" enctype="multipart/form-data" novalidate>
<fieldset>
<legend>Rekisteröityminen</legend>
<div class="container">
    <div class="row">
        <label for="etunimi" class="col-sm-4 form-label">Etunimi</label>
        <div class="col-sm-8">
            <input pattern="[A-Za-zÅÄÖåäö]+" type="text" class="mb-1 form-control <?= isset($errors['etunimi']) ? 'is-invalid' : ''; ?>" name="etunimi" id="etunimi" placeholder="Etunimi" value="<?= htmlspecialchars($etunimi); ?>" required autofocus>
            <div class="invalid-feedback">
                <?= $errors['etunimi'] ?? ''; ?>
            </div>
        </div>
    </div>
 
    <div class="row">
        <label for="sukunimi" class="col-sm-4 form-label">Sukunimi</label>
        <div class="col-sm-8">
            <input pattern="[A-Za-zÅÄÖåäö]+" type="text" class="mb-1 form-control <?= isset($errors['sukunimi']) ? 'is-invalid' : ''; ?>" name="sukunimi" id="sukunimi" placeholder="Sukunimi" value="<?= htmlspecialchars($sukunimi); ?>" required>
            <div class="invalid-feedback">
                <?= $errors['sukunimi'] ?? ''; ?>
            </div>
        </div>
    </div>
 
    <div class="row">
        <label for="puhelin" class="col-sm-4 form-label">Puhelinnumero</label>
        <div class="col-sm-8">
            <input type="tel" class="mb-1 form-control <?= isset($errors['puhelin']) ? 'is-invalid' : ''; ?>" name="puhelin" id="puhelin" placeholder="Puhelinnumero" value="<?= htmlspecialchars($puhelin); ?>" required>
            <div class="invalid-feedback">
                <?= $errors['puhelin'] ?? ''; ?>
            </div>
        </div>
    </div>
 
    <div class="row">
        <label for="email" class="col-sm-4 form-label">Sähköpostiosoite</label>
        <div class="col-sm-8">
            <input type="email" class="mb-1 form-control <?= isset($errors['email']) ? 'is-invalid' : ''; ?>" name="email" id="email" placeholder="etunimi.sukunimi@palvelu.fi" value="<?= htmlspecialchars($email); ?>" required>
            <div class="invalid-feedback">
                <?= $errors['email'] ?? ''; ?>
            </div>
        </div>
    </div>
 
    <div class="row">
        <label for="password" class="col-sm-4 form-label">Salasana</label>
        <div class="col-sm-8">
            <input type="password" class="mb-1 form-control <?= isset($errors['salasana']) ? 'is-invalid' : ''; ?>" name="salasana" id="salasana" placeholder="Salasana" required>
            <div class="invalid-feedback">
                <?= $errors['salasana'] ?? ''; ?>
            </div>
        </div>
    </div>
 
    <div class="row">
        <label for="password2" class="text-nowrap col-sm-4 form-label">Salasana uudestaan</label>
        <div class="col-sm-8">
            <input type="password" class="mb-1 form-control <?= isset($errors['salasana2']) ? 'is-invalid' : ''; ?>" name="salasana2" id="salasana2" placeholder="Salasana uudestaan" required>
            <div class="invalid-feedback">
                <?= $errors['salasana2'] ?? ''; ?>
            </div>
        </div>
    </div>
 
    <div class="row mb-sm-1">
        <label for="image" class="form-label mb-0 col-sm-4">Kuva</label>
        <div class="col-sm-8">
            <input id="image" name="image" type="file" accept="image/*" class="form-control <?= isset($errors['image']) ? 'is-invalid' : ''; ?>" placeholder="Kuva">
            <div class="invalid-feedback">
                <?= $errors['image'] ?? ''; ?>
            </div>
        </div>
        <div class="previewDiv mt-1 col-sm-8 d-none" id="previewDiv">
            <img class="previewImage" src="" id="previewImage" width="" height="">
            <button type="button" class="btn btn-outline-secondary btn-sm float-end mt-1" onclick="tyhjennaKuva('image')">Poista</button>
        </div>
    </div>
</div>
       
<button name='painike' type="submit" class="mt-3 float-end btn btn-primary">Rekisteröidy</button>
</fieldset>
</form>
 
<?php if ($success === 'success'): ?>
<div id="ilmoitukset" class="alert alert-<?= $success; ?> alert-dismissible fade show <?= $display; ?>" role="alert">
<p><?= $message; ?></p>
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>
 
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php include "footer.php"; ?>