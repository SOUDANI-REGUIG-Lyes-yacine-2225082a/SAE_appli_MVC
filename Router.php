<?php
use src\controller\BaseScheduleController;
class Router {
    private $routes = [];

    public function addRoute($group, $controller, $method) {
    $this->routes[$group] = ['controller' => $controller, 'method' => $method];
}

    public function dispatch($group) {
        if (array_key_exists($group, $this->routes)) {
        $controllerName = $this->routes[$group]['controller'];
        $methodName = $this->routes[$group]['method'];
        $controller = new $controllerName;
        $controller->$methodName();

    } else {
    // Gérer l'action par défaut ou l'erreur 404 ici
    echo "Page not found! ya pas weshhh";
}
}
}
