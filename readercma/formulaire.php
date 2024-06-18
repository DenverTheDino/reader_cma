<?php
include_once'functions.php';
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire pour JSON</title>
    <script>
        function afficherMessageTelechargement() {
            document.getElementById("message").innerText = "Téléchargement en cours, cela risque de prendre du temps si il y a beaucoup d'images dans le fichier ZIP veuillez patientez...";
        }
    </script>
</head>
<body>
    <h2>Formulaire pour Titre du magazine et envoie du fichier ZIP</h2>
    <form action="index.php" method="post" enctype="multipart/form-data">
        <label for="title">Titre :</label>
        <input type="text" id="title" name="title" required>
        <br><br>
        <label for="zip">Sélectionner un fichier ZIP :</label>
        <input type="file" id="zip" name="zip" accept=".zip" required>
        <br><br>
        <input type="submit" value="Télécharger" onclick="afficherMessageTelechargement()">
    </form>
    <p id="message"></p>
</body>
</html>