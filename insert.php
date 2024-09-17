<head>
<link rel="stylesheet"  href="for.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<header>
        <a href="#" class="logo"><span>D</span>ietFood</a>
        <div class="menuToggle" onclick="toggleMenu();"></div>
        <ul class="navbar">
            <li><a href="index.html#banniere" onclick="toggleMenu();">Accueil</a></li>
            <li><a href="index.html#apropos" onclick="toggleMenu();">A propos</a></li>
            <li>
                <a href="#menu" onmouseenter="showFilter();" onclick="showFilter();" onmouseleave="hideFilter();">Menu</a>
                <div id="menu-filter" class="filter-content" onmouseenter="showFilter();" onmouseleave="hideFilter();">
                    <ul>
                        <li><a href="get_recipes.php?categorie=salades" onclick="getRecipesByCategory('salades');">salades</a></li>
                        <li><a href="get_recipes.php?categorie=plats" onclick="getRecipesByCategory('plats');">plats</a></li>
                        <li><a href="get_recipes.php?categorie=soupes" onclick="getRecipesByCategory('soupes');">soupes</a></li>
                        <li><a href="get_recipes.php?categorie=desserts" onclick="getRecipesByCategory('desserts');">Desserts</a></li>
                        <li><a href="get_recipes.php?categorie=boissons" onclick="getRecipesByCategory('boissons');"> Boissons</a></li>
                    </ul>
                </div>
            </li>
            <li><a href="index.html#expert" onclick="toggleMenu();">Expert</a></li>
            <li><a href="index.html#temoignage" onclick="toggleMenu();">Temoignage</a></li>
            <li><a href="index.html#contact" onclick="toggleMenu();">Contact</a></li>
        </ul>
</header>


<body>
   

<?php


include "cnx.php";
if (isset($_POST["submit"])){
if(isset($_POST["nom"]) && isset($_POST["mot_de_passe"])&& isset($_POST["mail"])&& isset($_POST["type"])&&isset($_POST["gender"])){

    $nom=$_POST["nom"];
    $MotdePasse=$_POST["mot_de_passe"];
    $email=$_POST["mail"];
    $type=$_POST["type"];
    $sex=$_POST["gender"];

    $req=mysqli_query($link,"insert into utilisateur (nom,mot_de_passe,mail,type,sex) values('$nom','$MotdePasse','$email','$type','$sex')");
    if($req){ echo "insertion effectuee "; }
            else{ echo "erreur d'insertion"; }}}

            
      
    // Récupération des recettes correspondantes au type de diabète fourni
    $query = "SELECT recettes.id_recette, recettes.nom_recette, recettes.etapes, recettes.portion,recettes.image
    FROM recettes
    INNER JOIN type_de_diabete ON recettes.id_diabete = type_de_diabete.id_diabete
    WHERE type_de_diabete.nom_diabete = ?";
    $stmt=$link->prepare($query);
    $stmt->bind_param('s', $type);
    $stmt->execute();
    $result=$stmt->get_result();
    ?>
     <div id="recipes-container"></div>
    <div class="recipes">
    <?php while ($row = $result->fetch_array()) {
    $recetteId = $row['id_recette'];
      
    echo '<div class="recipe">';
    echo '<div class="card-box">';
    echo '<div class="recipe-image">';
    echo '<img src="' . $row['image'] . '" alt="Recipe Image">';
    echo '</div>';
    echo '</div>';
    echo '<div class="block">';
    echo '<h2 class="recipe-title">' . $row['nom_recette'] . '</h2>';
    echo '<button class="like-button" data-recette-id="' . $recetteId . '">';
    echo '<i class="fa-solid fa-heart"></i>';
    echo '</button>';
    echo '<div class="recipe-info">';
    echo '<span class="recipe-portion">' . $row['portion'] . '</span>';
    echo '</div>';
    echo '<div class="recipe-steps">';
    echo '<h3 class="recipe-subtitle">Étapes :</h3>';
    echo '<p class="recipe-text">' . $row['etapes'] . '</p>';
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
    $bt->bind_param('i', $row['id_recette']);
    $bt->execute();
    $res = $bt->get_result();
    
    while ($row2 = $res->fetch_array()) {
        echo '<li class="recipe-item">' . $row2['quantite'] . ' ' . $row2['unite'] . ' ' . $row2['nom_ingredient'] . '</li>';
    }
    
    echo '</ul>';
    echo '</div>';
    
    echo '</div>';
    echo '</div>';
    echo '<script>
        document.querySelector(".like-button[data-recette-id=\'' . $recetteId . '\']").addEventListener("click", function() {
            var recetteId = this.getAttribute("data-recette-id");

            // Envoyer une requête AJAX pour insérer le like dans la table "likes"
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "like.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    // Le like a été enregistré avec succès
                    console.log("Le like a été enregistré pour la recette : " + recetteId);
                }
            };
            xhr.send("id_recette=" + encodeURIComponent(recetteId));
        });
     </script>';
} ?>
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
      body: 'id_recette=' + encodeURIComponent(recetteId),
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
    