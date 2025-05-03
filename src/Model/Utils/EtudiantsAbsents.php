<?php

namespace App\Model\Utils;

/**
 * Cette classe est définie pour caractériser un étudiant avec ses absences
 * 
 * @property string $nom
 * @property string $prenom
 * @property string $cne
 * @property string $nomClasse
 * @property string $nomMatiere
 * @property int $nombreAbsences
 * @property array $absences
 */
class EtudiantsAbsents {

    /**
     * @var string $nom
     */
    private $nom;

    /**
     * @var string $prenom
     */
    private $prenom;

    /**
     * @var string $cne
     */
    private $cne;

    /**
     * @var string $nomClasse
     */
    private $nomClasse;

    /**
     * @var string $nomMatiere
     */
    private $nomMatiere;

    /**
     * @var int|null $nombreAbsences
     */
    private $nombreAbsences = null;

    /**
     * @var array $absences
     */
    private $absences;

    /**
     * Constructeur
     * 
     * @param string $nom
     * @param string $prenom
     * @param string $cne
     * @param string $nomClasse
     * @param string $nomMatiere
     * @param int|null $nombreAbsences
     * @param array $absences
     */
    public function __construct(
        string $nom,
        string $prenom,
        string $cne,
        string $nomClasse,
        string $nomMatiere,
        int $nombreAbsences = 0,
        array $absences = []
    ) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->cne = $cne;
        $this->nomClasse = $nomClasse;
        $this->nomMatiere = $nomMatiere;
        $this->nombreAbsences = $nombreAbsences;
        $this->absences = $absences;
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getNom(): ?string {
        return $this->nom;
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getPrenom(): ?string {
        return $this->prenom;
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getCne(): ?string {
        return $this->cne;
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getNomClasse(): ?string {
        return $this->nomClasse;
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getNomMatiere(): ?string {
        return $this->nomMatiere;
    }

    /**
     * Getters
     * 
     * @return int|null
     */
    public function getNombreAbsences() {
        return count($this->absences);
    }

    /**
     * Getters
     * 
     * @return array
     */
    public function getAbsences(): array {
        return $this->absences;
    }

    /**
     * Setters
     * 
     * @param string $nom
     */
    public function setNom(string $nom): void {
        $this->nom = $nom;
    }

    /**
     * Setters
     * 
     * @param string $prenom
     */
    public function setPrenom(string $prenom): void {
        $this->prenom = $prenom;
    }

    /**
     * Setters
     * 
     * @param string $cne
     */
    public function setCne(string $cne): void {
        $this->cne = $cne;
    }

    /**
     * Setters
     * 
     * @param string $nomClasse
     */
    public function setNomClasse(string $nomClasse): void {
        $this->nomClasse = $nomClasse;
    }

    /**
     * Setters
     * 
     * @param string $nomMatiere
     */
    public function setNomMatiere(string $nomMatiere): void {
        $this->nomMatiere = $nomMatiere;
    }

    /**
     * Setters
     * 
     * @param int $nombreAbsences
     */
    public function setNombreAbsences(int $nombreAbsences): void {
        $this->nombreAbsences = $nombreAbsences;
    }

    /**
     * Setters
     * 
     * @param array $absences
     */
    public function setAbsences(array $absences): void {
        $this->absences = $absences;
    }

    /**
     * Ajoute une absence à la liste (évite les doublons)
     * 
     * @param string $absence
     */
    /*public function addAbsence(string $absence): void {
        if (!in_array($absence, $this->absences)) {
            $this->absences[] = $absence;
        }
    }*/
    public function addAbsence($absence) {
        $this->absences[] = $absence;
    }

    /**
     * Trie les absences par ordre chronologique
     */
    public function sortAbsences(): void {
        sort($this->absences);
    }
}