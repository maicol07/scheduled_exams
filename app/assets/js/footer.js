function showFooter(btn) {
    $('#info_links').slideDown();
    $(btn).hide();
    $("#footer_hide").show()
}

function hideFooter(btn) {
    $('#info_links').slideUp();
    $(this).hide();
    $("#footer_show").show()
}