<?php

namespace App\Interface;

interface User {

    /**
     * Permet une connexion sur l'application web
     * 
     * @param string $username
     * @param string $password
     */
    public function seConnecter(string $username, string $password);
}