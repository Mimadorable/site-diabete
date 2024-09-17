<?php
include "cnx.php";
if (isset($_POST['username']) && isset($_POST['password'])) {
    
$nom_utilisateur = $_POST['username'];
$mot_de_passe = $_POST['password'];

// Vérifier les informations de connexion
$sql = "SELECT * FROM utilisateur WHERE nom = '$nom_utilisateur' AND mot_de_passe = '$mot_de_passe'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    // Utilisateur authentifié avec succès

    // Récupérer le type de diabète de l'utilisateur
    $row = $result->fetch_assoc();
    $type_diabete = $row['type'];

    // Récupérer les recettes correspondantes au type de diabète
    $sql_recettes = "SELECT * FROM recettes WHERE type_diabete = '$type_diabete'";
    $result_recettes = $conn->query($sql_recettes);

    if ($result_recettes->num_rows > 0) {
        // Afficher les recettes
        echo "Recettes pour le type de diabète : $type_diabete<br>";

        while ($row_recette = $result_recettes->fetch_assoc()) {
            $recetteId = $row_recette['id_recette'];
                      
            echo '<div class="recipe">';
            echo '<div class="card-box">';
            echo '<div class="recipe-image">';
            echo '<img src="' . $row_recette['image'] . '" alt="Recipe Image">';
            echo '</div>';
            echo '</div>';
            echo '<div class="block">';
            echo '<h2 class="recipe-title">' . $row_recette['nom_recette'] . '</h2>';
            echo '<button class="like-button" data-recette-id="' . $recetteId . '">';
            echo '<i class="fa-solid fa-heart"></i>';
            echo '</button>';
            echo '<div class="recipe-info">';
            echo '<span class="recipe-portion">' . $row_recette['portion'] . '</span>';
            echo '</div>';
            echo '<div class="recipe-steps">';
            echo '<h3 class="recipe-subtitle">Étapes :</h3>';
            echo '<p class="recipe-text">' . $row_recette['etapes'] . '</p>';
            echo '</div>';
            echo '<div class="recipe-ingredients">';
            echo '<h3 class="recipe-subtitle">Ingrédients :</h3>';
            echo '<ul class="recipe-list">';
            
            $bbb = "SELECT unite_de_mesure.unite, recettes_ingredients.quantite, ingredients.nom_ingredient
                    FROM unite_de_mesure 
                    INNER JOIN recettes_ingredients ON unite_de_mesure.id_mesure = recettes_ingredients.id_mesure
                    INNER JOIN ingredients ON recettes_ingredients.id_ingredient = ingredients.id_ingredient
                    WHERE recettes_ingredients.id_recette=?";
            $bt = $link->prepare($bbb);
            $bt->bind_param('i', $row_recette['id_recette']);
            $bt->execute();
            $res = $bt->get_result();
            
            while ($row2 = $res->fetch_array()) {
                echo '<li class="recipe-item">' . $row2['quantite'] . ' ' . $row2['unite'] . ' ' . $row2['nom_ingredient'] . '</li>';
            }
            
            echo '</ul>';
            echo '</div>';
            
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo "Aucune recette disponible pour le type de diabète : $type_diabete";
    }
} else {
    echo "Nom d'utilisateur ou mot de passe incorrect.";
}

$conn->close();}
?>