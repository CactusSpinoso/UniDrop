<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>

<body>
    <h1>Login</h1>
    <?php
    if (isset($_GET['errore']))
        echo '<p style="color: red;">Errore: ' . $_GET['errore'] . '</p>';
    if (isset($_GET['messaggio']))
        echo '<p style="color: green;">' . $_GET['messaggio'] . '</p>';
    ?>
    <form action="checkLogin.php" method="post">
        <table>
            <tr>
                <td><label for="Email">Email:</label></td>
                <td><input type="text" name="Email" required></td>
            </tr>
            <tr>
                <td><label for="Password">Password:</label></td>
                <td><input type="password" name="Password" required></td>
            </tr>
        </table>
        <br>
        <input type="submit" value="Login">
        <br>
        <br>
        <table>
            <tr>
                <td>Non hai un account?</td>
                <td><a href="signUp.php">Registrati</a></td>
            </tr>
            <tr>
                <td>Hai dimenticato la password?</td>
                <td><a href="recuperaPassword.php">Recupera password</a></td>
            </tr>
        </table>
    </form>
</body>

</html>
