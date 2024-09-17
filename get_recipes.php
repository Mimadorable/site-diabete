<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <title>Affichage des recettes</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200&display=swap');
       
    body {
        font-family: Poppins;
    }header{
    font-weight: 100;
    position: fixed;
    width: 100%;
    top: 0%;
    left: 0%;
    padding: 20px 10px;
    z-index: 1;
    display: flex;
    justify-content: space-evenly;
    align-items: center;
    transition: 0.5s;
    margin-bottom: 20px;
    /*background-color: #fff;
    border-bottom: #fff;*/
    height: 70px;
}

.logo{
    color: black;
    font-family: Poppins;
    font-size: 2em;
    text-decoration: none;
    font-weight: 400;
}

.navbar{
    display: flex;
    position: relative;
}
.navbar li{
    list-style: none;
}
.navbar a{
    color: black;
    text-decoration: none;
    margin-left: 30px;
    font-family: Poppins;
}



header .navbar li a:hover{
    color: black;
    border-bottom: 2px solid black;
}
#menu-filter {
    position: absolute;
    background: #f9f9f9;
    padding: 10px;
    border: 1px solid #ddd;
    z-index: 1;
    top: 100%;
    left: 0;
    width: 200px;
    height:250px;
    margin-left: 30%;
    display: none;
}

#menu-filter ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

#menu-filter li {
    margin-bottom: 5px;
}

#menu-filter a {
    text-decoration: none;
    color: #333;
}

#menu-filter a:hover {
    color: #000;
}


    .recipes{
 
 position: relative;
 display: block;
 grid-template-columns: 300px 600px;
 border-radius: 5px;
 margin-left: 200px;
 margin-right: 200px;

 
 
}

.recipe-image img {
     width: 400px;
     height: 100%;
     
  
}.recipe{
 display:flex;
 margin-left: 100px;
 margin-top: 170px;
 margin-bottom: 70px;
 box-sizing: content-box;

}
.like-button {
  margin-left: 90%;
  margin-top: 1px;
  right: 15px;
  background: none;
  border: none;
  top: 0;
  cursor: pointer;
  transition: transform 0.3s ease; /* Ajoute une transition de 0.3 seconde avec une courbe d'accélération */
}

.like-button i {
  font-size: 30px;
  color: black; /* Couleur du cœur vide */}

.like-button.liked i {
  color: black; 
  transform: scale(1.2); /* Applique une mise à l'échelle de 1.2 (agrandissement) au clic */
}



 .recipe-image {
    width: 100%;
  height: 100%;
  background-size: cover;
  background-position: center;
  filter:brightness(.9) saturate(1) contrast(1);
 }.card-box {
    width: 350px;
    height: 550px;
    border: 2px solid #ccc;
  border-radius: 8px;
  overflow: hidden;}

 .recipe-content {
   flex: 1 1 auto;
 }.block{
   width: 600px;
    max-width: 600px;
   border-radius: 8px;
   box-shadow: 0 15px 20px;

 }.recipe-title{
   font-weight: 700;
   margin-left:70px;
   margin-right: 10px;
   font-size: large;
   margin-top: 20px;
 }
 .recipe-steps{
   margin-left: 25px;
   font-size: small;
   margin-bottom: 20px;
 }.recipe-info{
   margin-left: 20px;
 }
 .recipe-ingredients{
   margin-left: 25px;
   font-size: small;

 }.recipe-subtitle{
   font-size: medium;
 }

    </style>
