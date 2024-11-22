<?php
// Connexion à la base de données
$host = 'localhost'; // ou votre hôte
$db = 'hopital';
$user = 'root'; // votre nom d'utilisateur
$pass = ''; // votre mot de passe

$conn = new mysqli($host, $user, $pass, $db);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer les publications
$sql = "SELECT title, content, publication_date FROM publication ORDER BY publication_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publications - Hôpital</title>
    <link rel="stylesheet" href="style2.css">
    <style>
           body {
                background-image: url('images/town.jpg');
                background-position: center;
                background-size: cover; /* L'image sera entièrement visible */
                background-repeat: no-repeat;
                background-color: #f4f4f4; /* Couleur de fond par défaut */
                height: 107vh;
                }
    </style>
    <?php include 'header1.php'; ?>
</head>
<body>
   
    <h1>Publications de l'Hôpital</h1>

    <section id="publications">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <article>
                    <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                    <p>Date de publication : <?php echo htmlspecialchars($row['publication_date']); ?></p>
                    <p><?php echo htmlspecialchars($row['content']); ?></p>
                </article>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Aucune publication disponible.</p>
        <?php endif; ?>
    </section>

    <?php $conn->close(); ?>
</body>
</html>