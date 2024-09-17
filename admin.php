<?php
include "cnx.php";

// Traitement de la création d'un nouvel utilisateur
if (isset($_POST["submit"])) {
    if (isset($_POST["nom"]) && isset($_POST["mot_de_passe"]) && isset($_POST["mail"]) && isset($_POST["type"]) && isset($_POST["gender"])) {
        $nom = $_POST["nom"];
        $motDePasse = $_POST["mot_de_passe"];
        $email = $_POST["mail"];
        $type = $_POST["type"];
        $sexe = $_POST["gender"];

        $req = mysqli_query($link, "INSERT INTO utilisateur (nom, mot_de_passe, mail, type, sexe) VALUES ('$nom', '$motDePasse', '$email', '$type', '$sexe')");

        if ($req) {
            echo "Insertion effectuée.";
        } else {
            echo "Erreur d'insertion.";
        }
    }
}

// Affichage de la liste des utilisateurs
$query = "SELECT * FROM utilisateur";
$result = mysqli_query($link, $query);
?>

<!-- Affichage des utilisateurs dans le formulaire -->
<section>
    <h2>Gestion des utilisateurs</h2>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>E-mail</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?php echo $row["nom"]; ?></td>
                <td><?php echo $row["mail"]; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</section>
<style>
    section {
  margin-top: 20px;
}

h2 {
  font-size: 20px;
  font-weight: bold;
}

table {
  width: 100%;
  border-collapse: collapse;
}

table th, table td {
  padding: 8px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

table th {
  background-color: #f5f5f5;
}

table tr:nth-child(even) {
  background-color: #f9f9f9;
}

table tr:hover {
  background-color: #f1f1f1;
}
</style>

<?php
include "cnx.php";

// Récupération des recettes
$query = "SELECT nom_recette, 'portion', etapes, image FROM recettes";
$result = mysqli_query($link, $query);
?>

<!-- Affichage des recettes -->
<section>
    <h2>Gestion des recettes</h2>
    <ul>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <li>
                <h3><?php echo $row["nom_recette"]; ?></h3>
                <p>Portion : <?php echo $row["portion"]; ?></p>
                <p>Étapes : <?php echo $row["etapes"]; ?></p>
                <img src="<?php echo $row["image"]; ?>" alt="Image de la recette">
            </li>
        <?php endwhile; ?>
    </ul>
</section>

