<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\DTO\CommandeDTO;
use App\Entity\Commande;
use App\Repository\CommandeRepository;
use App\Service\CommandeService;

/**
 * Test complet simulant un achat, du DTO jusqu'à la "facture".
 *
 * On utilise ici un faux Repository (qui n'écrit pas vraiment en base)
 * pour pouvoir lancer ce test sans avoir besoin d'une connexion
 * PostgreSQL fonctionnelle. C'est le principe de l'injection de
 * dépendances : le Service ne sait pas que le Repository est "faux".
 */
$fakeRepository = new class extends CommandeRepository {
    public function __construct()
    {
        // On ne fait pas appel à Database::getConnection() ici,
        // on court-circuite volontairement la vraie connexion.
    }

    public function save(Commande $commande): Commande
    {
        // Simule un id généré par la base.
        $commande->setId(1);
        return $commande;
    }
};

$service = new CommandeService($fakeRepository);

echo "=== Test 1 : Achat SANS code promo ===\n";
$dtoSansPromo = CommandeDTO::fromArray([
    'prix_initial' => 100,
    'code_promo' => '',
]);
$commande1 = $service->enregistrerCommande($dtoSansPromo);
assert($commande1->getPrixFinal() === 100.0);
assert($commande1->isReductionAppliquee() === false);
echo "Prix final : {$commande1->getPrixFinal()} € - Réduction : " .
    ($commande1->isReductionAppliquee() ? 'oui' : 'non') . "\n\n";

echo "=== Test 2 : Achat AVEC code promo valide (PROMO10) ===\n";
$dtoAvecPromo = CommandeDTO::fromArray([
    'prix_initial' => 100,
    'code_promo' => 'promo10', // volontairement en minuscules
]);
$commande2 = $service->enregistrerCommande($dtoAvecPromo);
assert($commande2->getPrixFinal() === 90.0);
assert($commande2->isReductionAppliquee() === true);
echo "Prix final : {$commande2->getPrixFinal()} € - Réduction : " .
    ($commande2->isReductionAppliquee() ? 'oui' : 'non') . "\n\n";

echo "=== Test 3 : Achat AVEC code promo invalide ===\n";
$dtoPromoInvalide = CommandeDTO::fromArray([
    'prix_initial' => 100,
    'code_promo' => 'CODEBIDON',
]);
$commande3 = $service->enregistrerCommande($dtoPromoInvalide);
assert($commande3->getPrixFinal() === 100.0);
assert($commande3->isReductionAppliquee() === false);
echo "Prix final : {$commande3->getPrixFinal()} € - Réduction : " .
    ($commande3->isReductionAppliquee() ? 'oui' : 'non') . "\n\n";

echo "Tous les tests sont passés avec succès.\n";
