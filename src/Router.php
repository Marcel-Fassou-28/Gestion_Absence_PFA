<?php

namespace App;
use AltoRouter;

class Router {

    /**
     * @var string $viewPath Chemin absolue vers lequel on voudra se diriger
     */
    private $viewPath;

    /**
     * @var object $router Contient une  instance de la classe Altorouter
     */
    private $router;

    public function __construct(string $viewPath)
    {
        $this->viewPath = $viewPath;
        $this->router = new AltoRouter();
    }

    /**
     * Pour maper les chemins en GET
     * @param string $url Chemin qu'on voudra accéder en get
     * @param string $view Chemin vers le fichier correspondant à l'url
     * @param string $name Nom donné à l'url
     * @return object $this Il retourne l'objet lui meme
     */
    public function get(string $url, string $view, ?string $name = null):self 
    {
        $this->router->map('GET', $url, $view, $name);
        return $this;
    }
    
    /**
     * Pour maper les chemins en POST
     * @param string $url Chemin qu'on voudra accéder en post
     * @param string $view Chemin vers le fichier correspondant à l'url
     * @param string $name  Nom donné à l'url
     * @return object $this Il retourne l'objet lui meme
     */
    public function post(string $url, string $view, ?string $name = null):self 
    {
        $this->router->map('POST', $url, $view, $name);
        return $this;
    }

    /**
     * Pour Maper les chemins en POST ou GET
     * @param string $url Chemin qu'on voudra accéder en post ou en get
     * @param string $view Chemin vers le fichier correspondant à l'url
     * @param string $name  Nom donné à l'url
     * @return object Il retourne l'objet lui meme
     */
    public function match(string $url, string $view, ?string $name = null):self 
    {
        $this->router->map('POST|GET', $url, $view, $name);

        return $this;
    }


    /**
     * Cette fonction permettra de matcher toutes les requetes d'url
     * @var string $view Recupère la cible, (la page demander par l'utilisateur)
     * @var string $params S'occupe des arguments de la page
     * @var object Pour rendre accessible le router depuis n'importe quel fichier
     * 
     * La fonction ob_start() active la mise en tampon de sortie (output buffering). 
     * Cela signifie que, au lieu d'envoyer directement la sortie (comme du HTML ou du texte) au navigateur,
     *  elle est stockée dans un tampon.
     */

    public function run():self
    {
        $match = $this->router->match();
        $view = $match['target'] ?? 'errors/erreur';
        $params = $match['params'] ?? '';
        $router = $this;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $urlUser = [];
        if (isset($_SESSION['id_user'])) {
            
            switch ($_SESSION['role']) {
                case 'admin':
                    $urlUser = [
                        'dashboard' => $router->url('administrator-dashboard'),
                        'home' => $router->url('user-home', ['role'=> $_SESSION['role']]),
                        'profil' => $router->url('user-profil', ['role'=> $_SESSION['role']]),
                        'listeProfesseurs' => $router->url('liste_Des_Professeur'),
                        'justifications' => $router->url('justification'),
                        'modification' =>$router->url('modifier_professeur'),
                        'ajouter' => $router->url('ajouterProf')
                    ];
                    break;
                case 'professeur':
                    $urlUser = [
                        'dashboard' => $router->url('user-dashboard',  ['role'=> $_SESSION['role']]),
                        'home' => $router->url('user-home', ['role'=> $_SESSION['role']]),
                        'profil' => $router->url('user-profil', ['role'=> $_SESSION['role']]),
                        'listeProfesseurs' => $router->url('liste_Des_Professeur')
                    ];
                    break;
                case 'etudiant':
                    $urlUser = [
                        'dashboard' => $this->url('user-dashboard',  ['role'=> $_SESSION['role']]),
                        'home' => $this->url('user-home', ['role'=> $_SESSION['role']]),
                        'profil' => $router->url('user-profil', ['role'=> $_SESSION['role']]),
                        'listeProfesseurs' => $router->url('liste_Des_Professeur')
                    ];
                    break;
            }
        }
        ob_start();
        require $this->viewPath . DIRECTORY_SEPARATOR. $view . '.php';
        $layout = isset($_SESSION['id_user']) ? 'defaultAuthenticated' : 'defaultUnauthenticated';
        $content = ob_get_clean();
        require $this->viewPath . DIRECTORY_SEPARATOR . 'layout/'. $layout.'.php';
        return $this;
    }
 
    /**
     * Genere une url qui en paramètre le nom qui lui est attribué
     * @param string $name Nom de l'url donné à la methode get, post, match
     * @param array $param Les paramètres de l'url, si elle dynamique
     */
    public function url(string $name, array $param = []) {
        return $this->router->generate($name, $param);
    }
}