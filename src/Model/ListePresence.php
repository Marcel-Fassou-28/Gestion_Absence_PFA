<?php 

namespace App\Model;

/**
 * Cette classe est un modèle qui caractérise une Liste de Présence
 * 
 * @property int $id
 * @property string $date
 * @property string $niveau
 * @property int $idFiliere
 * @property $imageJustificatif
 */
class ListePresence {
    /**
     * @var int $id
     */
    protected $id;
    
    /**
     * @var string $date
     */
    protected $date;
    
    /**
     * @var string $niveau
     */
    protected $niveau;
    
    /**
     * @var int $idFiliere
     */
    protected $idFiliere;
    
    /**
     * @var $imageJustificatif
     */
    protected $imageJustificatif;

    /*public function __construct(int $id, string $date, string $niveau, int $idFiliere, string $imageJustificatif) {
        $this->id = $id;
        $this->date = $date;
        $this->niveau = $niveau;
        $this->idFiliere = $idFiliere;
        $this->imageJustificatif = $imageJustificatif;
    }*/

    /**
     * Getters
     * 
     * @return int|null
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * Setters
     * 
     * @param int $id
     */
    public function setId(int $id) {
        $this->id = $id;
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getDate(): ?string {
        return $this->date;
    }

    /**
     * Setters
     * 
     * @param string $date
     */
    public function setDate(string $date) {
        $this->date = $date;
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getNiveau(): ?string {
        return $this->niveau;
    }

    /**
     * Setters
     * 
     * @param string $niveau
     */
    public function setNiveau(string $niveau) {
        $this->niveau = $niveau;
    }

    /**
     * Getters
     * 
     * @return int|null
     */
    public function getIdFiliere(): ?int {
        return $this->idFiliere;
    }

    /**
     * Setters
     * 
     * @param int $idFiliere
     */
    public function setIdFiliere(int $idFiliere) {
        $this->idFiliere = $idFiliere;
    }

    /**
     * Getters
     * 
     * @return $photo
     */
    public function getImageJustificatif() {
        return $this->imageJustificatif;
    }

    /**
     * Setters
     * 
     * @param string $imageJustificatif
     */
    public function setImageJustificatif(string $imageJustificatif) {
        $this->imageJustificatif = $imageJustificatif;
    }
}
