<?php

use App\Utils;

require_once __DIR__ . "/../core.php";

$title = __("Dashboard");
require_once DOCROOT . "/app/layout/top.php";
?>
<button class="mdc-fab mdc-fab--bottom <?php echo !$detector->isMobile() ? 'mdc-fab--extended' : '" aria-label="' . __("Crea classe") ?>"
        onclick="createClassroom()">
    <div class="mdc-fab__ripple"></div>
    <i class="mdi-outline-add mdc-fab__icon"></i>
    <?php
    if (!$detector->isMobile()) {
        echo '<span class="mdc-fab__label">' . __("Crea classe") . '</span>';
    }
    ?>
</button>
<?php
if (!empty($classrooms)) {
    echo '<h3>' . __("Classi") . '</h3>
<div class="mdc-layout-grid">
    <div class="mdc-layout-grid__inner">';
    foreach ($classrooms as $classroom) {
        $classroom = (object)$classroom;
        $classroom_users = json_decode($classroom->users);
        if (is_array($classroom_users) and !in_array((int)$user->getId(), $classroom_users)) {
            continue;
        }
        echo '
        <div class="mdc-layout-grid__cell" id="classroom_' . $classroom->code . '">
            <div class="mdc-card">
                <div class="mdc-card__primary-action" tabindex="0" onclick="window.location.href = BASEURL + \'/app/classroom?view=' . $classroom->code . '\'">
                    ' . (!empty($classroom->image) ?
                '<div class="mdc-card__media mdc-card__media--16-9" style="background-image: url(&quot;' . $classroom->image . '&quot;);"></div>'
                : '') . '
                    <div class="mdc-card__primary">
                        <h2 class="mdc-typography--headline6">' . $classroom->name . '</h2>
                    </div>
                    <div class="mdc-card__secondary mdc-typography--body2">
                        ' . $classroom->description . (!empty($classroom->description) ? "<br>" : "") . '
                    </div>
                </div>
                <div class="mdc-card__actions">
                    <div class="mdc-card__action-buttons">
                        <a href="classroom?view=' . $classroom->code . '" class="mdc-button mdc-card__action mdc-card__action--button">
                            <div class="mdc-button__ripple"></div>
                            <i class="mdi-outline-open_in_new mdc-button__icon"></i>
                            <span class="mdc-button__label">' . __("Apri") . '</span>
                        </a>
                    </div>
                    <div class="mdc-card__action-icons">
                        <button class="mdc-icon-button mdc-card__action mdc-card__action--icon"
                                title="' . __("Condividi") . '" onclick="shareClassroom(\'' . $classroom->code . '\')">
                          <i class="mdi-outline-share mdc-button__icon"></i>
                        </button>
                        ' . (in_array($user->getId(), json_decode($classroom->admins)) ? '<button class="mdc-icon-button mdc-card__action mdc-card__action--icon"
                                title="' . __("Elimina") . '"
                                onclick="deleteClassroom(\'' . $classroom->id . '\', \'' . $classroom->name . '\')">
                          <i class="mdi-outline-delete mdc-button__icon"></i>
                        </button>' : '<button class="mdc-icon-button mdc-card__action mdc-card__action--icon"
                                title="' . __("Abbandona") . '"
                                onclick="leaveClassroom(\'' . $classroom->id . '\', \'' . $classroom->name . '\')">
                          <i class="mdi-outline-exit_to_app mdc-button__icon"></i>
                        </button>') . '
                    </div>
                </div>
            </div>
        </div>';
    }
    echo "</div>
</div>";
} else {
    echo '
    <div id="noclassrooms" style="text-align: center" xmlns="http://www.w3.org/1999/html">
        <img src="' . Utils::buildAssetsURI("/app/assets/img/undraw/no_data.svg") . '" alt="' . __("Nessuna classe") . '"
        style="width: 500px; margin-bottom: 20px"><br>
        <span class="mdc-typography--headline5">' . __("Nessuna classe") . '</span><br>
        <span>' . __("Puoi aggiungere nuove classi dal pulsante in basso a destra oppure") . '</span>
    </div>';
}
?>
<h3><?php echo __("Unisciti ad una classe") ?></h3>
<?php echo __("Inserisci il codice della tua classe e premi il pulsante: ") ?>
<br><br>
<form id="join_classroom">
    <div class="mdc-text-field mdc-text-field--with-leading-icon">
        <i class="mdi-outline-confirmation_number mdc-text-field__icon mdc-text-field__icon--leading"></i>
        <input type="text" id="classroom_join_code" class="mdc-text-field__input">
        <label class="mdc-floating-label" for="classroom_join_code"><?php echo __("Codice classe") ?></label>
        <div class="mdc-line-ripple"></div>
    </div>

    <button class="mdc-button mdc-button--outlined" type="submit" style="margin-left: 15px; margin-top: 15px;">
        <div class="mdc-button__ripple"></div>
        <i class="mdi-outline-send mdc-button__icon"></i>
        <span class="mdc-button__label"><?php echo __("Unisciti") ?></span>
    </button>
</form>

<?php
$assets->add('classroom.js');
require_once DOCROOT . "/app/layout/bottom.php";
?>
