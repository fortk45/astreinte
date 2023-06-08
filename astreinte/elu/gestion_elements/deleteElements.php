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

require '../fonctions/fonctionsGet.php';
require '../fonctions/fonctionsGetInterv.php';
require '../confMySQL.php';

if (isset($_REQUEST['idDemandeur'])){
    //SUPPRESSION D'UN DEMANDEUR
    $idDemandeur = htmlspecialchars($_REQUEST['idDemandeur']);
    $verifNotUsed = getIntervByDemandeur($connection, $idDemandeur);
    if (!empty($verifNotUsed)){
        header('Location:../index.php?action=gestElements&codeDelete=6');
    } else {
        $delete = $connection->prepare("DELETE FROM `demandeur` WHERE `id_demandeur`=:idDemandeur");
        $delete->bindParam(':idDemandeur', $idDemandeur);
        if ($delete->execute()){
            header('Location:../index.php?action=gestElements&codeDelete=1');
        } else {
            header('Location:../index.php?action=gestElements&codeDelete=4');
        }
    }    
} else {
    //SUPPRESSION D'UN SUJET
    if (isset($_REQUEST['idSujet'])){
        $idSujet = htmlspecialchars($_REQUEST['idSujet']);
        $verifNotUsed = getIntervBySujet($connection, $idDemandeur);
        if (!empty($verifNotUsed)){
            header('Location:../index.php?action=gestElements&codeDelete=7');
        } else {
            $delete = $connection->prepare("DELETE FROM `sujet` WHERE `id_sujet`=:idSujet");
            $delete->bindParam(':idSujet', $idSujet);
            if ($delete->execute()){
                header('Location:../index.php?action=gestElements&codeDelete=2');
            } else {
                header('Location:../index.php?action=gestElements&codeDelete=5');
            }
        }
    } else {
        header('Location:../index.php?action=gestElements&codeDelete=3');
    }
}
?>
