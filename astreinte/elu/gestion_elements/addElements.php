<?php
session_start();
//Vérification que l'utilisateur connecté est bien un elu
if ((!isset($_SESSION['nom'])) || ($_SESSION['type']!="elu")) {
    header('Location:../index.php');
    die();
}
if ((!isset($_SESSION['adminRight'])) || (!$_SESSION['adminRight'])){
    header('Location:../index.php');
    die();
}

require '../confMySQL.php';

if (isset($_REQUEST['nomDemandeur'])){
    //Ajout d'un demandeur
    $nomDemandeur = htmlspecialchars($_REQUEST['nomDemandeur']);
    $update = $connection->prepare("INSERT INTO `demandeur`(`nom_demandeur`) VALUES (:nomDemandeur)");
    $update->bindParam(':nomDemandeur', $nomDemandeur);
    if ($update->execute()){
        header('Location:../index.php?action=gestElements&codeAdd=1');
    } else {
        header('Location:../index.php?action=gestElements&codeAdd=4');
    }
    
} else {
    //Ajout d'un sujet
    if (isset($_REQUEST['nomSujet'])){
        $nomSujet = htmlspecialchars($_REQUEST['nomSujet']);
        $update = $connection->prepare("INSERT INTO `sujet`(`libelle_sujet`) VALUES (:nomSujet)");
        $update->bindParam(':nomSujet', $nomSujet);
        if ($update->execute()){
            header('Location:../index.php?action=gestElements&codeAdd=2');
        } else {
            header('Location:../index.php?action=gestElements&codeAdd=5');
        }
    } else {
        header('Location:../index.php?action=gestElements&codeAdd=3');
    }
}
?>