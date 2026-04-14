<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!(isset($_POST['Email']) && isset($_POST['Password']))) {
    header("Location: login.php?errore=Tutti i campi sono obbligatori");
    die();
}

require_once "classes/usersManager.php";
require_once "classes/user.php";

$usersMngr = new UsersManager();

if ($usersMngr->doLogin($_POST['Email'], $_POST['Password'])) {
    // Controlla se l'account è stato attivato
    if (!$usersMngr->isUserActive($_POST['Email'])) {
        $_SESSION['emailDaAttivare'] = $_POST['Email'];
        header("Location: attiva.php?errore=Account non ancora attivato. Controlla la tua email.");
        die();
    }

    $_SESSION['ID_User'] = $usersMngr->getUserId($_POST['Email']);
    if ($usersMngr->isAdmin($_POST['Email'])) {
        $_SESSION['isAdmin'] = true;
        $_SESSION['Email'] = new User("", "", $_POST['Email']);
        header("Location: admin.php");
    } else {
        $_SESSION['isAdmin'] = false;
        $_SESSION['Email'] = new User("", "", $_POST['Email']);
        header("Location: index.php");
    }
} else {
    header("Location: login.php?errore=Credenziali non valide");
}
die();
