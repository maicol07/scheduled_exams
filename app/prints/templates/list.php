<?php

use App\Utils;
use Mpdf\HTMLParserMode;
use Mpdf\Output\Destination;
use src\Collection;

require_once __DIR__ . "/../../../core.php";

$list = new Collection($db, $user, null, get('view'));
$classroom = new \src\Classroom($db, $user, $list->classroom_id);

$title = __("Lista %list% - %classroom%", ['%list%' => $list->name, '%classroom%' => $classroom->name]);
require_once DOCROOT . "/app/prints/init.php";
$pdf->setTitle($title);

// Redefinition to avoid naming problems
$list = new Collection($db, $user, null, get('view'));

ob_start();
?>
<h4><?php echo __("Interrogazioni della lista %list% - Classe %classroom%", ['%list%' => $list->name, '%classroom%' => $classroom->name]) ?></h4>
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
    $key = 0;
    foreach ($list->rows as $row) {
        $row = (object)$row;
        $row_student = (object)$students[$row->student_id];
        echo '
                <tr id="list_row_' . $row->id . '">
                    <td>
                        ' . (string)($key + 1) . '
                    </td>
                    <td>
                        ' . $row_student->name . '
                    </td>
                    <td>
                        ' . Utils::getLocaleDate($row->date, $lang) . '
                    </td>
                </tr>
        ';
        $key += 1;
    }
    ?>
    </tbody>
</table>

<?php
$pdf->WriteHTML(ob_get_clean(), HTMLParserMode::HTML_BODY);

// Footer
$footer = '';
if ($list->type == "FROM_START_DATE") {
    $footer .= '<small id="list_details">';
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
    $footer .= '</small><br><br>';
}
$footer .= '<small>' . __("Stampato il %s utilizzando Interrogazioni Programmate", Utils::getLocaleDate(date("Y-m-d"), $lang)) . '</small>';

$pdf->SetHTMLFooter($footer);
$pdf->Output($title . ".pdf", Destination::INLINE);
?>
