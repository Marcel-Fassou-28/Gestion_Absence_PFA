<?php 

namespace App\Model;

/**
 * Cette classe est un modèle qui caractérise une Liste de Présence
 * 
 * @property int $id
 * @property string $cinProf
 * @property string $date
 * @property string $classe
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
     * @var string $classe
     */
    protected $classe;
    
    /**
     * @var string $cinProf
     */
    protected $cinProf;
    
    /**
     * @var $imageJustificatif
     */
    protected $imageJustificatif;

    /*public function __construct(int $id, string $date, string $classe, string $cinProf, $imageJustificatif) {
        $this->id = $id;
        $this->date = $date;
        $this->classe = $classe;
        $this->cinProf = $cinProf;
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
    public function getClasse(): ?string {
        return $this->classe;
    }

    /**
     * Setters
     * 
     * @param string $classe
     */
    public function setNiveau(string $classe) {
        $this->classe = $classe;
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getCINProf(): ?string {
        return $this->cinProf;
    }

    /**
     * Setters
     * 
     * @param string $cinProf
     */
    public function setCINProf(string $cinProf) {
        $this->cinProf = $cinProf;
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
