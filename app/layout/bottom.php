<?php

use src\Utils;

?>
</div>
</main>
<!-- START FOOTER
<footer class="page-footer footer footer-static footer-dark gradient-45deg-indigo-blue gradient-shadow navbar-border navbar-shadow">
    <div class="footer-copyright">
        <div class="container">
            <span><?php echo __("Copyright © 2019 Maicol07") ?></span>
            <span class="right hide-on-small-only">
                <a onclick="showInfo()" style="cursor: pointer;"><?php echo __("Versione") . ' ' . Utils::getVersion() ?></a> - <?php echo __("Sviluppato da Maicol07") ?>
            </span>
        </div>
    </div>
</footer>
 END FOOTER -->
</div>
<!-- ================================================
      Scripts
================================================ -->
<?php

echo "<script>
PRODUCTION = Boolean('" . PRODUCTION . "');
ROOTDIR = '" . ROOTDIR . "';
BASEURL = '" . BASEURL . "';
USER_LANG = '" . $lang . "';
</script>";


echo $assets->js();

if (isset($debugbar) and isset($debugbarRenderer)) {
    $debugbar["messages"]->addMessage(basename($_SERVER['SCRIPT_NAME'], '.php'));
    echo $debugbarRenderer->render();
}
?>
</body>
</html>