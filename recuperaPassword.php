<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Recupera Password</title>
</head>

<body>
    <h1>Recupera Password</h1>
    <p>Inserisci la tua email. Ti invieremo un link per reimpostare la password.</p>

    <?php
    if (isset($_GET['errore']))
        echo '<p style="color: red;">Errore: ' . $_GET['errore'] . '</p>';
    if (isset($_GET['messaggio']))
        echo '<p style="color: green;">' . $_GET['messaggio'] . '</p>';
    ?>

    <form action="checkRecuperoPassword.php" method="post">
        <table>
            <tr>
                <td><label for="Email">Email:</label></td>
                <td><input type="email" name="Email" required></td>
            </tr>
        </table>
        <br>
        <input type="submit" value="Invia link di recupero">
    </form>
    <br>
    <a href="login.php">Torna al login</a>
</body>

</html>
