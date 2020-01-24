<?php

use Mpdf\Mpdf;
use src\Collection;
use src\Utils;

require_once "../../core.php";

$list = new Collection($db, $user, null, get('view'));

$title = __("Lista %s", $list->name);
require_once DOCROOT . "/app/prints/layout/top.php";

// Redefinition to avoid naming problems
$list = new Collection($db, $user, null, get('view'));

$pdf = new Mpdf(['tempDir' => DOCROOT . '/app/prints/temp']);
ob_start();
?>
    <div class="mdc-data-table" style="width: 100%;">
        <h1 class="mdc-typography--headline1"><?php echo __("Interrogazioni della lista %s", $list->name) ?></h1>
        <table id="list_table" class="mdc-data-table__table"
               aria-label="<?php echo __("Interrogazioni della lista %s", $list->name) ?>">
            <thead>
            <tr class="mdc-data-table__header-row">
                <th class="mdc-data-table__header-cell" role="columnheader"
                    scope="col"><?php echo __("Studente") ?></th>
                <th class="mdc-data-table__header-cell" role="columnheader" scope="col"><?php echo __("Data") ?></th>
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
                        </tr>
                        ';
            }
            ?>
            </tbody>
        </table>
    </div>

<?php
//$assets->add('list.js');
require_once DOCROOT . "/app/prints/layout/bottom.php";
$pdf->WriteHTML(ob_get_clean());
$pdf->Output();
?>