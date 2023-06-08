<?php
session_start();
//Vérification que l'utilisateur connecté est bien un agent
if ((!isset($_SESSION['nom'])) || ($_SESSION['type']!="agent")) {
    header('Location:../index.php');
    die();
}
if ((!isset($_SESSION['adminRight'])) || (!$_SESSION['adminRight'])){
    header('Location:../index.php');
    die();
}

require '../confMySQL.php';

if (isset($_REQUEST['idDemandeur'])){
    //MODIFICAION D'UN DEMANDEUR
    $idDemandeur = htmlspecialchars($_REQUEST['idDemandeur']);
    $nomDemandeur = htmlspecialchars($_REQUEST['nomDemandeur']);
    $update = $connection->prepare("UPDATE `demandeur` SET `nom_demandeur`=:nomDemandeur WHERE `id_demandeur`=:idDemandeur");
    $update->bindParam(':idDemandeur', $idDemandeur);
    $update->bindParam(':nomDemandeur', $nomDemandeur);
    if ($update->execute()){
        header('Location:../index.php?action=gestElements&codeUpdate=1');
    } else {
        header('Location:../index.php?action=gestElements&codeUpdate=4');
    }
    
} else {
    //MODIFICATION D'UN SUJET
    if (isset($_REQUEST['idSujet'])){
        $idSujet = htmlspecialchars($_REQUEST['idSujet']);
        $nomSujet = htmlspecialchars($_REQUEST['nomSujet']);
        $update = $connection->prepare("UPDATE `sujet` SET `libelle_sujet`=:nomSujet WHERE `id_sujet`=:idSujet");
        $update->bindParam(':idSujet', $idSujet);
        $update->bindParam(':nomSujet', $nomSujet);
        if ($update->execute()){
            header('Location:../index.php?action=gestElements&codeUpdate=2');
        } else {
            header('Location:../index.php?action=gestElements&codeUpdate=5');
        }
    } else {
        header('Location:../index.php?action=gestElements&codeUpdate=3');
    }
}
?>