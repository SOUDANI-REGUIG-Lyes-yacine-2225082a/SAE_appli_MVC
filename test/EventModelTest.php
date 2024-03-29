<?php

namespace src\controller;
use PHPUnit\Framework\TestCase;
use src\model\EventModel;
class EventModelTest
{
    public function testGenerateIcsUrl() {
        $model = new EventModel();
        $url = $model->generateIcsUrl('BUT1g1', '2024-01-01', '2024-01-07');
        $this->assertStringContainsString('2024-01-01', $url);
        $this->assertStringContainsString('2024-01-07', $url);
    }
    public function testPrepareData() {
        $model = new EventModel();
        $cleanedData = $model->prepareData("Some\nText\nWith\nNewlines");
        $this->assertEquals("SomeTextWithNewlines", $cleanedData);
    }
    public function testRetrieveIcs() {
        $model = $this->createMock(EventModel::class);
        $model->method('recupIcs')->willReturn(/* Simuler la réponse attendue */);
        $events = $model->retrieveIcs('BUT1g1', '2024-01-01', '2024-01-07');
    }
    public function testGetAvailableRooms() {
        $model = new EventModel();
        $availableRooms = $model->getAvailableRooms();
    }

}