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
    <form action="index.php" method="post" enctype="multipart/form-data">
        <label for="title">Titre :</label>
        <input type="text" id="title" name="title">
        <br><br>
        <label for="file">Télécharger un fichier :</label>
        <input type="file" id="file" name="file">
        <input type="submit" value="Télécharger">
    </form>
</body>
</html>