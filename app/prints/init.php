<?php

use Mpdf\Mpdf;

$temp_dir = DOCROOT . '/app/prints/temp';

if (!is_dir($temp_dir)) {
    mkdir($temp_dir, 0777, true);
}

$pdf = new Mpdf(['tempDir' => $temp_dir]);