// Déclaration de la variable pour le Swiper
var mySwiper;

// Attente du chargement du document pour exécuter le code
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation du deuxième Swiper pour les miniatures
    var swiper2 = new Swiper(".mySwiper2", {
        loop: true,    
        freeMode: true,
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
            1000: {
                slidesPerView: 7,
                spaceBetween: 100,
            }
        },
    });

    // Initialisation du Swiper principal
    mySwiper = new Swiper('.mySwiper.swiper-zoom-container',  {
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
        zoom: {
            maxRatio: 3,
            minRatio: 1,
        },
        // Événements du Swiper principal
        on: {
            slideChange: function() {
                var currentSlideIndex = this.realIndex;
                updatePageNumber(currentSlideIndex + 1, this.slides.length);
                history.pushState(null, null, '#slide' + (currentSlideIndex + 1));
            },
            init: function() {
                // Mise à jour du numéro de page à l'initialisation
                updatePageNumber(1, this.slides.length);

                // Charger la diapositive en fonction de l'ancre dans l'URL
                var hash = window.location.hash;
                if (hash) {
                    var slideNumber = parseInt(hash.replace('#slide', ''));
                    if (!isNaN(slideNumber) && slideNumber > 0 && slideNumber <= this.slides.length) {
                        this.slideTo(slideNumber - 1);
                    }
                }
            }
        },
        // Configuration des miniatures liées
        thumbs: {
            swiper: swiper2,
        },
    });

    // Fonction pour vérifier périodiquement l'ancre dans l'URL et charger la diapositive correspondante
    function checkHashAndUpdateSlide() {
        var hash = window.location.hash;
        if (hash) {
            var slideNumber = parseInt(hash.replace('#slide', ''));
            if (!isNaN(slideNumber) && slideNumber > 0 && slideNumber <= mySwiper.slides.length) {
                mySwiper.slideTo(slideNumber - 1);
            }
        }
    }

    // Vérifier l'ancre dans l'URL toutes les 500 millisecondes
    setInterval(checkHashAndUpdateSlide, 500);

    // Récupération du conteneur pour les slides du Swiper principal
    var swiperContainer = document.getElementById('swiper-wrapper');

    // Vérification si imagesData est un tableau
    if (Array.isArray(imagesData)) {
        // Création des slides pour chaque image dans imagesData
        imagesData.forEach(function(image) {
            var slide = document.createElement('div');
            slide.classList.add('swiper-slide');

            var zoom = document.createElement('div');
            zoom.classList.add('swiper-zoom-container');

            var imageElement = document.createElement('img');
            imageElement.src = image;
            imageElement.alt = image.split('/').pop();

            zoom.appendChild(imageElement);
            slide.appendChild(zoom);
            swiperContainer.appendChild(slide);
            mySwiper.appendSlide(slide);
        });
    } else {
        console.error('imagesData n\'est pas un tableau valide.');
    }
});
// Récupération du conteneur pour les slides du deuxième Swiper
var swiperContainer2 = document.querySelector('.mySwiper2 .swiper-wrapper');

// Création des slides pour chaque image thumbnail du deuxième Swiper
imagesData2.forEach(function(cheminImage2) {
    var slide2 = document.createElement('div');
    slide2.classList.add('swiper-slide');

    var imageElement2 = document.createElement('img');
    imageElement2.src = cheminImage2;
    imageElement2.alt = cheminImage2.split('/').pop();

    slide2.appendChild(imageElement2);
    swiperContainer2.appendChild(slide2);
});

// Ajout d'un gestionnaire d'événement pour le bouton de zoom
document.querySelector('.zoom-button').addEventListener('click', function() {
    // Activation ou désactivation du zoom sur l'image actuelle
    mySwiper.zoom.toggle();
});

// Ajout d'un gestionnaire d'événement pour le bouton de zoom arrière
document.querySelector('.zoom-out-button').addEventListener('click', function() {
    // Réinitialisation du zoom sur le Swiper
    mySwiper.zoom.out();
});

// Déclaration de la variable pour le timeout
var timeoutId;

// Fonction pour afficher ou masquer le sous-menu
function toggleSousMenu(open) {
    var sousMenu = document.getElementById('sousMenu');
    if (open) {
        clearTimeout(timeoutId);
        sousMenu.style.display = 'block';
    } else {
        timeoutId = setTimeout(function() {
            sousMenu.style.display = 'none';
        }, 3000);
    }
}

