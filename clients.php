
<?php
require_once "connexion.php";

// Récupérer les clients
$sql = "SELECT id_client, nom, prenom, email, telephone
        FROM client
        ORDER BY id_client ASC";

$requete = $pdo->query($sql);
$clients = $requete->fetchAll(PDO::FETCH_ASSOC);

// Messages après une suppression
$messages = [
    "supprime" => "Le client a été supprimé avec succès.",
    "impossible" => "Suppression impossible : ce client possède des appareils.",
    "introuvable" => "Client introuvable.",
    "invalide" => "Identifiant de client invalide.",
    "erreur" => "Une erreur est survenue lors de la suppression."
];

$codeMessage = $_GET["message"] ?? "";
$message = $messages[$codeMessage] ?? "";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Liste des clients - RepairConnect</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
        }

        .conteneur {
            max-width: 1200px;
            margin: auto;
        }

        h1 {
            color: #17365d;
            text-align: center;
        }

        .actions-haut {
            display: flex;
            gap: 12px;
            margin: 25px 0;
        }

        .bouton {
            display: inline-block;
            background: #17365d;
            color: white;
            padding: 11px 16px;
            border-radius: 5px;
            text-decoration: none;
        }

        .modifier {
            background: #247a45;
        }

        .supprimer {
            background: #b00020;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .modifier, .supprimer {
            display: inline-block;
            color: white;
            padding: 9px 14px;
            border-radius: 5px;
            text-decoration: none;
        }

        .actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .actions form {
            margin: 0;
        }

        .message {
            background: #e8f3ff;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #17365d;
            color: white;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }
    </style>
</head>

<body>

<div class="conteneur">

    <h1>RepairConnect - Liste des clients</h1>

    <div class="actions-haut">
        <a class="bouton" href="index.php">
            Retour à l'accueil
        </a>

        <a class="bouton" href="ajouter_client.php">
            Ajouter un client
        </a>
    </div>

    <?php if ($message !== ""): ?>
        <p class="message">
            <?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?>
        </p>
    <?php endif; ?>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

            <?php foreach ($clients as $client): ?>

                <tr>
                    <td><?= (int) $client["id_client"] ?></td>

                    <td>
                        <?= htmlspecialchars(
                            $client["nom"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $client["prenom"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $client["email"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $client["telephone"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>
                    </td>

                    <td>
                        <div class="actions">

                            <a
                                class="modifier"
                                href="modifier_client.php?id=<?= (int) $client['id_client'] ?>"
                            >
                                Modifier
                            </a>

                            <form
                                method="POST"
                                action="supprimer_client.php"
                                onsubmit="return confirm('Voulez-vous vraiment supprimer ce client ?');"
                            >
                                <input
                                    type="hidden"
                                    name="id_client"
                                    value="<?= (int) $client['id_client'] ?>"
                                >

                                <button
                                    type="submit"
                                    class="supprimer"
                                >
                                    Supprimer
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>

            <?php endforeach; ?>

            </tbody>
        </table>
    </div>

    <p>
        <strong>Nombre de clients :</strong>
        <?= count($clients) ?>
    </p>

</div>

</body>
</html>
