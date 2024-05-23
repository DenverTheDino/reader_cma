<?php



// Fonction pour convertir les images JPEG en AVIF
function convertirJpegEnAvif($cheminSources, $cheminPages) {
    // Liste des fichiers dans le dossier
    $fichiers = scandir($cheminSources);

    foreach ($fichiers as $fichier) {
        $cheminFichier = $cheminSources . '/' . $fichier;

        // Si c'est un fichier JPEG
        if (is_file($cheminFichier) && strtolower(pathinfo($fichier, PATHINFO_EXTENSION)) === 'jpeg') {
            // Charger l'image JPEG
            $image = imagecreatefromjpeg($cheminFichier);

            if ($image === false) {
                echo "Erreur lors du chargement de l'image JPEG: " . $cheminFichier;
                continue;
            }

            // Chemin du fichier AVIF
            $cheminAvif = $cheminPages . '/' . pathinfo($fichier, PATHINFO_FILENAME) . '.avif';

            // Convertir en AVIF et enregistrer
            if (!imageavif($image, $cheminAvif)) {
                echo "Erreur lors de la conversion en AVIF: " . $cheminFichier;
            }

            // Libérer la mémoire
            imagedestroy($image);
        }
    }
}
?>
