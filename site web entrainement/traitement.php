<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name =$_POST["name"];
    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    //Affichage des données 
    echo "Nom: $name <br>";
    echo "Email: $email <br>";
    echo "Subject: $subject <br>";
    echo "Message $message <br>";
} else {
    echo "Erreur: Accès non autorisé";
}
?>