<?php
include "cnx.php";
session_start();

if (isset($_POST['id_recette'])) {
    $recetteId = $_POST['id_recette'];

    // Assurez-vous que l'utilisateur est connecté
    if (!isset($_SESSION['id_utilisateur'])) {
        // Gérer le cas où l'utilisateur n'est pas connecté
        die("L'utilisateur n'est pas connecté.");
    }

    // Récupérer l'identifiant de l'utilisateur connecté depuis la session
    $userId = $_SESSION['id_utilisateur'];

    // Insérer le like dans la table "likes"
    $insertQuery = "INSERT INTO likes (id_recette, id_utilisateur) VALUES (?, ?)";
    $stmt = $link->prepare($insertQuery);

    $stmt->bind_param('ii', $recetteId, $userId);
    $stmt->execute();

    // Vérifiez si l'insertion s'est déroulée avec succès
    if ($stmt->affected_rows > 0) {
        // L'insertion du like a réussi
        echo "Le like a été enregistré avec succès.";
    } else {
        // L'insertion du like a échoué
        echo "Une erreur s'est produite lors de l'enregistrement du like.";
    }

    $stmt->close();
} else {
    echo "Veuillez fournir l'identifiant de la recette.";
}

$link->close();
?>
