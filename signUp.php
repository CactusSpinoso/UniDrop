<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Registrati</title>
</head>

<body>
    <h1>Registrati</h1>
    <?php
    if (isset($_GET['errore']))
        echo '<p style="color: red;">Errore: ' . $_GET['errore'] . '</p>';
    ?>
    <form action="checkSign.php" method="post">
        <table>
            <tr>
                <td><label for="Nome">Nome:</label></td>
                <td><input type="text" name="Nome" required></td>
            </tr>
            <tr>
                <td><label for="Cognome">Cognome:</label></td>
                <td><input type="text" name="Cognome" required></td>
            </tr>
            <tr>
                <td><label for="Email">Email:</label></td>
                <td><input type="email" name="Email" required></td>
            </tr>
            <tr>
                <td><label for="Password">Password:</label></td>
                <td><input type="password" name="Password" required></td>
            </tr>
        </table>
        <input type="submit" value="Registrati">
        <table>
            <tr>
                <td>Hai già un account?</td>
                <td><a href="login.php">Accedi</a></td>
            </tr>
        </table>
    </form>
</body>

</html>
