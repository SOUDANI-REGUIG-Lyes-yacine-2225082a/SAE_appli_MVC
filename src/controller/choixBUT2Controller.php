<?php

namespace src\controller;

class choixBUT2Controller extends BaseScheduleController {
    public function displayBut2Full(){
        include "src/view/BUT2/But2Edt.php";
        $this->displayGroupSchedule2();
    }

    public function displayBut2GA1(){
        include "src/view/BUT2/But2GA1.php";
        $this->displayGroupSchedule2();
    }

    public function displayBut2GA2(){
        include "src/view/BUT2/But2GA2.php";
        $this->displayGroupSchedule2();
    }

    public function displayBut2GB(){
        include "src/view/BUT2/But2GB.php";
        $this->displayGroupSchedule2();
    }
}