<?php
require_once("../../includes/config.php");

if (isset($_POST["text"])) {
    if ($_POST["input"] == "username" or $_POST["input"] == "email") {
        if ($_POST["input"] == "username") {

        }
        $select = $db->prepare("SELECT {$_POST["input"]} FROM users");
        $select->execute();
        $res = $select->fetchAll();
        $searched = [];
        foreach ($res as $row) {
            array_push($searched, $row[$_POST["input"]]);
        }
        if (in_array($_POST["text"], $searched)) {
            if ($_POST["input"] == "username") {
                $ph = _("Nome utente già utilizzato. Scegline un altro.");
            } else {
                $ph = _("Email già utilizzata. Scegline un'altra.");
            }
            die($ph);
        } else {
            if ($_POST["input"] == "email") {
                // crea il codice di attivazione
                $token = md5(uniqid(rand(), true));
                // inserisce l'email e il codice nel campo dell'utente, in attesa della verifica
                $query = $db->prepare("UPDATE users SET newEmail = :email, token_newEmail = :token WHERE username = :usr");
                $query->execute(array("email" => $_POST["text"], "token" => $token, "usr" => $_POST["username"]));
                // invia l'email per l'attivazione della nuova email
                try {
                    $select = $db->prepare("SELECT email FROM users WHERE username = :usr");
                    $select->execute(array("usr" => $_POST["username"]));
                    $res = $select->fetch();
                    //send email
                    $to = $res["email"];
                    $subject = _("Cambio email") . " - " . _("Interrogazioni Programmate");
                    /** @noinspection JSUnusedGlobalSymbols */
                    $body = "<style>
@import url('https://fonts.googleapis.com/css?family=Black+Ops+One');
.logo-text {
    font-family: 'Black Ops One', cursive !important;
    font-variant: small-caps !important;
    color: #003471;
}
</style><p style=\"text-align:center;\"><img src=\"https://dev.interrogazioniprogrammate.tk/img/logo.svg\" alt=\"Interrogazioni programmate\"
                                                           align=\"center\" width=\"128\" height=\"128\"
                                                           onerror=\"this.src='https://dev.interrogazioniprogrammate.tk/img/logo.png'\"></p>
                        <h3 align=\"center\" class='logo-text'>" . _("Interrogazioni Programmate") . "</h3>
            " . _("<p>Qualcuno ha richiesto il reset della tua password.</p><p>Se non sei stato tu , ignora questa email e non succederà niente.</p>
			<p>Per resettare la tua password, visita il seguente indirizzo:") . " <a href='" . DIR . "/app/includes/validateNewEmail.php?key=$token'>" . _("Resetta la password") . "</a></p>
			<p>" . _("Se il collegamento sopra non dovesse funzionare, copia e incolla nel browser il seguente indirizzo:") . "</p>
			<p align='center'>" . DIR . "/app/includes/validateNewEmail.php?key=$token</p>";

                    $mail = new Mail();
                    $mail->setFrom(SITEEMAIL);
                    $mail->addAddress($to);
                    $mail->subject($subject);
                    $mail->body($body);
                    $mail->send();

                    // Invia conferma a JS
                    echo "newEmailOK";
                    exit;

                    // Altrimenti metti l'errore in una variabile (verrà mostrato successivamente)
                } catch (PDOException $e) {
                    die($e->getMessage());
                }
            }
        }
    } elseif ($_POST["input"] == "password") {
        if (strlen($_POST['text']) < 8) {
            die(_('La password inserita è troppo corta.'));
        }
        $select = $db->prepare("SELECT password FROM users WHERE username = :usr");
        $select->execute(array("usr" => $_POST["username"]));
        $hash = $select->fetch()[0];
        if ($user->password_verify($_POST["text"], $hash)) {
            $query = $db->prepare("DELETE FROM users WHERE username = :usr");
            $query->execute(array("usr" => $_POST["username"]));
            echo "OK";
            exit();
        } else {
            die("PSW_ERR");
        }
    }
    try {
        if ($_POST["input"] == "username") {
            $query = $db->prepare("UPDATE users SET {$_POST["input"]} = :val WHERE userID = :id");
            $query->execute(array("val" => $_POST["text"], "id" => $_POST["userID"]));
        } else {
            $query = $db->prepare("UPDATE users SET {$_POST["input"]} = :val WHERE username = :user");
            $query->execute(array("val" => $_POST["text"], "user" => $_POST["username"]));
        }
        echo "OK";
    } catch (PDOException $e) {
        $error[] = $e->getMessage();
    }
}