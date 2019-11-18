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
USER_LANG = '" . $user->getLanguage() . "';
</script>";
$scripts = [
    // jQuery
    "vendor/web-assets/jquery/dist/jquery.js",
    // Materialize
    "vendor/web-assets/material-components-web/dist/material-components-web.min.js",
    // SweetAlert2
    //"vendor/npm-asset/sweetalert2/dist/sweetalert2.all.js",
    // Polyfill (ES6 Promises for IE11 and Android Browsers)
    //"vendor/npm-asset/promise-polyfill/dist/polyfill.js",
    // GetText Translator
    'vendor/web-assets/gettext-translator/src/translator.js' => ['type' => "module"],
    // Perfect Scrollbar
    //'vendor/npm-asset/perfect-scrollbar/dist/perfect-scrollbar.js',
    // Cookie consent
    "vendor/web-assets/cookieconsent/build/cookieconsent.min.js",
    // Scripts
    "app/assets/js/*.js"
];
if (!empty($include_scripts)) {
    $scripts = array_merge($scripts, $include_scripts);
}
echo Utils::buildAssetsImport($scripts);
?>
</body>
</html>