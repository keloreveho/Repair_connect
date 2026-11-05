
<?php
require_once "connexion.php";

$message = "";

// Récupérer les clients pour le menu déroulant
$requete = $pdo->query(
    "SELECT id_client, nom, prenom
     FROM client
     ORDER BY nom, prenom"
);

$clients = $requete->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $categorie = trim($_POST["categorie"] ?? "");
    $marque = trim($_POST["marque"] ?? "");
    $modele = trim($_POST["modele"] ?? "");
    $date_achat = $_POST["date_achat"] ?? "";
    $id_client = filter_var(
        $_POST["id_client"] ?? null,
        FILTER_VALIDATE_INT
    );

    if (
        $categorie === "" ||
        $marque === "" ||
        $modele === "" ||
        !$id_client
    ) {
        $message = "Veuillez remplir les champs obligatoires.";
    } else {
        try {
            // Vérifier que le client existe
            $verification = $pdo->prepare(
                "SELECT COUNT(*) FROM client WHERE id_client = ?"
            );
            $verification->execute([$id_client]);

            if ($verification->fetchColumn() == 0) {
                $message = "Le client sélectionné n'existe pas.";
            } else {
                // Trouver le prochain identifiant
                $nouvelId = (int) $pdo->query(
                    "SELECT COALESCE(MAX(id_appareil), 0) + 1
                     FROM appareil"
                )->fetchColumn();

                // Enregistrer l'appareil
                $sql = "INSERT INTO appareil
                        (id_appareil, categorie, marque,
                         modele, date_achat, id_client)
                        VALUES (?, ?, ?, ?, ?, ?)";

                $requete = $pdo->prepare($sql);

                $requete->execute([
                    $nouvelId,
                    $categorie,
                    $marque,
                    $modele,
                    $date_achat !== "" ? $date_achat : null,
                    $id_client
                ]);

                header("Location: appareils.php");
                exit;
            }

        } catch (PDOException $e) {
            $message = "Erreur lors de l'ajout de l'appareil.";
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

    <title>Ajouter un appareil - RepairConnect</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }

        .formulaire {
            max-width: 480px;
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

        input, select {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            margin-top: 5px;
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

    <h1>Ajouter un appareil</h1>

    <?php if ($message !== ""): ?>
        <p class="erreur">
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <label>Catégorie</label>
        <select name="categorie" required>
            <option value="">Choisir une catégorie</option>
            <option value="Smartphone">Smartphone</option>
            <option value="Ordinateur portable">Ordinateur portable</option>
            <option value="Tablette">Tablette</option>
            <option value="Autre">Autre</option>
        </select>

        <label>Marque</label>
        <input type="text" name="marque" required>

        <label>Modèle</label>
        <input type="text" name="modele" required>

        <label>Date d'achat (facultative)</label>
        <input type="date" name="date_achat">

        <label>Propriétaire de l'appareil</label>
        <select name="id_client" required>
            <option value="">Choisir un client</option>

            <?php foreach ($clients as $client): ?>
                <option value="<?= (int) $client["id_client"] ?>">
                    <?= htmlspecialchars(
                        $client["prenom"] . " " . $client["nom"],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </option>
            <?php endforeach; ?>

        </select>

        <button type="submit">
            Enregistrer l'appareil
        </button>

    </form>

    <a href="appareils.php">
        Retour à la liste des appareils
    </a>

</div>

</body>
</html>
