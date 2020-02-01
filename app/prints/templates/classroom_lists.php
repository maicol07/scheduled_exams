<?php

use Mpdf\HTMLParserMode;
use Mpdf\Output\Destination;
use src\Classroom;
use src\Collection;
use src\Utils;

require_once "../../../core.php";

$classroom = new Classroom($db, $user, null, get('view'));
$list = new Collection($db, $user, null, null);
$lists = $list->getLists($classroom->id);

$title = __("Interrogazioni classe %s", $classroom->name);
require_once DOCROOT . "/app/prints/init.php";
$pdf->setTitle($title);

ob_start();
?>
<h2><?php echo __("Interrogazioni della classe %s", $classroom->name) ?></h2>
<?php
// Set to how much lists must be printed in one row
$max_lists_row = 2;
$column_size = ceil(12 / $max_lists_row);
//echo '<style>' . $css . '</style>';
echo '<div class="row">';
foreach ($lists as $list_key => $list) {
    if ((int)$list_key != 0 and (int)$list_key % $max_lists_row == 0) {
        echo '</div><div class="row">';
    }
    $list = new Collection($db, $user, $list['id'])
    ?>
    <div class="col-xs-<?php echo $column_size ?>" style="width: <?php echo 100 / $max_lists_row - 5 ?>%;
    <?php echo (((int)$list_key + 1) % 2 == 0) ? 'padding-left: 15px' : '' ?>">
        <h4><?php echo __("Lista %s", $list->name) ?></h4>
        <table id="list_<?php echo $list->code ?>_table" class='table table-striped table-bordered'>
            <thead>
            <tr>
                <th><?php echo __("N.") ?></th>
                <th><?php echo __("Studente") ?></th>
                <th><?php echo __("Data") ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $students = (new Classroom($db, $user, $list->classroom_id))->getStudents();
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
        echo $footer
        ?>
    </div>
    <?php
}
echo "</div>";
$html = ob_get_contents();
$pdf->WriteHTML($html, HTMLParserMode::HTML_BODY);
ob_end_clean();
//echo $html;

$pdf->SetHTMLFooter('<small>' . __("Stampato il %s utilizzando Interrogazioni Programmate", Utils::getLocaleDate(date("Y-m-d"), $lang)) . '</small>');

$pdf->Output($title . ".pdf", Destination::INLINE);
?>
