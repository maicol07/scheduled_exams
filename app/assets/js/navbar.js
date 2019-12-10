/**
 * Confirm dialog for language change. (NAVBAR)
 *
 * @param lang
 */
function langNotice(lang) {
    Swal_md.fire({
        title: tr.__("Vuoi cambiare la lingua preferita?"),
        text: tr.__("La lingua scelta verrà utilizzata per tutte le sessioni. Se invece vuoi utilizzare questa lingua solo in questa sessione (se navighi su altre pagine dovrai risceglierla ogni volta) premi No"),
        showCancelButton: true,
        confirmButtonText: tr.__("Sì"),
        cancelButtonText: tr.__("No"),
        showCloseButton: true,
        icon: "question"
    }).then((result) => {
        if (result.value) {
            Swal.showLoading();
            $.post({
                url: ROOTDIR + '/app/actions',
                data: {
                    action: "change_language",
                    lang: lang
                },
                success: function (data) {
                    Swal.fire({
                        title: tr.__("Lingua cambiata!"),
                        text: tr.__("La pagina ora si aggiornerà per rendere visibili le modifiche..."),
                        icon: "success"
                    });
                    Swal.showLoading();
                    window.location.reload();
                },
                error: function (jqxhr, status, error) {
                    Swal_md.fire({
                        title: tr.__("Ooops... qualcosa è andato storto!"),
                        html: tr.__("Si è verificato un errore!<br><br>") + status + " - " + error,
                        icon: "error"
                    })
                }
            });
            initSwalBtn()
        } else if (
            result.dismiss === Swal.DismissReason.backdrop ||
            result.dismiss === Swal.DismissReason.close ||
            result.dismiss === Swal.DismissReason.esc) {
            return null
        } else {
            if (!get('lang')) {
                window.location.href += ("?lang=" + lang)
            } else {
                window.location.href = window.location.href.split('?')[0] + '?lang=' + lang;
            }
        }
    });
    initSwalBtn()
}

$('#user_btn').click((e) => {
    var btn = e.currentTarget;
    Swal_md.fire({
        html: '<div id="user_info" style="align-content: center">' +
            '<img src="' + $(btn).attr('data-user-img') + '" alt="' + $(btn).attr('data-user-name') + '" style="border-radius: 50%"><br><br>' +
            '<b>' + $(btn).attr('data-user-name') + '</b><br>' +
            '<span>' + $(btn).attr('data-user-email') + '</span><br><br>' +
            '<a href="https://account.maicol07.it/a/" target="_blank" class="mdc-button mdc-button--outlined" style="border-radius: 100px" id="account_btn">' +
            '    <div class="mdc-button__ripple" style="border-radius: 100px"></div>' +
            '         <span class="mdc-button__label mdc-typography--button">' + tr.__("Gestisci il tuo account") + '</span>' +
            '    </a>' +
            '</div>',
        confirmButtonText: tr.__("Chiudi"),
        footer: "<button class='mdc-button' onclick='info()' style='vertical-align: middle'>" +
            "<div class='mdc-button__ripple'></div>" +
            "<i class='mdi-outline-info mdc-button__icon'></i>" +
            "<span class='mdc-button__label'>" + tr.__("Informazioni su Interrogazioni Programmate") + "</span>" +
            "</button>"
    });
    initRipple($('#account_btn, .swal2-footer .mdc-button'));
    initSwalBtn()
});

function info() {
    Swal.showLoading();
    var version;
    var dependencies = $("<ul style='list-style-type: none'></ul>");
    var finished;
    $.when(
        // Get system version
        $.get({
            url: ROOTDIR + '/VERSION',
            success: function (vers) {
                version = vers;
            },
            dataType: 'text'
        }),

        // Get libraries list
        $.getJSON(ROOTDIR + '/composer.json', function (composer) {
            $.getJSON(ROOTDIR + '/vendor/composer/installed.json', function (lib) {
                Object.keys(composer.require).forEach(function (value, key) {
                    if (value.includes('ext-')) {
                        return;
                    }
                    var element = $('<li></li>');
                    lib.some(function (obj, key) {
                        if (obj.name === value) {
                            var link;
                            if (typeof obj.homepage != "undefined") {
                                link = $('<a></a>');
                                link.text(obj.name);
                                link.attr('href', obj.homepage);
                            } else {
                                link = obj.name;
                            }
                            element.append(link);
                            return true;
                        }
                    });
                    dependencies.append(element)
                });
            });
        }),
        $.getJSON(ROOTDIR + '/package.json', function (pack) {
            Object.keys(pack.dependencies).forEach((value, key) => {
                dependencies.append('<li>' + value + '</li>');
            });
            finished = true;
        }),
    ).done(function () {
        function infoAlert() {
            if (finished) {
                var icons = {
                    "Kiranshastry": "https://www.flaticon.com/authors/kiranshastry"
                };
                var icons_ul = $('<ul style="list-style-type: none"></ul>');
                Object.keys(icons).forEach((value, key) => {
                    icons_ul.append('<li><a href="' + icons[value] + '" title="' + value + '">' + value + '</a> ' + tr.__("di") + ' <a href="https://www.flaticon.com" title="Flaticon">Flaticon</a></li>')
                });
                Swal_md.fire({
                    title: ("Info su Interrogazioni Programmate"),
                    // Advice for translators: for the part like <a href="XYZ" ...>ABAB</a> translate only ABAB as the other parts are HTML code to create the link! HTML tags (such as <br>) MUST stay unmodified as in source.
                    html: tr.__("Versione") + ' ' + version + '<br><br>' + tr.__("Interrogazioni Programmate è un software closed-source sviluppato da ") +
                        "<a href='https://maicol07.it'>Maicol Battistini (maicol07)</a>" + "<br><br>"
                        + tr.__("Librerie più utilizzate: ") + "<br>" + dependencies[0].outerHTML + "<br>" +
                        tr.__("Icone create da: ") + "<br>" + icons_ul[0].outerHTML,
                    footer: '<a href="' + ROOTDIR + '/changelog">' + tr.__("Leggi le note di rilascio") + '</a> - <a href="https://community.maicol07.it">' + tr.__("Community") + '</a>',
                    icon: "info",
                });
                initSwalBtn()
            }
        }

        setTimeout(infoAlert, 500)
    });
}