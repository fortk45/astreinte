<?php
session_start();
//Vérification que l'utilisateur connecté est bien un agent
if ((!isset($_SESSION['nom'])) || ($_SESSION['type']!="agent")) {
    header('Location:../index.php');
    die();
}
require '../confMySQL.php';
require '../fonctions/fonctionsGet.php';
if (!isset($_REQUEST['dateInterv'])){
    header('Location:../index.php');
    die();
}

$dateInterv = htmlspecialchars($_REQUEST['dateInterv']);
$heureAppel = htmlspecialchars($_REQUEST['heureAppel']);
$lieuInterv = htmlspecialchars($_REQUEST['lieuInterv']);
$motifInterv = htmlspecialchars($_REQUEST['motifInterv']);
$observations = htmlspecialchars($_REQUEST['observations']);

if ((!empty($_REQUEST['heureDebut'])) && (!empty($_REQUEST['heureFin']))){
    $heureDebut = htmlspecialchars($_REQUEST['heureDebut']);
    $heureFin = htmlspecialchars($_REQUEST['heureFin']);    
} else {
    $heureDebut = 0;
    $heureFin = 0;  
}

//vérifier si le demandeur est nouveau

$verifDemandeur = htmlspecialchars($_REQUEST['demandeur']);
if (strpos($verifDemandeur, "newDemand_", 0) !== false){
    $demandeurValue = substr(strchr($verifDemandeur, '_'), 1);

    $sqlAddDemand = $connection->prepare("INSERT INTO `demandeur`(`nom_demandeur`) VALUES (:newDemandeur)");
    $sqlAddDemand->bindParam(':newDemandeur', $demandeurValue);
    $sqlAddDemand->execute();
    $demandeur = $connection->lastInsertId();
} else {
    $demandeur = $verifDemandeur;
}

//récupérer tout ce sur quoi est faite l'intervention
$sujetArray = [];

foreach ($_REQUEST as $varRequest => $valueRequest) {

    //détecte les variables REQUEST avec "sujet"
    if (strpos($varRequest, "sujet", 0) !== false){
        $sujetActuel = htmlspecialchars($valueRequest);
        $sujetArray[] = $sujetActuel;
    }

    //détecte les nouveaux sujets
    if (strpos($varRequest, "newSujet_", 0) !== false){
        $sujetActuel = htmlspecialchars($valueRequest);
        $sqlAddSujet = $connection->prepare("INSERT INTO `sujet`(`libelle_sujet`) VALUES (:sujetActuel)");
        $sqlAddSujet->bindParam(':sujetActuel', $sujetActuel);
        $sqlAddSujet->execute();
        $idSujet = $connection->lastInsertId();
        $sujetArray[] = $idSujet;
    }
}

//Récupérer l'ID de l'utilisateur
$id_preferences = getIDUser($connection);


$query = "INSERT INTO `intervention` 
                            (`date_appel`, `heure_appel`, `lieu_appel`, `motif_appel`, 
                            `heure_debut`, `heure_fin`, `observations_interv`, 
                            `id_demandeur`, `id_preferences`)

                    VALUES (:date_appel, :heure_appel, :lieu_appel, :motif_appel,
                            :heure_debut, :heure_fin, :observations_interv, 
                            :id_demandeur, :id_preferences)";

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
$sql->execute();
$idInterv = $connection->lastInsertId();

//Entrer dans la BDD tout ce sur quoi est faite l'intervention

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


header('Location:../index.php?infoRapport='.$idInterv.'&justSent=true');


?>