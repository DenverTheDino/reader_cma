<?php
// Lire le contenu du fichier JSON existant
$jsonFile = 'data.json';
$existingReports = json_decode(file_get_contents($jsonFile), true);

// Vérifier si le fichier JSON est valide
if ($existingReports === null) {
    die('Le fichier JSON est invalide.');
}

// Parcourir les rapports existants
foreach ($existingReports as &$report) {
    $title = $report['titre'];

    // Nettoyer le titre avec preg_replace
    $cleanedTitle = preg_replace('/[^a-zA-Z0-9_-]/', '_', $title);

    // Créer le chemin du dossier "pdf"
    $pdfDir = 'support/' . $cleanedTitle . '/pdf/';

    // Vérifier si le dossier "pdf" n'existe pas, alors le créer
    if (!is_dir($pdfDir)) {
        if (!mkdir($pdfDir, 0777, true)) {
            die("Impossible de créer le dossier PDF pour le rapport $title.");
        }
        echo "Le dossier PDF pour le rapport $title a été créé avec succès.<br>";

        // Conversion de PDF en JPEG
        $pdfFile = '/pdf/'; // Remplacez par le chemin vers votre fichier PDF
        $jpegFile = $pdfDir . $cleanedTitle . '.jpg';
        convertPdfToJpeg($pdfFile, $jpegFile);

        // Conversion de JPEG en AVIF
        $avifFile = $pdfDir . $cleanedTitle . '.avif';
        convertJpegToAvif($jpegFile, $avifFile);

        // Redimensionner les AVIF
        resizeAvif($avifFile, $pdfDir);

        // Mettre à jour les informations dans le tableau des rapports existants
        $report['pdfDir'] = $pdfDir;
        $report['jpegFile'] = $jpegFile;
        $report['avifFile'] = $avifFile;
    } else {
        echo "Le dossier PDF pour le rapport $title existe déjà.<br>";
    }
}

// Écrire le tableau mis à jour dans le fichier JSON
file_put_contents($jsonFile, json_encode($existingReports, JSON_PRETTY_PRINT));

// Fonction pour convertir un PDF en JPEG
function convertPdfToJpeg($pdfFile, $jpegFile) {
    $image = new Imagick();
    $image->setResolution(300, 300);
    $image->readImage($pdfFile);
    $image->setImageFormat('jpeg');
    $image->writeImage($jpegFile);
    $image->clear();
    $image->destroy();
}

// Fonction pour convertir un JPEG en AVIF
function convertJpegToAvif($jpegFile, $avifFile) {
    exec("magick convert $jpegFile $avifFile");
}

// Fonction pour redimensionner un AVIF avec une hauteur maximale de 256 pixels
function resizeAvif($avifFile, $outputDir) {
    $image = new Imagick($avifFile);
    $image->resizeImage(0, 256, Imagick::FILTER_LANCZOS, 1);
    $image->writeImage($outputDir . basename($avifFile));
    $image->clear();
    $image->destroy();
}
?>