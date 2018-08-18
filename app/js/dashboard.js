var $root = $('html, body');
$(document).on('click', 'a[href^="#"]', function (event) {
    event.preventDefault();

    $root.animate({
        scrollTop: $($.attr(this, 'href')).offset().top
    }, 500);
});

function crea_classe() {
    swal({
        title: "Crea classe",
        text: "Inserisci il nome della classe",
        input: "text",
        confirmButtonText: "CREA!",
        cancelButtonText: "Annulla",
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
                    title: "Classe creata!",
                    text: "La classe " + name.value + " è stata creata!",
                    type: "success",
                });
            }
        };

        xmlhttp.open("GET", "includes/addclass.php?classe=" + name.value + "&username=" + username, true);
        xmlhttp.send();

    }).catch(err => {
        if (err) {
            swal("Oh no!", "Si è verificato un errore!", "error");
        } else {
            swal.disableLoading();
            swal.close();
        }
    });
}