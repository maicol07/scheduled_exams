<style>
    .lang-flag {
        height: 30px;
        vertical-align: middle;
        padding-right: 10px;
    }

    .collapsible li.active i {
        -ms-transform: rotate(180deg); /* IE 9 */
        -webkit-transform: rotate(180deg); /* Chrome, Safari, Opera */
        transform: rotate(180deg);
    }
</style>
<nav>
    <div class="nav-wrapper">
        <a data-target="nav-mobile" class="sidenav-trigger" style="cursor:pointer;"><i
                    class="material-icons">menu</i></a>
        <a href="index.php" class="brand-logo navimg" style="font-family: Raleway, sans-serif;"><img
                    src="../img/logo.svg" alt="Interrogazioni programmate" height="50" id="logo-image"
                    onerror="this.src='img/logo.png'"><span class="logo-text"
                                                            style="color: white; font-size: 3vw;"><?php echo _("Interrogazioni Programmate") ?></span></a>
        <ul class="right hide-on-med-and-down">
            <li><a id="dd-trigger-lang" class="dropdown-trigger waves-effect waves-light" data-target="lang-dropdown">
                    <img src="../img/flags/<?php echo $locale ?>.svg" class="lang-flag"></a></li>
            <li><a href="index.php#dashboard" class="waves-effect waves-light"><i
                            class="material-icons left">dashboard</i><?php echo _("Dashboard") ?></a>
            </li>
            <li><a id="dd-trigger-classi" href="index.php#classi" class="dropdown-trigger waves-effect waves-light"
                   data-target="dropdownclassi"><i class="material-icons left">class</i><?php echo _("Classi") ?></a>
            </li>
            <!-- Profile Dropdown Trigger -->
            <li><a id="dd-trigger-profile" class="dropdown-trigger navimg waves-effect waves-light"
                   data-target="dropdownprofile"><img
                            src="<?php
                            if ($userinfo["img"] == "") {
                                if ($userinfo["genere"] == "F") {
                                    echo "img/user/female.svg";
                                } else {
                                    echo "img/user/male.svg";
                                }
                            } else {
                                echo $userinfo["img"];
                            }
                            ?>" class="circle" width="50" height="50"></a></li>
        </ul>
    </div>
</nav>

<!-- Profile Dropdown Structure -->
<ul id="dropdownprofile" class="dropdown-content">
    <li><a href="profile.php" class="waves-effect waves-light"><i
                    class="material-icons left">account_circle</i><?php echo _("Profilo") ?></a>
    </li>
    <li><a href="settings.php" class="waves-effect waves-light"><i
                    class="material-icons left">settings</i><?php echo _("Impostazioni") ?></a></li>
    <li><a onclick="info()" class="waves-effect waves-light"><i
                    class="material-icons">info</i><?php echo _("Informazioni") ?></a></li>
    <li class="divider"></li>
    <li><a href="index.php?action=logout" class="waves-effect waves-light"><i
                    class="material-icons">power_settings_new</i><?php echo _("Disconnettiti") ?></a></li>
</ul>

<!-- Menu a tendina Classi -->
<ul id="dropdownclassi" class="dropdown-content">
    <?php
    $select = $db->prepare("SELECT classi FROM users WHERE userID = :userID");
    $select->execute(array('userID' => $_SESSION["userID"]));
    $classiutente = $select->fetch();
    $classiutente = explode(", ", $classiutente["classi"]);
    $query = $db->prepare("SELECT * FROM classi");
    $query->execute();
    $listaclassi = $query->fetchAll();
    foreach ($listaclassi as $classe) {
        if (in_array($classe["ID"], $classiutente)) {
            echo '<li><a href="classe.php?id=' . $classe["ID"] . '&nome=' . $classe["nome"] . '" class="waves-effect waves-light">' . $classe["nome"] . '</a></li>';
        }
    }
    ?>
</ul>

