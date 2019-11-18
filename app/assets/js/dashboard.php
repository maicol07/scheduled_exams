<script>
var $root = $('html, body');
$(document).on('click', 'a[href^="#"]', function (event) {
    event.preventDefault();

    $root.animate({
        scrollTop: $($.attr(this, 'href')).offset().top
    }, 500);
});
$(document).ready(function () {
    date_time('date_time');
});

function date_time(id) {
    date = new Date;
    year = date.getFullYear();
    month = date.getMonth();
    months = ['<?php echo _("Gennaio") ?>', '<?php echo _("Febbraio") ?>', '<?php echo _("Marzo") ?>', '<?php echo _("Aprile") ?>',
        '<?php echo _("Maggio") ?>', '<?php echo _("Giugno") ?>', '<?php echo _("Luglio") ?>', '<?php echo _("Agosto") ?>', '<?php echo _("Settembre") ?>',
        '<?php echo _("Ottobre") ?>', '<?php echo _("Novembre") ?>', '<?php echo _("Dicembre") ?>'];
    d = date.getDate();
    day = date.getDay();
    days = ['<?php echo _("Domenica") ?>', '<?php echo _("Lunedì") ?>', '<?php echo _("Martedì") ?>', '<?php echo _("Mercoledì") ?>', '<?php echo _("Giovedì") ?>',
        '<?php echo _("Venerdì") ?>', '<?php echo _("Sabato") ?>'];
    h = date.getHours();
    if (h < 10) {
        h = "0" + h;
    }
    m = date.getMinutes();
    if (m < 10) {
        m = "0" + m;
    }
    s = date.getSeconds();
    if (s < 10) {
        s = "0" + s;
    }
    result = '' + days[day] + ' ' + months[month] + ' ' + d + ' ' + year + ' ' + h + ':' + m + ':' + s;
    document.getElementById(id).innerHTML = result;
    setTimeout('date_time("' + id + '");', '1000');
    return true;
}

function crea_classe() {
    swal({
        title: "<?php echo _("Crea classe") ?>",
        text: "<?php echo _("Inserisci il nome della classe") ?>",
        input: "text",
        confirmButtonText: "<?php echo _("CREA!") ?>",
        cancelButtonText: "<?php echo _("Annulla") ?>",
    }).then(name => {
        if (!name.value) throw null;
        swal.showLoading();

        if (window.XMLHttpRequest) {
            // code for modern browsers
            var xmlhttp = new XMLHttpRequest();
        } else {
            // code for old IE browsers
            var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                var column = document.createElement("DIV");
                column.className += "col s6 m2 l3";
                var container = document.createElement("DIV"); // Create a <div> element
                container.className += "card activator hoverable waves-effect waves-light divlink"; // Add card class
                container.setAttribute("onclick", "window.location=\"classe.php?id=" + this.responseText + "&nome=" + name.value + "\""); // Add link to div
                var cardcontent = document.createElement("DIV");      // Create a <div> element
                cardcontent.className += "card-action"; // Add CSS class
                var cardtext = document.createTextNode(name.value); // Create a text node
                cardcontent.appendChild(cardtext); // Append the text node to <div>
                container.appendChild(cardcontent); // Append the <div> element to <div>
                column.appendChild(container); // Append <div> element to <div>
                document.getElementById("rigaclassi").appendChild(column);           // Append <div> to <div> with id="rigaclassi"
                swal({
                    title: "<?php echo _("Classe creata!") ?>",
                    text: "<?php echo _("La classe") ?> " + name.value + " <?php echo _("è stata creata!") ?>",
                    type: "success",
                });
            }
        };

        xmlhttp.open("GET", "includes/addclass.php?classe=" + name.value + "&username=" + username, true);
        xmlhttp.send();

    }).catch(err => {
        if (err) {
            swal("<?php echo _("Oh no!") ?>", "<?php echo _("Si è verificato un errore!") ?>", "error");
        } else {
            swal.disableLoading();
            swal.close();
        }
    });
}
</script>