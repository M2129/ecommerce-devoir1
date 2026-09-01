<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Valider ma commande</title>
</head>
<body>
    <h1>Récapitulatif du panier</h1>

    <form action="index.php?action=enregistrer" method="POST">
        <label for="prix_initial">Montant du panier (€) :</label>
        <input type="number" step="0.01" name="prix_initial" id="prix_initial" required>
        <br><br>

        <label for="code_promo">Code promo (optionnel) :</label>
        <input type="text" name="code_promo" id="code_promo">
        <br><br>

        <button type="submit">Valider et payer</button>
    </form>
</body>
</html>
