<?php

namespace App\Services;

use App\Models\Email;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;

class EmailService
{
    public function sendEmail(string $to, string $subject, string $body): void
    {
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host       = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAIL_USERNAME'];
            $mail->Password   = $_ENV['MAIL_PASSWORD'];
            $mail->Port       = $_ENV['MAIL_PORT'];

            $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
            $mail->addAddress($to);

            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();

            // Save data to DB
            $email = new Email();
            $email->save([
                'to' => $to,
                'subject' => $subject,
                'body' => $body,
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
