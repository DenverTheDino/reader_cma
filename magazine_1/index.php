<?php 
include_once 'functions.php';
include 'traitement.php';
?>

<?php
    $file_name = 'data.json';
    
        // Charger les données existantes depuis le fichier JSON s'il existe
        $data0 = [];
        if (file_exists($file_name)) {
            $json_data_0 = file_get_contents($file_name);
            $data0 = json_decode($json_data_0, true);
            // Vérifier si les données sont null
            if ($data0 === null) {
                $data0 = [];
            }
        }

        // Définir le titre de la page par défaut
        $pageTitre = "Titre par défaut";

        // Si des données existent dans le fichier JSON
        if (!empty($data0)) {
            // Récupérer les données du dernier élément du tableau
            $derniere_entree = end($data0);
            $title = isset($derniere_entree['titre']) ? $derniere_entree['titre'] : '';

            // Utiliser le titre pour le titre de la page
            $pageTitre = $title;
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
    <link rel="stylesheet" href="css/print.css" media="print">
    <link rel="stylesheet" href="css/cma-icones.css">
    <link rel="stylesheet" href="css/style.css">
    <title><?= $pageTitre?></title>
    
 
</head>

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
    <link rel="stylesheet" href="css/print.css" media="print">
    <link rel="stylesheet" href="css/cma-icones.css">
    <link rel="stylesheet" href="css/style.css">
    <title><?php echo $pageTitre; ?></title>
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
        <li><i class="icon-zoom-plus zoom-button" ></i></li>
        <li><i  class="icon-zoom-moins zoom-out-button"></i></li>
        <li><i onclick="fullScreen()" class="icon-fullscreen-rounded"></i></li>
        <li id="dropdownIcon" class="dropdown-container"> <!-- Ajout de la classe dropdown-container -->
          <i onmouseenter="toggleSousMenu(true)" onmouseleave="toggleSousMenu(false)"class="icon-menu-dots" ></i> <!-- Modification du déclencheur pour le clic -->
          <!-- Sous-menu -->
          <ul id="sousMenu" class="sous-menu" style="display: none;">
            <li onclick="darkMode()" >
                <i class="icon-contrast"></i>
                <p>Thème sombre</p>
            </li>
            <li>
              <i class="icon-printer" onclick="slidePrint()"></i>
              <p>Imprimer</p>
            </li>
            <ul id="sousMenuShare" class="sousMenuShare"  onmouseenter="toggleBoutonsPartage(true)" onmouseleave="toggleBoutonsPartage(false)">
              <i class="icon-partage">Partage</i> 
              <li class="shareFacebook" id="shareFacebookBtn" onclick="partagerSurFacebook()" >
                <i class="icon-facebook"></i>
                <p>Partager sur Facebook</p>
              </li>
              <li class="shareLinkedIn" id="shareLinkedInBtn" onclick="partagerSurLinkedIn()" >
                <i class="icon-linkedin"></i>
                <p>Partager sur LinkedIn</p>
              </li>
              <li class="shareTwitter" id="shareTwitterBtn" onclick="partagerSurTwitter()" >
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
            <br><?php echo $pageTitre; ?></h1> 
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Inclure votre script JavaScript -->


<script>
        // Définir la variable imagesData avec les données des images AVIF
        var imagesData = <?= $json_data; ?>;
        // Définir la variable imagesData2 avec les données des images AVIF
        var imagesData2 = <?=  $json_data_2; ?>;
    </script>

    <!-- Inclure votre script JavaScript -->
    <script src="js/script.js"></script>
</body>

</html>
