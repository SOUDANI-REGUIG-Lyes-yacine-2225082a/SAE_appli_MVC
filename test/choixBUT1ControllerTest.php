<?php

namespace src\controller;

use PHPUnit\Framework\TestCase;
use src\controller\choixBUT1Controller;
class choixBUT1ControllerTest extends TestCase {
    private choixBUT1Controller $controller;

    protected function setUp(): void {
        parent::setUp();
        $this->controller = new choixBUT1Controller();
    }

    public function testDisplayBut1Entier() {
        $this->controller->displayBut1Entier();
    }

    // Test pour displayBut1G1
    public function testDisplayBut1G1() {
        $this->controller->displayBut1G1();
    }
    public function testDisplayBut1G2() {
        $this->controller->displayBut1G2();
    }
    public function testDisplayBut1G3() {
        $this->controller->displayBut1G3();
    }
    public function testDisplayBut1G4() {
        $this->controller->displayBut1G4();
    }


}
