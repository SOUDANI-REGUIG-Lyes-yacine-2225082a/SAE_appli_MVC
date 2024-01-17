<?php

namespace src\controller;

use PHPUnit\Framework\TestCase;


use src\controller\BaseScheduleController;


class BaseScheduleControllerTest extends TestCase {

    public function testConstructInitializesObjectsAndSession() {
        $controller = new BaseScheduleController();
        $this->assertArrayHasKey('currentWeek', $_SESSION);
    }

    public function testDisplayGroupSchedule2() {
        $_GET['group'] = 'TestGroup';
        $_SESSION['currentWeek'] = '2024-01-01';

        $controller = new BaseScheduleController();
        $controller->displayGroupSchedule2();

    }

    public function testGetCurrentWeekDates() {
        $controller = new BaseScheduleController();
        $_SESSION['currentWeek'] = '2024-01-03';

        // Appel de la méthode
        $weekDates = $controller->getCurrentWeekDates();

        $this->assertEquals('2023-12-31', $weekDates['firstDate'], "La première date devrait être le premier jour de la semaine (dimanche).");
        $this->assertEquals('2024-01-06', $weekDates['lastDate'], "La dernière date devrait être le dernier jour de la semaine (samedi).");
    }

}