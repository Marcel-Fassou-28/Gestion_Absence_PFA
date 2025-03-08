<?php
$dsn = "mysql:host=localhost;dbname=gaensaj;charset=utf8mb4";
$username = "root";
$password = "";
$pdo = new PDO($dsn, $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
$password1 = password_hash('20242025', PASSWORD_BCRYPT);
$password2 = password_hash('2024@2025', PASSWORD_BCRYPT);
$pdo->exec("INSERT INTO etudiant (cin_etudiant,nom, prenom, password, cne, email, code_filiere) VALUES ('BJ8478559', 'Msaboue','Mohamed','$password1', 'BJ87478559', 'mohamedmsb6@gmail.com', 'IITE2425')");
$pdo->exec("INSERT INTO etudiant (cin_etudiant,nom, prenom, password, cne, email, code_filiere) VALUES ('CD8478559', 'Claude','Douglas','$password2', 'CD8478559', 'tyu487885@gmail.com', 'IITE2425')");
?>
