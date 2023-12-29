<?php

namespace src\controller;

class choixBUT2Controller extends BaseScheduleController {
    public function displayBut2Full(){
        include "src/view/BUT2/But2Edt.php";
        $this->displayGroupSchedule('BUT2Annee', '2024-01-08', '2024-01-12');
    }
    public function displayBut2GA1(){
        include "src/view/BUT2/But2GA1.php";
        $this->displayGroupSchedule('BUT2ga1', '2024-01-08', '2024-01-12');
    }

    public function displayBut2GA2(){
        include "src/view/BUT2/But2GA2.php";
        $this->displayGroupSchedule('BUT2ga2', '2024-01-08', '2024-01-12');
    }

    public function displayBut2GB(){
        include "src/view/BUT2/But2GB.php";
        $this->displayGroupSchedule('BUT2gb', '2024-01-08', '2024-01-12');
    }
}