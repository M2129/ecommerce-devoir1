<?php

namespace App\Entity;

use App\Core\AbstractEntity;

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
