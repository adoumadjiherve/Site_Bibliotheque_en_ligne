<?php
include 'php/config.php';

// Connexion à la base de données
$conn = getDBConnection();

$reader_id = 1; // Lecteur par défaut pour la démo

// Récupération de la liste de lecture
$sql = "SELECT l.*, ll.id as wishlist_id, ll.date_emprunt 
        FROM Livres l 
        INNER JOIN Liste_Lecture ll ON l.id = ll.id_livre 
        WHERE ll.id_lecteur = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $reader_id);
$stmt->execute();
$result = $stmt->get_result();

// Traitement de la suppression d'un livre de la liste
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_from_wishlist'])) {
    $wishlist_id = intval($_POST['wishlist_id']);
    
    $delete_sql = "DELETE FROM Liste_Lecture WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $wishlist_id);
    
    if ($delete_stmt->execute()) {
        header("Location: wishlist.php");
        exit();
    }
    $delete_stmt->close();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma liste de lecture - Bibliothèque en ligne</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Bibliothèque en ligne</h1>
            <nav>
                <ul>
                    <li><a href="index.html">Accueil</a></li>
                    <li><a href="wishlist.php" class="active">Ma liste de lecture</a></li>
                    <li><a href="admin.php">Administration</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="wishlist">
            <h2>Ma liste de lecture</h2>
            
            <?php if ($result->num_rows > 0): ?>
                <div class="books-grid">
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="book-card">
                            <h3><?php echo htmlspecialchars($row['titre']); ?></h3>
                            <p class="author"><?php echo htmlspecialchars($row['auteur']); ?></p>
                            <p class="publisher"><?php echo htmlspecialchars($row['maison_edition']); ?></p>
                            <p class="borrow-date">Emprunté le: <?php echo date('d/m/Y', strtotime($row['date_emprunt'])); ?></p>
                            
                            <div class="actions">
                                <a href="details.php?id=<?php echo $row['id']; ?>" class="btn">Voir détails</a>
                                <form method="POST" class="remove-form">
                                    <input type="hidden" name="wishlist_id" value="<?php echo $row['wishlist_id']; ?>">
                                    <button type="submit" name="remove_from_wishlist" class="btn danger">Retirer</button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="no-results">Votre liste de lecture est vide.</p>
                <a href="index.html" class="btn">Découvrir des livres</a>
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