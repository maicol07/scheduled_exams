<?php
require('includes/config.php');

//collect values from the url
$userID = trim($_GET['userID']);
$active = trim($_GET['token']);

//if id is number and the active token is not empty carry on
if (is_numeric($userID) && !empty($active)) {

    //update users record set the active column to Yes where the userID and active value match the ones provided in the array
    $stmt = $db->prepare("UPDATE users SET active = 'Yes' WHERE userID = :userID AND active = :active");
    $stmt->execute(array(
        ':userID' => $userID,
        ':active' => $active
    ));

    //if the row was updated redirect the user
    if ($stmt->rowCount() == 1) {

        //redirect to login page
        header('Location: index.php?action=active');
        exit;

    } else {
        echo '<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>';
        echo '<script>swal({
  title: "Errore!",
  text: "Il tuo account non può essere attivato. Contattare il supporto nella <a href=\'community.interrogazioniprogrammate.tk\'>Community</a>.",
  icon: "error",
});</script>';
    }
}