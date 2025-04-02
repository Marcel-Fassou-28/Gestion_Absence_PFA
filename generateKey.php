<?php

$secretKey = bin2hex(random_bytes(32));

file_put_contents('.env', "SECRET_KEY=" . $secretKey);

/*
Ce code génère une clé unique qu'on devra utiliser pour crypter les numero CIN qu'on devra utiliser 
comme ID de SESSION, cela est très important pour le control des liens provenants du site et aussi pour autre

NB: Cette clé générer, ce trouve dans le dossier .env, ce code doit etre executé une et une seule fois, car 
on utilise un nombre aléatoire de 32bites comme graine.
*/