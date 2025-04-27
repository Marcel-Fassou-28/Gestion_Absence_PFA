<?php

namespace App;

use App\Abstract\Table;
use PDO;
use App\Model\Message;

class MessageTable extends Table{

    /**
     * Envoie un message :
     *  - si l'expéditeur est étudiant, on envoie à tous les admins
     *  - sinon (admin), on envoie au destinataire spécifié
     */
    public function envoyerMessage(
        string $cinExpediteur,
        string $cinDestinataire,
        string $typeDestinataire,
        string $objet,
        string $contenu
    ): void{
        if (empty($objet) || empty($contenu)) {
            throw new \Exception("L'objet et le contenu ne peuvent pas être vides.");
        }

        $userTable = new UserTable($this->pdo);
        $expediteur = $userTable->findByCin($cinExpediteur);
        if (!$expediteur) {
            throw new \Exception("L'expéditeur n'existe pas.");
        }

        // Étudiant → tous les admins
        if ($expediteur->getRole() === 'etudiant') {
            $admins = $userTable->getAllAdmins();
            if (empty($admins)) {
                throw new \Exception("Aucun administrateur trouvé.");
            }
            foreach ($admins as $admin) {
                $this->insertMessage($cinExpediteur, $admin->getCIN(), 'admin', $objet, $contenu);
            }
        }
        // Admin → un étudiant
        else {
            if (!$userTable->findByCin($cinDestinataire)) {
                throw new \Exception("Le destinataire n'existe pas.");
            }
            if (!in_array($typeDestinataire, ['admin', 'etudiant'])) {
                throw new \Exception("Type de destinataire invalide.");
            }
            $this->insertMessage($cinExpediteur, $cinDestinataire, $typeDestinataire, $objet, $contenu);
        }
    }

    /** 
     * Insert avec création de statut de lecture 
     */
    private function insertMessage(
        string $cinExpediteur,
        string $cinDestinataire,
        string $typeDestinataire,
        string $objet,
        string $contenu,
        ?int $idMessageParent=null
    ): void {
        // 1) Insertion du message
        $stmt = $this->pdo->prepare(
            "INSERT INTO message 
             (objet, contenu, cinExpediteur, cinDestinataire, typeDestinataire)
             VALUES (:objet, :contenu, :exp, :dest, :type)"
        );
        $stmt->execute([
            'objet'   => $objet,
            'contenu' => $contenu,
            'exp'     => $cinExpediteur,
            'dest'    => $cinDestinataire,
            'type'    => $typeDestinataire
        ]);

    }

    /**
     * Récupère tous les messages (reçus ou envoyés) pour un utilisateur
     * et renvoie un tableau d’objets Message
     */
    public function getMessages(string $cinUtilisateur, string $role): array
    {
        $query = "
                    SELECT m.*
        FROM Message m
        JOIN Utilisateur u ON (m.cinExpediteur = u.cin OR m.cinDestinataire = u.cin)
        WHERE u.cin =:cin AND u.role = :role
        ORDER BY m.date DESC

        ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            'cin' => $cinUtilisateur,
            'role'   => $role
        ]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, Message::class);
        return $stmt->fetchAll() ?: [];
    }

    /**
     * Marque un message comme lu pour un utilisateur donné
     */
    public function marquerCommeLu(int $idMessage, string $cinUtilisateur): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE Message
             SET lu = 'oui' 
             WHERE idMessage = :idMsg 
             AND cinDestinataire = :cinUser"
        );
        $stmt->execute([
            'idMsg'  => $idMessage,
            'cinUser' => $cinUtilisateur
        ]);
    }
    /**
 * Modifier un message
 */
    public function modifierMessage(int $idMessage, string $nouvelObjet, string $nouveauContenu): void
    {
    $stmt = $this->pdo->prepare("UPDATE message SET objet = :objet, contenu = :contenu WHERE idMessage = :id");
    $stmt->execute([
        'objet' => $nouvelObjet,
        'contenu' => $nouveauContenu,
        'id' => $idMessage
    ]);
}

/**
 * Supprimer un message
 */
    public function supprimerMessage(int $idMessage): void
    {
    $stmt = $this->pdo->prepare("DELETE FROM message WHERE idMessage = :id");
    $stmt->execute([
        'id' => $idMessage
    ]);
    }


}
