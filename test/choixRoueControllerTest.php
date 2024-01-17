<?php

namespace src\controller;

use PHPUnit\Framework\TestCase;
use src\controller\choixRoueController;
class choixRoueControllerTest extends TestCase {
    private choixRoueController $controller;

    protected function setUp(): void {
        parent::setUp();
        $this->controller = new choixRoueController();
    }

    // Test pour displayRoue
    public function testDisplayRoue() {
        $this->controller->displayRoue();
    }

    // Test pour displayBut1
    public function testDisplayBut1() {
        $this->controller->displayBut1();
    }
    // Test pour displayBut2
    public function testDisplayBut2() {
        $this->controller->displayBut2();
    }

// Test pour displayBut3
    public function testDisplayBut3() {
        $this->controller->displayBut3();
    }

// Test pour showAvailableRooms
    public function testShowAvailableRooms() {
        $this->controller->showAvailableRooms();
    }

// Test pour displayButEnseignant
    public function testDisplayButEnseignant() {
        $this->controller->displayButEnseignant();
    }

}
