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

    // Convertir les images JPEG en AVIF et les déplacer dans le dossier "pages"
    convertirJPEGenAVIF($cheminSources, $cheminPages);

    echo "Le fichier ZIP a été téléchargé, décompressé et les images ont été converties en AVIF avec succès.";
}

?>
<?php
// Fonction pour convertir les images JPEG en AVIF
function convertirJPEGenAVIF($cheminSources, $cheminPages) {
    // Liste des fichiers dans le dossier
    $fichiers = scandir($cheminSources);

    foreach ($fichiers as $fichier) {
        $cheminFichier = $cheminSources . '/' . $fichier;

        // Si c'est un fichier JPEG
        if (is_file($cheminFichier) && strtolower(pathinfo($fichier, PATHINFO_EXTENSION)) === 'jpg') {
            // Chemin du fichier AVIF
            $cheminAVIF = $cheminPages . '/' . pathinfo($fichier, PATHINFO_FILENAME) . '.avif';

            // Convertir en AVIF et enregistrer
            if (!convertirJPEGenAVIF($cheminFichier, $cheminAVIF)) {
                echo "Erreur lors de la conversion en AVIF: " . $cheminFichier;
            }
        }
    }
}

// Fonction pour convertir une image JPEG en AVIF
function convertirJPEGenAVIF($cheminJPEG, $cheminAVIF) {
    // Charger l'image JPEG
    $image = imagecreatefromjpeg($cheminJPEG);

    if ($image === false) {
        echo "Erreur lors du chargement de l'image JPEG: " . $cheminJPEG;
        return false;
    }

    // Convertir en AVIF et enregistrer
    if (!imageavif($image, $cheminAVIF)) {
        echo "Erreur lors de la conversion en AVIF: " . $cheminJPEG;
        return false;
    }

    // Libérer la mémoire
    imagedestroy($image);

    return true;
}
?>
<?php
// Fonction pour redimensionner une image en conservant la hauteur
function redimensionnerImage($cheminImage, $cheminRedimensionne, $hauteurNouvelle) {
    // Charger l'image
    $image = imagecreatefromjpeg($cheminImage);

    if ($image === false) {
        echo "Erreur lors du chargement de l'image : " . $cheminImage;
        return false;
    }

    $largeurOriginale = imagesx($image);
    $hauteurOriginale = imagesy($image);

    // Calculer la largeur en conservant les proportions
    $largeurNouvelle = ($hauteurNouvelle / $hauteurOriginale) * $largeurOriginale;

    // Créer une nouvelle image redimensionnée
    $imageRedimensionnee = imagecreatetruecolor($largeurNouvelle, $hauteurNouvelle);
    imagecopyresampled($imageRedimensionnee, $image, 0, 0, 0, 0, $largeurNouvelle, $hauteurNouvelle, $largeurOriginale, $hauteurOriginale);

    // Enregistrer l'image redimensionnée
    if (!imagejpeg($imageRedimensionnee, $cheminRedimensionne)) {
        echo "Erreur lors de l'enregistrement de l'image redimensionnée : " . $cheminRedimensionne;
        return false;
    }

    // Libérer la mémoire
    imagedestroy($image);
    imagedestroy($imageRedimensionnee);

    return true;
}

// Exemple d'utilisation :
$cheminImage = '/pages'; // Remplacez par le chemin réel de votre image JPEG
$cheminRedimensionne = '/thumbnails'; // Remplacez par le chemin réel où vous voulez enregistrer l'image redimensionnée
$hauteurNouvelle = 256;

if (redimensionnerImage($cheminImage, $cheminRedimensionne, $hauteurNouvelle)) {
    echo "L'image a été redimensionnée avec succès.";
} else {
    echo "Une erreur s'est produite lors du redimensionnement de l'image.";
}
?>
