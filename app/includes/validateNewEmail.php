<?php
require('../../includes/config.php');

//if not logged in redirect to login page
if (!$user->is_logged_in()) {
    header('Location: ../index.php?redir=' . $filename . '');
}

if (isset($_GET["key"]) and $_GET["key"] != "") {

    $stmt = $db->prepare('SELECT username, token_newEmail, newEmail FROM users WHERE token_newEmail = :token');
    $stmt->execute(array(':token' => $_GET['key']));
    $row = $stmt->fetch();

    //if no token from db then kill the page
    if (empty($row['resetToken'])) {
        $stop = _("Token fornito non valido, usa il link all'interno dell'email.");
        header("Location: ../index.php?emailError={$stop}");
    } elseif ($row['resetComplete'] == 'Yes') {
        $stop = _('La tua email è già stata cambiata con questo token!');
        header("Location: ../index.php?emailError={$stop}");
    }
    try {

        $stmt = $db->prepare("UPDATE users SET email = :newEmail, token_newEmail = '', newEmail = ''  WHERE username = :usr");
        $stmt->execute(array(
            ':newEmail' => $row["newEmail"],
            ':usr' => $row['username']
        ));

        //redirect to index page
        header('Location: ../index.php?newEmail=success');
        exit;

        //else catch the exception and show the error.
    } catch (PDOException $e) {
        $error[] = $e->getMessage();
    }
}