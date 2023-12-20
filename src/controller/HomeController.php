<?php

require_once 'src/view/HomeView.php';

class HomeController {

    private $view;

    public function __construct() {
        $this->view = new HomeView();
    }

    public function handleRequest() {
        $this->view->displayHome();
    }
}
