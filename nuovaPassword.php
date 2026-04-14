<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Nuova Password</title>
</head>

<body>
    <?php
    require_once "classes/usersManager.php";

    if (!isset($_GET['token'])) {
        header("Location: login.php?errore=Link non valido");
        die();
    }

    $usersMngr = new UsersManager();

    if (!$usersMngr->verifyRecoveryToken($_GET['token'])) {
        header("Location: login.php?errore=Link non valido o scaduto");
        die();
    }
    ?>

    <h1>Reimposta Password</h1>
    <p>Inserisci la tua nuova password.</p>

    <?php
    if (isset($_GET['errore']))
        echo '<p style="color: red;">Errore: ' . $_GET['errore'] . '</p>';
    ?>

    <form action="checkNuovaPassword.php" method="post">
        <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
        <table>
            <tr>
                <td><label for="Password">Nuova Password:</label></td>
                <td><input type="password" name="Password" required></td>
            </tr>
        </table>
        <br>
        <input type="submit" value="Salva nuova password">
    </form>
</body>

</html>
