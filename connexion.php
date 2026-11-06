
<?php

$serveur = "localhost";
$base = "repairconnect";
$utilisateur = "root";
$mot_de_passe = "";

try {
    $pdo = new PDO(
        "mysql:host=$serveur;dbname=$base;charset=utf8mb4",
        $utilisateur,
        $mot_de_passe
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données.";
}

?>
