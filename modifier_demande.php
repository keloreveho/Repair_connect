
<?php
require_once "connexion.php";

$id_demande = filter_var(
    $_GET["id"] ?? $_POST["id_demande"] ?? null,
    FILTER_VALIDATE_INT
);

if (!$id_demande || $id_demande < 1) {
    die("Identifiant de demande invalide.");
}

$message = "";

// Rechercher la demande
$requete = $pdo->prepare(
    "SELECT * FROM demande_reparation
     WHERE id_demande = ?"
);
$requete->execute([$id_demande]);
$demande = $requete->fetch(PDO::FETCH_ASSOC);

if (!$demande) {
    die("Demande introuvable.");
}

// Récupérer les appareils et leurs propriétaires
$sql = "SELECT a.id_appareil, a.marque, a.modele,
               c.nom, c.prenom
        FROM appareil a
        INNER JOIN client c
            ON a.id_client = c.id_client
        ORDER BY c.nom, a.marque";

$appareils = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Traitement de la modification
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_appareil = filter_var(
        $_POST["id_appareil"] ?? null,
        FILTER_VALIDATE_INT
    );

    $date_depot = $_POST["date_depot"] ?? "";
    $description = trim($_POST["description_panne"] ?? "");
    $cout = $_POST["cout_estime"] ?? "";

    $dateValide = preg_match(
        '/^\d{4}-\d{2}-\d{2}$/',
        $date_depot
    ) && checkdate(
        (int) substr($date_depot, 5, 2),
        (int) substr($date_depot, 8, 2),
        (int) substr($date_depot, 0, 4)
    );

    if (
        !$id_appareil ||
        !$dateValide ||
        $description === "" ||
        !is_numeric($cout) ||
        (float) $cout < 0
    ) {
        $message = "Veuillez remplir correctement tous les champs.";
    } else {
        try {
            $sql = "UPDATE demande_reparation
                    SET id_appareil = ?,
                        date_depot = ?,
                        description_panne = ?,
                        cout_estime = ?
                    WHERE id_demande = ?";

            $modification = $pdo->prepare($sql);
            $modification->execute([
                $id_appareil,
                $date_depot,
                $description,
                $cout,
                $id_demande
            ]);

            header("Location: demandes.php");
            exit;

        } catch (PDOException $e) {
            $message = "Impossible de modifier la demande.";
        }
    }

    // Garder les valeurs saisies si une erreur survient
    $demande["id_appareil"] = $id_appareil;
    $demande["date_depot"] = $date_depot;
    $demande["description_panne"] = $description;
    $demande["cout_estime"] = $cout;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Modifier une demande - RepairConnect</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }

        .formulaire {
            max-width: 500px;
            margin: 40px auto;
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
            margin-top: 15px;
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 11px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        textarea {
            min-height: 100px;
        }

        button {
            width: 100%;
            background: #17365d;
            color: white;
            padding: 12px;
            margin-top: 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
        }

        .erreur {
            color: #b00020;
        }
    </style>
</head>

<body>

<div class="formulaire">

    <h1>Modifier une demande</h1>

    <p>Demande n° <?= (int) $demande["id_demande"] ?></p>

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

        <label>Appareil concerné</label>

        <select name="id_appareil" required>
            <?php foreach ($appareils as $appareil): ?>
                <option
                    value="<?= (int) $appareil["id_appareil"] ?>"
                    <?= (int) $demande["id_appareil"] ===
                        (int) $appareil["id_appareil"]
                        ? "selected" : "" ?>
                >
                    <?= htmlspecialchars(
                        $appareil["marque"] . " " .
                        $appareil["modele"] . " - " .
                        $appareil["prenom"] . " " .
                        $appareil["nom"],
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Date de dépôt</label>
        <input
            type="date"
            name="date_depot"
            value="<?= htmlspecialchars(
                $demande["date_depot"],
                ENT_QUOTES,
                "UTF-8"
            ) ?>"
            required
        >

        <label>Description de la panne</label>
        <textarea
            name="description_panne"
            required
        ><?= htmlspecialchars(
            $demande["description_panne"],
            ENT_QUOTES,
            "UTF-8"
        ) ?></textarea>

        <label>Coût estimé (€)</label>
        <input
            type="number"
            name="cout_estime"
            min="0"
            step="0.01"
            value="<?= htmlspecialchars(
                $demande["cout_estime"],
                ENT_QUOTES,
                "UTF-8"
            ) ?>"
            required
        >

        <button type="submit">
            Enregistrer les modifications
        </button>

    </form>

    <a href="demandes.php">
        Retour aux demandes
    </a>

</div>

</body>
</html>
