<?php

namespace src\controller;

use PHPUnit\Framework\TestCase;
use src\controller\choixBUT2Controller;
class choixBUT2ControllerTest extends TestCase {
    private choixBUT2Controller $controller;

    protected function setUp(): void {
        parent::setUp();
        $this->controller = new choixBUT2Controller();
    }

    // Test pour displayBut2Full
    public function testDisplayBut2Full() {
        $this->controller->displayBut2Full();
    }

    public function testDisplayBut2GA1() {
        $this->controller->displayBut2GA1();
    }
    public function testDisplayBut2GA2() {
        $this->controller->displayBut2GA2();
    }



}
