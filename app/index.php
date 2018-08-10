<?php /** @noinspection ALL */
/** @noinspection ALL */
/** @noinspection ALL */
$title = "Dashboard";
$inc_script = "dashboard";
require_once("layout/header.php");
?>
<body>
<!-- START NAVBAR -->
<?php include("layout/navbar.php") ?>
<!-- END NAVBAR -->
<!-- START Body -->
<p align="right" style="padding-right: 20px" class="hide-on-small-only">Benvenuto <?php
    if ($userinfo["nome"] !== "" or $userinfo["cognome"] !== "") {
        echo $userinfo["nome"] . " " . $userinfo["cognome"];
    } else {
        echo $_SESSION["username"];
    } ?><br>
    <?php echo date('d/m/Y - H:i:s'); ?></p>
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
                                        class="fal fa-plus-circle"></i> Crea classe</span>
                        </div>
                    </div>
            </div>
            <?php
            foreach ($listaclassi as $classe) {
                if (in_array($classe["ID"], $classiutente)) {
                    echo '<div class="col s6 m2 l3">
                    <div class="card activator hoverable waves-effect waves-light" style="cursor: pointer;" onclick="window.location=classe.php?id=' . $classe["ID"] . '&nome=' . $classe["nome"] . '">
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
<!-- Compiled and minified JavaScript -->
<script src="../js/materialize.min.js"></script>
</body>