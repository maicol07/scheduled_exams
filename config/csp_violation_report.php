<?php
require_once __DIR__ . "/../core.php";

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

$requestContent = file_get_contents("php://input");
$data = json_decode($requestContent, true);

$mail = new PHPMailer(TRUE);
try {
    $mail->setFrom($config->get('csp', 'sender_email'), $config->get('csp', 'sender_name'));
    $mail->addAddress($config->get('csp', 'recipient_email'), $config->get('csp', 'recipient_name'));
    $mail->Subject = "CSP Violations - Scheduled Exams";
    $mail->isHTML();
    $message = "Following violations occured:<br/><br/>";

    if (!empty($document_uri = $data['csp-report']['document-uri'])) {
        $message .= "<b>Document URI:</b> " . $document_uri . "<br><br>";
    }
    if (!empty($referrer = $data['csp-report']['referrer'])) {
        $message .= "<b>Referrer:</b> " . $referrer . "<br><br>";
    }
    if (!empty($blocked_uri = $data['csp-report']['blocked-uri'])) {
        $message .= "<b>Blocked URI:</b> " . $blocked_uri . "<br><br>";
    }
    if (!empty($violated_directive = $data['csp-report']['violated-directive'])) {
        $message .= "<b>Violated Directive:</b> " . $violated_directive . "<br><br>";
    }
    if (!empty($original_policy = $data['csp-report']['original_policy'])) {
        $message .= "<b>Original Policy:</b> " . $original_policy . "<br><br>";
    }
    $mail->Body = $message;
    $mail->send();
} catch (Exception $e) {
    /* PHPMailer exception */
    die(json_encode([
        'success' => false,
        'error' => "An unexpected error occured:" . "<br>" . $e->errorMessage()
    ]));
} catch (\Exception $e) {
    /* PHP exception */
    die(json_encode([
        'success' => false,
        'error' => "An unexpected error occured:" . "<br>" . $e->getMessage()
    ]));
}