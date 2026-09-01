<?php

declare(strict_types=1);

namespace App\DTO;

/**
 * CommandeDTO
 *
 * "Objet de transport aveugle" : il ne fait AUCUN calcul et ne connaît
 * aucune règle métier. Son seul rôle est de porter, de façon typée et
 * sécurisée, les données brutes envoyées par le client (le panier),
 * du Controller jusqu'au Service.
 *
 * On ne fait jamais confiance à $_POST directement : on le transforme
 * en CommandeDTO dès que possible (voir CommandeController, Étape 4).
 */
final class CommandeDTO
{
    public function __construct(
        public readonly float $prixInitial,
        public readonly string $codePromo = ''
    ) {
    }

    /**
     * Fabrique un DTO à partir d'un tableau brut (ex: $_POST).
     * C'est ici, et seulement ici, qu'on filtre/caste les données brutes.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            prixInitial: (float) ($data['prix_initial'] ?? 0),
            codePromo: trim((string) ($data['code_promo'] ?? ''))
        );
    }
}
