var mySwiper; // Déclarer la variable en dehors de la fonction

// fonction pour initialisation du swipper
document.addEventListener('DOMContentLoaded', function() {
  var swiper2 = new Swiper(".mySwiper2", {
    loop: true,    
    freeMode: true,
    // watchSlidesProgress: true,
    slideToClickedSlide: true,
    breakpoints: {
      200: {
        slidesPerView: 3,
        spaceBetween: 30,
      },
      
      680: {
        slidesPerView: 4,
        spaceBetween: 50,
      },
      1000:{
        slidesPerView: 7,
        spaceBetween: 100,
      }
    }, // Permet de cliquer sur une miniature pour changer le slide principal
  });
  mySwiper = new Swiper('.mySwiper.swiper-zoom-container',  {
      // Options Swiper ici
      
      slidesPerGroup: 1,
      loop: true,
      hashNavigation: {
        watchState: true,
      },
      navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
      },
      pagination: {
          el: '.swiper-pagination',
          type: "progressbar",
          
      },
      zoom:{
        maxRatio: 3,
        minRatio: 1,
      },
      on: {
        slideChange: function() {

          // Obtenez l'index du slide actuellement affiché
          var currentSlideIndex = this.realIndex;
          
           window.location.hash = "slide" + (currentSlideIndex + 1);
          
          // Mise à jour du numéro de page
          updatePageNumber(currentSlideIndex + 1, this.slides.length);
          
          // Appeler d'autres actions ou fonctions ici si nécessaire
          
      },
      init: function() {
          // Mise à jour du numéro de page lors de l'initialisation
          updatePageNumber(1, this.slides.length);
      }
      },
      thumbs: {
        swiper: swiper2,
      },
  });
  
 
var swiperContainer = document.getElementById('swiper-wrapper');

// Vérifier si imagesData est un tableau
if (Array.isArray(imagesData)) {
    // Boucle pour créer une div pour chaque image
    imagesData.forEach(function(image, index) {
        var slide = document.createElement('div');
        slide.classList.add('swiper-slide');
        
        slide.setAttribute('data-hash', 'page'  + (index + 1));

        var zoom = document.createElement('div');
        zoom.classList.add('swiper-zoom-container');

        var imageElement = document.createElement('img');
        imageElement.src = image; // Le chemin de l'image AVIF
        imageElement.alt = image.split('/').pop(); // Le nom de l'image AVIF
       
        zoom.appendChild(imageElement);
        slide.appendChild(zoom);
        swiperContainer.appendChild(slide);
        mySwiper.appendSlide(slide);
    });
} else {
    console.error('imagesData n\'est pas un tableau valide.');
}
});

// Obtenez le conteneur du deuxième swiper
var swiperContainer2 = document.querySelector('.mySwiper2 .swiper-wrapper');

// Boucle pour créer une diapositive pour chaque image thumbnail du deuxième swiper
imagesData2.forEach(function(cheminImage2) {
    // Créer une div pour la slide
    var slide2 = document.createElement('div');
    slide2.classList.add('swiper-slide');

    // Créer l'élément image
    var imageElement2 = document.createElement('img');
    imageElement2.src = cheminImage2; // Définir le chemin de l'image thumbnail
    imageElement2.alt = cheminImage2.split('/').pop(); // Définir le nom de l'image comme attribut alt

    // Ajouter l'élément image à la slide
    slide2.appendChild(imageElement2);

    // Ajouter la slide au conteneur du deuxième swiper
    swiperContainer2.appendChild(slide2);
});



  // Ajouter un écouteur d'événement pour le bouton de zoom complet
  document.querySelector('.zoom-button').addEventListener('click', function() {
    // Activer ou désactiver le zoom complet sur l'image actuellement affichée dans Swiper
    mySwiper.zoom.toggle();
});

 // Ajouter un écouteur d'événement pour le bouton de zoom out
 document.querySelector('.zoom-out-button').addEventListener('click', function() {
  // Réinitialiser le zoom sur Swiper
  mySwiper.zoom.out();
});




var timeoutId; // Déclaration de la variable timeoutId

