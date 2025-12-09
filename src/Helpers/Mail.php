<?php

namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// require_once __DIR__ . '/../../vendor/autoload.php';

class Mail
{
    protected $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);

        try {
            // Configuración SMTP
            $this->mailer->isSMTP();
            $this->mailer->Host       = $_ENV['MAIL_HOST'];
            $this->mailer->SMTPAuth   = true;
            $this->mailer->Username   = $_ENV['MAIL_USERNAME'];
            $this->mailer->Password   = $_ENV['MAIL_PASSWORD'];
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port       = $_ENV['MAIL_PORT'] ?? 587;

            $this->mailer->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
            $this->mailer->isHTML(true); // Emails en HTML

        } catch (Exception $e) {
            error_log("Mailer Error: {$e->getMessage()}");
        }
    }

    public function send($to, $subject, $body)
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($to);
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $body;

            return $this->mailer->send();

        } catch (Exception $e) {
            error_log("Error enviando email: {$e->getMessage()}");
            return false;
        }
    }
}
