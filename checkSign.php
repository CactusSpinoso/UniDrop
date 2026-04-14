<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!(isset($_POST['Nome']) && isset($_POST['Cognome']) && isset($_POST['Email']) && isset($_POST['Password']))) {
    header("Location: signUp.php?errore=Tutti i campi sono obbligatori");
    die();
}

require_once "classes/usersManager.php";
require_once "classes/MailManager.php";

$usersMngr = new UsersManager();

if ($usersMngr->doSignup($_POST['Nome'], $_POST['Cognome'], $_POST['Email'], $_POST['Password'])) {
    // Genera codice di attivazione a 6 cifre
    $codice = rand(100000, 999999);

    // Salva il codice nel database
    $usersMngr->saveActivationCode($_POST['Email'], $codice);

    // Invia l'email con il codice
    $mailer = new MailManager();
    $mailer->sendActivationCode($_POST['Email'], $_POST['Nome'], $codice);

    // Salva l'email in sessione per usarla nella pagina di attivazione
    $_SESSION['emailDaAttivare'] = $_POST['Email'];

    header("Location: attiva.php");
} else {
    header("Location: signUp.php?errore=Email già registrata");
}
die();
