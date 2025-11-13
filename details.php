<?php
include 'php/config.php';

$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($book_id <= 0) {
    header("Location: index.html");
    exit();
}

// Connexion à la base de données
$conn = getDBConnection();

// Récupération des détails du livre
$sql = "SELECT * FROM Livres WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: index.html");
    exit();
}

$book = $result->fetch_assoc();
$stmt->close();

// Traitement de l'ajout à la liste de lecture
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_wishlist'])) {
    $reader_id = 1; // Lecteur par défaut pour la démo
    
    // Vérifier si le livre n'est pas déjà dans la liste
    $check_sql = "SELECT * FROM Liste_Lecture WHERE id_livre = ? AND id_lecteur = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $book_id, $reader_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows === 0) {
        // Ajouter à la liste de lecture
        $insert_sql = "INSERT INTO Liste_Lecture (id_livre, id_lecteur, date_emprunt) VALUES (?, ?, CURDATE())";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ii", $book_id, $reader_id);
        
        if ($insert_stmt->execute()) {
            $message = "Livre ajouté à votre liste de lecture avec succès!";
        } else {
            $message = "Erreur lors de l'ajout à la liste de lecture.";
        }
        $insert_stmt->close();
    } else {
        $message = "Ce livre est déjà dans votre liste de lecture.";
    }
    $check_stmt->close();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($book['titre']); ?> - Bibliothèque en ligne</title>
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
        <section class="book-details">
            <div class="book-header">
                <h2><?php echo htmlspecialchars($book['titre']); ?></h2>
                <p class="author">par <?php echo htmlspecialchars($book['auteur']); ?></p>
            </div>
            
            <div class="book-info">
                <div class="book-meta">
                    <p><strong>Maison d'édition:</strong> <?php echo htmlspecialchars($book['maison_edition']); ?></p>
                    <p><strong>Exemplaires disponibles:</strong> <?php echo $book['nombre_exemplaire']; ?></p>
                </div>
                
                <div class="book-description">
                    <h3>Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>
                </div>
                
                <?php if (!empty($message)): ?>
                    <div class="message"><?php echo $message; ?></div>
                <?php endif; ?>
                
                <form method="POST" class="wishlist-form">
                    <button type="submit" name="add_to_wishlist" class="btn">Ajouter à ma liste de lecture</button>
                </form>
                
                <a href="index.html" class="btn secondary">Retour à l'accueil</a>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 Bibliothèque en ligne. Tous droits réservés.</p>
        </div>
    </footer>

    <?php $conn->close(); ?>
</body>
</html>