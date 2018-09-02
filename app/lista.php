<?php
if (!isset($_GET["id"])) {
    header("Location: index.php");
}
$title = $_GET["nome"];
$inc_script = "lista";
$filename = "lista.php";
require_once("layout/header.php");
$listinfo = $user->get_list_data($_GET["id"]);
$classinfo = $user->get_class_data($listinfo["classe"]);
$admins = explode(", ", $classinfo["admin"]);
?>
    <script>
        var listid = "<?php echo $_GET["id"]; ?>";
        var listname = "<?php echo $_GET["nome"]; ?>";
        var classid = '<?php echo $listinfo["classe"]; ?>';
        var classusers = "<?php echo $classinfo["users"]; ?>";
        var contenuto = '<?php echo $listinfo["contenuto"]; ?>';
        var giust_rim = <?php echo $userinfo["giustificazioni_rim"]; ?>;
    </script>
    <!-- START NAVBAR -->
<?php include("layout/navbar.php"); ?>
    <!-- END NAVBAR -->
    <!-- START Body -->
<?php
if ($listinfo["img"] == null) {
    echo '<div class="fixed-action-btn">
    <a id="fab-link" class="btn-floating btn-large waves-effect waves-light red" onclick="edit_list_mode()">
        <i id="fab-icon" class="large material-icons">mode_edit</i>
    </a>
</div>';
}
?>
    <div class="container">
        <h2><?php echo $_GET["nome"]; ?> - Classe <?php echo $classinfo["nome"]; ?></h2>
        <div class="row">
            <div class="col s12 m7" id="maincontainer">
                <?php
                // Struttura tabella nel database (colonna contenuto): ["nome_utente" => ["giornata" => "...","giustificato" => "S"/"N"]]
                if ($listinfo["contenuto"] != "" OR $listinfo["contenuto"] != null) {
                    $contenuto = unserialize($listinfo["contenuto"]);
                    echo '<div class="card hoverable"><table class="striped highlight centered" id="list-table"><thead>
                    <tr>
                        <th>N°</th>
                        <th>Studente</th>
                        <th>Giornata</th>
                        <th><span class="tooltipped" data-position="top" data-tooltip="Giustificazione" style="border-bottom: 1px dotted gray;">G</span></th>
                    </tr>
                </thead><tbody>';
                    foreach ($contenuto as $name => $value) {
                        echo '<tr><td>' . (array_search($name, array_keys($contenuto)) + 1) . '</td>';
                        $userinfo = $user->get_user_data($name);
                        if ($userinfo["img"] === "" || $userinfo["img"] === "null") {
                            if ($userinfo["genere"] === "F") {
                                $userimg = "img/user/female.svg";
                            } else {
                                $userimg = "img/user/male.svg";
                            }
                        } else {
                            $userimg = $userinfo["img"];
                        }
                        $user_name = $userinfo["nome"] . " " . $userinfo["cognome"];
                        echo '<td><div class="chip hoverable"><img src="' . $userimg . '" alt="' . $user_name . '">' . $user_name . '</div></td>';
                        $a = $value;
                        if ($a["giustificato"] == "S") {
                            if ($_SESSION["username"] == $name) {
                                $g = '<label><input type="checkbox" checked="checked" id="giustificazione-' . $name . '" onclick="giustificazione(\'' . $name . '\')"/><span class="tooltipped" data-position="right" data-tooltip="Sei giustificato. Vuoi revocare la giustificazione?" id="giustificazione-span-' . $name . '"></span></label>';
                            } else {
                                $g = '<i class="fal fa-check tooltipped" data-position="right" data-tooltip="Giustificato"></i>';
                            }
                        } else {
                            if ($_SESSION["username"] == $name) {
                                $g = '<label><input type="checkbox" id="giustificazione-' . $name . '" onclick="giustificazione(\'' . $name . '\')"/><span class="tooltipped" data-position="right" data-tooltip="Non sei giustificato. Vuoi giustificarti? Giustificazioni rimanenti: ' . $userinfo["giustificazioni_rim"] . '" id="giustificazione-span-' . $name . '"></span></label>';
                            } else {
                                $g = '';
                            }
                        }
                        $rg = "<span id='span-" . $name . "'>" . $a["giornata"] . "</span>";
                        echo '<td id="rigagiornata">' . $rg . '</td><td>' . $g . '</td></tr>';
                    }
                    echo '</tbody></table></div>';
                } else {
                    if (in_array($_SESSION["username"], $admins)) {
                        echo '<h6 class="center-align">Sembra che questa lista sia stata appena creata. Seleziona un\' azione:</h6>
<br>
<div class="row center-align">
    <a onclick="generazione_casuale()" class="btn light-green waves-effect waves-light">Generazione casuale</a>
s</div>';
                    } else {
                        echo '<h6 class="center-align">Sembra che questa lista sia stata appena creata. Chiedi ad un amministratore di generare la lista.';
                    }
                }
                ?>
            </div>
            <div class="col s12 m5">
                <div class="card hoverable">
                    <?php if ($listinfo["img"] != null) {
                        echo '<div class="card-image">
                        <img src="' . $listinfo["img"] . '">
                        <span id="listname" class="card-title">' . $_GET["nome"] . '</span>
                        <a id="fab-link" class="btn-floating halfway-fab waves-effect waves-light red" onclick="edit_list_mode()"><i id="fab-icon" class="material-icons">mode_edit</i></a>
                    </div>
                    <div id="dettagli-lista" class="card-content">';
                    } else {
                        echo '<div id="dettagli-lista" class="card-content">
                        <span id="listname" class="card-title">' . $_GET["nome"] . '</span>';
                    }
                    ?>
                    <p id="description"><?php echo $listinfo["description"]; ?></p>
                    <?php
                    echo "</div>";
                    if (in_array($_SESSION["username"], $admins)) {
                        echo '<div id="list_action" class="card-action"><a id="deletelist" onclick="delete_list()"
                   style="cursor: pointer;" class="btn-flat waves-effect red-text">Elimina lista</a></div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <!-- END Body -->
<?php
include("layout/footer.php")
?>