function toggleSousMenu(open) {
  var sousMenu = document.getElementById('sousMenu');
  if (open) {
    clearTimeout(timeoutId); // Efface le timeout précédent
    sousMenu.style.display = 'block'; // Affiche le sous-menu au survol
  } else {
    // Ferme le menu après un délai
    timeoutId = setTimeout(function() {
      sousMenu.style.display = 'none';
    }, 3000); // Délai en millisecondes (3 secondes)
  }
}
// fonction pour le pleine écran
function fullScreen() {
  if (!document.fullscreenElement) {
      document.documentElement.requestFullscreen();
  } else {
      if (document.exitFullscreen) {
          document.exitFullscreen();
      }
  }
}
// fonction pour activer le dark mode 
function darkMode() {
    
    var slides = document.getElementsByClassName('swiper-slide');
    
    // Loop  all elements and toggle the "dark-mode" class on each one
    for (var i = 0; i < slides.length; i++) {
        slides[i].classList.toggle("dark-mode");
    }
}

// Fonction pour convertir une image GD en AVIF
function imageavif($image, $chemin_destination) {
  // Vérifier si la fonction avif_encode est disponible
  if (function_exists('avif_encode')) {
      // Encoder l'image en AVIF et l'enregistrer
      return avif_encode_file($image, $chemin_destination);
  } else {
       "La fonction avif_encode n'est pas disponible. Assurez-vous que l'extension AVIF est installée.\n";
      return false;
  }
  
}

document.addEventListener('DOMContentLoaded', function() {
    var showThumbnailsButton = document.getElementById('showThumbnails');
    var thumbnailsSlider = document.querySelector('.mySwiper2');
  
    showThumbnailsButton.addEventListener('click', function() {
      
      if (thumbnailsSlider.style.display === 'none' || thumbnailsSlider.style.display === '') {
        thumbnailsSlider.style.display = 'block';
        
      } else {
        thumbnailsSlider.style.display = 'none';
      }
    });
  });
  

  function slidePrint() {
    window.print(); // Fonction native de JavaScript pour imprimer la page
}



// Fonction pour le partage sur les différents réseaux sociaux
function partagerSurLinkedIn() {
  // URL de partage LinkedIn
  var linkedinShareUrl = 'https://www.linkedin.com/shareArticle?url=' + encodeURIComponent(window.location.href);
  
  // Ouvrir une nouvelle fenêtre pour le partage sur LinkedIn
  window.open(linkedinShareUrl, '_blank');
}
function partagerSurFacebook() {
  // URL de partage Facebook
  var facebookShareUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(window.location.href);
  
  // Ouvrir une nouvelle fenêtre pour le partage sur Facebook
  window.open(facebookShareUrl, '_blank');
}
function partagerSurTwitter() {
  // URL de partage Twitter
  var twitterShareUrl = 'https://twitter.com/intent/tweet?url=' + encodeURIComponent(window.location.href);
  
  // Ouvrir une nouvelle fenêtre pour le partage sur Twitter
  window.open(twitterShareUrl, '_blank');
}

// fonction pour le nombre total de pages et sur la page actuelle
var pageNumberElement = document.getElementById('pageNumber');

function updatePageNumber(currentPage, totalPages) {
    pageNumberElement.textContent = currentPage + ' / ' + totalPages;
}




























function toggleBoutonsPartage(display) {
  var facebookButton = document.getElementById('shareFacebookBtn');
  var linkedinButton = document.getElementById('shareLinkedInBtn');
  var twitterButton = document.getElementById('shareTwitterBtn');

  if (display) {
    facebookButton.style.display = 'block';
    linkedinButton.style.display = 'block';
    twitterButton.style.display = 'block';
    
  } else {
    // Ajoutez un délai de 500 millisecondes avant de masquer les boutons
    setTimeout(function() {
      facebookButton.style.display = 'none';
      linkedinButton.style.display = 'none';
      twitterButton.style.display = 'none';
    }, 1000);
  }
}

var sousMenuShare = document.getElementById('sousMenuShare');
sousMenuShare.addEventListener('mouseenter', function() {
  toggleBoutonsPartage(true);
});

sousMenuShare.addEventListener('mouseleave', function() {
  toggleBoutonsPartage(false);
});

