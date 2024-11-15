<?php
include_once'functions.php';
?>
<?php
// Fonction pour récupérer les chemins des images dans un dossier avec extensions spécifiques
function getImages($dir, $extensions = ['avif', 'webp']) {
    $images = [];
    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $file) {
            $file_extension = pathinfo($file, PATHINFO_EXTENSION);
            if (in_array($file_extension, $extensions)) {
                $images[] = $dir . '/' . $file;
            }
        }
    }
    return $images;
}

// Fonction pour récupérer la première image d'un dossier
function getFirstImage($dir, $extensions = ['avif', 'webp']) {
    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $file) {
            $file_extension = pathinfo($file, PATHINFO_EXTENSION);
            if (in_array($file_extension, $extensions)) {
                return $dir . '/' . $file;
            }
        }
    }
    return 'placeholder.jpg'; // Image de remplacement si aucune image n'est trouvée
}

// Fonction pour extraire le titre à partir d'un ID dans un fichier JSON
function getTitleFromJson($id, $json_file) {
    // Charger le contenu du fichier JSON
    $json_data = file_get_contents($json_file);
    // Décoder le JSON en tableau associatif
    $data = json_decode($json_data, true);

    // Rechercher l'entrée correspondant à l'ID donné
    foreach ($data as $entry) {
        if ($entry['id'] == $id) {
            return $entry['title'];
        }
    }

    // Retourner un titre par défaut si l'ID n'est pas trouvé
    return "Titre inconnu";
}

// Extraire l'ID de l'URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$json_file = 'data.json';

if ($id > 0) {
    // Définir les chemins des dossiers
    $pagesPath = "supports/$id/pages";
    $thumbnailsPath = "supports/$id/thumbnails";

    // Récupérer les images avec les extensions avif et webp
    $images = getImages($pagesPath);
    $thumbnails = getImages($thumbnailsPath);

    // Encoder les chemins des images en JSON
    $json_data = json_encode($images);
    $json_data_2 = json_encode($thumbnails);

    // Récupérer le titre correspondant à l'ID
    $title = getTitleFromJson($id, $json_file);
} else {
    // Charger le contenu JSON
    $json_data = file_get_contents($json_file);
    $data = json_decode($json_data, true);
}
?>






<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Ajout font Montserrat et Roboto Slab -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital@0;1&family=Roboto+Slab&display=swap" rel="stylesheet">
    <!-- Ajout CDN Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!-- ajout css ajouté  -->
    
    <link rel="stylesheet" href="css/cma-icones.css">
    <link rel="stylesheet" href="css/style.css">
    <?php if ($id > 0): ?>
        <title><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
    <?php else: ?>
        <title>Page d'accueil</title>
    <?php endif; ?>
</head>
<body id="body">
<header class="header">
  <div class="header__banner">
    <!-- Logo cma -->
    <div class="header__banner-icon">
      <i class="icon-logo-cma"></i>
    </div>
    <div class="header__banner-picto">
      <!-- Liste pour les picto -->  
      <ul>
    <li><i id="zoomPlusBtn" class="icon-zoom-plus zoom-button"></i></li>
    <li><i id="zoomMoinsBtn" class="icon-zoom-moins zoom-out-button"></i></li>
    <li><i id="fullscreenBtn" onclick="fullScreen()" class="icon-fullscreen-rounded"></i></li>
    <li id="dropdownIcon" class="dropdown-container"> <!-- Ajout de la classe dropdown-container -->
        <i id="menuDotsIcon" onmouseenter="toggleSousMenu(true)" onmouseleave="toggleSousMenu(false)" class="icon-menu-dots"></i> <!-- Modification du déclencheur pour le clic -->
        <!-- Sous-menu -->
        <ul id="sousMenu" class="sous-menu" style="display: none;">
            <li onclick="darkMode()">
                <i class="icon-contrast"></i>
                <p>Thème sombre</p>
            </li>
            <li onclick="slidePrint()">
                <i class="icon-printer"></i>
                <p>Imprimer</p>
            </li>
            <ul id="sousMenuShare" class="sousMenuShare" onmouseenter="toggleBoutonsPartage(true)" onmouseleave="toggleBoutonsPartage(false)">
                <i class="icon-partage">Partage</i>
                <li class="shareFacebook" id="shareFacebookBtn" onclick="partagerSurFacebook()">
                    <i class="icon-facebook"></i>
                    <p>Partager sur Facebook</p>
                </li>
                <li class="shareLinkedIn" id="shareLinkedInBtn" onclick="partagerSurLinkedIn()">
                    <i class="icon-linkedin"></i>
                    <p>Partager sur LinkedIn</p>
                </li>
                <li class="shareTwitter" id="shareTwitterBtn" onclick="partagerSurTwitter()">
                    <i class="icon-x"></i>
                    <p>Partager sur X</p>
                </li>
            </ul>
        </ul>
    </li>
</ul>
    </div>
  </div>
</header>
<?php if ($id > 0): ?>
    <div id="swiper" class="swiper mySwiper swiper-zoom-container">
        <div class="swiper-wrapper" id="swiper-wrapper">  
            <!--s'affiche ici les slide grace a la boucle dans le dossier JS  -->
        </div>
  
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination custom-progress"></div>
    </div>

    <i class="icon-miniatures" id="showThumbnails" ></i>

    <div thumbsSlider="" class="swiper2 mySwiper2">
        <div class="swiper-wrapper">
        
        </div>
    </div>
    <footer class="footer">
        <div class="footer__headband">
            <h1> 
                <span id="pageNumber">
                
                </span> 
            <br><?= $title; ?></h1> 
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Inclure votre script JavaScript -->


<script>
        // Définir la variable imagesData avec les données des images AVIF
        var imagesData = <?= $json_data; ?>;
        // Définir la variable imagesData2 avec les données des images AVIF
        var imagesData2 = <?= $json_data_2; ?>;
    </script>

    <!-- Inclure votre script JavaScript -->
    
    <script src="script.js" ></script>
    <?php else: ?>
    <!-- Si aucun paramètre ID n'est présent, afficher une liste de cartes -->
    <div class="card-container">
        <?php foreach ($data as $entry): ?>
            <div class="card">
                <img src="<?php echo getFirstImage("supports/{$entry['id']}/thumbnails"); ?>" alt="Thumbnail">
                <div class="card-content">
                    <h2><?php echo $entry['title']; ?></h2>
                    <a href="index.php?id=<?php echo $entry['id']; ?>">Voir plus</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>  
</body>

</html>
