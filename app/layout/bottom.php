<?php

use App\Utils;

?>
</div>
</main>
<!-- START FOOTER -->
<footer>
    <div id="info_links">
        <button id="footer_hide" class="mdc-icon-button" style="position: absolute; right: 256px;"
                onclick="hideFooter(this)">
            <i class="mdc-icon-button__icon mdi-outline-keyboard_arrow_down"></i>
        </button>
        <div id="footer_info" style="flex: 1;">
            <img src="<?php echo Utils::buildAssetsURI("/app/assets/img/logo.svg") ?>"
                 alt="<?php echo __("Interrogazioni Programmate") ?>"
                 style="margin-left: 15px; width: 40px; height: 40px; margin-right: -8px; vertical-align: middle">
            <span class="footer-title">
                <?php echo __("Interrogazioni Programmate") ?>
            </span>
            <br><br>
            <span class="footer-content">
                <?php echo __("Un software closed source sviluppato da %s", "<a href='https://maicol07.it'>Maicol Battistini (maicol07)</a>") ?>
            </span>
        </div>
        <div id="footer_links" style="flex: 1;">
            <h3><?php echo __("Link utili") ?></h3>
            <ul style="list-style: none; padding-left: initial">
                <?php
                $links = [
                    [
                        "label" => __("Sito web"),
                        "url" => "https://scheduledexams.tk",
                        "icon" => 'public'
                    ],
                    [
                        "label" => __("Segnala un problema"),
                        "url" => "https://bugs.maicol07.it",
                        "icon" => 'bug'
                    ],
                    [
                        "label" => __("Documentazione"),
                        "url" => "https://docs.maicol07.it",
                        "icon" => 'file'
                    ],
                    [
                        "label" => __("Informazioni"),
                        "onclick" => "info()",
                        "icon" => 'info'
                    ]
                ];
                foreach ($links as $link) {
                    $link = (object)$link;
                    echo '
                        <li>
                            <a ' . (property_exists($link, 'url') ? 'href="' . $link->url . '"' : '') . ' class="mdc-button"
                            onclick="' . (property_exists($link, 'onclick') ? $link->onclick : '') . '">
                                <div class="mdc-button__ripple"></div>
                                <i class="mdi-outline-' . $link->icon . ' mdc-button__icon"></i>
                                <span class="mdc-button__label">' . $link->label . '</span>
                            </a>
                        </li>';
                }
                ?>
            </ul>
        </div>
    </div>
    <div id="copyright_footer">
        <button id="footer_show" class="mdc-icon-button"
                style="display: none; position: absolute; right: 256px; scale: 0.75; bottom: -15px;"
                onclick="showFooter(this)">
            <i class="mdc-icon-button__icon mdi-outline-keyboard_arrow_up"></i>
        </button>
        <span style="flex: 1"><?php echo __("Copyright © 2019-%s Maicol07", date("Y")) ?></span>
        <?php if (!$detector->isMobile()) { ?>
            <span style="flex: 1">
            <a onclick="info()"
               style="cursor: pointer;"><?php echo __("Versione") . ' ' . Utils::getVersion() ?></a> - <?php echo __("Sviluppato da Maicol07") ?>
        </span>
        <?php } ?>
    </div>
</footer>
<!-- END FOOTER -->
</div>
<script>
    var $buoop = {
        required: {e: 79, f: 74, o: -3, s: 12, c: 75},
        insecure: true,
        unsupported: true
    };

    function $buo_f() {
        var e = document.createElement("script");
        e.src = "https://browser-update.org/update.min.js";
        document.body.appendChild(e);
    }

    try {
        document.addEventListener("DOMContentLoaded", $buo_f, false)
    } catch (e) {
        window.attachEvent("onload", $buo_f)
    }
</script>
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

if (!empty($debugbar) and isset($debugbarRenderer)) {
    echo $debugbarRenderer->render();
}
?>
</body>
</html>