<?php

require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// load dotenv for environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

class Mailer
{
    private PHPMailer $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->configure();
    }

    private function configure(): void
    {
        // SMTP configuration
        $this->mail->isSMTP();
        $this->mail->Host = $_ENV['SMTP_HOST'];
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $_ENV['SMTP_USERNAME'];
        $this->mail->Password = $_ENV['SMTP_PASSWORD'];
        $this->mail->SMTPSecure = $_ENV['SMTP_ENCRYPTION'];
        $this->mail->Port = $_ENV['SMTP_PORT'];
    }

    public function sendEmail(string $to, string $subject, string $body, bool $isHTML = false   ): bool
    {
        try {
            // Set email parameters
            $this->mail->setFrom($_ENV['SMTP_USERNAME'], 'Innerspace');
            $this->mail->addAddress($to);
            $this->mail->isHTML($isHTML);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;

            // Send the email
            return $this->mail->send();
        } catch (Exception $e) {
            error_log('Mailer Error: ' . $e->getMessage());
            return false;
        }
    }
}
