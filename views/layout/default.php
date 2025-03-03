<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jacques+Francois&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/login_page.css">
    <link rel="stylesheet" href="/css/general_style.css">
    <title>Gestion d'Absence</title>
</head>
<body>
    <nav class="row nav-bar">
        <div class="logo col-3">
            <span>GAENSAJ</span>
        </div>
        <hr>
        <ul class="wrapped col-3">
            <li><a href=<?= $router->url('accueil') ?> target="_self">Accueil</a></li>
            <li><a href=<?= $router->url('login_page') ?> target="_blank">Connexion</a></li>
        </ul>
    </nav>
    <div class="toggle">&times;</div>
    <div class="toggle-open">&#9776;</div>
    <?= $content ?>
    <script src="/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>