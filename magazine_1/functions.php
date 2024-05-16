<?php

// Fonction php pour pouvoir modifier les images .jpeg en .avif et les envoyés dans le dossier pages

// Chemin du dossier contenant les images JPEG
$dossier_jpeg = 'images/sources/';

// Chemin du dossier où les images AVIF seront enregistrées
$dossier_avif = 'images/pages/';

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

//  Recupération des images dans le dossier pages et les redimmensionné grace a GD pour les ajouter dans le dossier thumbails

// Dossier contenant les images originales AVIF
$dossierOrigine = "images/pages/";

// Dossier de destination pour les images redimensionnées
$dossierDestination = "images/thumbails/";

// Définir la hauteur souhaitée pour les images redimensionnées
$nouvelleHauteur = 256;

// Obtenir la liste des fichiers AVIF dans le dossier d'origine
$imagesOriginales = glob($dossierOrigine . "*.avif");

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
$dossier_avif = 'images/pages/';

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
$dossier_avif_2 = 'images/thumbails/';

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

<?php
// Chemin vers le fichier JSON
$file_name = 'data.json';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer le titre du formulaire
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';

    // Charger les données existantes depuis le fichier JSON s'il existe
    $data0 = [];
    if (file_exists($file_name)) {
        $json_data0 = file_get_contents($file_name);
        $data0 = json_decode($json_data0, true);
        // Vérifier si les données sont nulles à cause d'une erreur de décodage
        if (is_null($data0)) {
            $data0 = [];
        }
    }

    // Générer un nouvel ID unique pour la nouvelle entrée
    $new_id = count($data0) + 1;

    // Ajouter le nouveau titre avec un ID unique au tableau de données
    $data0[] = ['id' => $new_id, 'titre' => $title];

    // Convertir les données en format JSON
    $json_data0 = json_encode($data0, JSON_PRETTY_PRINT);

    // Écrire les données JSON dans le fichier
    if (file_put_contents($file_name, $json_data0) === false) {
        // Gérer l'erreur si l'écriture dans le fichier échoue
        die('Erreur lors de l\'écriture dans le fichier JSON');
    }

    // Rediriger l'utilisateur vers la page du formulaire
    header("Location: formulaire.php");
    exit;
}

$id = 2; // Vous pouvez remplacer cette valeur par celle que vous souhaitez utiliser dynamiquement

// Charger les données existantes depuis le fichier JSON s'il existe
$data0 = [];
if (file_exists($file_name)) {
    $json_data0 = file_get_contents($file_name);
    $data0 = json_decode($json_data0, true);
    // Vérifier si les données sont nulles à cause d'une erreur de décodage
    if (is_null($data0)) {
        $data0 = [];
    }
}

// Initialiser le titre de la page par défaut
$pageTitre = "";

// Vérifier si un ID est passé dans l'URL pour récupérer une entrée spécifique
foreach ($data0 as $entree) {
    if ($entree['id'] == $id) {
        $pageTitre = htmlspecialchars($entree['titre'], ENT_QUOTES, 'UTF-8');
        break;
    }
}

// Gestion du téléchargement de fichiers
if (isset($_FILES['file'])) {
    $file = $_FILES['file'];

    // Vérifie s'il y a des erreurs lors du téléchargement
    if ($file['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'images/pdf/';
        $uploadPath = $uploadDir . basename($file['name']);

        // Déplace le fichier téléchargé vers le dossier de destination
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            echo 'Fichier ' . htmlspecialchars($file['name'], ENT_QUOTES, 'UTF-8') . ' téléchargé avec succès.';
        } else {
            echo 'Une erreur est survenue lors de l\'enregistrement du fichier.';
        }
    } else {
        echo 'Une erreur est survenue lors du téléchargement du fichier.';
    }
}
?>
