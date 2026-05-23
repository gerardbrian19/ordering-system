<?php
/**
 * mailer.php
 * Thin wrapper around PHPMailer for sending transactional emails via SMTP.
 *
 * Usage:
 *   require_once __DIR__ . '/mailer.php';
 *   $ok = sendMail('user@example.com', 'Juan', 'Subject', '<p>Hello</p>');
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

/**
 * Send an email via SMTP (PHPMailer).
 *
 * @param string $to       Recipient email address
 * @param string $toName   Recipient display name
 * @param string $subject  Email subject
 * @param string $htmlBody HTML email body
 * @param string $altBody  Plain-text fallback (auto-generated from $htmlBody if omitted)
 * @return bool            True on success, false on failure (error logged)
 */
function sendMail(
    string $to,
    string $toName,
    string $subject,
    string $htmlBody,
    string $altBody = ''
): bool {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        $mail->addAddress($to, $toName);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $htmlBody;
        $mail->AltBody = $altBody !== '' ? $altBody : strip_tags($htmlBody);

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('Mailer error [' . $to . ']: ' . $mail->ErrorInfo);
        return false;
    }
}
