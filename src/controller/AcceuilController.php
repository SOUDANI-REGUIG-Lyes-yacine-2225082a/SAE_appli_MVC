<?php
/*ROUE*/
class AcceuilController
{
    private $assetsPath = "../../_assets";
    private $roueChoixPage;

    public function displayRoue() {
    include "src/view/choixRoue.php";
}
}
