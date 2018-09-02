<?php /** @noinspection ALL */
$title = "Dashboard";
$inc_script = "dashboard";
$filename = "";
require_once("layout/header.php");
// START NAVBAR
include("layout/navbar.php");
// END NAVBAR
if (isset($_GET["deleteclass"]) and $_GET["deleteclass"] == "success") {
    echo '<script>swal({
title: "Classe eliminata",
text: "La classe ' . $_GET["nome"] . ' è stata eliminata!",
type: "success" 
})</script>';
}
if (isset($_GET["invite-class"]) and $_GET["invite-class"] == "success") {
    echo '<script>swal({
title: "Aggiunto alla classe",
text: "Sei stato agginto alla classe ' . $_GET["nome"] . '",
type: "success" 
})</script>';
}
if (isset($_GET["deletelist"]) and $_GET["deletelist"] == "success") {
    echo '<script>swal({
title: "Lista eliminata",
text: "La lista ' . $_GET["deletedlistname"] . ' è stata eliminata!",
type: "success" 
})</script>';
}
?>
<!-- START Body -->
<p align="right" style="padding-right: 20px" class="hide-on-small-only">Benvenuto <?php
    if ($userinfo["nome"] !== "" or $userinfo["cognome"] !== "") {
        echo $userinfo["nome"] . " " . $userinfo["cognome"];
    } else {
        echo $_SESSION["username"];
    } ?><br>
    <?php echo "<span id='date_time'></span>" ?></p>
<div class="container">
    <section id="dashboard">
        <h2>Dashboard</h2>
        <p>Pagina non ancora disponibile!</p>
    </section>
    <section id="classi">
        <h2>Classi</h2>
        <div class="row" id="rigaclassi">
            <div class="col s6 m2 l3">
                <div class="card activator hoverable waves-effect waves-light divlink" onclick='crea_classe()'>
                        <div class="card-action">
                            <span style="text-transform: uppercase;" class="center-block"><i
                                        class="material-icons left">add_circle_outline</i>Crea classe</span>
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