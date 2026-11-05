
<?php
require_once "connexion.php";

// Récupérer les appareils et leurs propriétaires
$sql = "SELECT
            a.id_appareil,
            a.categorie,
            a.marque,
            a.modele,
            a.date_achat,
            c.nom,
            c.prenom
        FROM appareil AS a
        INNER JOIN client AS c
            ON a.id_client = c.id_client
        ORDER BY a.id_appareil ASC";

$requete = $pdo->query($sql);
$appareils = $requete->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>Liste des appareils - RepairConnect</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }

        h1 {
            color: #17365d;
            text-align: center;
        }

        .conteneur {
            max-width: 1200px;
            margin: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 25px;
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

        .bouton {
            display: inline-block;
            padding: 12px 18px;
            background: #17365d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>
<div class="conteneur">

    <h1>RepairConnect - Liste des appareils</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Catégorie</th>
                <th>Marque</th>
                <th>Modèle</th>
                <th>Date d'achat</th>
                <th>Propriétaire</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($appareils as $appareil): ?>
                <tr>
                    <td>
                        <?= (int) $appareil["id_appareil"] ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($appareil["categorie"]) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($appareil["marque"]) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($appareil["modele"]) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($appareil["date_achat"] ?? "Non renseignée") ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $appareil["prenom"] . " " .
                            $appareil["nom"]
                        ) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p>Nombre d'appareils : <?= count($appareils) ?></p>

</div>
</body>
</html>
