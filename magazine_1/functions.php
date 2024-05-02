


<?php

// Fonction php pour pouvoir modifier les images .jpeg en .avif et les envoyés dans le dossier pages


// Chemin du dossier contenant les images JPEG
$dossier_jpeg = 'miniatures/sources/';

// Chemin du dossier où les images AVIF seront enregistrées
$dossier_avif = 'miniatures/pages/';

// Liste des fichiers JPEG dans le dossier
$images_jpeg = glob($dossier_jpeg . '*.jpg');

// Boucler à travers chaque image JPEG
foreach ($images_jpeg as $image_jpeg) {
    // Nom de l'image sans extension
    $nom_image = pathinfo($image_jpeg, PATHINFO_FILENAME);

    // Chemin de l'image AVIF
    $chemin_image_avif = $dossier_avif . $nom_image . '.avif';

    // Vérifier si l'image AVIF n'existe pas déjà
    if (!file_exists($chemin_image_avif)) {
        // Charger l'image JPEG
        $image = imagecreatefromjpeg($image_jpeg);

        // Convertir l'image en AVIF
        if (function_exists('imageavif')) {
            if (imageavif($image, $chemin_image_avif)) {
                // echo "L'image $nom_image a été convertie en AVIF avec succès.\n";
            } else {
                // echo "La conversion de l'image $nom_image en AVIF a échoué.\n";
            }
        } else {
        //     echo "La fonction imageavif n'est pas disponible. Assurez-vous que l'extension AVIF est installée.\n";
         }

        // Libérer la mémoire de l'image GD
        imagedestroy($image);
    } else {
        // echo "L'image AVIF $nom_image existe déjà.\n";
    }
}
?>

<?php
//  Recupération des images dans le dossier pages et les redimmensionné grace a GD pour les ajouter dans le dossier thumbails



// Dossier contenant les images originales AVIF
$dossierOrigine = "miniatures/pages/";

// Dossier de destination pour les images redimensionnées
$dossierDestination = "miniatures/thumbails/";

// Définir la hauteur souhaitée pour les images redimensionnées
$nouvelleHauteur = 256;

// Obtenir la liste des fichiers AVIF dans le dossier d'origine
$imagesOriginales = glob($dossierOrigine . "*.avif");

// Parcourir chaque image et redimensionner puis transférer si elle n'existe pas déjà dans le dossier de destination
// Parcourir chaque image et redimensionner puis transférer si elle n'existe pas déjà dans le dossier de destination
foreach ($imagesOriginales as $imageOriginale) {
    // Nom de l'image dans le dossier de destination
    $nomImage = basename($imageOriginale);

    // Vérifier si l'image existe déjà dans le dossier de destination
    if (!file_exists($dossierDestination . $nomImage)) {
        // Créer une ressource d'image pour l'image AVIF
        $image = imagecreatefromavif($imageOriginale);

        // Obtenir les dimensions de l'image originale
        $largeurOriginale = imagesx($image);
        $hauteurOriginale = imagesy($image);

        // Calculer la nouvelle largeur en maintenant le ratio
        $ratio = $hauteurOriginale / $nouvelleHauteur;
        $nouvelleLargeur = $largeurOriginale / $ratio;

        // Créer une nouvelle image avec les dimensions spécifiées
        $nouvelleImage = imagecreatetruecolor($nouvelleLargeur, $nouvelleHauteur);

        // Redimensionner l'image originale vers la nouvelle image
        imagecopyresampled($nouvelleImage, $image, 0, 0, 0, 0, $nouvelleLargeur, $nouvelleHauteur, $largeurOriginale, $hauteurOriginale);

        // Sauvegarder l'image redimensionnée dans le dossier de destination
        $cheminDestination = $dossierDestination . $nomImage;
        imageavif($nouvelleImage, $cheminDestination);

        // Libérer la mémoire en supprimant les ressources d'image
        imagedestroy($image);
        imagedestroy($nouvelleImage);

        // echo "L'image $nomImage a été redimensionnée et transférée avec succès vers : $cheminDestination<br>";
    } else {
        // echo "L'image $nomImage existe déjà dans le dossier de destination. Passant à l'image suivante.<br>";
    }
}

// echo "Toutes les images ont été vérifiées et les nouvelles ont été redimensionnées et transférées si nécessaire.";

?>

<!-- recupération des images .avif du dossier pages et les encodés en JSON -->
<?php
// Chemin du dossier où les images AVIF seront enregistrées
$dossier_avif = 'miniatures/pages/';

// Liste des fichiers AVIF dans le dossier
$images_avif = glob($dossier_avif . '*.avif');

// Tableau pour stocker les chemins des images AVIF
$chemins_images_avif = array();

// Boucler à travers chaque image AVIF
foreach ($images_avif as $image_avif) {
    // Ajouter le chemin de l'image AVIF au tableau
    $chemins_images_avif[] = $dossier_avif . basename($image_avif);
}

// Encoder le tableau des chemins d'images AVIF en JSON
$json_data = json_encode($chemins_images_avif);
?>


<?php
// Chemin du dossier où les images AVIF seront enregistrées pour le deuxième Swiper
$dossier_avif_2 = 'miniatures/thumbails/';

// Liste des fichiers AVIF dans le dossier pour le deuxième Swiper
$images_avif_2 = glob($dossier_avif_2 . '*.avif');

// Tableau pour stocker les chemins des images AVIF pour le deuxième Swiper
$chemins_images_avif_2 = array();

// Boucler à travers chaque image AVIF pour le deuxième Swiper
foreach ($images_avif_2 as $image_avif_2) {
    // Ajouter le chemin de l'image AVIF au tableau pour le deuxième Swiper
    $chemins_images_avif_2[] = $dossier_avif_2 . basename($image_avif_2);
}

// Encoder le tableau des chemins d'images AVIF en JSON pour le deuxième Swiper
$json_data_2 = json_encode($chemins_images_avif_2);
?>



