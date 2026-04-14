<?php
if (!isset($_POST['Email'])) {
    header("Location: recuperaPassword.php?errore=Inserisci la tua email");
    die();
}

require_once "classes/usersManager.php";
require_once "classes/MailManager.php";

$usersMngr = new UsersManager();
$email = $_POST['Email'];

if ($usersMngr->emailEsiste($email)) {
    // Genera un token univoco
    $token = md5(uniqid());

    // Salva il token nel database
    $usersMngr->saveRecoveryToken($email, $token);

    // Invia l'email con il link di recupero
    $nome = $usersMngr->getNomeByEmail($email);
    $mailer = new MailManager();
    $mailer->sendRecoveryToken($email, $nome, $token);
}

// Mostriamo sempre lo stesso messaggio per non rivelare se l'email esiste o meno
header("Location: recuperaPassword.php?messaggio=Se l'email è registrata, riceverai un link per reimpostare la password.");
die();
