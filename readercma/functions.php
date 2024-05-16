<?php
// Récupérer le titre du formulaire et le fichier PDF téléchargé
$title = $_POST['title']; 
$pdf_file = $_FILES['pdf']; 

// Chemin du dossier principal où les dossiers seront créés
$main_dir = 'readercma/support'; 

// Appeler la fonction pour créer un nouveau dossier et déplacer le PDF
$new_directory = createDirectoryAndMovePdf($main_dir, $pdf_file, $title);

// Si le dossier a été créé avec succès
if ($new_directory !== null) {
    // Appeler la fonction pour traiter les dossiers (conversion, redimensionnement, etc.)
    processDirectories($main_dir);
}
// Fonction pour convertir PDF en JPEG avec Imagick
function convertPdfToJpeg($pdf_path, $output_dir) {
    $imagick = new Imagick();
    $imagick->setResolution(300, 300); // Définir la résolution du PDF (dpi)
    $imagick->readImage($pdf_path);

    foreach ($imagick as $index => $page) {
        $page->setImageFormat('jpeg');
        $jpeg_path = $output_dir . '/' . basename($pdf_path, '.pdf') . "_page_" . ($index + 1) . '.jpg';
        $page->writeImage($jpeg_path);
    }
    $imagick->clear();
    $imagick->destroy();
}

// Fonction pour convertir JPEG en AVIF avec GD
function convertJpegToAvif($jpeg_path, $avif_path) {
    $image = imagecreatefromjpeg($jpeg_path);
    if (function_exists('imageavif')) {
        imageavif($image, $avif_path);
    } else {
        // Si la fonction imageavif n'est pas disponible, utiliser une autre méthode
        ob_start();
        imagegd($image);
        $gd_image_data = ob_get_clean();
        file_put_contents($avif_path, $gd_image_data);
    }
    imagedestroy($image);
}

// Fonction pour redimensionner l'image avec GD
function resizeImage($input_path, $output_path, $new_height) {
    $image = imagecreatefromavif($input_path);
    $original_width = imagesx($image);
    $original_height = imagesy($image);
    $new_width = ($new_height / $original_height) * $original_width;

    $new_image = imagecreatetruecolor($new_width, $new_height);
    imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);

    imageavif($new_image, $output_path);

    imagedestroy($image);
    imagedestroy($new_image);
}

// Fonction pour créer un nouveau dossier avec le titre du formulaire et déplacer le PDF téléchargé
function createDirectoryAndMovePdf($main_dir, $pdf_file, $title) {
    // Supprimer les espaces et convertir en minuscules
    $directory_name = strtolower(str_replace(' ', '-', $title));
    
    $new_dir = $main_dir . '/' . $directory_name;
    
    // Vérifier si le dossier existe déjà
    if (!is_dir($new_dir)) {
        // Créer le nouveau dossier
        if (mkdir($new_dir, 0777, true)) {
            // Créer le sous-dossier "pdf" s'il n'existe pas déjà
            $pdf_dir = $new_dir . '/pdf';
            if (!is_dir($pdf_dir)) {
                mkdir($pdf_dir, 0777, true);
            }
            
            // Déplacer le PDF téléchargé dans le dossier "pdf"
            $pdf_path = $pdf_dir . '/' . basename($pdf_file['name']);
            move_uploaded_file($pdf_file['tmp_name'], $pdf_path);
            
            echo "Le dossier '$directory_name' a été créé avec succès.";
            return $new_dir; // Retourner le chemin du nouveau dossier créé
        } else {
            echo "Erreur lors de la création du dossier.";
        }
    } else {
        echo "Le dossier '$directory_name' existe déjà.";
    }
    return null; // Retourner null si une erreur s'est produite
}

// Fonction principale pour traiter les dossiers
function processDirectories($main_dir) {
    // Récupérer tous les sous-dossiers dynamiquement
    $sub_dirs = glob($main_dir . '/*', GLOB_ONLYDIR);

    foreach ($sub_dirs as $sub_dir) {
        $pdf_dir = $sub_dir . '/pdf';
        $jpeg_dir = $sub_dir . '/images';
        $avif_dir = $sub_dir . '/pages';
        $thumbs_dir = $sub_dir . '/thumbnails';

        // Créer les dossiers s'ils n'existent pas
        if (!file_exists($jpeg_dir)) mkdir($jpeg_dir, 0777, true);
        if (!file_exists($avif_dir)) mkdir($avif_dir, 0777, true);
        if (!file_exists($thumbs_dir)) mkdir($thumbs_dir, 0777, true);

        // Convertir PDF en JPEG
        $pdf_files = glob($pdf_dir . '/*.pdf');
        foreach ($pdf_files as $pdf_file) {
            convertPdfToJpeg($pdf_file, $jpeg_dir);
        }

        // Convertir JPEG en AVIF
        $jpeg_files = glob($jpeg_dir . '/*.jpg');
        foreach ($jpeg_files as $jpeg_file) {
            $avif_path = $avif_dir . '/' . basename($jpeg_file, '.jpg') . '.avif';
            convertJpegToAvif($jpeg_file, $avif_path);
        }

        // Redimensionner les images AVIF pour créer des vignettes
        $avif_files = glob($avif_dir . '/*.avif');
        foreach ($avif_files as $avif_file) {
            $thumb_path = $thumbs_dir . '/' . basename($avif_file);
            resizeImage($avif_file, $thumb_path, 256); // Taille de vignette souhaitée
        }
    }

    echo "Traitement terminé pour tous les dossiers.";
}



// Fonction pour nettoyer le titre et le convertir en un nom de dossier valide
function slugify($text) {
    // Remplacer les caractères non-lettres ou chiffres par un tiret
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    // Translitérer (convertir les accents en lettres simples)
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // Supprimer les caractères indésirables
    $text = preg_replace('~[^-\w]+~', '', $text);

    
}