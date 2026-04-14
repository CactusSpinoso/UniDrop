<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['ID_User'])) {
    header("Location: login.php?errore=Devi effettuare il login");
    exit;
}

if (!isset($_FILES["file"])) {
    header("Location: file.php?errore=File non caricato");
    exit;
}

if ($_FILES["file"]["error"] == 4) {
    header("Location: file.php?errore=Nessun file selezionato");
    exit;
}

if ($_FILES["file"]["error"] != 0) {
    header("Location: file.php?errore=Errore durante il caricamento");
    exit;
}

$nomeOriginale = $_FILES["file"]["name"];
$dimensioneFile = $_FILES["file"]["size"];

if ($dimensioneFile > 20 * 1024 * 1024) {
    header("Location: file.php?errore=File troppo grande");
    exit;
}

$nomeTemp = $_FILES["file"]["tmp_name"];
if ($nomeTemp == "") {
    header("Location: file.php?errore=File non valido");
    exit;
}

$contenuto = file_get_contents($nomeTemp);

// Rinomina il file con SHA1 del contenuto + estensione originale
$sha1 = sha1($contenuto);
$estensione = pathinfo($nomeOriginale, PATHINFO_EXTENSION);
$nomeFile = $estensione !== "" ? $sha1 . "." . $estensione : $sha1;
$destinazione = "uploads/" . $nomeFile;

require_once "classes/database.php";
$db = new Database();

// Controlla se il file è già presente nel DB
$sha1Esc = $db->escape($sha1);
$result = $db->query("SELECT ID FROM files WHERE SHA1='$sha1Esc'");

if ($result->num_rows > 0) {
    // File già esistente: recupera l'ID
    $idFile = $result->fetch_assoc()['ID'];
} else {
    // Salva il file su disco
    file_put_contents($destinazione, $contenuto);

    // Inserisce il record nella tabella files
    $nomeOriginaleEsc = $db->escape($nomeOriginale);
    $db->query("INSERT INTO files (SHA1, NomeOriginale) VALUES ('$sha1Esc', '$nomeOriginaleEsc')");
    $idFile = $db->lastInsertId();
}

// Associa il file all'utente (se non già associato)
$idUser = (int) $_SESSION['ID_User'];
$assoc = $db->query("SELECT 1 FROM user_files WHERE ID_User=$idUser AND ID_File=$idFile");

if ($assoc->num_rows == 0) {
    $db->query("INSERT INTO user_files (ID_User, ID_File) VALUES ($idUser, $idFile)");
}

$_SESSION['uploaded_file'] = $destinazione;
header("Location: index.php?successo=File caricato con successo");
exit;
