<?php

if (isset($_REQUEST['infoRapport'])){
    $idInterv = $_REQUEST['infoRapport'];
} else {
    if (isset($_REQUEST['modifRapport'])){
        $idInterv = $_REQUEST['modifRapport'];
    }
}

if (!$infos = getAnInterv($connection, $idInterv)){
    $headerComeback = 'index.php?action=planning';
    if ((isset($_REQUEST['w_plan'])) && (isset($_REQUEST['y_plan']))){
        $headerComeback .= '&w_plan='.$_REQUEST['w_plan'].'&y_plan='.$_REQUEST['y_plan'];
    } 
    echo "<script type='text/javascript'>
        window.location.href = '".$headerComeback."';
    </script>";
    die();
}

$timeFin = strtotime($infos['heure_fin']); 
$timeDebut = strtotime($infos['heure_debut']);

$date_appel = date('d/m/Y', strtotime($infos['date_appel']));
$heure_appel =  strtotime($infos['heure_appel']);
$displayHeureAppel = gmdate("H", $heure_appel)."h".gmdate("i", $heure_appel);

$duree = gmdate("H", $timeFin-$timeDebut)."h".gmdate("i", $timeFin-$timeDebut);
$displayTimeDebut = gmdate("H", $timeDebut)."h".gmdate("i", $timeDebut);
$displayTimeFin = gmdate("H", $timeFin)."h".gmdate("i", $timeFin);

//RECHERCHER LE NOM DU DEMANDEUR
$idDemandeur = $infos['id_demandeur'];
$sqlRequestDemandeur = $connection->prepare("SELECT `nom_demandeur` FROM `demandeur` WHERE `id_demandeur`=:id_demandeur");
$sqlRequestDemandeur->bindParam(':id_demandeur', $idDemandeur);
$sqlRequestDemandeur->execute();
$nameDemandeur = $sqlRequestDemandeur->fetch();

//Rechercher le nom du cadre
$idCadre = $infos['id_preferences'];
$sqlRequestCadre = $connection->prepare("SELECT `prenom`, `nom` FROM `preferences` WHERE `id_preferences`=:id_preferences");
$sqlRequestCadre->bindParam(':id_preferences', $idCadre);
$sqlRequestCadre->execute();
$nomCadre = $sqlRequestCadre->fetch();

//RECHERCHER LES SUJETS
$listeSujets = getSujets($connection);
$listeSujetInterv = getSujetsInterv($connection, $idInterv);


//Si un formulaire vient d'être envoyé
if (isset($_REQUEST['justSent'])){
    echo "<div class='confirmSent'>Rapport envoyé !</div>";
}
if (isset($_REQUEST['justEdit'])){
    echo "<div class='confirmSent'>Rapport mis à jour !</div>";
}

?>

<div class="fiche_Info">
    <?php
    if ($adminRight && isset($adm_function) && $adm_function) {
        echo '<a href="index.php?modifRapport='.$idInterv.'&'.$urlReturn.'" class="button1 paramfiles_button" style="margin-inline-start: -30px;margin-top: -30px;border-radius: 20px"><span class="tooltiptext">Modifier</span></a>';
    }
    ?>
    <table>
        <tr>
            <th colspan="2" class='caption'>Appel</th>
        </tr>
        <tbody class='valueInfos'>
            <tr>
                <th scope="row">Date</th>
                <td> <?php echo $date_appel ?> </td>
            </tr>
            <tr>
                <th scope="row">Heure</th>
                <td> <?php echo $displayHeureAppel ?> </td>
            </tr>
            <tr>
                <th scope="row">Demandeur</th>
                <td> <?php echo $nameDemandeur[0] ?> </td>
            </tr>
            <tr>
                <th scope="row">Cadre</th>
                <td> <?php echo $nomCadre['prenom']." ".$nomCadre['nom'] ?> </td>
            </tr>
            <tr>
                <th scope="row">Lieu</th>
                <td> <?php echo $infos['lieu_appel'] ?> </td>
            </tr>
            <tr>
                <th scope="row">Motif</th>
                <td> <?php echo $infos['motif_appel'] ?> </td>
            </tr>
            
        </tbody>

            <tr>
                <th colspan="2" class='caption'><br/>Intervention</th>
            </tr>
            <tbody class='valueInfos'>
            <tr>
                <th scope="row">Début</th>
                <td> <?php echo $displayTimeDebut ?> </td>
            </tr>
            <tr>
                <th scope="row">Fin</th>
                <td> <?php echo $displayTimeFin ?> </td>
            </tr>
            <tr>
                <th scope="row">Durée</th>
                <td> <?php echo $duree ?> </td>
            </tr>
        </tbody>
    </table>
    <br/>
    <b class="caption">Effectué sur</b>
    <ul>
        <?php 
            foreach($listeSujets as $ligneSujets){
                if (in_array($ligneSujets['id_sujet'], $listeSujetInterv)){
                    echo "<li>".$ligneSujets['libelle_sujet']."</li>";
                }
            }
        ?>
    </ul>

    <?php
    echo "<b class='caption'>Observations</b><br/>";
    echo "<div style='margin-left:auto;margin-right:auto;text-align:center;'>".$infos['observations_interv']."</div>";
    ?>