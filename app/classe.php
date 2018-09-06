<?php
if (!isset($_GET["id"])) {
    header("Location: index.php");
}
$title = $_GET["nome"];
$inc_script = "classe";
$filename = "classe.php";
require_once("layout/header.php");
$classinfo = $user->get_class_data($_GET["id"]);
?>
<script>
    var classid = "<?php echo $_GET["id"]?>";
    var classname = "<?php echo $_GET["nome"]; ?>";
</script>
<!-- START NAVBAR -->
<?php include("layout/navbar.php"); ?>
<!-- END NAVBAR -->
<!-- START Body -->
<?php
if ($classinfo["img"] == null) {
    echo '<div class="fixed-action-btn">
    <a id="fab-link" class="btn-floating btn-large waves-effect waves-light red tooltipped" onclick="edit_class_mode()" data-position="left" data-tooltip="' . _("Modifica") . '">
        <i id="fab-icon" class="large material-icons">mode_edit</i>
    </a>
</div>';
}
?>
<div class="container">
    <h2><?php echo _("Classe") . " " . $_GET["nome"]; ?></h2>
    <div class="row">
        <div class="col s12 m6">
            <h3><?php echo _("Liste") ?></h3>
            <div class="row" id="rigalista">
                <div class="col s6">
                    <div class="card activator hoverable waves-effect waves-light divlink" onclick='crea_lista()'>
                        <div class="card-action">
                            <span style="text-transform: uppercase;"><i
                                        class="material-icons left">add_circle_outline</i><?php echo _("Crea lista") ?></span>
                        </div>
                    </div>
                </div>
                <?php
                $select = $db->prepare("SELECT liste FROM classi WHERE ID = :classID");
                $select->execute(array('classID' => $_GET["id"]));
                $listeclasse = $select->fetch();
                $listeclasse = explode(", ", $listeclasse["liste"]);
                $query = $db->prepare("SELECT * FROM liste");
                $query->execute();
                $liste = $query->fetchAll();
                foreach ($liste as $lista) {
                    if (in_array($lista["ID"], $listeclasse)) {
                        echo '<div class="col s6">
                    <div class="card activator hoverable waves-effect waves-light" style="cursor: pointer;" onclick="window.location=\'lista.php?id=' . $lista["ID"] . '&nome=' . $lista["nome"] . '\'">
                        <div class="card-action">
                            <span style="text-transform: uppercase;">' . $lista["nome"] . '
                    </span></div></div></div>';
                    }
                }
                ?>
            </div>
        </div>
        <div class="col s12 m6">
            <div class="card hoverable">
                <?php if ($classinfo["img"] != null) {
                    echo '<div class="card-image">
                        <img src="' . $classinfo["img"] . '">
                        <span id="classname" class="card-title">' . $_GET["nome"] . '</span>
                        <a id="fab-link" class="btn-floating halfway-fab waves-effect waves-light red tooltipped" onclick="edit_class_mode()"  data-position="left" data-tooltip="' . _("Modifica") . '"><i id="fab-icon" class="material-icons">mode_edit</i></a>
                    </div>
                    <div id="dettagli-classe" class="card-content">';
                } else {
                    echo '<div id="dettagli-classe" class="card-content">
                        <span id="classname" class="card-title">' . $_GET["nome"] . '</span>';
                }
                ?>
                <p id="description"><?php echo $classinfo["description"]; ?></p>
                <br>
                <div class="divider"></div>
                <ul>
                    <li><?php echo _("Numero partecipanti:") ?> <a
                                onclick="show_class('<?php echo $classinfo["users"]; ?>')"
                                style="cursor: pointer;"><?php echo count(explode(", ", $classinfo["users"])); ?></a>
                    </li>
                </ul>
                <?php echo "</div>" ?>
                <div id="class_action" class="card-action">
                    <a onclick="invite_users()"
                       class="btn-flat waves-effect indigo-text"><?php echo _("Invita utenti") ?></a>
                    <a id="deleteclass" onclick="delete_class()"
                       style="cursor: pointer;"
                       class="btn-flat waves-effect red-text"><?php echo _("Elimina classe") ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Body -->
<?php
include("layout/footer.php")
?>