
<?php
require_once "connexion.php";

// Récupérer les demandes avec les appareils et les clients
$sql = "SELECT
            d.id_demande,
            d.date_depot,
            d.description_panne,
            d.statut,
            d.cout_estime,
            a.categorie,
            a.marque,
            a.modele,
            c.nom,
            c.prenom
        FROM demande_reparation d
        INNER JOIN appareil a
            ON d.id_appareil = a.id_appareil
        INNER JOIN client c
            ON a.id_client = c.id_client
        ORDER BY d.id_demande ASC";

$demandes = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Couleurs des statuts
$classes = [
    "en attente" => "en-attente",
    "en cours" => "en-cours",
    "terminée" => "terminee",
    "annulée" => "annulee"
];

// Messages après une suppression
$messages = [
    "supprime" => "La demande a été supprimée avec succès.",
    "impossible" => "Suppression impossible : cette demande possède des interventions.",
    "introuvable" => "Demande introuvable.",
    "invalide" => "Identifiant de demande invalide.",
    "erreur" => "Une erreur est survenue pendant la suppression."
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

    <title>Demandes de réparation - RepairConnect</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }

        .conteneur {
            max-width: 1500px;
            margin: auto;
        }

        h1 {
            color: #17365d;
            text-align: center;
        }

        .bouton {
            display: inline-block;
            background: #17365d;
            color: white;
            padding: 9px 12px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            white-space: nowrap;
        }

        .bouton:hover {
            opacity: 0.85;
        }

        .modifier {
            background: #247a45;
        }

        .supprimer {
            background: #b00020;
        }

        .actions-haut {
            display: flex;
            gap: 10px;
            margin: 20px 0;
            flex-wrap: wrap;
        }

        .actions {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
        }

        .actions form {
            margin: 0;
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

        .statut {
            font-weight: bold;
        }

        .en-cours {
            color: #c77700;
        }

        .terminee {
            color: green;
        }

        .en-attente {
            color: #0066cc;
        }

        .annulee {
            color: #b00020;
        }

        .message {
            background: #e8f3ff;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }
    </style>
</head>

<body>

<div class="conteneur">

    <h1>RepairConnect - Demandes de réparation</h1>

    <div class="actions-haut">
        <a class="bouton" href="index.php">
            Retour à l'accueil
        </a>

        <a class="bouton" href="ajouter_demande.php">
            Ajouter une demande
        </a>
    </div>

    <?php if ($message !== ""): ?>
        <p class="message">
            <?= htmlspecialchars(
                $message,
                ENT_QUOTES,
                "UTF-8"
            ) ?>
        </p>
    <?php endif; ?>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date de dépôt</th>
                    <th>Client</th>
                    <th>Appareil</th>
                    <th>Description de la panne</th>
                    <th>Statut</th>
                    <th>Coût estimé</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

            <?php foreach ($demandes as $demande): ?>

                <?php
                $classe = $classes[$demande["statut"]] ?? "";
                ?>

                <tr>
                    <td>
                        <?= (int) $demande["id_demande"] ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $demande["date_depot"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $demande["prenom"] . " " .
                            $demande["nom"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $demande["categorie"] . " " .
                            $demande["marque"] . " " .
                            $demande["modele"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $demande["description_panne"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>
                    </td>

                    <td class="statut <?= $classe ?>">
                        <?= htmlspecialchars(
                            $demande["statut"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>
                    </td>

                    <td>
                        <?= number_format(
                            (float) $demande["cout_estime"],
                            2,
                            ",",
                            " "
                        ) ?> €
                    </td>

                    <td>
                        <div class="actions">

                            <!-- Modifier la demande -->
                            <a
                                class="bouton modifier"
                                href="modifier_demande.php?id=<?= (int) $demande['id_demande'] ?>"
                            >
                                Modifier la demande
                            </a>

                            <!-- Modifier le statut -->
                            <a
                                class="bouton"
                                href="modifier_statut.php?id=<?= (int) $demande['id_demande'] ?>"
                            >
                                Modifier le statut
                            </a>

                            <!-- Consulter les interventions -->
                            <a
                                class="bouton"
                                href="interventions.php?id=<?= (int) $demande['id_demande'] ?>"
                            >
                                Voir les interventions
                            </a>

                            <!-- Supprimer la demande -->
                            <form
                                method="POST"
                                action="supprimer_demande.php"
                                onsubmit="return confirm('Voulez-vous vraiment supprimer cette demande de réparation ?');"
                            >
                                <input
                                    type="hidden"
                                    name="id_demande"
                                    value="<?= (int) $demande['id_demande'] ?>"
                                >

                                <button
                                    type="submit"
                                    class="bouton supprimer"
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
        <strong>Nombre de demandes :</strong>
        <?= count($demandes) ?>
    </p>

</div>

</body>
</html>
