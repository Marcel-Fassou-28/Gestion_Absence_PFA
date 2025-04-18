<?php
namespace App\Model;

class Message {
    private $id;
    private $date;
    private $objet;
    private $contenu;
    private $cinExpediteur;
    private $cinDestinataire;
    private $typeDestinataire;
    private $lu;
    /*
    // Constructeur pour initialiser un message
    public function __construct($id, $date, $objet, $contenu, $cinExpediteur, $cinDestinataire, $typeDestinataire, $lu) {
        $this->id = $id;
        $this->date = $date;
        $this->objet = $objet;
        $this->contenu = $contenu;
        $this->cinExpediteur = $cinExpediteur;
        $this->cinDestinataire = $cinDestinataire;
        $this->typeDestinataire = $typeDestinataire;
        $this->lu = $lu;
    }*/

    // Getter methods
    public function getId() { 
        return $this->id; 
    }

    public function getDate() { 
        return $this->date; 
    }

    public function getObjet() { 
        return $this->objet; 
    }

    public function getContenu() { 
        return $this->contenu; 
    }

    public function getCinExpediteur() { 
        return $this->cinExpediteur; 
    }

    public function getCinDestinataire() { 
        return $this->cinDestinataire; 
    }

    public function getTypeDestinataire() { 
        return $this->typeDestinataire; 
    }

    public function getLu() { 
        return $this->lu; 
    }

    // Les setters pour initialiser le msg
    public function setId($id) { 
        $this->id=$id; 
    }

    public function setDate($date) { 
        $this->date=$date; 
    }

    public function setObjet($objet) { 
        $this->objet=$objet; 
    }

    public function setContenu($contenu) { 
        $this->contenu=$contenu; 
    }

    public function setCinExpediteur($cinExpediteur) { 
        $this->cinExpediteur=$cinExpediteur; 
    }

    public function setCinDestinataire($cinDestinataire) { 
        $this->cinDestinataire=$cinDestinataire; 
    }

    public function setTypeDestinataire($typeDestinataire) { 
        $this->typeDestinataire=$typeDestinataire; 
    }

    public function setLu($lu) { 
        $this->lu=$lu; 
    }

    
}
