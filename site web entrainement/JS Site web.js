// Fonction pour faire défiler jusqu'en haut de la page en douceur
function scrollToTop() {
    const c = document.documentElement.scrollTop || document.body.scrollTop;
    if (c > 0) {
        window.requestAnimationFrame(scrollToTop);
        window.scrollTo(0,0)
    }
}