<?php

require_once 'src/model/HomeModel.php';
require_once 'src/view/HomeView.php';

class HomeController {

    private $model;
    private $view;

    public function __construct() {
        $this->model = new HomeModel();
        $this->view = new HomeView();
    }

    public function handleRequest_Home() {
        // Obtenir des données du modèle
        $welcomeMessage = $this->model->getWelcomeMessage();

        // Afficher la vue avec les données
        $this->view->displayHome($welcomeMessage);
    }
}
