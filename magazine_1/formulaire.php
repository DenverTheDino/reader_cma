<?php
include_once 'functions.php'
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire pour JSON</title>
</head>
<body>
    <h2>Formulaire pour JSON</h2>
    <form action="formulaire.php" method="post">
        <label for="titre">Titre :</label>
        <input type="text" id="title" name="title" required><br>
        
        <input type="submit" value="Enregistrer le titre">
    </form>
</body>
</html>