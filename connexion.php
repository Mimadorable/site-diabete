<head>
<link rel="stylesheet"  href="for.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<header>
        <a href="#" class="logo"><span>D</span>ietFood</a>
        <div class="menuToggle" onclick="toggleMenu();"></div>
        <ul class="navbar">
            <li><a href="#banniere" onclick="toggleMenu();">Accueil</a></li>
            <li><a href="#apropos" onclick="toggleMenu();">A propos</a></li>
            <li>
                <a href="#menu" onmouseenter="showFilter();" onclick="showFilter();" onmouseleave="hideFilter();">Menu</a>
                <div id="menu-filter" class="filter-content" onmouseenter="showFilter();" onmouseleave="hideFilter();">
                    <ul><li><a href="get_recipes.php?categorie=salades" onclick="getRecipesByCategory('salades');">salades</a></li>
                        <li><a href="get_recipes.php?categorie=plats" onclick="getRecipesByCategory('plats');">plats</a></li>
                        <li><a href="get_recipes.php?categorie=soupes" onclick="getRecipesByCategory('soupes');">soupes</a></li>
                        <li><a href="get_recipes.php?categorie=desserts" onclick="getRecipesByCategory('desserts');">Desserts</a></li>
                        <li><a href="get_recipes.php?categorie=boissons" onclick="getRecipesByCategory('boissons');"> Boissons</a></li>
                    </ul>
                </div>
            </li>
            <li><a href="#expert" onclick="toggleMenu();">Expert</a></li>
            <li><a href="#temoignage" onclick="toggleMenu();">Temoignage</a></li>
            <li><a href="#contact" onclick="toggleMenu();">Contact</a></li>
        </ul>
    </header>
<body>
<?php
include "cnx.php";
if (isset($_POST['username']) && isset($_POST['password'])) {
    
$nom_utilisateur = $_POST['username'];
$mot_de_passe = $_POST['password'];

//Vérifier les informations de connexion
$sql = "SELECT * FROM utilisateur WHERE nom = '$nom_utilisateur' AND mot_de_passe = '$mot_de_passe'";
$result = $link->query($sql);

if ($result->num_rows == 1) {
    // Utilisateur authentifié avec succès

    // Récupérer le type de diabète de l'utilisateur
    $row = $result->fetch_assoc();
    $type_diabete = $row['type'];

    // Récupérer les recettes correspondantes au type de diabète
    $sql_recettes = "SELECT * FROM recettes WHERE id_diabete = '$type_diabete'";
    $result_recettes = $link->query($sql_recettes);

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

$link->close();}

    
?>
<script>
// Récupérer tous les boutons "J'aime"
const likeButtons = document.querySelectorAll('.like-button');

// Parcourir tous les boutons "J'aime" et ajouter un gestionnaire d'événements
likeButtons.forEach((button) => {
  button.addEventListener('click', () => {
    // Changer la couleur du bouton lorsque l'utilisateur clique dessus
    button.classList.toggle('liked');

    // Récupérer l'identifiant de la recette associée au bouton "J'aime"
    const recetteId = button.getAttribute('data-recette-id');

    // Envoyer une requête au serveur pour enregistrer le "like" de l'utilisateur
    fetch('/like.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: 'recetteId=' + encodeURIComponent(recetteId),
    });
  });
});


</script>




    <script>
        var menuFilter = document.getElementById("menu-filter");
        var filterTimeout;

        function toggleMenu() {
            var navbar = document.querySelector('.navbar');
            if (navbar.style.display === 'block') {
                navbar.style.display = 'none';
            } else {
                navbar.style.display = 'block';
            }
        }

        function showFilter() {
            clearTimeout(filterTimeout);
            menuFilter.style.display = "block";
        }
        function hideFilter() {
            filterTimeout = setTimeout(function() {
                menuFilter.style.display = "none";
            }, 300);
        }

        function getRecipesByCategory(categorie) {
        $.ajax({
            url: 'get_recipes.php',
            method: 'GET',
            data: { categorie: categorie },
            dataType: 'json',
            success: function (recipes) {
                var recipesList = $('#recipes-list');
                recipesList.empty();

                for (var i = 0; i < recipes.length; i++) {
                    var recipe = recipes[i];
                    var recipeItem = $('<li>').text(recipe.nom_recette);
                    recipesList.append(recipeItem);
                }
            },
            error: function (xhr, status, error) {
                console.log('Une erreur s\'est produite lors de la récupération des recettes.');
            }
        });
    }

    function displayRecipes(recipes) {
        var recipesContainer = document.getElementById("recipes-container");
        recipesContainer.innerHTML = "";

        if (recipes.length > 0) {
            for (var i = 0; i < recipes.length; i++) {
                var recipe = recipes[i];
                var recipeElement = document.createElement("div");
                recipeElement.innerHTML = "<h3>" + recipe.nom_recette + "</h3><p>" + recipe.description_recette + "</p>";
                recipesContainer.appendChild(recipeElement);
            }
        } else {
            var noRecipesMessage = document.createElement("p");
            noRecipesMessage.textContent = "Aucune recette disponible pour cette catégorie.";
            recipesContainer.appendChild(noRecipesMessage);
        }
    }
</script>
</body>
    