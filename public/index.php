<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controller\CommandeController;

// Point d'entrée unique du projet : c'est le seul fichier qui lit
// directement $_POST / $_GET, comme convenu sur les autres projets.
$action = $_GET['action'] ?? 'form';

switch ($action) {
    case 'enregistrer':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Méthode non autorisée.';
            break;
        }

        $controller = new CommandeController();
        header('Content-Type: text/plain; charset=utf-8');
        echo $controller->enregistrer($_POST);
        break;

    case 'form':
    default:
        require __DIR__ . '/../views/commande_form.php';
        break;
}
