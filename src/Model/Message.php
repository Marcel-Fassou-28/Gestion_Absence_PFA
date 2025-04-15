<?php
namespace App\Model;

class Message {
    private $id;
    private $date;
    private $objet;
    private $contenu;
    private $idExpediteur;
    private $idDestinataire;
    private $typeDestinataire;
    private $lu;

    // Getters
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
    public function getIdExpediteur() { 
        return $this->idExpediteur; 
    }
    public function getIdDestinataire() { 
        return $this->idDestinataire; 
    }
    public function getTypeDestinataire() { 
        return $this->typeDestinataire; 
    }
    public function getLu() { 
        return $this->lu; 
    }
}