<?php
use src\controller\BaseScheduleController;
class Router {
    private $routes = [];

    public function addRoute($action, $controller, $method) {
    $this->routes[$action] = ['controller' => $controller, 'method' => $method];
}

    public function dispatch($action) {
        $controller = new BaseScheduleController();

        if ($action == 'prevWeek' || $action == 'nextWeek') {
            $controller->handleWeekNavigation();
        }
    elseif (array_key_exists($action, $this->routes)) {
        $controllerName = $this->routes[$action]['controller'];
        $methodName = $this->routes[$action]['method'];
        $controller = new $controllerName;
        $controller->$methodName();

    } else {
    // Gérer l'action par défaut ou l'erreur 404 ici
    echo "Page not found! ya pas weshhh";
}
}
}
