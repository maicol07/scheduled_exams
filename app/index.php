<?php /** @noinspection ALL */
$title = "Dashboard";
$inc_script = "dashboard";
$filename = "";
require_once("layout/header.php");
// START NAVBAR
include("layout/navbar.php");
// END NAVBAR
if (isset($_GET["newEmail"]) and $_GET["newEmail"] == "success") {
    echo '<script>swal({
title: "' . _('Email cambiata con successo') . '",
text: "' . _('La tua email è stata cambiata! Verrai ora disconnesso, in modo da effettuare l\'accesso con la nuova email.') . '",
type: "success" 
}).then((result) => {
    window.location.href = "?action=logout";
})</script>';
}
if (isset($_GET["deleteclass"]) and $_GET["deleteclass"] == "success") {
    echo '<script>swal({
title: "' . _('Classe eliminata') . '",
text: "' . _('La classe ') . $_GET["nome"] . _(" è stata eliminata!") . '",
type: "success" 
})</script>';
}
if (isset($_GET["invite-class"]) and $_GET["invite-class"] == "success") {
    echo '<script>swal({
title: "' . _('Aggiunto alla classe') . '",
text: "' . _("Sei stato agginto alla classe ") . $_GET["nome"] . '",
type: "success" 
})</script>';
}
if (isset($_GET["deletelist"]) and $_GET["deletelist"] == "success") {
    echo '<script>swal({
title: "' . _("Lista eliminata") . '",
text: "' . _("La lista ") . $_GET["deletedlistname"] . _(' è stata eliminata!') . '",
type: "success" 
})</script>';
}
?>
<!-- START Body -->
    <p align="right" style="padding-right: 20px" class="hide-on-small-only"><?php echo _("Benvenuto ");
    if ($userinfo["nome"] !== "" or $userinfo["cognome"] !== "") {
        echo $userinfo["nome"] . " " . $userinfo["cognome"];
    } else {
        echo $_SESSION["username"];
    } ?><br>
    <?php echo "<span id='date_time'></span>" ?></p>
<div class="container">
    <section id="dashboard">
        <h2><?php echo _("Dashboard") ?></h2>
        <div class="row">
            <div class="col l4 s12">
                <div class="card-panel stats-card blue lighten-2 indigo-text text-lighten-5">
                    <i class="material-icons">class</i>
                    <span class="count"><?php echo count($listaclassi) ?></span>
                    <div class="name"><?php if (count($listaclassi) == 1) {
                            echo _("Classe");
                        } else {
                            echo _("Classi");
                        } ?></div>
                    </a>
                </div>
            </div>
    </section>
    <section id="classi">
        <h2><?php echo _("Classi"); ?></h2>
        <div class="row" id="rigaclassi">
            <div class="col s6 m2 l3">
                <div class="card activator hoverable waves-effect waves-light divlink" onclick='crea_classe()'>
                        <div class="card-action">
                            <span style="text-transform: uppercase;" class="center-block"><i
                                        class="material-icons left">add_circle_outline</i><?php echo _("Crea classe") ?></span>
                        </div>
                    </div>
            </div>
            <?php
            foreach ($listaclassi as $classe) {
                if (in_array($classe["ID"], $classiutente)) {
                    echo '<div class="col s6 m2 l3">
                    <div class="card activator hoverable waves-effect waves-light" style="cursor: pointer;" onclick="window.location=\'classe.php?id=' . $classe["ID"] . '&nome=' . $classe["nome"] . '\'">
                        <div class="card-action">
                            ' . $classe["nome"] . '
                        </div>
                    </div>';
                }
            }
            ?>
        </div>
    </section>
</div>
<!-- END Body -->
<?php
include("layout/footer.php")
?>