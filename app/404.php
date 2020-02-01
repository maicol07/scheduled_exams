<?php

use src\Utils;

require_once __DIR__ . "/../core.php";

$title = __("404 - Pagina non trovata!");
require_once DOCROOT . "/app/layout/top.php";

?>
<div style="display: block; margin-top: 3em; text-align: center;">
    <img src="<?php echo Utils::buildAssetsURI("/app/assets/img/undraw/404.svg") ?>" alt="<?php echo $title ?>"
         style="width: 30em;">
    <h4 class="mdc-typography--headline4"><?php echo __("404 - Pagina non trovata!") ?></h4>
    <p><?php echo __("La pagina specificata non è stata trovata! Rieffettuare la ricerca o premi il pulsante qui sotto per tornare alla Dashboard") ?>
        .</p>
    <a class="mdc-button mdc-button--raised" href="<?php echo ROOTDIR; ?>/app/">
        <div class="mdc-button__ripple"></div>
        <i class="mdc-button__icon mdi-outline-dashboard"></i>
        <span class="mdc-button__label"><?php echo __("Vai alla Dashboard") ?></span>
    </a>
    <br><br>
</div>
<?php
require_once DOCROOT . "/app/layout/bottom.php";
?>
