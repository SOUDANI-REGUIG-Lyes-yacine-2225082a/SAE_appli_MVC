<?php
/*ROUE*/
class choixRoueController
{
    private $assetsPath = "../../_assets";
    private $roueChoixPage;

    public function displayRoue() {
    include "src/view/choixRoue.php";
    }
    public function displayBut1() {
        include "src/view/But1View.php";
    }
    public function displayBut2() {
        include "src/view/But2View.php";
    }
    public function displayBut3() {
        include "src/view/But2View.php";
    }


}
