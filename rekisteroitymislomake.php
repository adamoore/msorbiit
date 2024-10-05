<?php 


$tietokanta = "kalenteri";
$title = 'Rekisteröityminen';
$kentat = ['etunimi','sukunimi','email','password','password2'];
$kentat_suomi = ['etunimi','sukunimi','email','salasana','salasana'];
$pakolliset = ['etunimi','sukunimi','email','password','password2'];

$kentat_tiedosto = ['image'];
$css = 'rekisteröityminen.css';
//echo "<script>const virheilmoitukset = $virheilmoitukset_json</script>";


include "posti.php";
include "rekisterointi.php";

// Varmista, että $success on määritelty
$success = $success ?? "";

?>
<div class="container"> 

<?php 
if ($success != "success") { ?>



<form method="post" class="mb-3 needs-validation" enctype="multipart/form-data" novalidate >
<fieldset>
<legend>Rekisteröityminen</legend>

<div class="row">
<label for="etunimi" class="col-sm-4 form-label">Etunimi</label>
<div class="col-sm-8">
<input pattern="<?= pattern("etunimi"); ?>" type="text" class="mb-1 form-control <?= is_invalid('etunimi'); ?>" name="etunimi" id="etunimi" 
       placeholder="Etunimi" value="<?= arvo("etunimi"); ?>" 
       required autofocus> 
<div class="invalid-feedback">
<?= $errors['etunimi'] ?? ""; ?>    
</div>
</div>    
</div>

<div class="row">
<label for="sukunimi" class="col-sm-4 form-label">Sukunimi</label>
<div class="col-sm-8">
<input type="text" class="mb-1 form-control <?= is_invalid('sukunimi'); ?>" name="sukunimi" id="sukunimi" 
       placeholder="Sukunimi" value="<?= arvo("sukunimi"); ?>" required>
<div class="invalid-feedback">
<?= $errors['sukunimi'] ?? ""; ?>    
</div>
</div>
</div>

<div class="row">
<label for="puhelin" class="col-sm-4 form-label">Puhelinnumero</label>
<div class="col-sm-8">
<input type="tel" class="mb-1 form-control <?= is_invalid('puhelin'); ?>" name="puhelin" id="puhelin" 
       placeholder="Puhelinnumero" value="<?= arvo("puhelin"); ?>" required>
<div class="invalid-feedback">
<?= $errors['puhelin'] ?? ""; ?>    
</div>
</div>
</div>

<div class="row">
<label for="email" class="col-sm-4 form-label">Sähköpostiosoite</label>
<div class="col-sm-8">
<input type="email" class="mb-1 form-control <?= is_invalid('email'); ?>" name="email" id="email" 
       placeholder="etunimi.sukunimi@palvelu.fi" value="<?= arvo("email"); ?>"
       pattern="<?= pattern('email'); ?>" required>
<div class="invalid-feedback">
<?= $errors['email'] ?? ""; ?>    
</div>
</div>
</div>

<div class="row">
<label for="password" class="col-sm-4 form-label">Salasana</label>
<div class="col-sm-8">
<input type="password" class="mb-1 form-control <?= is_invalid('password'); ?>" name="password" id="password" 
       placeholder="salasana" pattern="<?= pattern('password'); ?>" required>
<div class="invalid-feedback">
<?= $errors['password'] ?? ""; ?>    
</div>
</div>
</div>

<div class="row">
<label for="password2" class="text-nowrap col-sm-4 form-label">Salasana uudestaan</label>
<div class="col-sm-8">
<input type="password" class="mb-1 form-control <?= is_invalid('password2'); ?>" name="password2" id="password2" 
       placeholder="salasana uudestaan" pattern="<?= pattern('password2'); ?>" required>
<div class="invalid-feedback">
<?= $errors['password2'] ?? ""; ?>    
</div>
</div>
</div>

<div class="row mb-sm-1">
<label for="image" class="form-label mb-0 col-sm-4">Kuva</label>
<div class="col-sm-8">
<input id="image" name="image" type="file" accept="image/*" pattern="<?= pattern('image'); ?>" class="form-control <?= is_invalid('image'); ?>" placeholder="kuva"></input>
<div class="invalid-feedback">
<?= $errors['image'] ?? ""; ?>
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

<?php } ?>

<div  id="ilmoitukset" class="alert alert-<?= $success ;?> alert-dismissible fade show <?= $display ?? ""; ?>" role="alert">
<p><?= $message; ?></p>
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

</div>
<?php