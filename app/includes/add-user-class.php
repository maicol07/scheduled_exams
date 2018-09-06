<?php
require_once "../../includes/config.php";
language("app");

//if not logged in redirect to login page
if (!$user->is_logged_in()) {
    header('Location: ../index.php?redir=' . $filename . '');
}

if (isset($_GET["action"]) and $_GET["action"] == "logout") {

    //logout
    $user->logout();

    //logged in return to index page
    header('Location: index.php');
    exit;
}

if (isset($_GET["token"]) and $_GET["token"] != "") {
    $token = trim($_GET['token']);
    $select = $db->prepare("SELECT invitationtokens FROM classi WHERE ID = :id");
    $select->execute(array("id" => $_GET["classid"]));
    $r = $select->fetch();
    $tokens = explode(", ", $r);
    if (in_array($token, $tokens) and ($key = array_search($token, $tokens)) !== FALSE) {
        unset($tokens[$key]);
    }
    $update = $db->prepare("UPDATE classi SET invitationtokens = :newtokenlist, users = CONCAT(users, ', ', :newuser ) WHERE ID = :classID");
    $update->execute(array(
        'newtokenlist' => implode(", ", $tokens),
        'newuser' => $_SESSION["username"],
        'classID' => $_GET["classid"]
    ));

    //if the row was updated redirect the user
    if ($update->rowCount() == 1) {

        //redirect to login page
        header('Location: index.php?invite-class=success&nome=' . $_GET["classname"]);
        exit;

    } else {
        echo '<!-- Import SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2"></script>
    <!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
    <script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js"></script>';
        echo '<script>swal({
  title: "' . _("Errore!") . '",
  html: "' . _('Non puoi essere aggiunto alla classe. Contattare il supporto nella <a href=\'https://community.interrogazioniprogrammate.tk\'>Community</a>.') . '",
  type: "error",
});</script>';
    }
}