<?php

namespace src\controller;

class ProfModelTest
{
    public function testSupprimerProfesseur() {
        $model = new ProfModel();
        $model->supprimerProfesseur('NomProf');
    }
    public function testGenerateIcsUrl() {
        $model = new ProfModel();
        $url = $model->generateIcsUrl('NomGroupe', '2024-01-01', '2024-01-07');
        $this->assertStringContainsString('2024-01-01', $url);
        $this->assertStringContainsString('2024-01-07', $url);
    }
    public function testPrepareData() {
        $model = new ProfModel();
        $cleanedData = $model->prepareData("Some\nText\nWith\nNewlines");
        $this->assertEquals("SomeTextWithNewlines", $cleanedData);
    }
    public function testRetrieveIcs() {
        $model = $this->createMock(ProfModel::class);
        $model->method('recupIcs')->willReturn(/* Simuler la réponse attendue */);
        $events = $model->retrieveIcs('NomGroupe', '2024-01-01', '2024-01-07');
    }

}