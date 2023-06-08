<?php
session_start();
//Vérification que l'utilisateur connecté est bien un elu
if ((!isset($_SESSION['nom'])) || ($_SESSION['type']!="elu")) {
    header('Location:../index.php');
    die();
}

//Vérification que l'utilisateur connecté aie bien les droits d'admin
if ((!isset($_SESSION['adminRight'])) || (!$_SESSION['adminRight'])){
    header('Location:../index.php');
    die();
}

$declareLeGetIDWeek = true;
if ((!isset($_REQUEST['newAgent'])) || (!isset($_REQUEST['categSelect']))){
    header('Location:../index.php');
    die();
}

//Importation des fonctions
require '../fonctions/fonctionsGet.php';
require '../fonctions/fonctionsGetInterv.php';
require '../confMySQL.php';

$newAgent = htmlspecialchars($_REQUEST['newAgent']);
$categSelect = htmlspecialchars($_REQUEST['categSelect']);

//vérifier si la semaine existe
$week = htmlspecialchars($_REQUEST['week']);
$year = htmlspecialchars($_REQUEST['year']);

$idWeek = getIDweek($connection, $week, $year);
if ($idWeek == 0){
    $sqlAddWeek = $connection->prepare("INSERT INTO `semaine`(`semaine`, `annee`) VALUES (:theWeek, :theYear)");
    $sqlAddWeek->bindParam(':theWeek', $week);
    $sqlAddWeek->bindParam(':theYear', $year);
    $sqlAddWeek->execute();
    $idWeek = getIDweek($connection, $week, $year);
}

$sql = $connection->prepare("INSERT INTO `associer`(`id_preferences`, `id_semaine`, `categ`) VALUES (:newAgent,:idWeek,:categSelect)");
$sql->bindParam(':newAgent', $newAgent);
$sql->bindParam(':idWeek', $idWeek);
$sql->bindParam(':categSelect', $categSelect);
$sql->execute();

header('Location:../index.php?action=planning&w_plan='.$week.'&y_plan='.$year);

?>