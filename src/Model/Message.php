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

    // Constructeur pour initialiser un message
    public function __construct($id, $date, $objet, $contenu, $idExpediteur, $idDestinataire, $typeDestinataire, $lu) {
        $this->id = $id;
        $this->date = $date;
        $this->objet = $objet;
        $this->contenu = $contenu;
        $this->idExpediteur = $idExpediteur;
        $this->idDestinataire = $idDestinataire;
        $this->typeDestinataire = $typeDestinataire;
        $this->lu = $lu;
    }

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

    // Méthode pour initialiser un message à partir d'un tableau de données
    public static function fromArray($data) {
        return new self(
            $data['id'], 
            $data['date_envoi'], 
            $data['objet'], 
            $data['contenu'], 
            $data['id_expediteur'], 
            $data['id_destinataire'], 
            $data['type_destinataire'], 
            $data['lu']
        );
    }
}
