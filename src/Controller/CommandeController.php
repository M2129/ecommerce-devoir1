<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CommandeDTO;
use App\Repository\CommandeRepository;
use App\Service\CommandeService;

/**
 * CommandeController
 *
 * Le "guichetier" : seul point de contact entre le navigateur (HTTP)
 * et la logique métier. Il ne calcule rien lui-même, il orchestre :
 *   1. transforme le $_POST brut en CommandeDTO (typé, filtré)
 *   2. demande au Service de faire le travail
 *   3. formate la réponse (ici : texte simple, adaptable en HTML/JSON)
 */
class CommandeController
{
    private CommandeService $commandeService;

    public function __construct()
    {
        // En "vrai" projet on injecterait ces dépendances de l'extérieur
        // (conteneur d'injection). Ici on les construit à la main pour
        // rester simple et pédagogique.
        $repository = new CommandeRepository();
        $this->commandeService = new CommandeService($repository);
    }

    /**
     * Traite la soumission du formulaire "Enregistrement de la commande".
     * Reçoit le tableau $_POST du navigateur.
     */
    public function enregistrer(array $postData): string
    {
        // Etape 1 : $_POST brut -> objet métier typé (DTO)
        $dto = CommandeDTO::fromArray($postData);

        // Etape 2 : le Service fait le travail (calcul + stockage)
        $commande = $this->commandeService->enregistrerCommande($dto);

        // Etape 3 : formatage de la "facture" renvoyée au client
        return $this->formaterFacture($commande);
    }

    private function formaterFacture(\App\Entity\Commande $commande): string
    {
        $statutReduction = $commande->isReductionAppliquee()
            ? 'Réduction de 10% appliquée (code PROMO10)'
            : 'Aucune réduction appliquée';

        return sprintf(
            "Commande n°%d enregistrée le %s\nMontant à payer : %.2f €\n%s",
            $commande->getId(),
            $commande->getDateCreation()->format('d/m/Y H:i'),
            $commande->getPrixFinal(),
            $statutReduction
        );
    }
}
