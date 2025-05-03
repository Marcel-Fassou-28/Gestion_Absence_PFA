<?php 

namespace App\Model;

/**
 * Cette classe est un modèle qui caractérise une Liste de Présence
 * 
 * @property int $id
 * @property string $cinProf
 * @property string $date
 * @property string $classe
 * @property string $matiere
 * @property string $nomFichierPresence
 */
class ListePresence {
    /**
     * @var int $id
     */
    private $id;
    
    /**
     * @var string $date
     */
    private $date;
    
    /**
     * @var string $classe
     */
    private $classe;

    /**
     * @var string $matiere
     */
    private $matiere;
    
    /**
     * @var string $cinProf
     */
    private $cinProf;

    /**
     * @var string $nomPrenom
     */
    private $nomPrenom;
    
    /**
     * @var string $nomFichierPresence
     */
    private $nomFichierPresence;

    /*public function __construct(int $id, string $date, string $classe, string $cinProf, $nomFichierPresence, $nomPrenom) {
        $this->id = $id;
        $this->date = $date;
        $this->classe = $classe;
        $this->cinProf = $cinProf;
        $this->nomFichierPresence = $nomFichierPresence;
        $this->nomPrenom = $nomPrenom;
    }*/

    /**
     * Getters
     * 
     * @return string $nomPrenom
     */
    public function getNomPrenom():?string {
        return $this->nomPrenom;
    }

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
    public function setClasse(string $classe) {
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
     * @return string|null $nomFichierPresence
     */
    public function getNomFichierPresence():?string {
        return $this->nomFichierPresence;
    }

    /**
     * Setters
     * 
     * @param string $nomFichierPresence
     */
    public function setNomFichierPresence(string $nomFichierPresence) {
        $this->nomFichierPresence = $nomFichierPresence;
    }

    public function getMatiere() {
      return $this->matiere;
    }
    public function setMatiere($value) {
      $this->matiere = $value;
    }
}
