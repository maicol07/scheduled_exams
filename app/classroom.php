<?php

use src\Classroom;
use src\Collection;
use src\Utils;

require_once "../core.php";

$classroom = new Classroom($db, $user, null, get('view'));

$title = __("Classe %s", $classroom->name);
$assets->add('datepicker.css');
require_once DOCROOT . "/app/layout/top.php";
?>
<button class="mdc-fab mdc-fab--extended mdc-fab--bottom" onclick="createList()">
    <div class="mdc-fab__ripple"></div>
    <i class="mdi-outline-add mdc-fab__icon"></i>
    <span class="mdc-fab__label"><?php echo __("Crea lista") ?></span>
</button>
<div class="mdc-layout-grid">
    <div class="mdc-layout-grid__inner" style="display: flex">
        <div id="lists_grid"
             class="mdc-layout-grid__cell<?php echo $detector->isMobile() ? ' mdc-layout-grid__cell--order-2' : '' ?>"
             style="flex: 1">
            <?php
            $lists = (new Collection($db, $user))->getLists($classroom->id);
            if (!empty($lists)) {
                echo '<h3>' . __("Liste") . '</h3>
                <div class="mdc-layout-grid__inner" style="display: flex">';
                foreach ($lists as $list) {
                    $list = (object)$list;
                    echo '
                    <div class="mdc-layout-grid__cell" id="list_' . $list->code . '">
                        <div class="mdc-card">
                            <div class="mdc-card__primary-action" tabindex="0" onclick="window.location.href = BASEURL + \'app/list?view=' . $list->code . '\'">
                                ' . (!empty($list->image) ?
                            '<div class="mdc-card__media mdc-card__media--16-9" style="background-image: url(&quot;' . $list->image . '&quot;);"></div>'
                            : '') . '
                                <div class="mdc-card__primary">
                                    <h2 class="mdc-typography--headline6">' . $list->name . '</h2>
                                </div>
                                <div class="mdc-card__secondary mdc-typography--body2">
                                    ' . $list->description . (!empty($list->description) ? "<br>" : "") . '
                                </div>
                            </div>
                            <div class="mdc-card__actions">
                                <div class="mdc-card__action-buttons">
                                    <a href="list?view=' . $list->code . '" class="mdc-button mdc-card__action mdc-card__action--button">
                                        <div class="mdc-button__ripple"></div>
                                        <i class="mdi-outline-open_in_new mdc-button__icon"></i>
                                        <span class="mdc-button__label">' . __("Apri") . '</span>
                                    </a>
                                </div>
                                <div class="mdc-card__action-icons">
                                    <button class="mdc-icon-button mdc-card__action mdc-card__action--icon"
                                            title="' . __("Condividi") . '" onclick="shareList(\'' . $list->code . '\')">
                                      <i class="mdi-outline-share mdc-button__icon"></i>
                                    </button>
                                    ' . ($user->getId() == $classroom->admin ? '<button class="mdc-icon-button mdc-card__action mdc-card__action--icon"
                                            title="' . __("Elimina") . '"
                                            onclick="deleteList(\'' . $list->id . '\', \'' . $list->name . '\')">
                                      <i class="mdi-outline-delete mdc-button__icon"></i>
                                    </button>' : '') . '
                                </div>
                            </div>
                        </div>
                    </div>';
                }
                echo "</div>";
            } else {
                echo '
                <div id="nolists" style="text-align: center" xmlns="http://www.w3.org/1999/html">
                    <img src="' . Utils::buildAssetsURI("/app/assets/img/undraw/no_data.svg") . '" alt="' . __("Nessuna lista") . '"
                    style="width: 350px; margin-bottom: 20px"><br>
                    <span class="mdc-typography--headline5">' . __("Nessuna lista") . '</span><br>
                    <span>' . __("Puoi aggiungere nuove liste dal pulsante in basso a destra") . '</span>
                </div>';
            }
            ?>
        </div>
        <div class="mdc-layout-grid__cell<?php echo $detector->isMobile() ? ' mdc-layout-grid__cell--order-1' : '' ?>"
             style="flex: 1">
            <div class="mdc-card" id="class_info">
                <div class="mdc-card__primary-action">
                    <div class="mdc-card__media mdc-card__media--16-9"
                         style="background-image: url(&quot;<?php echo(!empty($classroom->image) ? $classroom->image : ROOTDIR . '/app/assets/img/undraw/exams.svg') ?>&quot;);"></div>
                </div>
                <div class="mdc-card__primary">
                    <div class="mdc-typography--headline6"><?php echo $classroom->name ?></div>
                </div>
                <div class="mdc-card__secondary">
                    <div class="mdc-typography--subtitle2"><?php echo $classroom->description ?></div>
                </div>
                <div class="mdc-card__actions">
                    <div class="mdc-card__action-buttons">
                        <?php if ($user->getId() == $classroom->admin) {
                            echo '<button class="mdc-button mdc-card__action mdc-card__action--button" onclick="studentsList()">
                                <div class="mdc-button__ripple"></div>
                                <i class="mdc-button__icon mdi-outline-people_outline"></i>
                                <span class="mdc-button__label">' . __("Lista studenti") . '</span>
                            </button>';
                        }
                        ?>
                    </div>
                    <div class="mdc-card__action-icons">
                        <button class="mdc-icon-button mdc-card__action mdc-card__action--icon"
                                title="<?php echo __("Condividi") ?>"
                                onclick="shareClassroom('<?php echo $classroom->code ?>')">
                            <i class="mdc-button__icon mdi-outline-share"></i>
                        </button>
                        <?php if ($user->getId() == $classroom->admin) {
                            echo '<button class="mdc-icon-button mdc-card__action mdc-card__action--icon"
                                title="' . __("Modifica") . '"
                                id="edit_button"
                                onclick="editClassroom()">
                            <i class="mdc-button__icon mdi-outline-edit"></i>
                        </button>
                        <button class="mdc-icon-button mdc-card__action mdc-card__action--icon"
                                title="' . __("Elimina") . '"
                                onclick="deleteClassroom(' . (string)$classroom->id . ', \'' . $classroom->name . '\')">
                            <i class="mdc-button__icon mdi-outline-delete"></i>
                        </button>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once DOCROOT . '/app/assets/lib/material-date-picker/datepicker.php';
$assets->add(['classroom.js', 'list.js', 'datepicker.js']);
require_once DOCROOT . "/app/layout/bottom.php";
?>
<script>
    var dp = DatePicker($(':root').css("--mdc-theme-secondary"))
</script>