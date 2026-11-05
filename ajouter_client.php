
<?php
require_once "connexion.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST["nom"] ?? "");
    $prenom = trim($_POST["prenom"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $telephone = trim($_POST["telephone"] ?? "");

    if (
        $nom === "" ||
        $prenom === "" ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        $telephone === ""
    ) {
        $message = "Veuillez remplir correctement tous les champs.";
    } else {
        try {
            // Les identifiants existants vont de 1 à 10.
            $nouvelId = (int) $pdo->query(
                "SELECT COALESCE(MAX(id_client), 0) + 1 FROM client"
            )->fetchColumn();

            $sql = "INSERT INTO client
                    (id_client, nom, prenom, email, telephone)
                    VALUES (?, ?, ?, ?, ?)";

            $requete = $pdo->prepare($sql);
            $requete->execute([
                $nouvelId,
                $nom,
                $prenom,
                $email,
                $telephone
            ]);

            header("Location: clients.php");
            exit;
        } catch (PDOException $e) {
            $message = "Impossible d'ajouter le client. Vérifiez notamment que l'email n'existe pas déjà.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un client - RepairConnect</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }

        .formulaire {
            max-width: 450px;
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

        input {
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
        <h1>Ajouter un client</h1>

        <?php if ($message !== ""): ?>
            <p class="erreur">
                <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>

        <form method="POST">
            <label>Nom</label>
            <input type="text" name="nom" required>

            <label>Prénom</label>
            <input type="text" name="prenom" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Téléphone</label>
            <input type="tel" name="telephone" required>

            <button type="submit">Enregistrer le client</button>
        </form>

        <a href="clients.php">Retour à la liste des clients</a>
    </div>
</body>
</html>
