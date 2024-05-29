<?php
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

        // Ajoutez cet appel à la fonction pour renommer les images
        renommerImages($cheminSources); // Ajout de cette ligne
    } else {
        die("Erreur lors de la décompression du fichier ZIP");
    }

    // Supprime le fichier ZIP après extraction
    unlink($cheminDestination);

    // Crée les dossiers "pages" et "thumbnails" au même niveau que "sources"
    $cheminPages = $dossierSupport . '/pages';
    $cheminThumbnails = $dossierSupport . '/thumbnails';
    if (!file_exists($cheminPages)) {
        mkdir($cheminPages, 0777, true);
    }
    if (!file_exists($cheminThumbnails)) {
        mkdir($cheminThumbnails, 0777, true);
    }

    // Convertit les images JPEG en AVIF ou WebP
    convertirJpegEnAvifOuWebp($cheminSources, $cheminPages);

    // Redimensionne les images dans le dossier "pages" et les sauvegarde dans le dossier "thumbnails"
    redimensionnerImages($cheminPages, $cheminThumbnails, 256);

    echo "Le fichier ZIP a été téléchargé, décompressé, les images ont été converties en AVIF (ou WebP) et redimensionnées avec succès.";
}
function renommerImages($cheminSources) {
    $fichiers = scandir($cheminSources);
    $index = 1;

    foreach ($fichiers as $fichier) {
        $cheminFichier = $cheminSources . '/' . $fichier;
        $extension = strtolower(pathinfo($fichier, PATHINFO_EXTENSION));

        // Filtrer uniquement les fichiers d'image
        if (is_file($cheminFichier) && in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'])) {
            // Formater le nouveau nom de fichier avec une numérotation séquentielle
            $nouveauNom = sprintf("%03d", $index) . '.' . $extension;

            // Chemin du nouveau fichier avec le nouveau nom
            $cheminNouveauFichier = $cheminSources . '/' . $nouveauNom;

            // Renommer le fichier
            rename($cheminFichier, $cheminNouveauFichier);

            // Incrémenter l'index pour le prochain fichier
            $index++;
        }
    }
}


function convertirJpegEnAvifOuWebp($cheminSources, $cheminPages) {
    // Vérifier si la fonction imageavif existe
    $supportAvif = function_exists('imageavif');

    // Liste des fichiers dans le dossier
    $fichiers = scandir($cheminSources);

    foreach ($fichiers as $fichier) {
        $cheminFichier = $cheminSources . '/' . $fichier;

        // Si c'est un fichier JPEG
        if (is_file($cheminFichier) && in_array(strtolower(pathinfo($fichier, PATHINFO_EXTENSION)), ['jpeg', 'jpg'])) {
            // Charger l'image JPEG
            $image = imagecreatefromjpeg($cheminFichier);

            if ($image === false) {
                // echo "Erreur lors du chargement de l'image JPEG: " . $cheminFichier . "\n";
                continue;
            }

            if ($supportAvif) {
                // Chemin du fichier AVIF
                $cheminAvif = $cheminPages . '/' . pathinfo($fichier, PATHINFO_FILENAME) . '.avif';

                // Convertir en AVIF et enregistrer
                if (!imageavif($image, $cheminAvif)) {
                    // echo "Erreur lors de la conversion en AVIF: " . $cheminFichier . "\n";
                } else {
                    // echo "Image convertie en AVIF: " . $cheminAvif . "\n";
                }
            } else {
                // Si AVIF n'est pas supporté, convertir en WebP
                $cheminWebp = $cheminPages . '/' . pathinfo($fichier, PATHINFO_FILENAME) . '.webp';

                // Convertir en WebP et enregistrer
                if (!imagewebp($image, $cheminWebp)) {
                    // echo "Erreur lors de la conversion en WebP: " . $cheminFichier . "\n";
                } else {
                    // echo "Image convertie en WebP: " . $cheminWebp . "\n";
                }
            }

            // Libérer la mémoire
            imagedestroy($image);
        }
    }
}

function redimensionnerImages($cheminPages, $cheminThumbnails, $nouvelleHauteur) {
    // Liste des fichiers dans le dossier
    $fichiers = scandir($cheminPages);

    foreach ($fichiers as $fichier) {
        $cheminFichier = $cheminPages . '/' . $fichier;
        $extension = strtolower(pathinfo($fichier, PATHINFO_EXTENSION));

        // Vérifie si c'est un fichier AVIF ou WebP
        if (is_file($cheminFichier) && in_array($extension, ['avif', 'webp'])) {
            if ($extension === 'avif') {
                $image = imagecreatefromavif($cheminFichier);
            } elseif ($extension === 'webp') {
                $image = imagecreatefromwebp($cheminFichier);
            }

            if ($image === false) {
                // echo "Erreur lors du chargement de l'image: " . $cheminFichier . "\n";
                continue;
            }

            $largeur = imagesx($image);
            $hauteur = imagesy($image);
            $nouvelleLargeur = ($nouvelleHauteur / $hauteur) * $largeur;

            // Crée une nouvelle image redimensionnée
            $nouvelleImage = imagecreatetruecolor($nouvelleLargeur, $nouvelleHauteur);
            imagecopyresampled($nouvelleImage, $image, 0, 0, 0, 0, $nouvelleLargeur, $nouvelleHauteur, $largeur, $hauteur);

            // Détermine le chemin de sauvegarde de la miniature
            $cheminThumbnail = $cheminThumbnails . '/' . $fichier;

            // Enregistre l'image redimensionnée
            if ($extension === 'avif') {
                if (!imageavif($nouvelleImage, $cheminThumbnail)) {
                    // echo "Erreur lors de l'enregistrement de l'image AVIF redimensionnée: " . $cheminThumbnail . "\n";
                } else {
                    // echo "Image AVIF redimensionnée: " . $cheminThumbnail . "\n";
                }
            } elseif ($extension === 'webp') {
                if (!imagewebp($nouvelleImage, $cheminThumbnail)) {
                    // echo "Erreur lors de l'enregistrement de l'image WebP redimensionnée: " . $cheminThumbnail . "\n";
                } else {
                    // echo "Image WebP redimensionnée: " . $cheminThumbnail . "\n";
                }
            }

            // Libérer la mémoire
            imagedestroy($image);
            imagedestroy($nouvelleImage);
        }
    }
}
?>
