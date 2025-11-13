<?php
include 'php/config.php';

$query = isset($_GET['query']) ? secure_input($_GET['query']) : '';

// Connexion à la base de données
$conn = getDBConnection();

// Recherche des livres
$sql = "SELECT * FROM Livres WHERE titre LIKE ? OR auteur LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%$query%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de recherche - Bibliothèque en ligne</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Bibliothèque en ligne</h1>
            <nav>
                <ul>
                    <li><a href="index.html">Accueil</a></li>
                    <li><a href="wishlist.php">Ma liste de lecture</a></li>
                    <li><a href="admin.php">Administration</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="search-results">
            <h2>Résultats de recherche pour "<?php echo htmlspecialchars($query); ?>"</h2>
            
            <?php if ($result->num_rows > 0): ?>
                <div class="books-grid">
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="book-card">
                            <h3><?php echo htmlspecialchars($row['titre']); ?></h3>
                            <p class="author"><?php echo htmlspecialchars($row['auteur']); ?></p>
                            <p class="publisher"><?php echo htmlspecialchars($row['maison_edition']); ?></p>
                            <p class="copies">Exemplaires disponibles: <?php echo $row['nombre_exemplaire']; ?></p>
                            <a href="details.php?id=<?php echo $row['id']; ?>" class="btn">Voir détails</a>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="no-results">Aucun livre trouvé pour votre recherche.</p>
                <a href="index.html" class="btn">Retour à l'accueil</a>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 Bibliothèque en ligne. Tous droits réservés.</p>
        </div>
    </footer>

    <?php
    $stmt->close();
    $conn->close();
    ?>
</body>
</html>