<?php
// Vérifie si un fichier a été téléchargé
if(isset($_FILES['file'])) {
    $file = $_FILES['file'];
    
    // Vérifie s'il y a des erreurs lors du téléchargement
    if($file['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'miniatures/pdf/';
        $uploadPath = $uploadDir . basename($file['name']);
        
        // Déplace le fichier téléchargé vers le dossier de destination
        if(move_uploaded_file($file['tmp_name'], $uploadPath)) {
            echo 'Fichier ' . $file['name'] . ' téléchargé avec succès.';
            
        } else {
            echo 'Une erreur est survenue lors de l\'enregistrement du fichier.';
        }
    } else {
        echo 'Une erreur est survenue lors du téléchargement du fichier.';
    }
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Téléchargement de fichiers PDF</title>
</head>
<body>
    <h2>Téléchargement de fichiers PDF</h2>
    <form action="formulairepdf.php" method="post" enctype="multipart/form-data">
        <input type="file" name="file" accept=".pdf" multiple>
        <button type="submit">Télécharger</button>
    </form>
</body>
</html>