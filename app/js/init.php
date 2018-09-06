<script>
$(document).ready(function () {
    $("#dd-trigger-profile").dropdown({
        inDuration: 300,
        outDuration: 225,
        constrainWidth: false, // Does not change width of dropdown to that of the activator
        hover: false, // Activate on hover
        coverTrigger: false, // Displays dropdown below the button
        alignment: 'right' // Displays dropdown with edge aligned to the right of button);
    });
    $("#dd-trigger-classi").dropdown({
        inDuration: 300,
        outDuration: 225,
        constrainWidth: false, // Does not change width of dropdown to that of the activator
        hover: true, // Activate on hover
        coverTrigger: false, // Displays dropdown below the button
        alignment: 'right' // Displays dropdown with edge aligned to the right of button);
    });
    $('.collapsible').collapsible();
    $('.sidenav').sidenav();
});

//<![CDATA[
$(window).on('load', function () { // makes sure the whole site is loaded
    $('#status').fadeOut(); // will first fade out the loading animation
    $('#preloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website.
    $('body').delay(350).css({'overflow': 'visible'});
});

//]]>

function info() {
    swal({
        title: "<?php echo _("Informazioni") ?>",
        html: '<?php echo _("Interrogazioni Programmate è un software closed-source sviluppato da ") ?>' +
            '<a href="https://www.maicol07.it" target="_blank">maicol07</a>.<h5><?php echo _("Link utili") ?></h5>' +
            '<ul>' +
            '<li><a href="https://community.interrogazioniprogrammate.tk"><?php echo _("Community") ?></a></li>' +
            '<li><a href="https://interrogazioniprogrammate.tk/community/roadmap.php"><?php echo _("Roadmap") ?></a></li>' +
            '</ul>' +
            '<h5><?php echo _("Riconoscimenti") ?></h5><?php echo _("Icona creata da ") ?>' +
            '<a href="https://www.flaticon.com/authors/smashicons" title="Smashicons">Smashicons</a> <?php echo _("di") ?> ' +
            '<a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> <?php echo _("con licenza") ?> ' +
            '<a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>' +
            '<h5><?php echo _("Script e librerie di terze parti") ?></h5><ul>' +
            '<li><a href="https://materializecss.com">Materialize CSS</a></li>' +
            '<li><a href="https://jquery.com/">JQuery</a></li>' +
            '<li><a href="https://sweetalert2.github.io/">SweetAlert 2</a></li>' +
            '<li><a href="https://github.com/taylorhakes/promise-polyfill">Promise Polyfill</a></li>' +
            '</ul>',
        type: "info",
    })
}
</script>