<?php

use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;

$temp_dir = DOCROOT . '/app/prints/temp';

if (!is_dir($temp_dir)) {
    mkdir($temp_dir, 0777, true);
}

$pdf = new Mpdf(['tempDir' => $temp_dir]);
$pdf->setAuthor(__("Interrogazioni Programmate"));

$css = '';
foreach (['/app/prints/css/bootstrap.css', '/app/prints/css/style.css'] as $css_file) {
    $docroot = explode("/", DOCROOT);
    $css_file_split = explode("/", $css_file);
    /*if (end($docroot) == $css_file_split[1]) {
        array_pop($docroot);
        $docroot = implode("/", $docroot);
        $css_file = implode("/", $css_file_split);
    } else {
        $docroot = DOCROOT;
    }*/
    $css .= file_get_contents(DOCROOT . $css_file);
}
$pdf->WriteHTML($css, HTMLParserMode::HEADER_CSS);