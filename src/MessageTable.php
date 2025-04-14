<?php
namespace App;
use App\Abstract\Table;
use App\Model\Message;

class MessageTable extends Table {
    protected $table = "message";
    protected $class = Message::class;

    // Envoyer un message
    public function envoyerMessage(int $idExpediteur, int $idDestinataire, string $typeDestinataire, string $objet, string $contenu) {
        $query = $this->pdo->prepare('
            INSERT INTO message (idExpediteur, idDestinataire, typeDestinataire, objet, contenu)
            VALUES (:idExpediteur, :idDestinataire, :typeDestinataire, :objet, :contenu)
        ');
        $query->execute([
            'idExpediteur' => $idExpediteur,
            'idDestinataire' => $idDestinataire,
            'typeDestinataire' => $typeDestinataire,
            'objet' => $objet,
            'contenu' => $contenu
        ]);
    }

    // Récupérer les messages d'un utilisateur
    public function getMessages(int $idUtilisateur, string $role): array {
        $query = $this->pdo->prepare('
            SELECT * FROM message 
            WHERE (idDestinataire = :id AND typeDestinataire = :role) 
               OR (idExpediteur = :id)
            ORDER BY date DESC
        ');
        $query->execute(['id' => $idUtilisateur, 'role' => $role]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->class);
        $result = $query->fetchAll();
        return $result ?: [];
    }

    // Marquer un message comme lu
    public function marquerCommeLu(int $idMessage) {
        $query = $this->pdo->prepare('
            UPDATE message SET lu = "oui" WHERE id = :id
        ');
        $query->execute(['id' => $idMessage]);
    }
}