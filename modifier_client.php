
<?php
require_once "connexion.php";

$id_client = filter_var(
    $_GET["id"] ?? $_POST["id_client"] ?? null,
    FILTER_VALIDATE_INT
);

if (!$id_client || $id_client < 1) {
    die("Identifiant du client invalide.");
}

$message = "";

// Rechercher le client
$requete = $pdo->prepare(
    "SELECT * FROM client WHERE id_client = ?"
);
$requete->execute([$id_client]);
$client = $requete->fetch(PDO::FETCH_ASSOC);

if (!$client) {
    die("Client introuvable.");
}

// Enregistrer les modifications
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
        $message = "Veuillez remplir correctement les champs.";
    } else {
        try {
            $sql = "UPDATE client
                    SET nom = ?,
                        prenom = ?,
                        email = ?,
                        telephone = ?
                    WHERE id_client = ?";

            $modification = $pdo->prepare($sql);

            $modification->execute([
                $nom,
                $prenom,
                $email,
                $telephone,
                $id_client
            ]);

            header("Location: clients.php");
            exit;

        } catch (PDOException $e) {
            $message = "Modification impossible. Vérifiez que l'email n'existe pas déjà.";
        }
    }

    // Conserver les valeurs saisies en cas d'erreur
    $client["nom"] = $nom;
    $client["prenom"] = $prenom;
    $client["email"] = $email;
    $client["telephone"] = $telephone;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Modifier un client - RepairConnect</title>

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

        input {
            width: 100%;
            box-sizing: border-box;
            padding: 11px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            background: #17365d;
            color: white;
            padding: 12px;
            margin-top: 25px;
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

    <h1>Modifier un client</h1>

    <p>
        Client n° <?= (int) $client["id_client"] ?>
    </p>

    <?php if ($message !== ""): ?>
        <p class="erreur">
            <?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <input
            type="hidden"
            name="id_client"
            value="<?= (int) $client["id_client"] ?>"
        >

        <label>Nom</label>
        <input
            type="text"
            name="nom"
            value="<?= htmlspecialchars($client["nom"], ENT_QUOTES, "UTF-8") ?>"
            required
        >

        <label>Prénom</label>
        <input
            type="text"
            name="prenom"
            value="<?= htmlspecialchars($client["prenom"], ENT_QUOTES, "UTF-8") ?>"
            required
        >

        <label>Email</label>
        <input
            type="email"
            name="email"
            value="<?= htmlspecialchars($client["email"], ENT_QUOTES, "UTF-8") ?>"
            required
        >

        <label>Téléphone</label>
        <input
            type="tel"
            name="telephone"
            value="<?= htmlspecialchars($client["telephone"], ENT_QUOTES, "UTF-8") ?>"
            required
        >

        <button type="submit">
            Enregistrer les modifications
        </button>

    </form>

    <a href="clients.php">
        Retour à la liste des clients
    </a>

</div>

</body>
</html>
