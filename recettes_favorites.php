<?php
// Inclure le fichier de configuration de la base de données
include 'cnx.php';


if (isset($_SESSION['utilisateurId'])) {
    // Récupérer l'identifiant de l'utilisateur actuellement connecté
    $utilisateurId = $_SESSION['utilisateurId'];

    // Récupérer les recettes favorites de l'utilisateur depuis la base de données
    $query = "SELECT recettes.id_recette, recettes.nom_recette, recettes.etapes, recettes.portion, recettes.image
              FROM recettes
              INNER JOIN likes ON recettes.id_recette = likes.id_recette
              WHERE likes.id_utilisateur = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param('i', $utilisateurId);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>
    
   
    <div class="recipes">
        <?php
        while ($row = $result->fetch_array()) {
            
        }
        ?>
    </div>

    <?php
    // Fermer la connexion à la base de données
    $link->close();
} else {
    // Rediriger vers la page de connexion ou afficher un message d'erreur, car l'utilisateur n'est pas connecté
    header('Location: connexion.php');
    exit;
}
?>
