<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Arial', sans-serif;
}

html, body {
    height: 100%;
    width: 100%;
    overflow: hidden;
}

/* Arrière-plan flouté et centré */
body {
    background: url('/images/background.jpg') no-repeat center center fixed;
    background-size: cover;
    backdrop-filter: blur(8px); /* Flou ajusté pour un équilibre entre visibilité et effet */
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    color: #ffffff;
    position: relative;
}

/* Overlay pour améliorer la lisibilité */
body::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: #1c1d1e33;
    z-index: 1;
}

/* Conteneur principal */
.container {
    position: relative;
    z-index: 2;
    text-align: center;
    padding: 40px 30px;
    max-width: 600px;
    width: 90%;
    background: rgba(54, 55, 83, 0.95);
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    animation: fadeIn 1s ease-in-out;
}

/* Animation d'apparition */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Style du titre 404 */
h1 {
    font-size: 120px;
    line-height: 1;
    color: #8bb0f0;
    margin-bottom: 15px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

/* Animation du texte */
.animate-text {
    display: inline-block;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

/* Style du sous-titre */
h2 {
    font-size: 28px;
    color: #ffffff;
    margin-bottom: 15px;
}

/* Style du texte */
p {
    font-size: 16px;
    color: #ffffff;
    margin-bottom: 20px;
    opacity: 0.9;
}

/* Barre de recherche */
.search-bar {
    display: flex;
    justify-content: center;
    margin: 25px 0;
}

.search-bar input {
    padding: 12px;
    width: 250px;
    max-width: 100%;
    border: 1px solid #8bb0f0;
    background: #1c1d1e;
    color: #ffffff;
    border-radius: 5px 0 0 5px;
    font-size: 14px;
    outline: none;
}

.search-bar input::placeholder {
    color: #8bb0f0;
    opacity: 0.7;
}

.search-bar button {
    padding: 12px 15px;
    background-color: #8bb0f0;
    border: none;
    border-radius: 0 5px 5px 0;
    color: #1c1d1e;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s;
}

.search-bar button:hover {
    background-color: #ffffff;
    color: #1c1d1e;
}

/* Style du bouton */
.btn {
    display: inline-block;
    padding: 12px 30px;
    background-color: #8bb0f0;
    color: #1c1d1e;
    text-decoration: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: 500;
    transition: background-color 0.3s ease, transform 0.2s;
}

.btn:hover {
    background-color: #ffffff;
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    h1 {
        font-size: 90px;
    }

    h2 {
        font-size: 24px;
    }

    p {
        font-size: 14px;
    }

    .search-bar input {
        width: 200px;
        padding: 10px;
    }

    .search-bar button {
        padding: 10px 12px;
    }

    .btn {
        padding: 10px 25px;
        font-size: 14px;
    }

    .container {
        padding: 30px 20px;
    }
}

@media (max-width: 480px) {
    h1 {
        font-size: 70px;
    }

    h2 {
        font-size: 20px;
    }

    p {
        font-size: 13px;
    }

    .search-bar input {
        width: 160px;
        padding: 8px;
    }

    .search-bar button {
        padding: 8px 10px;
    }

    .btn {
        padding: 8px 20px;
        font-size: 13px;
    }

    .container {
        padding: 25px 15px;
    }
}
</style>


<div class="container">
    <h1 class="animate-text">404</h1>
    <h2>Oups ! Page introuvable</h2>
    <p>Désolé, la page que vous cherchez n'existe pas ou a été déplacée.</p>
    <p>Vous serez redirigé vers l'accueil dans <span id="countdown">5</span> secondes.</p>
    <div class="search-bar">
        <input type="text" placeholder="Rechercher sur le site..." id="search-input">
        <button onclick="searchSite()"><i class="fas fa-search"></i></button>
    </div>
    <a href="/" class="btn">Retour à l'accueil</a>
</div>


<script>
    
    let countdown = 10;
    const countdownElement = document.getElementById('countdown');

    const timer = setInterval(() => {
        countdown--;
        countdownElement.textContent = countdown;
        if (countdown <= 0) {
            clearInterval(timer);
            window.location.href = '/'; // Redirige vers la page d'accueil
        }
    }, 1000);

    function searchSite() {
        const query = document.getElementById('search-input').value;
        if (query) {
            // Remplacez ceci par l'URL de recherche de votre site
            window.location.href = `/search?q=${encodeURIComponent(query)}`;
        }
    }
</script>
<?php
exit();
?>
