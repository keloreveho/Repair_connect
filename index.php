
<?php
require_once "connexion.php";

// Compter les clients
$nombreClients = $pdo->query(
    "SELECT COUNT(*) FROM client"
)->fetchColumn();

// Compter toutes les demandes
$nombreDemandes = $pdo->query(
    "SELECT COUNT(*) FROM demande_reparation"
)->fetchColumn();

// Compter les demandes en cours
$nombreEnCours = $pdo->query(
    "SELECT COUNT(*) FROM demande_reparation
     WHERE statut = 'en cours'"
)->fetchColumn();

// Récupérer les 10 dernières demandes
$sql = "SELECT
            d.id_demande,
            d.statut,
            d.description_panne,
            c.nom,
            c.prenom,
            a.marque,
            a.modele
        FROM demande_reparation d
        INNER JOIN appareil a
            ON d.id_appareil = a.id_appareil
        INNER JOIN client c
            ON a.id_client = c.id_client
        ORDER BY d.id_demande DESC
        LIMIT 10";

$demandes = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Accueil - RepairConnect</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f4f6f9;
            color: #253247;
        }

        header {
            background: #17365d;
            color: white;
            padding: 28px 20px;
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 34px;
        }

        header p {
            margin-bottom: 0;
        }

        .conteneur {
            max-width: 1200px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .statistiques {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 35px;
        }

        .carte {
            background: white;
            border-radius: 10px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 2px 8px #00000012;
        }

        .carte h2 {
            font-size: 36px;
            color: #17365d;
            margin: 0 0 10px;
        }

        .carte p {
            margin: 0;
            color: #5d6776;
        }

        .navigation {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 35px;
        }

        .bouton {
            display: block;
            background: #17365d;
            color: white;
            text-decoration: none;
            text-align: center;
            padding: 15px;
            border-radius: 7px;
            font-weight: bold;
        }

        .bouton:hover {
            background: #285b91;
        }

        .section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px #00000012;
        }

        .section h2 {
            margin-top: 0;
            color: #17365d;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 13px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #17365d;
            color: white;
        }

        tr:nth-child(even) {
            background: #f4f6f9;
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

        .lien {
            color: #17365d;
            font-weight: bold;
        }

        footer {
            text-align: center;
            padding: 25px;
            color: #6b7280;
        }

        @media (max-width: 750px) {
            .statistiques,
            .navigation {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<header>
    <h1>RepairConnect</h1>
    <p>Gestion des réparations électroniques</p>
</header>

<div class="conteneur">

    <div class="statistiques">

        <div class="carte">
            <h2><?= (int) $nombreClients ?></h2>
            <p>Nombre de clients</p>
        </div>

        <div class="carte">
            <h2><?= (int) $nombreDemandes ?></h2>
            <p>Total des demandes</p>
        </div>

        <div class="carte">
            <h2><?= (int) $nombreEnCours ?></h2>
            <p>Demandes en cours</p>
        </div>

    </div>

    <div class="navigation">

        <a class="bouton" href="clients.php">
            Liste des clients
        </a>

        <a class="bouton" href="ajouter_client.php">
            Ajouter un client
        </a>

        <a class="bouton" href="appareils.php">
            Liste des appareils
        </a>

        <a class="bouton" href="ajouter_appareil.php">
            Ajouter un appareil
        </a>

        <a class="bouton" href="demandes.php">
            Liste des demandes
        </a>

        <a class="bouton" href="ajouter_demande.php">
            Créer une demande
        </a>

    </div>

    <div class="section">

        <h2>Dernières demandes de réparation</h2>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Client</th>
                        <th>Appareil</th>
                        <th>Panne</th>
                        <th>Statut</th>
                        <th>Interventions</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($demandes as $demande): ?>

                    <?php
                    $classes = [
                        "en cours" => "en-cours",
                        "terminée" => "terminee",
                        "en attente" => "en-attente",
                        "annulée" => "annulee"
                    ];

                    $classe = $classes[$demande["statut"]] ?? "";
                    ?>

                    <tr>
                        <td>
                            REP-<?= (int) $demande["id_demande"] ?>
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
                            <a
                                class="lien"
                                href="interventions.php?id=<?= (int) $demande['id_demande'] ?>"
                            >
                                Voir les interventions
                            </a>
                        </td>
                    </tr>

                <?php endforeach; ?>

                </tbody>
            </table>
        </div>

    </div>

</div>

<footer>
    RepairConnect - Projet de base de données
</footer>

</body>
</html>
