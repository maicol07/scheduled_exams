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
$scripts = [
    // jQuery
    "jquery",
    // Materialize
    "material-components-web",
    // SweetAlert2
    "sweetalert2",
    // Polyfill (ES6 Promises for IE11 and Android Browsers)
    //"promise-polyfill",
    // GetText Translator
    'gettext-translator',
    // Perfect Scrollbar
    //'vendor/npm-asset/perfect-scrollbar/dist/perfect-scrollbar.js',
    // Cookie consent
    "cookieconsent",
    // Scripts
    "init.js",
    "navbar.js"
];
if (!empty($include_scripts)) {
    $scripts = array_merge($scripts, $include_scripts);
}
echo $assets->add($scripts)->js();

if (!PRODUCTION) {
    $debugbar["messages"]->addMessage(basename($_SERVER['SCRIPT_NAME'], '.php'));
    echo $debugbarRenderer->render();
}
?>
</body>
</html>