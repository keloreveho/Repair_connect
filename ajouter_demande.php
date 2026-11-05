
<?php
require_once "connexion.php";

$message = "";

// Récupérer les appareils et leurs propriétaires
$sql = "SELECT a.id_appareil, a.categorie, a.marque,
               a.modele, c.nom, c.prenom
        FROM appareil a
        INNER JOIN client c ON a.id_client = c.id_client
        ORDER BY c.nom, a.marque";

$appareils = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Enregistrer la demande
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_appareil = filter_var(
        $_POST["id_appareil"] ?? null,
        FILTER_VALIDATE_INT
    );

    $date_depot = $_POST["date_depot"] ?? "";
    $description = trim($_POST["description_panne"] ?? "");
    $cout = $_POST["cout_estime"] ?? "";
    $statut = "en attente";

    if (
        !$id_appareil ||
        !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_depot) ||
        !checkdate(
            (int) substr($date_depot, 5, 2),
            (int) substr($date_depot, 8, 2),
            (int) substr($date_depot, 0, 4)
        ) ||
        $description === "" ||
        !is_numeric($cout) ||
        (float) $cout < 0
    ) {
        $message = "Veuillez remplir correctement tous les champs.";
    } else {
        try {
            // Vérifier que l'appareil existe
            $verification = $pdo->prepare(
                "SELECT COUNT(*) FROM appareil WHERE id_appareil = ?"
            );
            $verification->execute([$id_appareil]);

            if ($verification->fetchColumn() == 0) {
                $message = "L'appareil sélectionné n'existe pas.";
            } else {

                // Trouver le prochain identifiant
                $nouvelId = (int) $pdo->query(
                    "SELECT COALESCE(MAX(id_demande), 0) + 1
                     FROM demande_reparation"
                )->fetchColumn();

                $requete = $pdo->prepare(
                    "INSERT INTO demande_reparation
                     (id_demande, date_depot, description_panne,
                      statut, cout_estime, id_appareil)
                     VALUES (?, ?, ?, ?, ?, ?)"
                );

                $requete->execute([
                    $nouvelId,
                    $date_depot,
                    $description,
                    $statut,
                    $cout,
                    $id_appareil
                ]);

                header("Location: demandes.php");
                exit;
            }
        } catch (PDOException $e) {
            $message = "Erreur lors de l'enregistrement de la demande.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Nouvelle demande - RepairConnect</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }

        .formulaire {
            max-width: 500px;
            margin: 40px auto;
            padding: 30px;
            background: white;
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
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        textarea {
            min-height: 100px;
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

    <h1>Créer une demande de réparation</h1>

    <?php if ($message !== ""): ?>
        <p class="erreur">
            <?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <label>Appareil concerné</label>
        <select name="id_appareil" required>
            <option value="">Choisir un appareil</option>

            <?php foreach ($appareils as $appareil): ?>
                <option value="<?= (int) $appareil["id_appareil"] ?>">
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
            value="<?= date('Y-m-d') ?>"
            required
        >

        <label>Description de la panne</label>
        <textarea
            name="description_panne"
            placeholder="Décrivez le problème rencontré..."
            required
        ></textarea>

        <label>Coût estimé (€)</label>
        <input
            type="number"
            name="cout_estime"
            min="0"
            step="0.01"
            required
        >

        <button type="submit">
            Enregistrer la demande
        </button>

    </form>

    <a href="demandes.php">
        Retour à la liste des demandes
    </a>

</div>

</body>
</html>
