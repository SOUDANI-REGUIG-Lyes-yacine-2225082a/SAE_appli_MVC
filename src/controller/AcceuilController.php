<?php

class AcceuilController
{
    private $assetsPath = "../../_assets";
    private $roueChoixPage;


    public function __construct() {

    }
    public function displayHome() {
    include "src/view/ChoixTemplate.html";  // Vous pourriez inclure le HTML ici directement, mais l'inclure depuis un autre fichier est plus propre
}
}
