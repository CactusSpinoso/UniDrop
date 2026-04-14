<?php
if (!(isset($_POST['token']) && isset($_POST['Password']))) {
    header("Location: login.php?errore=Richiesta non valida");
    die();
}

require_once "classes/usersManager.php";

$usersMngr = new UsersManager();

if ($usersMngr->verifyRecoveryToken($_POST['token'])) {
    $usersMngr->updatePassword($_POST['token'], $_POST['Password']);
    header("Location: login.php?messaggio=Password aggiornata con successo! Ora puoi accedere.");
} else {
    header("Location: nuovaPassword.php?token=" . $_POST['token'] . "&errore=Link non valido o scaduto");
}
die();
