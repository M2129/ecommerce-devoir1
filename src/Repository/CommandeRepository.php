<?php

declare(strict_types=1);

namespace App\Repository;

use App\Config\Database;
use App\Entity\Commande;
use PDO;

/**
 * CommandeRepository
 *
 * Classe dédiée à 100% à la communication avec PostgreSQL pour tout
 * ce qui concerne la table "commande". Ni le Service ni le Controller
 * n'écrivent de SQL : ils passent tous par ce Repository.
 */
class CommandeRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Enregistre une nouvelle Commande en base et renvoie l'entité
     * complétée avec son id généré par PostgreSQL.
     */
    public function save(Commande $commande): Commande
    {
        $sql = 'INSERT INTO commande (prix_final, reduction_appliquee, date_creation)
                VALUES (:prix_final, :reduction_appliquee, :date_creation)
                RETURNING id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'prix_final' => $commande->getPrixFinal(),
            'reduction_appliquee' => $commande->isReductionAppliquee(),
            'date_creation' => $commande->getDateCreation()->format('Y-m-d H:i:s'),
        ]);

        $id = (int) $stmt->fetchColumn();
        $commande->setId($id);

        return $commande;
    }

    /**
     * Récupère une Commande par son id, ou null si elle n'existe pas.
     */
    public function findById(int $id): ?Commande
    {
        $sql = 'SELECT id, prix_final, reduction_appliquee, date_creation
                FROM commande
                WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if ($row === false) {
            return null;
        }

        return $this->hydrate($row);
    }

    /**
     * Retourne toutes les commandes enregistrées.
     *
     * @return Commande[]
     */
    public function findAll(): array
    {
        $sql = 'SELECT id, prix_final, reduction_appliquee, date_creation
                FROM commande
                ORDER BY date_creation DESC';

        $stmt = $this->pdo->query($sql);

        return array_map(
            fn (array $row): Commande => $this->hydrate($row),
            $stmt->fetchAll()
        );
    }

    /**
     * Transforme une ligne SQL brute en objet Commande (hydratation).
     */
    private function hydrate(array $row): Commande
    {
        return new Commande(
            prixFinal: (float) $row['prix_final'],
            reductionAppliquee: (bool) $row['reduction_appliquee'],
            id: (int) $row['id'],
            dateCreation: new \DateTimeImmutable($row['date_creation'])
        );
    }
}
