
<?php
require_once "connexion.php";

// Autoriser uniquement les demandes POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: demandes.php");
    exit;
}

// Vérifier l'identifiant de la demande
$id_demande = filter_var(
    $_POST["id_demande"] ?? null,
    FILTER_VALIDATE_INT
);

if (!$id_demande || $id_demande < 1) {
    header("Location: demandes.php?message=invalide");
    exit;
}

try {
    // Vérifier si la demande possède des interventions
    $verification = $pdo->prepare(
        "SELECT COUNT(*)
         FROM intervention
         WHERE id_demande = ?"
    );

    $verification->execute([$id_demande]);

    $nombreInterventions = (int) $verification->fetchColumn();

    if ($nombreInterventions > 0) {
        header("Location: demandes.php?message=impossible");
        exit;
    }

    // Supprimer la demande si elle n'a aucune intervention
    $suppression = $pdo->prepare(
        "DELETE FROM demande_reparation
         WHERE id_demande = ?"
    );

    $suppression->execute([$id_demande]);

    if ($suppression->rowCount() > 0) {
        header("Location: demandes.php?message=supprime");
    } else {
        header("Location: demandes.php?message=introuvable");
    }

    exit;

} catch (PDOException $e) {
    header("Location: demandes.php?message=erreur");
    exit;
}
?>
