<?php

namespace src\controller;

use src\model\ProfModel;
include 'src/view/ButEnseignant.php';

class ProfesseurController
{
    private ProfModel $model;

    public function __construct() {
        $this->model = new ProfModel();
    }

    public function ajouter() {
        $nom = $_POST['profName'] ?? '';
        $id = $_POST['profId'] ?? '';

        if ($this->model->ajouterProfesseur($nom, $id)) {
            header("Location: index.php?controller=professeur&action=index");
            exit();
        } else {
            echo "Erreur lors de l'ajout du professeur.";
        }
    }









    public function index() {
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nom = $_POST['profName'] ?? '';
            $id = $_POST['profId'] ?? '';

            if (empty($nom) || empty($id)) {
                $message = "Veuillez remplir tous les champs.";
            } else {
                if ($this->model->ajouterProfesseur($nom, $id)) {
                    $message = "Professeur ajouté avec succès.";
                } else {
                    $message = "Le professeur existe déjà ou une erreur s'est produite.";
                }
            }
        }

        $professeurs = $this->model->getListeProfesseurs();
        $profResourceIds = $this->model->profResourceIds;


        include 'src/view/ButEnseignant.php';
    }







    public function emploiDuTemps() {
        $profId = $_GET['profId'] ?? '';
        if (!$profId || !isset($this->model->profResourceIds[$profId])) {
            echo "Professeur non trouvé";
            return;
        }

        $emploiDuTemps = $this->model->retrieveIcs($profId, '2024-01-01', '2024-12-31'); // Dates exemples
        include '../view/ButEnseignant.php'; // Modifiez le chemin selon votre structure
    }


}
