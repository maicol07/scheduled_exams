<!-- Compiled and minified JavaScript -->
<script src="../js/materialize.min.js"></script>
</main>
</body>
<footer class="page-footer">
    <div class="container">
        <div class="row">
            <div class="col l6 s12">
                <a href="index.php" class="brand-logo footer-img" style="font-family: Raleway, sans-serif;"><img
                            src="../img/logo.svg" alt="Interrogazioni programmate"
                            width="75" height="75" onerror="this.src='img/logo.png'"><span
                            class="logo-text footer-logo-text"
                            style="color: white; font-size: 6vw;"><?php echo _("Interrogazioni Programmate") ?></span></a>
                <p class="grey-text text-lighten-4"><?php echo _("Gestisci le tue interrogazioni online insieme alla tua classe.") ?></p>
            </div>
            <div class="col l4 offset-l2 s12">
                <h5 class="white-text">Link utili</h5>
                <ul style="line-height: 175%">
                    <li><a class="grey-text text-lighten-3" href="index.php#dashboard"><i class="material-icons left">dashboard</i><?php echo _("Dashboard") ?>
                        </a>
                    </li>
                    <li><a class="grey-text text-lighten-3" href="index.php#classi"><i
                                    class="material-icons left">class</i><?php echo _("Classi") ?></a></li>
                    <li><a class="grey-text text-lighten-3" href="profile.php"><i class="material-icons left">account_circle</i><?php echo _("Profilo") ?>
                        </a>
                    </li>
                    <li><a class="grey-text text-lighten-3" href="settings.php"><i
                                    class="material-icons left">settings</i><?php echo _("Impostazioni") ?></a></li>
                    <li><a class="grey-text text-lighten-3" href="https://docs.interrogazioniprogrammate.tk"><i
                                    class="material-icons left">description</i><?php echo _("Documentazione") ?></a>
                    </li>
                    <li><a class="grey-text text-lighten-3" onclick="info()" style="cursor: pointer"><i
                                    class="material-icons left">info</i><?php echo _("Informazioni") ?></a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-copyright">
        <div class="container">
            Copyright © 2018 <?php echo _("Interrogazioni Programmate") ?>
            <a class="grey-text text-lighten-4 right"
               href="https://interrogazioniprogrammate.tk"><?php echo _("Sito web") ?></a>
        </div>
    </div>
</footer>
</html>