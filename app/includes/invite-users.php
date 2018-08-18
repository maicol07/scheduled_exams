<?php
require_once "../../includes/config.php";
if (isset($_POST["email_list"]) and $_POST["email_list"] !== "") {
    $emails = explode(", ", $_POST["email_list"]);
    foreach ($emails as $email) {
        $userslist = $user->get_class_data($_POST["classid"])["users"];
        foreach (explode(", ", $userslist) as $username) {
            $select = $db->prepare("SELECT email FROM users WHERE username = :uname");
            $select->execute(array("uname" => $username));
            $email_list = $select->fetchAll();
            $email_ar = [];
            foreach ($email_list as $e) {
                array_push($email_ar, $e[0]);
            }
        }
        if (in_array($email, $email_ar)) {
            continue;
        }
        // crea il token
        $token = md5(uniqid(rand(), true));
        $query = $db->prepare("UPDATE classi SET invitationtokens = CONCAT(invitationtokens, ', ', :newtoken) WHERE ID = :id");
        $query->execute(array("newtoken" => $token, "id" => $_POST["classid"]));
        $to = $email;
        $subject = "Invito alla classe " . $_POST['classname'] . " - Interrogazioni Programmate";
        $body = '<style>
    @import url("https://fonts.googleapis.com/css?family=Black+Ops+One");
    .logo-text {
    font-family: "Black Ops One", cursive !important;
    font-variant: small-caps !important;
    color: #003471;
    }
    </style><p style="text-align:center;"><img src="https://dev.interrogazioniprogrammate.tk/img/logo.svg" alt="Interrogazioni programmate"
                                                       align="center" width="128" height="128"
                                                       onerror="this.src=\'https://dev.interrogazioniprogrammate.tk/img/logo.png\'"></p>
                    <h3 align="center" class="logo-text">Interrogazioni Programmate</h3>
        <p>L\'utente ' . $_POST["user"] . ' ti ha invitato a partecipare alla classe ' . $_POST["classname"] . '.</p>
        <p>Per partecipare alla classe clicca su questo link ed effettua l\'accesso (registrazione se non registrato) se non sei già autenticato: <a href="' . DIR . 'app/includes/add-user-class.php?token=$token&classid=' . $_POST["classid"] . '&classname=' . $_POST["classname"] . '">Accetta l\'invito</a></p>
        <p>Saluti, \n Il team di Interrogazioni Programmate</p>';

        try {
            $mail = new Mail();
            $mail->setFrom(SITEEMAIL);
            $mail->addAddress($to);
            $mail->subject($subject);
            $mail->body($body);
            $mail->send();
        } catch (PhpMailerException $e) {
            throw new Exception("Impossibile inviare l'email. " . $e);
        }
    }
    echo "OK";
}