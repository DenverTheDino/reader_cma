<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer l'ID et le titre à partir du formulaire
    $id = htmlspecialchars($_POST['id']);
    $title = htmlspecialchars($_POST['title']);
    
    // Remplacer les caractères non valides pour les noms de dossiers
    $titleDir = preg_replace('/[^a-zA-Z0-9_-]/', '_', $title);
    $uploadDir = 'support/' . $titleDir . '/';
    
    // Créer le dossier du titre s'il n'existe pas
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    // Créer les sous-dossiers si nécessaire
    $pdfDir = $uploadDir . 'pdf/';
    $sourcesDir = $uploadDir . 'sources/';
    $pagesDir = $uploadDir . 'pages/';
    $thumbnailsDir = $uploadDir . 'thumbnails/';
    
    if (!is_dir($pdfDir)) mkdir($pdfDir, 0777, true);
    if (!is_dir($sourcesDir)) mkdir($sourcesDir, 0777, true);
    if (!is_dir($pagesDir)) mkdir($pagesDir, 0777, true);
    if (!is_dir($thumbnailsDir)) mkdir($thumbnailsDir, 0777, true);
    
    // Téléchargement et traitement du fichier PDF
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        if ($fileExtension === 'pdf') {
            // Déplacement du PDF dans le dossier correspondant
            $pdfDestPath = $pdfDir . $fileName;
            move_uploaded_file($fileTmpPath, $pdfDestPath);
            
            // Conversion PDF en JPEG
            $image = new Imagick();
            $image->setResolution(300, 300);
            $image->readImage($pdfDestPath);
            $image->setImageFormat('jpeg');
            $image->writeImages($sourcesDir . pathinfo($fileName, PATHINFO_FILENAME) . '.jpg', false);
            $image->clear();
            $image->destroy();
            
            // Conversion JPEG en AVIF
            $jpegFilePath = $sourcesDir . pathinfo($fileName, PATHINFO_FILENAME) . '.jpg';
            $avifFileName = pathinfo($fileName, PATHINFO_FILENAME) . '.avif';
            exec("magick convert $jpegFilePath $pagesDir$avifFileName");
            
            // Redimensionnement des images à une hauteur maximale de 256px
            $avifFilePath = $pagesDir . $avifFileName;
            list($width, $height) = getimagesize($avifFilePath);
            $newHeight = min(256, $height);
            $newWidth = ($width / $height) * $newHeight;
            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
            $originalImage = imagecreatefromavif($avifFilePath);
            imagecopyresampled($resizedImage, $originalImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imageavif($resizedImage, $thumbnailsDir . $avifFileName);
            imagedestroy($resizedImage);
            imagedestroy($originalImage);
            
            echo "Le fichier PDF a été téléchargé et traité avec succès.";
        } else {
            echo "Seuls les fichiers PDF sont autorisés.";
        }
    } else {
        echo "Aucun fichier n'a été téléchargé ou une erreur s'est produite.";
    }
}
?>
<?php
// Vérifier si l'ID est présent dans l'URL
if (isset($_GET['id'])) {
    $id = htmlspecialchars($_GET['id']);
    
    // Vérifier si le dossier correspondant à l'ID existe
    $dossier_avif = 'support/' . $id . '/pages/';

    if (is_dir($dossier_avif)) {
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

        // Afficher les données JSON
        echo $json_data;
    } else {
        echo "Le dossier correspondant à l'ID spécifié n'existe pas.";
    }
} else {
    echo "L'ID n'est pas spécifié dans l'URL.";
}
?>