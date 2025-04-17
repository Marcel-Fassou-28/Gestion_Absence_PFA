<?php

namespace App\Model;

/**
 * Cette classe est un modèle qui caractérise un Justificatif
 * 
 * @property int $idJustificatif
 * @property string $dateSoumission
 * @property string $statut
 * @property string $message
 * @property int $idAbsence
 * @property string $nomFichierJustificatif
 */
class Justificatif {

    /**
     * @var int $idJustificatif
     */
    protected $idJustificatif;

    /**
     * @var string $dateSoumission
     */
    protected $dateSoumission;

    /**
     * @var string $statut
     */
    protected $statut;

    /**
     * @var string $message
     */
    protected $message;

    /**
     * @var int $idAbsence
     */
    protected $idAbsence;

    /**
     * @var string $nomFichierJustificatif
     */
    protected $nomFichierJustificatif;

    /*public function __construct(int $idJustificatif, string $dateSoumission, string $statut, string $message, int $idAbsence) {
        $this->idJustificatif = $idJustificatif;
        $this->dateSoumission = $dateSoumission;
        $this->statut = $statut;
        $this->message = $message;
        $this->idAbsence = $idAbsence;
    }*/

    /**
     * Getters
     * 
     * @return int|null
     */
    public function getIdJustificatif(): ?int {
        return $this->idJustificatif;
    }

    /**
     * Setters
     * 
     * @param int $idJustificatif
     */
    public function setIdJustificatif(int $idJustificatif) {
        $this->idJustificatif = $idJustificatif;
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getDateSoumission(): ?string {
        return $this->dateSoumission;
    }

    /**
     * Setters
     * 
     * @param string $dateSoumission
     */
    public function setDateSoumission(string $dateSoumission) {
        $this->dateSoumission = $dateSoumission;
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getStatut(): ?string {
        return $this->statut;
    }

    /**
     * Setters
     * 
     * @param string $statut
     */
    public function setStatut(string $statut) {
        $this->statut = $statut;
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getMessage(): ?string {
        return $this->message;
    }

    /**
     * Setters
     * 
     * @param string $message
     */
    public function setMessage(string $message) {
        $this->message = $message;
    }

    /**
     * Getters
     * 
     * @return int|null
     */
    public function getIdAbsence(): ?int {
        return $this->idAbsence;
    }

    /**
     * Setters
     * 
     * @param int $idAbsence
     */
    public function setIdAbsence(int $idAbsence) {
        $this->idAbsence = $idAbsence;
    }

    /**
     * Getters
     * 
     * @return string|null
     */
    public function getNomFichierJustificatif():?string {
      return $this->nomFichierJustificatif;
    }

    /**
     * Setters
     * 
     * @param string $value
     */
    public function setNomFichierJustificatif(string $value) {
      $this->nomFichierJustificatif = $value;
    }
}