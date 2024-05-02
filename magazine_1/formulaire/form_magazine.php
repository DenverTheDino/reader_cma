

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de collecte de données</title>
</head>
<body>
    <h1>Formulaire de collecte de données</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="texte">Texte :</label><br>
        <textarea id="texte" name="texte" rows="4" cols="50"></textarea><br><br>
        <label for="pdf">PDF :</label><br>
        <input type="file" id="pdf" name="pdf" accept=".pdf"><br><br>
        <input type="submit" value="Soumettre">
    </form>
</body>
</html>
