<?php
include 'php/config.php';

// Connexion à la base de données
$conn = getDBConnection();

// Traitement des opérations CRUD
$message = '';

// Ajout d'un livre
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_book'])) {
    $titre = secure_input($_POST['titre']);
    $auteur = secure_input($_POST['auteur']);
    $description = secure_input($_POST['description']);
    $maison_edition = secure_input($_POST['maison_edition']);
    $nombre_exemplaire = intval($_POST['nombre_exemplaire']);
    
    $sql = "INSERT INTO Livres (titre, auteur, description, maison_edition, nombre_exemplaire) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $titre, $auteur, $description, $maison_edition, $nombre_exemplaire);
    
    if ($stmt->execute()) {
        $message = "Livre ajouté avec succès!";
    } else {
        $message = "Erreur lors de l'ajout du livre.";
    }
    $stmt->close();
}

// Suppression d'un livre
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Vérifier si le livre n'est pas dans une liste de lecture
    $check_sql = "SELECT * FROM Liste_Lecture WHERE id_livre = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows === 0) {
        $sql = "DELETE FROM Livres WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $message = "Livre supprimé avec succès!";
        } else {
            $message = "Erreur lors de la suppression du livre.";
        }
        $stmt->close();
    } else {
        $message = "Impossible de supprimer ce livre car il est dans une liste de lecture.";
    }
    $check_stmt->close();
}

// Récupération de tous les livres
$books_result = $conn->query("SELECT * FROM Livres ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Bibliothèque en ligne</title>
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
                    <li><a href="admin.php" class="active">Administration</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="admin-section">
            <h2>Administration des livres</h2>
            
            <?php if (!empty($message)): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <div class="admin-forms">
                <!-- Formulaire d'ajout -->
                <div class="form-container">
                    <h3>Ajouter un nouveau livre</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label for="titre">Titre:</label>
                            <input type="text" id="titre" name="titre" required>
                        </div>
                        <div class="form-group">
                            <label for="auteur">Auteur:</label>
                            <input type="text" id="auteur" name="auteur" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea id="description" name="description" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="maison_edition">Maison d'édition:</label>
                            <input type="text" id="maison_edition" name="maison_edition">
                        </div>
                        <div class="form-group">
                            <label for="nombre_exemplaire">Nombre d'exemplaires:</label>
                            <input type="number" id="nombre_exemplaire" name="nombre_exemplaire" value="1" min="1">
                        </div>
                        <button type="submit" name="add_book" class="btn">Ajouter le livre</button>
                    </form>
                </div>
            </div>
            
            <!-- Liste des livres existants -->
            <div class="books-list">
                <h3>Livres existants</h3>
                
                <?php if ($books_result->num_rows > 0): ?>
                    <div class="books-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Titre</th>
                                    <th>Auteur</th>
                                    <th>Maison d'édition</th>
                                    <th>Exemplaires</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($book = $books_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $book['id']; ?></td>
                                        <td><?php echo htmlspecialchars($book['titre']); ?></td>
                                        <td><?php echo htmlspecialchars($book['auteur']); ?></td>
                                        <td><?php echo htmlspecialchars($book['maison_edition']); ?></td>
                                        <td><?php echo $book['nombre_exemplaire']; ?></td>
                                        <td class="actions">
                                            <a href="details.php?id=<?php echo $book['id']; ?>" class="btn small">Voir</a>
                                            <a href="admin.php?delete=<?php echo $book['id']; ?>" class="btn small danger" 
                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce livre?')">Supprimer</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p>Aucun livre dans la base de données.</p>
                <?php endif; ?>
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