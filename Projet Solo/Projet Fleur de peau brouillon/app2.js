// Sélection de tout les élements <li> dans la navigation
const links = document.querySelectorAll("nav li");

// Écouter d'évènement pour l'icône de menu 
icons.addEventListener("click", () => {
    nav.classList.toggle("active");
});

// Retrait de la classe 'active' lorsque l'un des liens de navigation est cliqué 
links.forEach((link) => {
    link.addEventListener("click", () => {
        nav.classList.remove("active");
    });
});

// Configuration de la fonctionnalité de défilement pour la carte
const cardWrapper = document.querySelector('.card-wrapper');
const widthToScroll = cardWrapper.children[0].offsetWidth;
const arrowPrev = document.querySelector('.arrow.prev');
const arrowNext = document.querySelector('.arrow.next');
const cardBouding = cardWrapper.getBoundingClientRect();
const cardImageAndLink = cardWrapper.querySelectorAll('img, a');
let currScroll = 0;
let initPos = 0;
let clicked = false;

// Empêche le glisser-déposer sur les images et liens dans la carte
cardImageAndLink.forEach(item => {
    item.setAttribute('draggable', false);
});

// Défilement vers la gauche
arrowPrev.onclick = function() {
    cardWrapper.scrollLeft -= widthToScroll;
}

// Défilement vers la droite
arrowNext.onclick = function() {
    cardWrapper.scrollLeft += widthToScroll;
}

// Gestion du mouvement de la souris pour le défilement 
cardWrapper.onmousemove = function(e) {
    if(clicked) {
        const xPos = e.clientX - cardBouding.left;
        cardWrapper.scrollLeft = currScroll + -(xPos - initPos);
    }
}

//Gestion de la fin du clic ou lorsque la souris quitte la zone
cardWrapper.onmouseup = mouseUpAndLeave;
cardWrapper.onmouseleave = mouseUpAndLeave;

function mouseAndLeave() {
    cardWrapper.classList.remove('grab');
    clicked = false;
}

// fonction à exécuter au chargement de la page 
window.onload = function() { filterPhotos('all'); };

// Filtre les photos en fonction de la balise 
function filterPhotos(tag) {
    var photos = document.querySelectorAll('.photo');

    photos.forEach(function(photo) {
        if (tag === 'all' || photo.classList.contains(tag)) {
            photo.computedStyleMap.display = 'flex';
        } else {
            photo.computedStyleMap.display = 'none';
        }
    });
}

// Gestion du clic pour afficher ou masquer un paragraphe caché
document.getElementById('toggleButton').addEventListener('click', function() {
    var hiddenParagraph = document.getElementById('hiddenParagraph');

    if (hiddenParagraph.style.display === 'none') {
        hiddenParagraph.style.display = 'block';
        this.innerHTML = '-';
    } else {
        hiddenParagraph.style.display = 'none';
        this.innerHTML = '+';
    }
});
