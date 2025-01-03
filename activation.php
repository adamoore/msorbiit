<?php
/* Huom. aktivointitokenin voimassaoloa ei tässä tarkisteta.*/
$email_verified = $email_already_verified = $activation_error = "";
$token = $_GET['token'] ?? "";
if ($token) {
    $token = $yhteys->real_escape_string($token); 
    $query ="SELECT id,kayttajatunnus,salasana FROM signup_tokens s /*users taulun sarakkeet ovat id,kayttajatunnus,salasana,email,luotu*/
             LEFT JOIN users ON users_id = id WHERE s.token = '$token'";
    $result = $yhteys->query($query);
    if ($result->num_rows){
        list($id,$aktiivinen) = $result->fetch_row();
        if ($aktiivinen == 0) {
            $query = "UPDATE users SET aktiivinen  = '1' WHERE id = '$id'";
            $result = $yhteys->query($query);
            if ($result) {
                $email_verified = 
                  '<div class="alert alert-success">
                  Sähköpostiosoitteesi on vahvistettu.
                   </div>';
                }
            } 
        else {
            $email_already_verified = 
              '<div class="alert alert-danger">
               Sähköpostiosoitteesi on jo vahvistettu.
               </div>';
            }   
        $query = "DELETE FROM signup_tokens WHERE token = '$token'";
        $result = $yhteys->query($query);
        $poistettiin = $yhteys->affected_rows;
        } 
    else {
        $activation_error = 
          '<div class="alert alert-danger">
          Virhe, sähköpostiosoitteesi saattaa olla jo vahvistettu.
          </div>';
        }
    }
?>