<?php

namespace src\controller;

/**
 * Contrôleur pour la gestion de la vue But1.
 */
class choixBUT1Controller extends BaseScheduleController {
    /**
     * Affiche la vue But1Edt.
     *
     * @return void
     */
    public function displayBut1Entier(){
        include "src/view/BUT1/But1Edt.php";
        $this->displayGroupSchedule2();
    }

    /**
     * Affiche la vue But1G1.
     *
     * @return void
     */

    public function displayBut1G1(){
        include "src/view/BUT1/But1G1.php";
        $this->displayGroupSchedule2();
    }


    /**
     * Affiche la vue But1G2.
     *
     * @return void
     */
    public function displayBut1G2(){
        include "src/view/BUT1/But1G2.php";
        $this->displayGroupSchedule2();
    }

    /**
     * Affiche la vue But1G3.
     *
     * @return void
     */
    public function displayBut1G3(){
        include "src/view/BUT1/But1G3.php";
        $this->displayGroupSchedule2();
    }

    /**
     * Affiche la vue But1G4.
     *
     * @return void
     */
    public function displayBut1G4(){
        include "src/view/BUT1/But1G4.php";
        $this->displayGroupSchedule2();
    }
}
