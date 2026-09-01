<?php

declare(strict_types=1);

namespace App\Core;

/**
 * AbstractEntity
 *
 * Classe mère de toutes les entités du projet (Commande, Produit, Utilisateur...).
 * Elle mutualise les champs techniques communs à toutes les tables :
 * l'identifiant et la date de création.
 *
 * Etant "abstract", elle ne peut jamais être instanciée directement
 * (on ne crée pas de "AbstractEntity", on crée une "Commande").
 */
abstract class AbstractEntity
{
    protected ?int $id;
    protected \DateTimeImmutable $dateCreation;

    public function __construct(?int $id = null, ?\DateTimeImmutable $dateCreation = null)
    {
        $this->id = $id;
        // Si aucune date n'est fournie (nouvelle entité pas encore en base),
        // on prend l'instant présent.
        $this->dateCreation = $dateCreation ?? new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getDateCreation(): \DateTimeImmutable
    {
        return $this->dateCreation;
    }
}
