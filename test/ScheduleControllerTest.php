<?php

namespace src\controller;

use PHPUnit\Framework\TestCase;
use src\model\EventModel;
use src\view\ScheduleView;

class ScheduleControllerTest extends TestCase {
    private ScheduleController $controller;

    protected function setUp(): void {
        parent::setUp();
        $this->controller = new ScheduleController();
        // Simuler les dépendances
        $this->controller->eventModel = $this->createMock(EventModel::class);
        $this->controller->scheduleView = $this->createMock(ScheduleView::class);
    }

    public function testShowAvailableRooms() {
        $this->controller->eventModel->method('retrieveIcsSalles')
            ->willReturn(/* Valeur simulée */);
        $this->controller->eventModel->method('getAvailableRooms')
            ->willReturn(/* Valeur simulée */);

        // Simuler $_SESSION
        $_SESSION['currentWeek'] = '2024-01-01';

        $this->controller->showAvailableRooms();

    }
}
