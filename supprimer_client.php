
<?php
require_once "connexion.php";

// La suppression doit passer par un formulaire POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: clients.php");
    exit;
}

// Vérifier l'identifiant du client
$id_client = filter_var(
    $_POST["id_client"] ?? null,
    FILTER_VALIDATE_INT
);

if (!$id_client || $id_client < 1) {
    header("Location: clients.php?message=invalide");
    exit;
}

try {
    // Vérifier si le client possède des appareils
    $verification = $pdo->prepare(
        "SELECT COUNT(*)
         FROM appareil
         WHERE id_client = ?"
    );

    $verification->execute([$id_client]);
    $nombreAppareils = (int) $verification->fetchColumn();

    if ($nombreAppareils > 0) {
        header("Location: clients.php?message=impossible");
        exit;
    }

    // Supprimer uniquement le client sélectionné
    $suppression = $pdo->prepare(
        "DELETE FROM client WHERE id_client = ?"
    );

    $suppression->execute([$id_client]);

    if ($suppression->rowCount() > 0) {
        header("Location: clients.php?message=supprime");
    } else {
        header("Location: clients.php?message=introuvable");
    }
    exit;

} catch (PDOException $e) {
    header("Location: clients.php?message=erreur");
    exit;
}
?>
