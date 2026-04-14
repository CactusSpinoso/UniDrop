<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['emailDaAttivare'])) {
    header("Location: login.php");
    die();
}

if (!isset($_POST['Codice'])) {
    header("Location: attiva.php?errore=Inserisci il codice di attivazione");
    die();
}

require_once "classes/usersManager.php";

$usersMngr = new UsersManager();
$email = $_SESSION['emailDaAttivare'];

if ($usersMngr->verifyActivationCode($email, $_POST['Codice'])) {
    $usersMngr->activateUser($email);
    unset($_SESSION['emailDaAttivare']);
    header("Location: login.php?messaggio=Account attivato con successo! Ora puoi accedere.");
} else {
    header("Location: attiva.php?errore=Codice non valido");
}
die();
