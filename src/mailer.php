<?php
namespace RemnWeb;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    private PHPMailer $mail;

    public function __construct()
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->Host = $_ENV['SMTP_HOST'] ?? 'localhost';
        $this->mail->Port = (int)($_ENV['SMTP_PORT'] ?? 25);
        $this->mail->SMTPAuth = !empty($_ENV['SMTP_USER']);
        if ($this->mail->SMTPAuth) {
            $this->mail->Username = $_ENV['SMTP_USER'];
            $this->mail->Password = $_ENV['SMTP_PASS'] ?? '';
        }
        $this->mail->setFrom($_ENV['SMTP_FROM'] ?? 'no-reply@example.com', 'RemnVPN');
    }

    public function send(string $to, string $subject, string $body): bool
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($to);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            return $this->mail->send();
        } catch (Exception $e) {
            return false;
        }
    }
}
