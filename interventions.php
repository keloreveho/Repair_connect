
<?php
require_once "connexion.php";

// Récupérer l'identifiant de la demande
$id_demande = filter_var(
    $_GET["id"] ?? null,
    FILTER_VALIDATE_INT
);

if (!$id_demande || $id_demande < 1) {
    die("Identifiant de demande invalide.");
}

// Récupérer les informations de la demande
$sqlDemande = "SELECT
                  d.id_demande,
                  d.description_panne,
                  d.statut,
                  a.marque,
                  a.modele,
                  c.nom,
                  c.prenom
               FROM demande_reparation d
               INNER JOIN appareil a
                   ON d.id_appareil = a.id_appareil
               INNER JOIN client c
                   ON a.id_client = c.id_client
               WHERE d.id_demande = ?";

$requete = $pdo->prepare($sqlDemande);
$requete->execute([$id_demande]);
$demande = $requete->fetch(PDO::FETCH_ASSOC);

if (!$demande) {
    die("Demande introuvable.");
}

// Récupérer les interventions et leurs techniciens
$sql = "SELECT
            i.id_intervention,
            i.date_intervention,
            i.description,
            i.cout_main_oeuvre,
            t.nom AS nom_technicien,
            t.prenom AS prenom_technicien,
            t.specialite
        FROM intervention i
        INNER JOIN technicien t
            ON i.id_technicien = t.id_technicien
        WHERE i.id_demande = ?
        ORDER BY i.date_intervention ASC,
                 i.id_intervention ASC";

$requete = $pdo->prepare($sql);
$requete->execute([$id_demande]);

$interventions = $requete->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Interventions - RepairConnect</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }

        .conteneur {
            max-width: 1100px;
            margin: auto;
        }

        h1 {
            color: #17365d;
            text-align: center;
        }

        .informations {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
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
            background: #17365d;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
        }

        .message {
            background: white;
            padding: 20px;
            border-radius: 8px;
        }
    </style>
</head>

<body>

<div class="conteneur">

    <h1>RepairConnect - Interventions</h1>

    <div class="informations">
        <h2>
            Demande n° <?= (int) $demande["id_demande"] ?>
        </h2>

        <p>
            <strong>Client :</strong>
            <?= htmlspecialchars(
                $demande["prenom"] . " " . $demande["nom"],
                ENT_QUOTES,
                "UTF-8"
            ) ?>
        </p>

        <p>
            <strong>Appareil :</strong>
            <?= htmlspecialchars(
                $demande["marque"] . " " . $demande["modele"],
                ENT_QUOTES,
                "UTF-8"
            ) ?>
        </p>

        <p>
            <strong>Panne :</strong>
            <?= htmlspecialchars(
                $demande["description_panne"],
                ENT_QUOTES,
                "UTF-8"
            ) ?>
        </p>

        <p>
            <strong>Statut :</strong>
            <?= htmlspecialchars(
                $demande["statut"],
                ENT_QUOTES,
                "UTF-8"
            ) ?>
        </p>
    </div>

    <h2>Liste des interventions</h2>

    <?php if (count($interventions) > 0): ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Technicien</th>
                    <th>Spécialité</th>
                    <th>Description</th>
                    <th>Coût main-d'œuvre</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($interventions as $intervention): ?>
                    <tr>
                        <td>
                            <?= (int) $intervention["id_intervention"] ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $intervention["date_intervention"],
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $intervention["prenom_technicien"] . " " .
                                $intervention["nom_technicien"],
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $intervention["specialite"],
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $intervention["description"],
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>
                        </td>

                        <td>
                            <?= number_format(
                                (float) $intervention["cout_main_oeuvre"],
                                2,
                                ",",
                                " "
                            ) ?> €
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p>
            <strong>Nombre d'interventions :</strong>
            <?= count($interventions) ?>
        </p>

    <?php else: ?>

        <p class="message">
            Aucune intervention enregistrée pour cette demande.
        </p>

    <?php endif; ?>

    <a class="bouton" href="demandes.php">
        Retour aux demandes
    </a>

</div>

</body>
</html>
