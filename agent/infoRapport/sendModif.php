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
require '../confMySQL.php';
require '../fonctions/fonctionsGet.php';
if (!isset($_REQUEST['idInterv'])){
    header('Location:../index.php');
    die();
}

$dateInterv = htmlspecialchars($_REQUEST['laDate']);
$heureAppel = htmlspecialchars($_REQUEST['lheure']);
$lieuInterv = htmlspecialchars($_REQUEST['leLieu']);
$motifInterv = htmlspecialchars($_REQUEST['leMotif']);
$observations = htmlspecialchars($_REQUEST['lesObservations']);

if ((!empty($_REQUEST['debut'])) && (!empty($_REQUEST['fin']))){
    $heureDebut = htmlspecialchars($_REQUEST['debut']);
    $heureFin = htmlspecialchars($_REQUEST['fin']);    
} else {
    $heureDebut = 0;
    $heureFin = 0;  
}

$id_preferences = htmlspecialchars($_REQUEST['leCadre']);
$demandeur = htmlspecialchars($_REQUEST['leDemandeur']);
$idInterv = $_REQUEST['idInterv'];

//récupérer tout ce sur quoi est faite l'intervention
$sujetArray = [];

foreach ($_REQUEST as $varRequest => $valueRequest) {
    //détecte les variables REQUEST avec "sujet"
    if (strpos($varRequest, "sujet", 0) !== false){
        $sujetActuel = htmlspecialchars($valueRequest);
        $sujetArray[] = $sujetActuel;
    }
}

//La requête pour envoyer les modifs
$query = "UPDATE `intervention` 
        SET `date_appel`=:date_appel,`heure_appel`=:heure_appel,`lieu_appel`=:lieu_appel,
        `motif_appel`=:motif_appel,`heure_debut`=:heure_debut,`heure_fin`=:heure_fin,
        `observations_interv`=:observations_interv,`id_demandeur`=:id_demandeur,`id_preferences`=:id_preferences 
        WHERE `id_interv`=:id_interv";
$sql = $connection->prepare($query);
$sql->bindParam(':date_appel', $dateInterv);
$sql->bindParam(':heure_appel', $heureAppel);
$sql->bindParam(':lieu_appel', $lieuInterv);
$sql->bindParam(':motif_appel', $motifInterv);
$sql->bindParam(':heure_debut', $heureDebut);
$sql->bindParam(':heure_fin', $heureFin);
$sql->bindParam(':observations_interv', $observations);
$sql->bindParam(':id_demandeur', $demandeur);
$sql->bindParam(':id_preferences', $id_preferences);
$sql->bindParam(':id_interv', $idInterv);
$sql->execute();

//Entrer dans la BDD tout ce sur quoi est faite l'intervention
$sqlRemoveSujets = $connection->prepare("DELETE FROM `effectue_sur` WHERE `id_interv`=:id_interv");
$sqlRemoveSujets->bindParam(":id_interv", $idInterv);
$sqlRemoveSujets->execute();


if (count($sujetArray) != 0){
    $querySujets = "INSERT INTO `effectue_sur`(`id_sujet`, `id_interv`) VALUES ";
    //récupérer tout ce sur quoi est faite l'intervention
    for ($i=0; $i < count($sujetArray); $i++) { 
        //détecte les variables REQUEST avec "sujet"
            if ($i != 0){
                $querySujets .= ", ";
            }
            $querySujets .= "(:id_sujet".$i.", :id_interv".$i.")";
    } 
    $querySujets .= ";";

    $sqlSujets = $connection->prepare($querySujets);
    for ($i=0; $i < count($sujetArray); $i++) { 
        $sqlSujets->bindParam(":id_sujet$i", $sujetArray[$i]);
        $sqlSujets->bindParam(":id_interv$i", $idInterv);
    }
    $sqlSujets->execute();
}

$comeBack = "";
if ((isset($_REQUEST['w_plan'])) && isset($_REQUEST['y_plan'])){
    $comeBack = "&w_plan=".$_REQUEST['w_plan']."&y_plan=".$_REQUEST['y_plan'];
}

if (isset($_REQUEST['search_rapport'])){
    //si on cherchait par critère
    $comeBack = "&search_rapport=".$_REQUEST['search_rapport'];
    if (isset($_REQUEST['search_value'])){
        $comeBack .= "&search_value=".$_REQUEST['search_value'];
    }
    if ((isset($_REQUEST['search_debut'])) && (isset($_REQUEST['search_fin']))){
        $comeBack .= "&search_debut=".$_REQUEST['search_debut']."&search_fin=".$_REQUEST['search_fin'];
    }
}

header('Location:../index.php?infoRapport='.$idInterv.$comeBack.'&justEdit=true');

?>