<!-- Thanks to Gustavo Costa for the language picker (https://codepen.io/gusbemacbe/pen/ZaOGjy) -->
<ul id="lang-dropdown" class="dropdown-content">
    <?php
    $languages = ["it_IT" => _("Italiano"), "en_US" => _("Inglese (Stati Uniti)"), "fr_FR" => _("Francese")];
    echo '<li><a href="?lang=' . $locale . '" style="font-weight: bolder"><img src="../img/flags/' . $locale . '.svg" class="lang-flag">' . $languages[$locale] . '</a></li>';
    $langs = scandir("../locale");
    foreach ($langs as $lang) {
        if ($lang == $locale or strpos($lang, ".")) {
            continue;
        }
        if (in_array($lang, array_keys($languages))) {
            echo '<li><a href="?lang=' . $lang . '" style="font-weight: bolder"><img src="../img/flags/' . $lang . '.svg" class="lang-flag">' . $languages[$lang] . '</a></li>';
        } else {
            echo '<li><a href="?lang=' . $lang . '" style="font-weight: bolder"><img src="../img/flags/' . $lang . '.svg" class="lang-flag">' . $lang . '</a></li>';
        }
    }
    ?>
</ul>

<ul id="nav-mobile" class="sidenav">
    <li>
        <div class="user-view">
            <div class="background" style="background-color: #1a237e">
            </div>
            <a href="profile.php"><img class="circle waves-effect waves-circle waves-light" src="<?php
                if ($userinfo["img"] == "") {
                    if ($userinfo["genere"] == "F") {
                        echo "img/user/female.svg";
                    } else {
                        echo "img/user/male.svg";
                    }
                } else {
                    echo $userinfo["img"];
                }
                ?>"></a>
            <a href="profile.php"><span class="white-text name"><?php
                    if ($userinfo["nome"] !== "" or $userinfo["cognome"] !== "") {
                        echo $userinfo["nome"] . " " . $userinfo["cognome"];
                    } else {
                        echo $_SESSION["username"];
                    } ?></span></a>
            <a href="profile.php"><span class="white-text email"><?php echo $userinfo["email"]; ?></span></a>
        </div>
    </li>
    <li><a href="#dashboard" class="waves-effect"><i class="material-icons">dashboard</i><?php echo _("Dashboard") ?>
        </a></li>
    <li class="no-padding">
        <ul class="collapsible collapsible-accordion">
            <li>
                <a class="collapsible-header waves-effect"><i
                            class="material-icons left">class</i><?php echo _("Classi") ?><i
                            class="material-icons right">keyboard_arrow_down</i></a>
                <!-- Menu a tendina Classi -->
                <div class="collapsible-body">
                    <ul>
                        <?php
                        foreach ($listaclassi as $classe) {
                            if (in_array($classe["ID"], $classiutente)) {
                                echo '<li><a href="classe.php?id=' . $classe["ID"] . '&nome=' . $classe["nome"] . '" class="waves-effect waves-light">' . $classe["nome"] . '</a></li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
            </li>
        </ul>
    </li>
    <li>
        <div class="divider"></div>
    </li>
    <li><a href="profile.php" class="waves-effect"><i
                    class="material-icons">account_circle</i><?php echo _("Profilo") ?></a></li>
    <li><a href="settings.php" class="waves-effect"><i
                    class="material-icons">settings</i><?php echo _("Impostazioni") ?></a></li>
    <li><a onclick="info()" class="waves-effect"><i class="material-icons">info</i><?php echo _("Informazioni") ?></a>
    </li>
    <li>
        <ul class="collapsible collapsible-accordion">
            <li>
                <div class="collapsible-header waves-effect">
                    <img src="../img/flags/<?php echo $locale ?>.svg"
                         class="lang-flag"> <?php echo $languages[$locale] ?>
                    <i class="material-icons right">keyboard_arrow_down</i>
                </div>
                <div class="collapsible-body">
                    <ul>
                        <?php
                        echo '<li><a href="?lang=' . $locale . '" style="font-weight: bolder"><img src="../img/flags/' . $locale . '.svg" class="lang-flag">' . $languages[$locale] . '</a></li>';
                        $langs = scandir("../locale");
                        foreach ($langs as $lang) {
                            if ($lang == $locale or strpos($lang, ".")) {
                                continue;
                            }
                            if (in_array($lang, array_keys($languages))) {
                                echo '<li><a href="?lang=' . $lang . '" style="font-weight: bolder"><img src="../img/flags/' . $lang . '.svg" class="lang-flag">' . $languages[$lang] . '</a></li>';
                            } else {
                                echo '<li><a href="?lang=' . $lang . '" style="font-weight: bolder"><img src="../img/flags/' . $lang . '.svg" class="lang-flag">' . $lang . '</a></li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
            </li>
        </ul>
    </li>
    <li>
        <div class="divider"></div>
    </li>
    <li><a href="index.php?action=logout" class="waves-effect"><i
                    class="material-icons">power_settings_new</i><?php echo _("Disconnettiti") ?></a>
    </li>
</ul>
<main>