<?php
use src\controller\ProfesseurController;
class Router {
    private $routes = [];

    public function addRoute($group, $controller, $method) {
    $this->routes[$group] = ['controller' => $controller, 'method' => $method];
    }

    public function dispatch($group) {
        if ($_GET['group'] == 'supprimer') {
            $controller = new ProfesseurController();
            $controller->supprimer();
        }
        if ($group === 'professeur') {
            $controller = new ProfesseurController();
            $controller->displayEnseignant();
        } elseif (array_key_exists($group, $this->routes)) {
        $controllerName = $this->routes[$group]['controller'];
        $methodName = $this->routes[$group]['method'];
        $controller = new $controllerName;
        $controller->$methodName();

    } else {
    echo "Page not found! ";
        }
    }
}
