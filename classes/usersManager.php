<?php
class UsersManager
{
    public function getUsers()
    {
        require_once __DIR__ . "/database.php";
        require_once __DIR__ . "/user.php";
        $db = new Database();

        $result = $db->query("SELECT * FROM users");
        $users = [];

        while ($row = $result->fetch_assoc()) {
            $users[] = new User($row['Nome'], $row['Cognome'], $row['Email']);
        }

        return $users;
    }

    public function doLogin($email, $pass)
    {
        require_once __DIR__ . "/database.php";
        $db = new Database();

        $hashedPass = md5($pass);
        $result = $db->query("SELECT * FROM users WHERE Email='$email' AND Password='$hashedPass'");

        return $result->num_rows == 1;
    }

    public function doSignup($nome, $cognome, $email, $pass)
    {
        require_once __DIR__ . "/database.php";
        $db = new Database();

        $hashedPass = md5($pass);
        $result = $db->query("INSERT INTO users (Nome, Cognome, Email, Password) VALUES ('$nome', '$cognome', '$email', '$hashedPass')");

        return (bool) $result;
    }

    public function isAdmin($email)
    {
        require_once __DIR__ . "/database.php";
        $db = new Database();

        $result = $db->query("SELECT * FROM users WHERE Email='$email' AND IsAdmin=1");

        return $result->num_rows == 1;
    }

    public function getUserId($email)
    {
        require_once __DIR__ . "/database.php";
        $db = new Database();

        $emailEsc = $db->escape($email);
        $result = $db->query("SELECT ID FROM users WHERE Email='$emailEsc'");

        if ($result->num_rows == 1) {
            return $result->fetch_assoc()['ID'];
        }
        return null;
    }

    // -------------------------------------------------------
    // ATTIVAZIONE ACCOUNT
    // -------------------------------------------------------

    public function saveActivationCode($email, $codice)
    {
        require_once __DIR__ . "/database.php";
        $db = new Database();

        $emailEsc = $db->escape($email);
        $codiceEsc = $db->escape($codice);
        $result = $db->query("UPDATE users SET codiceAttivazione='$codiceEsc', dataInvioCodiceAttivazione=NOW() WHERE Email='$emailEsc'");

        return (bool) $result;
    }

    public function verifyActivationCode($email, $codice)
    {
        require_once __DIR__ . "/database.php";
        $db = new Database();

        $emailEsc = $db->escape($email);
        $codiceEsc = $db->escape($codice);
        $result = $db->query("SELECT * FROM users WHERE Email='$emailEsc' AND codiceAttivazione='$codiceEsc'");

        return $result->num_rows == 1;
    }

    public function activateUser($email)
    {
        require_once __DIR__ . "/database.php";
        $db = new Database();

        $emailEsc = $db->escape($email);
        $result = $db->query("UPDATE users SET isActive=1, codiceAttivazione=NULL WHERE Email='$emailEsc'");

        return (bool) $result;
    }

    public function isUserActive($email)
    {
        require_once __DIR__ . "/database.php";
        $db = new Database();

        $emailEsc = $db->escape($email);
        $result = $db->query("SELECT * FROM users WHERE Email='$emailEsc' AND isActive=1");

        return $result->num_rows == 1;
    }

    // -------------------------------------------------------
    // RECUPERO PASSWORD
    // -------------------------------------------------------

    public function emailEsiste($email)
    {
        require_once __DIR__ . "/database.php";
        $db = new Database();

        $emailEsc = $db->escape($email);
        $result = $db->query("SELECT * FROM users WHERE Email='$emailEsc'");

        return $result->num_rows == 1;
    }

    public function getNomeByEmail($email)
    {
        require_once __DIR__ . "/database.php";
        $db = new Database();

        $emailEsc = $db->escape($email);
        $result = $db->query("SELECT Nome FROM users WHERE Email='$emailEsc'");

        if ($result->num_rows == 1) {
            return $result->fetch_assoc()['Nome'];
        }
        return null;
    }

    public function saveRecoveryToken($email, $token)
    {
        require_once __DIR__ . "/database.php";
        $db = new Database();

        $emailEsc = $db->escape($email);
        $tokenEsc = $db->escape($token);
        $result = $db->query("UPDATE users SET tokenRecupero='$tokenEsc', dataInvioTokenRecupero=NOW() WHERE Email='$emailEsc'");

        return (bool) $result;
    }

    public function verifyRecoveryToken($token)
    {
        require_once __DIR__ . "/database.php";
        $db = new Database();

        $tokenEsc = $db->escape($token);
        // Il token è valido per 30 minuti
        $result = $db->query("SELECT * FROM users WHERE tokenRecupero='$tokenEsc' AND dataInvioTokenRecupero >= NOW() - INTERVAL 30 MINUTE");

        return $result->num_rows == 1;
    }

    public function updatePassword($token, $nuovaPassword)
    {
        require_once __DIR__ . "/database.php";
        $db = new Database();

        $tokenEsc = $db->escape($token);
        $hashedPass = md5($nuovaPassword);
        $result = $db->query("UPDATE users SET Password='$hashedPass', tokenRecupero=NULL, dataInvioTokenRecupero=NULL WHERE tokenRecupero='$tokenEsc'");

        return (bool) $result;
    }
}
