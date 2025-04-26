<?php

namespace App;

use App\Abstract\Table;
use App\Model\Classe;

class ClasseTable extends Table
{
    protected $table = 'classe';
    protected $class = Classe::class;

    /**
     * Récupère une classe par son ID
     *
     * @param int $id
     * @return Classe|null
     */
    public function findById(int $id): ?Classe
    {
        $query = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE idClasse = :id");
        $query->execute(['id' => $id]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->class);
        $classe = $query->fetch();

        return $classe ?: null;
    }
}
