<?php

class HomeView {
    public function displayHome($welcomeMessage) {
        // Début du HTML
        echo '<html><head><title>Accueil</title></head><body>';

        // Affichage du message de bienvenue
        echo '<h1>' . htmlspecialchars($welcomeMessage) . '</h1>';

        // Affichage d'une image (sera ajoutée plus tard)
        // echo '<img src="chemin_vers_image" alt="IUT Informatique"/>';

        // Fin du HTML
        echo '</body></html>';
    }
}
