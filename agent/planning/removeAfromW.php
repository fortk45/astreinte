<?php
session_start();
//Vérification que l'utilisateur connecté est bien un agent
if ((!isset($_SESSION['nom'])) || ($_SESSION['type']!="agent")) {
    header('Location:../index.php');
    die();
}

//Vérification que l'utilisateur connecté aie bien les droits d'admin
if ((!isset($_SESSION['adminRight'])) || (!$_SESSION['adminRight'])){
    header('Location:../index.php');
    die();
}

$declareLeGetIDWeek = true;
if ((!isset($_REQUEST['a'])) || (!isset($_REQUEST['w'])) || (!isset($_REQUEST['y']))){
    header('Location:../index.php');
    die();
}

require '../fonctions/fonctionsGet.php';
require '../fonctions/fonctionsGetInterv.php';
require '../confMySQL.php';



$agent = htmlspecialchars($_REQUEST['a']);
$week = htmlspecialchars($_REQUEST['w']);
$year = htmlspecialchars($_REQUEST['y']);

$idWeek = getIDweek($connection, $week, $year);


$sql = $connection->prepare("DELETE FROM `associer` WHERE `id_preferences`=:newAgent AND `id_semaine`=:idWeek");
$sql->bindParam(':newAgent', $agent);
$sql->bindParam(':idWeek', $idWeek);
$sql->execute();

header('Location:../index.php?action=planning&w_plan='.$week.'&y_plan='.$year);


?>