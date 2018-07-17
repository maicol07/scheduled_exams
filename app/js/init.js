$(document).ready(function () {
    $('.sidenav').sidenav();
    $(".dropdown-trigger").dropdown({
        inDuration: 300,
        outDuration: 225,
        constrainWidth: false, // Does not change width of dropdown to that of the activator
        hover: false, // Activate on hover
        coverTrigger: false, // Displays dropdown below the button
        alignment: 'right' // Displays dropdown with edge aligned to the left of button);
    })
});
var $root = $('html, body');
$(document).on('click', 'a[href^="#"]', function (event) {
    event.preventDefault();

    $root.animate({
        scrollTop: $($.attr(this, 'href')).offset().top
    }, 500);
});