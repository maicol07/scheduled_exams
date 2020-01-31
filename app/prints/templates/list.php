<?php

use Mpdf\HTMLParserMode;
use Mpdf\Output\Destination;
use src\Collection;
use src\Utils;

require_once "../../../core.php";

$list = new Collection($db, $user, null, get('view'));
$classroom = new \src\Classroom($db, $user, $list->classroom_id);

$title = __("Lista :list: - :classroom:", [':list:' => $list->name, ':classroom:' => $classroom->name]);
require_once DOCROOT . "/app/prints/init.php";
$pdf->setTitle($title);

// Redefinition to avoid naming problems
$list = new Collection($db, $user, null, get('view'));

ob_start();
?>
    <h4><?php echo __("Interrogazioni della lista :list: - Classe :classroom:", [':list:' => $list->name, ':classroom:' => $classroom->name]) ?></h4>
    <table id="list_table" class='table table-striped table-bordered'>
        <thead>
        <tr>
            <th><?php echo __("N.") ?></th>
            <th><?php echo __("Studente") ?></th>
            <th><?php echo __("Data") ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $students = (new \src\Classroom($db, $user, $list->classroom_id))->getStudents();
        foreach ($list->rows as $key => $row) {
            $row = (object)$row;
            $row_student = (object)$students[$row->student_id];
            echo '
                <tr id="list_row_' . $row->id . '">
                    <td>
                        ' . (string)((int)$key + 1) . '
                    </td>
                    <td>
                        ' . $row_student->name . '
                    </td>
                    <td>
                        ' . Utils::getLocaleDate($row->date, $lang) . '
                    </td>
                </tr>
                ';
        }
        ?>
        </tbody>
    </table>

<?php
$pdf->WriteHTML(ob_get_clean(), HTMLParserMode::HTML_BODY);

// Footer
$footer = '<small id="list_details">';
if ($list->type == "FROM_START_DATE") {
    if (!empty($list->start_date)) {
        $date = Utils::getLocaleDate($list->start_date, $lang);
        $footer .= '<span id="list_start_date">' . __("Data di inizio delle interrogazioni: <b>%s</b>", $date) . '</span><br>';
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
        $footer .= '<span id="list_weekdays">' . __("Giorni in cui si effettua l'interrogazione: <b>%s</b>", $weekdays_str) . '</span><br>';
    }
    $footer .= '<span id="list_max_students">' . __("Numero massimo di studenti interrogati per volta: <b>%s</b>", $list->quantity) . '</span>';
}

$pdf->SetHTMLFooter($footer);
$pdf->Output($title . ".pdf", Destination::INLINE);
?>