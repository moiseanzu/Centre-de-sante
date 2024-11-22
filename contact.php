<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <?php include 'header1.php' ?>
    <link rel="stylesheet" href="style04.css">
    <style>
        .contact-icons img {
            width: 40px; /* Réglez la taille des icônes */
            margin: 10px; /* Espacement entre les icônes */
        }
    </style>
</head>

<body>
    <h1><strong>Bienvenue sur notre page de contact</strong></h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <strong>Votre nom</strong>
        <input type="text" name="nom" required>
        <br><br>

        <strong>Objectif</strong>
        <input type="text" name="objet" required>
        <br><br>

        <strong><label for="sms">Écrivez-nous ici</label></strong>
        <textarea id="sms" name="sms" rows="6" cols="40" required></textarea>
        <br><br>

        <strong>Votre numéro de téléphone</strong>
        <input type="text" name="telephone" required>
        <br><br>

        <strong>Ajouter un document (PDF ou image)</strong>
        <input type="file" name="file" accept=".pdf, .jpg, .jpeg, .png" required>
        <br><br>

        <div>
            <button type="submit" class="centered-button">Envoyer</button>
        </div>
    </form>

    <footer>
        <div>
            <p>Vous pouvez nous contacter aussi sur :</p>
        </div>
        <div class="contact-icons">
            <a href="https://www.facebook.com/Moise Anzuruni Kibaja Johnson" target="_blank">
                <img src="images/face.png" alt="Facebook">
            </a>
            <a href="https://wa.me/+25765881704" target="_blank">
                <img src="images/whats.png" alt="WhatsApp">
            </a>
            <a href="mailto:moiseanzukijo@gmail.com" target="_blank">
                <img src="images/email.png" alt="Gmail">
            </a>
            <section id="contact">
    
            <p>
                Vous pouvez aussi nous appeler au <strong>+25765881704</strong>.
            </p>
  
        </div>
    </footer>

    <?php
    $host = 'localhost';
    $db = 'hopital';
    $user = 'root';
    $pass = '';
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Validation des entrées
            $nom = validateInput($_POST["nom"]);
            $objet = validateInput($_POST["objet"]);
            $sms = validateInput($_POST["sms"]);
            $phone = validateInput($_POST["telephone"]);

            // Vérification des doublons
            $sql = "SELECT COUNT(*) FROM contact WHERE nom = ? AND telephone = ? AND sms = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nom,$phone,$sms]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                echo "Ce message existe déjà.";
            } else {
                // Gestion du fichier
                if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES['file'];
                    $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];

                    if (in_array($file['type'], $allowedTypes)) {
                        $fileContent = file_get_contents($file['tmp_name']);

                        // Insertion dans la base de données
                        $sql = "INSERT INTO contact (nom, objet, sms, telephone, document) VALUES (?, ?, ?, ?, ?)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$nom, $objet, $sms, $phone, $fileContent]);

                        header("Location: recupeDataContact.php");
                        exit;
                    } else {
                        echo "Type de fichier non autorisé.";
                    }
                } else {
                    echo "Erreur lors du téléchargement du fichier.";
                }
            }
        }
    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
    }
    
    $pdo = null;

    function validateInput($data) {
        $data = trim($data); // Supprime les espaces inutiles
        $data = stripslashes($data); // Supprime les antislashs
        $data = htmlspecialchars($data); // Échappe les caractères spéciaux
        return $data;
    }
    ?>   
</body>

</html>