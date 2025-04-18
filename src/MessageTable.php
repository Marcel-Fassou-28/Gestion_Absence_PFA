<?php

namespace App;

use PDO;
use App\Model\Message;

class MessageTable
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Envoie un message :
     *  - si l'expéditeur est étudiant, on envoie à tous les admins
     *  - sinon (admin), on envoie au destinataire spécifié
     */
    public function envoyerMessage(
        int $idExpediteur,
        int $idDestinataire,
        string $typeDestinataire,
        string $objet,
        string $contenu,
        ?int $idMessageParent = null
    ): void {
        if (empty($objet) || empty($contenu)) {
            throw new \Exception("L'objet et le contenu ne peuvent pas être vides.");
        }

        $userTable = new UserTable($this->pdo);
        $expediteur = $userTable->findById($idExpediteur);
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
                $this->insertMessage($idExpediteur, $admin->getId(), 'admin', $objet, $contenu, $idMessageParent);
            }
        }
        // Admin → un étudiant
        else {
            if (!$userTable->findById($idDestinataire)) {
                throw new \Exception("Le destinataire n'existe pas.");
            }
            if (!in_array($typeDestinataire, ['admin', 'etudiant'])) {
                throw new \Exception("Type de destinataire invalide.");
            }
            $this->insertMessage($idExpediteur, $idDestinataire, $typeDestinataire, $objet, $contenu, $idMessageParent);
        }
    }

    /** 
     * Insert avec création de statut de lecture 
     */
    private function insertMessage(
        int $idExpediteur,
        int $idDestinataire,
        string $typeDestinataire,
        string $objet,
        string $contenu,
        ?int $idMessageParent
    ): void {
        // 1) Insertion du message
        $stmt = $this->pdo->prepare(
            "INSERT INTO message 
             (date, objet, contenu, idExpediteur, idDestinataire, typeDestinataire, idMessageParent)
             VALUES (NOW(), :objet, :contenu, :exp, :dest, :type, :parent)"
        );
        $stmt->execute([
            'objet'   => $objet,
            'contenu' => $contenu,
            'exp'     => $idExpediteur,
            'dest'    => $idDestinataire,
            'type'    => $typeDestinataire,
            'parent'  => $idMessageParent
        ]);

        $idMessage = (int)$this->pdo->lastInsertId();

        // 2) Création du statut de lecture
        $statusStmt = $this->pdo->prepare(
            "INSERT INTO MessageStatus (idMessage, idUtilisateur, lu, ouvertParAutreAdmin)
             VALUES (:idMsg, :user, 'non', 'non')"
        );
        $statusStmt->execute([
            'idMsg' => $idMessage,
            'user'  => $idDestinataire
        ]);
    }

    /**
     * Récupère tous les messages (reçus ou envoyés) pour un utilisateur
     * et renvoie un tableau d’objets Message
     */
    public function getMessages(int $idUtilisateur, string $role): array
    {
        $query = "
            SELECT m.*,
                   exp.nom   AS expNom,   exp.prenom   AS expPrenom,
                   dest.nom  AS destNom,  dest.prenom  AS destPrenom,
                   ms.lu     AS messageLu,
                   ms.ouvertParAutreAdmin AS ouvertParAutre
            FROM message m
            LEFT JOIN utilisateur exp  ON m.idExpediteur   = exp.id
            LEFT JOIN utilisateur dest ON m.idDestinataire = dest.id
            LEFT JOIN MessageStatus ms 
              ON m.id = ms.idMessage AND ms.idUtilisateur = :idUser
            WHERE (m.idDestinataire = :idUser AND m.typeDestinataire = :role)
               OR (m.idExpediteur   = :idUser)
            ORDER BY m.date DESC
        ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            'idUser' => $idUtilisateur,
            'role'   => $role
        ]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, Message::class);
        return $stmt->fetchAll() ?: [];
    }

    /**
     * Marque un message comme lu pour un utilisateur donné
     */
    public function marquerCommeLu(int $idMessage, int $idUtilisateur): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE MessageStatus 
             SET lu = 'oui' 
             WHERE idMessage = :idMsg 
               AND idUtilisateur = :idUser"
        );
        $stmt->execute([
            'idMsg'  => $idMessage,
            'idUser' => $idUtilisateur
        ]);
    }

    /**
     * Renvoie le nom complet (nom + prénom) d’un utilisateur
     */
    public function getUserNameById(int $id): string
    {
        $stmt = $this->pdo->prepare(
            "SELECT CONCAT(nom, ' ', prenom) AS fullname 
             FROM utilisateur 
             WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['fullname'] : 'Inconnu';
    }
}
