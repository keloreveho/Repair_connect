
<?php
require_once "connexion.php";

$statuts = [
    "en attente",
    "en cours",
    "terminée",
    "annulée"
];

$message = "";

// Récupérer l'identifiant de la demande
$id_demande = filter_var(
    $_GET["id"] ?? $_POST["id_demande"] ?? null,
    FILTER_VALIDATE_INT
);

if (!$id_demande) {
    die("Identifiant de demande invalide.");
}

// Récupérer la demande concernée
$requete = $pdo->prepare(
    "SELECT id_demande, description_panne, statut
     FROM demande_reparation
     WHERE id_demande = ?"
);

$requete->execute([$id_demande]);
$demande = $requete->fetch(PDO::FETCH_ASSOC);

if (!$demande) {
    die("Demande introuvable.");
}

// Modifier le statut après validation du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nouveau_statut = $_POST["statut"] ?? "";

    if (!in_array($nouveau_statut, $statuts, true)) {
        $message = "Statut invalide.";
    } else {
        $modification = $pdo->prepare(
            "UPDATE demande_reparation
             SET statut = ?
             WHERE id_demande = ?"
        );

        $modification->execute([
            $nouveau_statut,
            $id_demande
        ]);

        header("Location: demandes.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Modifier le statut - RepairConnect</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }

        .formulaire {
            max-width: 480px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
        }

        h1 {
            color: #17365d;
            text-align: center;
        }

        label {
            display: block;
            margin-top: 20px;
            font-weight: bold;
        }

        select {
            width: 100%;
            box-sizing: border-box;
            padding: 12px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 25px;
            background: #17365d;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        a {
            display: block;
            margin-top: 20px;
            text-align: center;
        }

        .erreur {
            color: #b00020;
        }
    </style>
</head>

<body>

<div class="formulaire">

    <h1>Modifier le statut</h1>

    <p>
        <strong>Demande n° :</strong>
        <?= (int) $demande["id_demande"] ?>
    </p>

    <p>
        <strong>Panne :</strong>
        <?= htmlspecialchars(
            $demande["description_panne"],
            ENT_QUOTES,
            "UTF-8"
        ) ?>
    </p>

    <?php if ($message !== ""): ?>
        <p class="erreur">
            <?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <input
            type="hidden"
            name="id_demande"
            value="<?= (int) $demande["id_demande"] ?>"
        >

        <label>Nouveau statut</label>

        <select name="statut" required>

            <?php foreach ($statuts as $statut): ?>

                <option
                    value="<?= htmlspecialchars($statut, ENT_QUOTES, 'UTF-8') ?>"
                    <?= $demande["statut"] === $statut ? "selected" : "" ?>
                >
                    <?= htmlspecialchars($statut, ENT_QUOTES, "UTF-8") ?>
                </option>

            <?php endforeach; ?>

        </select>

        <button type="submit">
            Enregistrer la modification
        </button>

    </form>

    <a href="demandes.php">
        Retour aux demandes
    </a>

</div>

</body>
</html>
