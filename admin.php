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
    <title>Admin</title>
</head>

<body>
    <h1>Ciao Admin</h1>
    <form action="logout.php" method="post">
        <input type="submit" value="Logout">
    </form>
</body>
</html>
