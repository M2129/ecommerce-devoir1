<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\CommandeDTO;
use App\Entity\Commande;
use App\Repository\CommandeRepository;

/**
 * CommandeService
 *
 * Le "cerveau" de la fonctionnalité. C'est ici, et nulle part ailleurs,
 * que vit la logique marketing/métier : vérifier le code promo et
 * appliquer -10% si besoin.
 *
 * Le Service est totalement indépendant de l'affichage web : il ne
 * connaît ni $_POST, ni HTML. Il reçoit un DTO propre, fait ses calculs,
 * instancie l'Entité, et délègue le stockage au Repository.
 */
class CommandeService
{
    // Code promo valide. En vrai projet, ça viendrait d'une table
    // "code_promo" en base, mais on le fixe ici pour rester simple.
    private const CODE_PROMO_VALIDE = 'PROMO10';
    private const TAUX_REDUCTION = 0.10;

    public function __construct(
        private readonly CommandeRepository $commandeRepository
    ) {
    }

    /**
     * Traite une nouvelle commande : applique la réduction si le code
     * promo est correct, crée l'entité Commande, la sauvegarde et la
     * renvoie (avec son id généré par la base).
     */
    public function enregistrerCommande(CommandeDTO $dto): Commande
    {
        $reductionAppliquee = $this->codePromoEstValide($dto->codePromo);

        $prixFinal = $reductionAppliquee
            ? $dto->prixInitial * (1 - self::TAUX_REDUCTION)
            : $dto->prixInitial;

        $commande = new Commande(
            prixFinal: round($prixFinal, 2),
            reductionAppliquee: $reductionAppliquee
        );

        return $this->commandeRepository->save($commande);
    }

    private function codePromoEstValide(string $codePromo): bool
    {
        return strtoupper(trim($codePromo)) === self::CODE_PROMO_VALIDE;
    }
}
