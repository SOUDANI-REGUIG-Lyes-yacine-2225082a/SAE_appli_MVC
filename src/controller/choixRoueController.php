<?php


namespace src\controller;
/**
 * Classe choixRoueController
 *
 * Cette classe gère l'affichage des différentes vues liées aux choix de roues.
 */
class choixRoueController
{

    /**
     * Affiche la vue de choix de roue principale.
     *
     * @return void
     */
    public function displayRoue() {
    include "src/view/choixRoueView.php";
    }
    /**
     * Affiche la vue correspondant au premier bouton.
     *
     * @return void
     */
    public function displayBut1() {
        include "src/view/But1View.php";
    }
    /**
     * Affiche la vue correspondant au deuxième bouton.
     *
     * @return void
     */
    public function displayBut2() {
        include "src/view/But2View.php";
    }
    /**
     * Affiche la vue correspondant au troisième bouton.
     *
     * @return void
     */
    public function displayBut3() {
        include "src/view/But3View.php";
    }
    public function displaySalles(){
        include "src/view/ButSalles.php";
    }

    public function displayEnseignants(){
        include "src/view/ButEnseignant.php";
    }
}
