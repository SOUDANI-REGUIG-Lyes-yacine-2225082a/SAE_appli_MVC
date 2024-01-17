<?php

namespace src\controller;

use PHPUnit\Framework\TestCase;
use src\controller\choixBUT1Controller;
class choixBUT3ControllerTest extends TestCase {
    private choixBUT3Controller $controller;

    protected function setUp(): void {
        parent::setUp();
        $this->controller = new choixBUT3Controller();

    }
    public function testDisplayBut3GB() {
        $this->controller->displayBut3GB();
    }

    // Test pour displayBut3GA1
    public function testDisplayBut3GA1() {
        $this->controller->displayBut3GA1();
    }
    public function testDisplayBut3GA2() {
        $this->controller->displayBut3GA2();
    }

}