// Fonction pour passer en mode plein écran
function fullScreen() {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen();
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        }
    }
}

// Fonction pour activer le mode sombre
function darkMode() {
    var slides = document.getElementsByClassName('swiper-slide');
    for (var i = 0; i < slides.length; i++) {
        slides[i].classList.toggle("dark-mode");
    }
}

// Fonction pour convertir une image GD en AVIF (à adapter avec JavaScript si nécessaire)
function imageavif($image, $chemin_destination) {
    if (function_exists('avif_encode')) {
        return avif_encode_file($image, $chemin_destination);
    } else {
        "La fonction avif_encode n'est pas disponible. Assurez-vous que l'extension AVIF est installée.\n";
        return false;
    }
}

// Ajout d'un gestionnaire d'événement pour le bouton d'affichage des miniatures
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

// Fonction pour imprimer la diapositive actuelle
function slidePrint() {
    window.print();
}

// Fonction pour partager sur LinkedIn
function partagerSurLinkedIn() {
    var linkedinShareUrl = 'https://www.linkedin.com/shareArticle?url=' + encodeURIComponent(window.location.href);
    window.open(linkedinShareUrl, '_blank');
}

// Fonction pour partager sur Facebook
function partagerSurFacebook() {
    var facebookShareUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(window.location.href);
    window.open(facebookShareUrl, '_blank');
}

// Fonction pour partager sur Twitter
function partagerSurTwitter() {
    var twitterShareUrl = 'https://twitter.com/intent/tweet?url=' + encodeURIComponent(window.location.href);
    window.open(twitterShareUrl, '_blank');
}

// Récupération de l'élément pour afficher le numéro de page
var pageNumberElement = document.getElementById('pageNumber');

// Fonction pour mettre à jour le numéro de page
function updatePageNumber(currentPage, totalPages) {
    pageNumberElement.textContent = currentPage + ' / ' + totalPages;
}

// Fonction pour afficher ou masquer les boutons de partage
function toggleBoutonsPartage(display) {
    var facebookButton = document.getElementById('shareFacebookBtn');
    var linkedinButton = document.getElementById('shareLinkedInBtn');
    var twitterButton = document.getElementById('shareTwitterBtn');

    if (display) {
        facebookButton.style.display = 'block';
        linkedinButton.style.display = 'block';
        twitterButton.style.display = 'block';
    } else {
        setTimeout(function() {
            facebookButton.style.display = 'none';
            linkedinButton.style.display = 'none';
            twitterButton.style.display = 'none';
        }, 1000);
    }
}

// Gestionnaire d'événement pour afficher ou masquer les boutons de partage
var sousMenuShare = document.getElementById('sousMenuShare');
sousMenuShare.addEventListener('mouseenter', function() {
    toggleBoutonsPartage(true);
});

// Écouteur d'événement pour le changement d'ancre dans l'URL
window.addEventListener('hashchange', function() {
    var hash = window.location.hash;
    if (hash) {
        var slideNumber = parseInt(hash.replace('#slide', ''));
        if (!isNaN(slideNumber) && slideNumber > 0 && slideNumber <= mySwiper.slides.length) {
            mySwiper.slideTo(slideNumber - 1);
        }
    }
});

// Fonction pour charger la diapositive en fonction de l'ancre dans l'URL lors de l'initialisation
function loadSlideFromHash() {
    var hash = window.location.hash;
    if (hash) {
        var slideNumber = parseInt(hash.replace('#slide', ''));
        if (!isNaN(slideNumber) && slideNumber > 0 && slideNumber <= mySwiper.slides.length) {
            mySwiper.slideTo(slideNumber - 1);
        }
    }
}
function getSlideNumberFromUrl() {
    var hash = window.location.hash;
    if (hash) {
        // Trouver la chaîne qui commence par "slide" suivie d'un nombre
        var match = hash.match(/slide(\d+)/);
        if (match) {
            // Extraire le numéro de diapositive du résultat de la correspondance
            var slideNumber = parseInt(match[1]);
            if (!isNaN(slideNumber)) {
                return slideNumber;
            }
        }
    }
    return null; // Retourne null si aucun numéro de diapositive n'est trouvé dans l'URL
}

// Initialiser le chargement de la diapositive à partir de l'ancre lors du chargement du document
document.addEventListener('DOMContentLoaded', function() {
    loadSlideFromHash();
});