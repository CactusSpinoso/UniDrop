<?php
require_once __DIR__ . '/../vendor/PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../vendor/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../vendor/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailManager
{
    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);

        $this->mail->isSMTP();
        $this->mail->Host       = 'smtp.gmail.com';
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = 'esercizio-5ainf@ismonnet.eu';   // <-- cambia con la tua email
        $this->mail->Password   = 'mkpm othv gkuj ursl';            // <-- cambia con la tua app password
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mail->Port       = 465;

        $this->mail->setFrom('esercizio-5ainf@ismonnet.eu', 'UniDrop NoReply');
        $this->mail->isHTML(true);
    }

    public function sendActivationCode($emailDestinatario, $nome, $codice)
    {
        try {
            $this->mail->addAddress($emailDestinatario);
            $this->mail->Subject = 'UniDrop - Conferma il tuo account';
            $this->mail->Body    = "Ciao <b>$nome</b>,<br><br>
                                    Il tuo codice di attivazione è: <b>$codice</b><br><br>
                                    Inseriscilo nella pagina di attivazione per completare la registrazione.";
            $this->mail->AltBody = "Ciao $nome, il tuo codice di attivazione e': $codice";

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function sendRecoveryToken($emailDestinatario, $nome, $token)
    {
        try {
            $this->mail->addAddress($emailDestinatario);
            $this->mail->Subject = 'UniDrop - Recupero password';
            $this->mail->Body    = "Ciao <b>$nome</b>,<br><br>
                                    Clicca sul link seguente per reimpostare la tua password:<br>
                                    <a href='http://localhost/UniDrop/nuovaPassword.php?token=$token'>Reimposta password</a><br><br>
                                    Se non hai richiesto il recupero password, ignora questa email.";
            $this->mail->AltBody = "Ciao $nome, accedi a questo link per reimpostare la password: http://localhost/UniDrop/nuovaPassword.php?token=$token";

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
