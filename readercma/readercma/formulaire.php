<?php
include_once'functions.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupère les données du formulaire
    $titre = $_POST['title'];
    $zipFile = $_FILES['zip']['tmp_name'];
    $zipName = $_FILES['zip']['name'];

    // Charge les données existantes depuis le fichier JSON et génère un ID unique séquentiel
    $fichierJSON = 'data.json';
    $donneesExistantes = [];
    if (file_exists($fichierJSON)) {
        $jsonData = file_get_contents($fichierJSON);
        $donneesExistantes = json_decode($jsonData, true);
    }
    $id = count($donneesExistantes) + 1;

    // Crée un tableau avec les nouvelles données
    $nouvellesDonnees = [
        'id' => $id,
        'title' => $titre
    ];

    // Ajoute les nouvelles données aux données existantes
    $donneesExistantes[] = $nouvellesDonnees;

    // Enregistre le tableau de données dans le fichier JSON
    if (file_put_contents($fichierJSON, json_encode($donneesExistantes, JSON_PRETTY_PRINT)) === false) {
        die("Erreur lors de l'enregistrement des données JSON");
    }

    // Chemin du dossier de support
    $dossierSupport = 'supports/' . $id;

    // Crée le dossier de support
    if (!file_exists($dossierSupport)) {
        if (!mkdir($dossierSupport, 0777, true)) {
            die("Erreur lors de la création du dossier de support");
        }
    }

    // Déplace le fichier ZIP téléchargé dans le dossier de support
    $cheminDestination = $dossierSupport . '/' . $zipName;
    if (!move_uploaded_file($zipFile, $cheminDestination)) {
        die("Erreur lors du téléchargement du fichier ZIP");
    }

    // Décompresse le fichier ZIP
    $cheminSources = $dossierSupport . '/sources';
    if (!file_exists($cheminSources)) {
        mkdir($cheminSources, 0777, true);
    }

    $zip = new ZipArchive;
    if ($zip->open($cheminDestination) === TRUE) {
        $zip->extractTo($cheminSources);
        $zip->close();
    } else {
        die("Erreur lors de la décompression du fichier ZIP");
    }

    // Supprime le fichier ZIP après extraction
    unlink($cheminDestination);

    // Crée le dossier "pages" au même niveau que "sources"
    $cheminPages = $dossierSupport . '/pages';
    if (!file_exists($cheminPages)) {
        mkdir($cheminPages, 0777, true);
    }

    echo "Le fichier ZIP a été téléchargé, décompressé et les images ont été converties en AVIF avec succès.";
}

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
        <input type="text" id="title" name="title" required>
        <br><br>
        <label for="zip">Sélectionner un fichier ZIP :</label>
        <input type="file" id="zip" name="zip" accept=".zip" required>
        <br><br>
        <input type="submit" value="Télécharger">
    </form>
</body>
</html>