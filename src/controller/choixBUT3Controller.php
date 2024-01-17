<?php

namespace src\controller;
class choixBUT3Controller extends  BaseScheduleController {
    public function displayBut3GB(){
        include "src/view/BUT3/But3GB.php";
        $this->displayGroupSchedule2();
    }

    public function displayBut3GA1(){
        include "src/view/BUT3/But3GA1.php";
        $this->displayGroupSchedule2();
    }
    public function displayBut3GA2(){
        include "src/view/BUT3/But3GA2.php";
        $this->displayGroupSchedule2();
    }
    public function displayBut3Entier(){
        include "src/view/BUT3/But3Annee.php";
        $this->displayGroupSchedule2();
    }
}