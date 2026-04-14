<?php
class User
{
    private $nome;
    private $cognome;
    private $email;

    public function __construct($nome, $cognome, $email)
    {
        $this->nome = $nome;
        $this->cognome = $cognome;
        $this->email = $email;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getCognome()
    {
        return $this->cognome;
    }

    public function getEmail()
    {
        return $this->email;
    }
}
?>