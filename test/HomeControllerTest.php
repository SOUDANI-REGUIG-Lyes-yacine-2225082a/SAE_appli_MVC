<?php

namespace src\controller;

use PHPUnit\Framework\TestCase;
use src\controller\HomeController;
class HomeControllerTest extends TestCase {
    private HomeController $controller;

    protected function setUp(): void {
        parent::setUp();
        $this->controller = new HomeController();
    }

    public function testDisplayHome() {
        $this->controller->displayHome();
    }
}
