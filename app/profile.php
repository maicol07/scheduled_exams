<?php /** @noinspection ALL */
$title = "Profilo";
$inc_script = "profile";
require_once("layout/header.php");
?>
<body>
<!-- START NAVBAR -->
<?php include("layout/navbar.php"); ?>
<!-- END NAVBAR -->
<!-- START BODY -->
<div class="fixed-action-btn">
    <a id="fab-link" class="btn-floating btn-large waves-effect waves-light red" onclick="edit_profile_mode()">
        <i id="fab-icon" class="large material-icons">mode_edit</i>
    </a>
</div>
<div class="container">
    <h2>Profilo</h2>
    <div id="profile-page" class="section">
        <!-- profile-page-header -->
        <div id="profile-page-header" class="card hoverable">
            <div id="bg-div" class="card-image">
                <?php
                if ($userinfo["backimg"] == "") {
                    echo '<img id="profile-bg" src="img/user/background-default.jpg" style="height: 250px"';
                } else {
                    echo '<img id="profile-bg" src="' . $userinfo["backimg"] . '" style="height: 250px;">';
                }
                ?>
            </div>
            <?php
            if ($userinfo["img"] == "") {
                if ($userinfo["genere"] == "F") {
                    echo '<img id="profile-img" src = "img/user/female.svg" width = "256" height = "256" alt = "' . $userinfo["username"] . '" class="z-depth-2 responsive-img card-profile-image hoverable" onerror = "this.src=\'img/user/female.png\'" style="border-radius: 50%;">';
                } else {
                    echo '<img id="profile-img" src = "img/user/male.svg" width = "256" height = "256" alt = "' . $userinfo["username"] . '" class="z-depth-2 responsive-img card-profile-image hoverable" onerror = "this.src=\'img/user/male.png\'" style="border-radius: 50%;">';
                }
            } else {
                echo '<img id="profile-img" src="' . $userinfo["img"] . '" width = "256" height = "256" class="z-depth-2 responsive-img card-profile-image hoverable" alt="' . $userinfo["username"] . '" style="border-radius: 50%;">';
            }
            ?>
            <div class="card-content">
                <div class="row" align="center" id="card-content-row">
                    <?php
                    $a = array("nome", "cognome", "username");
                    foreach ($a as $item) {
                        if (array_search($item, $a) == 0) {
                            $first = "offset-m3";
                        } else {
                            $first = "center-align";
                        }
                        if ($userinfo[$item] !== "") {
                            echo '<div class="col s12 m3 ' . $first . '" id="' . $item . '-div"><h4 class="card-title" id="' . $item . '-h4">' . $userinfo[$item] . '</h4>';
                        } else {
                            echo '<div class="col s12 m3 ' . $first . '" id="' . $item . '-div" hidden><h4 class="card-title" id="' . $item . '-h4"></h4>';
                        }
                        if ($item !== "") {
                            if ($item == "username") {
                                $txt = "Nome utente";
                            } else {
                                $txt = ucfirst($item);
                            }
                            echo '<p class="grey-text small" style="text-align: center">' . $txt . '</p></div>';
                        } else {
                            echo '<p class="grey-text small" style="text-align: center" hidden>' . ucfirst($item) . '</p></div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <!--/ profile-page-header -->

    <!-- profile-page-content -->
    <div id="profile-page-content" class="row">
        <!-- Profile About  -->
        <?php
        if ($userinfo["descrizione"] !== "") {
            echo '<div id="aboutme" class="card light-blue hoverable col s12 m5">
                <div class="card-content white-text">
                    <span class="card-title">Su di me!</span>
                    <p id="aboutme-content">' . $userinfo["descrizione"] . '</p>
                </div>
                </div>';
        } else {
            echo '<div id="aboutme" class="card light-blue hoverable col s12 m5" hidden>
<div class="card-content white-text">
                    <span class="card-title">Su di me!</span><p id="aboutme-content"></p>
                </div></div>';
        }
        ?>
        <!-- Profile About  -->

        <!-- Profile About Details  -->
        <?php
        if ($userinfo["compleanno"] == "" and $userinfo["genere"] == "") {
            $hid = "hidden";
        } else {
            $hid = "";
        }
        if ($userinfo["compleanno"] == "") {
            $compl = "hidden";
        } else {
            $compl = "";
        }
        if ($userinfo["genere"] == "") {
            $gen = "hidden";
        } else {
            $gen = "";
            if ($userinfo["genere"] == "M") {
                $genere = "Maschio";
            } else {
                $genere = "Femmina";
            }
        }
        echo '<div class="col s12 m5 offset-m1"><ul id="profile-page-about-details" class="collection z-depth-1 hoverable" ' . $hid . '>
                <li id="compleanno-li" class="collection-item" ' . $compl . '>
                    <div class="row">
                        <div class="col s6"><i class="material-icons left">cake</i> Compleanno</div>
                        <div class="col s6 right-align"><span id="compleanno-content">' . $userinfo["compleanno"] . '</span></div>
                    </div>
                    </li>
                    <li id="genere-li" class="collection-item" ' . $gen . '>
                    <div class="row">
                        <div class="col s5"><i class="fal fa-transgender"></i> Genere</div>
                        <div class="col s7 right-align"><span id="genere-content">' . $genere . '</span></div>
                    </div>
                </li>
            </ul></div>';
        ?>
        <!--/ Profile About Details  -->

        <!-- Profile About  -->
        <div class="card amber darken-2 hoverable col s12">
            <div class="card-content white-text center-align">
                <p class="card-title"><?php echo $userinfo["seguaci"]; ?> <i class="material-icons">group</i></p>
                <p>Seguaci</p>
            </div>
        </div>
        <!-- Profile About  -->
    </div>
</div>
<script src="../js/materialize.min.js"></script>
</body>
</html>