<?php
require_once __DIR__ . "/classes/user.php";
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['Email'])) {
    header("Location: login.php");
    die();
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>

<body>
    <h1>Ciao <?php echo $_SESSION['Email']->getEmail(); ?></h1>
    <?php
    if (isset($_GET['successo']))
        echo '<p style="color: green;">' . $_GET['successo'] . '</p>';
    ?>
    <form action="file.php" method="post">
        <input type="submit" value="Vai al caricamento file">
    </form>
    <br>
    <form action="visualizzaFile.php" method="get">
        <input type="submit" value="Visualizza i miei file">
    </form>
    <br>
    <form action="logout.php" method="post">
        <input type="submit" value="Logout">
    </form>
</body>

</html>