</head>
<body>
<header>
        <a href="#" class="logo"><span>D</span>ietFood</a>
        <div class="menuToggle" onclick="toggleMenu();"></div>
        <ul class="navbar">
            <li><a href="index.html#banniere" onclick="toggleMenu();">Accueil</a></li>
            <li><a href="index.html#apropos" onclick="toggleMenu();">A propos</a></li>
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
            <li><a href="index.html#expert" onclick="toggleMenu();">Expert</a></li>
            <li><a href="index.html#temoignage" onclick="toggleMenu();">Temoignage</a></li>
            <li><a href="index.html#contact" onclick="toggleMenu();">Contact</a></li>
        </ul>
    </header>
    <div class="recipes-container">
        <div id="recipes">
            <?php
            // Connexion à la base de données
            $host = 'localhost';
            $db = 'projet';
            $user = 'root';
            $password = '';

            try {
                $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Erreur de connexion à la base de données : " . $e->getMessage();
                exit();
            }

            // Récupération de la catégorie depuis les paramètres GET
            $categorie = $_GET['categorie'];

            // Requête pour récupérer les recettes de la catégorie spécifiée
            $query =  "SELECT recettes.id_recette, recettes.nom_recette, recettes.etapes, recettes.portion, recettes.image
            FROM recettes
            INNER JOIN type_de_diabete ON recettes.id_diabete = type_de_diabete.id_diabete
            JOIN recettes_categories ON recettes.id_recette = recettes_categories.id_recette
            JOIN categories ON recettes_categories.id_cat = categories.id_cat
            WHERE categories.categorie = :categorie";
            $statement = $pdo->prepare($query);
            $statement->bindParam(':categorie', $categorie);
            $statement->execute();
            $recipes = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($recipes as $recipe) {
                   echo' <div class="recipe">';
                   echo'<div class="card-box">';
                   echo'<div class="recipe-image">';
                   echo '<img src="' . $recipe['image'] . '" alt="Recipe Image">';
                   echo '</div>';
                   echo '</div>';
                   echo'<div class="block">';
                   echo '<h2 class="recipe-title">' . $recipe['nom_recette'] . '</h2>';
                   echo '<button class="like-button" data-recipe-id="' . $recipe['id_recette'] . '" onclick="likeRecipe(' . $recipe['id_recette'] . ')">';
                   echo '<i class="fas fa-heart"></i> J\'aime</button>';                   
                    echo '<div class="recipe-info">';
                    echo '<span class="recipe-portion">' . $recipe['portion'] . '</span>';
                    echo '</div>';
                    echo '<div class="recipe-steps">';
                    echo '<h3 class="recipe-subtitle">Étapes :</h3>';
                    echo '<p class="recipe-text">' . $recipe['etapes'] . '</p>';
                    echo '</div>';
                    echo '<div class="recipe-ingredients">';
                    echo '<h3 class="recipe-subtitle">Ingrédients :</h3>';
                    echo '<ul class="recipe-list">';
                
                            // Requête pour récupérer les ingrédients de la recette
                $ingredientsQuery = "SELECT unite_de_mesure.unite, recettes_ingredients.quantite, ingredients.nom_ingredient
                FROM unite_de_mesure 
                INNER JOIN recettes_ingredients ON unite_de_mesure.id_mesure = recettes_ingredients.id_mesure
                INNER JOIN ingredients ON recettes_ingredients.id_ingredient = ingredients.id_ingredient
                WHERE recettes_ingredients.id_recette = :recetteId";
                $ingredientsStatement = $pdo->prepare($ingredientsQuery);
                $ingredientsStatement->bindParam(':recetteId', $recipe['id_recette']);
                $ingredientsStatement->execute();
                $ingredients = $ingredientsStatement->fetchAll(PDO::FETCH_ASSOC);

                foreach ($ingredients as $ingredient) {
                    echo '<li class="recipe-item">' . $ingredient['quantite'] . ' ' . $ingredient['unite'] . ' ' . $ingredient['nom_ingredient'] . '</li>';
                }

                
                echo '</ul>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>
            </div>
            </div>
    <script>
            function likeRecipe(recipeId) {
    // Récupérer le bouton "J'aime" correspondant à l'ID de la recette
    var likeButton = document.querySelector('.like-button[data-recipe-id="' + recipeId + '"]');

    // Effectuer les opérations nécessaires
    console.log('J\'aime la recette avec l\'ID : ' + recipeId);
}

    </script>
    <script type="text/javascript">
     window.addEventListener('scroll', function(){
         const header =document.querySelector('header');
         header.classList.toggle("sticky", window.scrollY > 0 );
     });

     function toggleMenu(){
         const tmenuToggle = document.querySelector('.menuToggle');
         const navbar = document.querySelector('.navbar');
         navbar.classList.toggle('active');
         menuToggle.classList.toggle('active');
     }
 </script>
 <script type="text/javascript">
    const images = document.querySelectorAll(".banniere img");
let currentImage = 0;

setInterval(() => {
  images[currentImage].classList.remove("active");
  currentImage = (currentImage + 1) % images.length;
  images[currentImage].classList.add("active");
}, 3000);
  </script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
      const menuContainer = document.querySelector('.card-container');
      const sliderLeft = document.querySelector('.slider-left');
      const sliderRight = document.querySelector('.slider-right');
  
      let translateValue = 0;
      const slideAmount = 300; // Ajustez la valeur selon la largeur de vos éléments de menu
  
      sliderLeft.addEventListener('click', function () {
        translateValue += slideAmount;
        menuContainer.style.transform = `translateX(${translateValue}px)`;
      });
  
      sliderRight.addEventListener('click', function () {
        translateValue -= slideAmount;
        menuContainer.style.transform = `translateX(${translateValue}px)`;
      });
    });
  </script>
  <Script>
const menuContainer = document.querySelector('.card-container');
const slideAmount = 300; // Ajustez la valeur selon la largeur de vos éléments de menu

menuContainer.addEventListener('mouseenter', () => {
  menuContainer.style.transform = `translateX(-${slideAmount}px)`;
});

menuContainer.addEventListener('mouseleave', () => {
  menuContainer.style.transform = 'translateX(0)';
});
sliderLeft.addEventListener('click', () => {
  if (translateValue < 0) {
    translateValue += slideAmount;
    if (translateValue > 0) {
      translateValue = 0;
    }
    menuContainer.style.transform = `translateX(${translateValue}px)`;
  }
});

</Script>
</body>


