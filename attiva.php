<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Attiva Account</title>
</head>

<body>
    <?php
    if (!isset($_SESSION)) {
        session_start();
    }
    if (!isset($_SESSION['emailDaAttivare'])) {
        header("Location: login.php");
        die();
    }
    ?>

    <h1>Attiva il tuo account</h1>
    <p>Ti abbiamo inviato un codice di attivazione all'indirizzo: <b><?php echo $_SESSION['emailDaAttivare']; ?></b></p>
    <p>Inserisci il codice ricevuto per completare la registrazione.</p>

    <?php
    if (isset($_GET['errore']))
        echo '<p style="color: red;">Errore: ' . $_GET['errore'] . '</p>';
    ?>

    <form action="checkAttivazione.php" method="post">
        <table>
            <tr>
                <td><label for="Codice">Codice di attivazione:</label></td>
                <td><input type="text" name="Codice" maxlength="6" required></td>
            </tr>
        </table>
        <br>
        <input type="submit" value="Attiva Account">
    </form>
</body>

</html>
