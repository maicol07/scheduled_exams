<?php

use src\Collection;
use src\Utils;

require_once "../core.php";

$list = new Collection($db, $user, null, get('view'));

$title = __("Lista %s", $list->name);
require_once DOCROOT . "/app/layout/top.php";

// Redefinition to avoid naming problems
$list = new Collection($db, $user, null, get('view'));
?>
<div class="mdc-layout-grid">
    <div class="mdc-layout-grid__inner" style="display: flex">
        <div class="mdc-layout-grid__cell<?php echo $detector->isMobile() ? ' mdc-layout-grid__cell--order-2' : '' ?>"
             style="flex: 1; margin-top: 15px">
            <div class="mdc-data-table" style="width: 100%;">
                <table id="list_table" class="mdc-data-table__table"
                       aria-label="<?php echo __("Interrogazioni della lista %s", $list->name) ?>">
                    <thead>
                    <tr class="mdc-data-table__header-row">
                        <th class="mdc-data-table__header-cell" role="columnheader"
                            scope="col"><?php echo __("Studente") ?></th>
                        <th class="mdc-data-table__header-cell" role="columnheader"
                            scope="col"><?php echo __("Data") ?></th>
                        <th class="mdc-data-table__header-cell" role="columnheader" scope="col"></th>
                    </tr>
                    </thead>
                    <tbody class="mdc-data-table__content">
                    <?php
                    $students = (new \src\Classroom($db, $user, $list->classroom_id))->getStudents();
                    foreach ($list->rows as $row) {
                        $row = (object)$row;
                        $row_student = (object)$students[$row->student_id];
                        echo '
                        <tr id="list_row_' . $row->id . '" class="mdc-data-table__row">
                            <td class="mdc-data-table__cell">
                                <div class="mdc-chip-set" role="grid">
                                    <div class="mdc-chip" role="row">
                                        <div class="mdc-chip__ripple"></div>
                                        <img src="' . $row_student->image . '" class="mdc-chip__icon mdc-chip__icon--leading" alt="' . $row_student->name . '">
                                        <span role="gridcell">
                                            <span role="button" tabindex="0" class="mdc-chip__text">' . $row_student->name . '</span>
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="mdc-data-table__cell">
                                <span id="unix_timestamp" style="display: none">' . strtotime($row->date) . '</span>
                                ' . Utils::getLocaleDate($row->date, $lang) . '
                            </td>
                            <td class="mdc-data-table__cell">
                                <button class="mdc-icon-button mdc-card__action mdc-card__action--icon"
                                            title="' . __("Modifica") . '" onclick="editRow(\'list_row_' . $list->code . '\')">
                                      <i class="mdi-outline-edit mdc-button__icon"></i>
                                </button>
                                <button class="mdc-icon-button mdc-card__action mdc-card__action--icon"
                                            title="' . __("Elimina") . '" onclick="deleteRow(\'list_row_' . $list->code . '\')">
                                      <i class="mdi-outline-delete mdc-button__icon"></i>
                            </td>
                        </tr>
                        ';
                    }
                    if (empty($list->rows)) {
                        echo '
                            <tr id="no_rows" class="mdc-data-table__row">
                                <td class="mdc-data-table__cell" colspan="3" style="text-align: center;">
                                    ' . __("Nessuna riga nella lista") . '
                                </td>
                            </tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div id="actions_buttons" style="margin-top: 10px">
                <button class="mdc-button mdc-button--raised" style="float: left;"
                        onclick="addRow('list_row_<?php echo $list->code ?>')">
                    <div class="mdc-button__ripple"></div>
                    <i class="mdi-outline-add mdc-button__icon"></i>
                    <span class="mdc-button__label"><?php echo __("Aggiungi") ?></span>
                </button>
                <a class="mdc-button" href="prints/templates/list?view=<?php echo $list->code ?>" target="_blank"
                   style="float: right;">
                    <div class="mdc-button__ripple"></div>
                    <i class="mdi-outline-print mdc-button__icon"></i>
                    <span class="mdc-button__label"><?php echo __("Stampa") ?></span>
                </a>
            </div>
        </div>
        <div class="mdc-layout-grid__cell<?php echo $detector->isMobile() ? ' mdc-layout-grid__cell--order-1' : '' ?>"
             style="flex: 1">
            <h3><?php echo __("Informazioni sulla lista") ?></h3>
            <div class="mdc-card" id="list_info">
                <div class="mdc-card__primary-action">
                    <div class="mdc-card__media mdc-card__media--16-9"
                         style="background-image: url(&quot;<?php echo(!empty($list->image) ? $list->image : ROOTDIR . '/app/assets/img/undraw/list.svg') ?>&quot;);"></div>
                </div>
                <div class="mdc-card__primary">
                    <div class="mdc-typography--headline6"><?php echo $list->name ?></div>
                </div>
                <div class="mdc-card__secondary">
                    <div class="mdc-typography--subtitle2"><?php echo $list->description ?></div>
                    <br><br>
                    <?php
                    $types = [
                        'AUTO' => __("Automatica"),
                        'FROM_START_DATE' => __("Automatica da data di inizio"),
                        'MANUAL' => __("Manuale")
                    ]
                    ?>
                    <small id="list_details">
                        <span
                            id="list_type"><?php echo __("Tipo generazione lista: <b>%s</b>", $types[$list->type]) ?></span><br>
                        <?php
                        if ($list->type == "FROM_START_DATE") {
                            if (!empty($list->start_date)) {
                                $date = Utils::getLocaleDate($list->start_date, $lang);
                                echo '<span id="list_start_date">' . __("Data di inizio delle interrogazioni: <b>%s</b>", $date) . '</span><br>';
                            }
                            $weekdays = unserialize($list->weekdays);
                            if (!empty($weekdays)) {
                                $week = [
                                    'monday' => __("Lunedì"),
                                    'tuesday' => __("Martedì"),
                                    'wednesday' => __("Mercoledì"),
                                    'thursday' => __("Giovedì"),
                                    'friday' => __("Venerdì"),
                                    'saturday' => __("Sabato"),
                                    'sunday' => __("Domenica")
                                ];
                                $weekdays_str = '';
                                foreach ($weekdays as $weekday) {
                                    if (array_search($weekday, $weekdays) > 0) {
                                        $weekdays_str .= ", ";
                                    }
                                    $weekdays_str .= "{$week[$weekday]}";
                                }
                                echo '<span id="list_weekdays">' . __("Giorni in cui si effettua l'interrogazione: <b>%s</b>", $weekdays_str) . '</span><br>';
                            }
                            echo '<span id="list_max_students">' . __("Numero massimo di studenti interrogati per volta: <b>%s</b>", $list->quantity) . '</span>';
                        }
                        ?>
                    </small>
                </div>
                <div class="mdc-card__actions">
                    <div class="mdc-card__action-icons">
                        <button class="mdc-icon-button mdc-card__action mdc-card__action--icon"
                                title="<?php echo __("Condividi") ?>"
                                onclick="shareList('<?php echo $list->code ?>')">
                            <i class="mdc-button__icon mdi-outline-share"></i>
                        </button>
                        <?php if ($user->getId() == (new \src\Classroom($db, $user, $list->classroom_id))->admin) {
                            echo '<button class="mdc-icon-button mdc-card__action mdc-card__action--icon"
                                title="' . __("Modifica") . '"
                                id="edit_button"
                                onclick="editList()">
                            <i class="mdc-button__icon mdi-outline-edit"></i>
                        </button>
                        <button class="mdc-icon-button mdc-card__action mdc-card__action--icon"
                                title="' . __("Elimina") . '"
                                onclick="deleteList(' . (string)$list->id . ', \'' . $list->name . '\')">
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
$assets->add('list.js');
require_once DOCROOT . "/app/layout/bottom.php";
?>
