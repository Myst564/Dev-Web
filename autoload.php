<?php

// Inclure la bibliothèque Intervention Image
require_once 'vendor/autoload.php';

use Intervention\Image\ImageManagerStatic as Image;

// Fonction pour générer un mème aléatoire
function genererMeme($images, $mots) {
    // Sélectionner une image aléatoire
    $imageAleatoire = $images[array_rand($images)];
    
    // Charger l'image
    $img = Image::make($imageAleatoire);
    
    // Choisir une couleur de texte aléatoire
    $couleurTexte = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    
    // Choisir un texte aléatoire
    $texteAleatoire = $mots[array_rand($mots)];
    
    // Ajouter le texte à l'image
    $img->text($texteAleatoire, $img->width() / 2, $img->height() / 2, function($font) use ($couleurTexte) {
        $font->file('arial.ttf');
        $font->size(48);
        $font->color($couleurTexte);
        $font->align('center');
        $font->valign('middle');
    });
    
    // Afficher l'image générée
    header('Content-Type: image/jpeg');
    echo $img->encode('jpg');
}

// Liste des images disponibles
$images = glob('images/*.jpg');

// Liste des mots disponibles
$mots = [
    "Goes on computer after 5 hours of studying Mom Walks in",
    "Going to work to pick up my paycheck",
    "Stay Focused!",
    "Stromarrant"
];

// Générer un mème aléatoire
genererMeme($images, $mots);

?>

