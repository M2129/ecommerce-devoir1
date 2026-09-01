<?php

declare(strict_types=1);

namespace App\Entity;

use App\Core\AbstractEntity;

/**
 * Commande
 *
 * Entité "métier" : elle ne se concentre QUE sur ce qui est propre
 * à une commande (prix, réduction). Elle ne gère ni id ni date_creation,
 * ces champs techniques sont hérités de AbstractEntity.
 */
class Commande extends AbstractEntity
{
    private float $prixFinal;
    private bool $reductionAppliquee;

    public function __construct(
        float $prixFinal,
        bool $reductionAppliquee,
        ?int $id = null,
        ?\DateTimeImmutable $dateCreation = null
    ) {
        // On délègue la gestion de id/dateCreation au parent (héritage).
        parent::__construct($id, $dateCreation);

        $this->prixFinal = $prixFinal;
        $this->reductionAppliquee = $reductionAppliquee;
    }

    public function getPrixFinal(): float
    {
        return $this->prixFinal;
    }

    public function setPrixFinal(float $prixFinal): void
    {
        $this->prixFinal = $prixFinal;
    }

    public function isReductionAppliquee(): bool
    {
        return $this->reductionAppliquee;
    }

    public function setReductionAppliquee(bool $reductionAppliquee): void
    {
        $this->reductionAppliquee = $reductionAppliquee;
    }
}
