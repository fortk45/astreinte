<?php

$idInterv = $_REQUEST['modifRapport'];
$infos = getAnInterv($connection, $idInterv);

$timeFin = strtotime($infos['heure_fin']); 
$timeDebut = strtotime($infos['heure_debut']);

$date_appel = date('Y-m-d', strtotime($infos['date_appel']));
$heure_appel =  strtotime($infos['heure_appel']);
$displayHeureAppel = gmdate("H", $heure_appel).":".gmdate("i", $heure_appel);

$duree = gmdate("H", $timeFin-$timeDebut)."h".gmdate("i", $timeFin-$timeDebut);
$displayTimeDebut = gmdate("H", $timeDebut).":".gmdate("i", $timeDebut);
$displayTimeFin = gmdate("H", $timeFin).":".gmdate("i", $timeFin);

//RECUPERER LE DEMANDEUR ET LE CADRE
$idDemandeur = $infos['id_demandeur'];
$idCadre = $infos['id_preferences'];

//RECHERCHER LES SUJETS
$listeSujetInterv = getSujetsInterv($connection, $idInterv);

?>

<form action="infoRapport/sendModif.php?idInterv=<?php echo $idInterv.'&'.$urlReturn ?>" method="post" class="fiche_Info" style="padding:10px">
    <table>
        <tr>
            <th colspan="2" class='caption'>Appel</th>
        </tr>
        <tbody class='valueInfos'>
            <tr>
                <th scope="row"><label for="laDate">Date</label></th>
                <td> <input type="date" name="laDate" value=<?php echo $date_appel ?> required></td> 
            </tr>
            <tr>
                <th scope="row"><label for="lheure">Heure</label></th>
                <td> <input type="time" name="lheure" value=<?php echo $displayHeureAppel ?> required></td> 
            </tr>
            <tr>
                <th scope="row"><label for="leDemandeur">Demandeur</label></th>
                <td>
                    <select name='leDemandeur' style='width:auto' required>
                        <option disabled value="">Sélection du demandeur</option>
                        <?php foreach (getDemandeurs($connection) as $ligne) {
                            echo '<option value="'.$ligne[0].'"'; 
                            if ($ligne[0] == $idDemandeur){
                                echo " selected";
                            }
                            echo ">" . $ligne[1] . "</option>";
                        }?>
                    </select>
                </td> 
            </tr>
            <tr>
                <th scope="row"><label for="leCadre">Cadre</label></th>
                <td> 
                    <select name='leCadre' style="width:auto" required>
                        <option disabled value="">Sélection du cadre</option>
                        <?php foreach (getAllUsers($connection) as $ligne) {
                            echo '<option value="'.$ligne[0].'"';
                            if ($ligne[0] == $idCadre){
                                echo " selected";
                            }
                            echo ">" . $ligne['prenom'].' '.$ligne['nom'] . "</option>";
                        }?>
                    </select>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="leLieu">Lieu</label></th>
                <td> <input type="text" name="leLieu" list="liste_lieux" value="<?php echo $infos['lieu_appel'] ?>" required></td> 
            </tr>
            <tr>
                <th scope="row"><label for="leMotif">Motif</label></th>
                <td> <textarea name="leMotif" required><?php echo $infos['motif_appel'] ?></textarea></td> 
            </tr>

            <datalist id="liste_lieux">
                <?php
                    //La liste des lieux déjà entrés
                    foreach (getLieux($connection) as $unLieu){
                        echo '<option value="'.$unLieu[0].'">';
                    }
                ?>
            </datalist>
            
        </tbody>

        
        <tr>
            <th colspan="2" class='caption'><br/>Intervention</th>
        </tr>
        <tbody class='valueInfos'>

            <tr>
                <th scope="row"><label for="debut">Début</label></th>
                <td> <input type="time" name="debut" value=<?php echo $displayTimeDebut ?>></td> 
            </tr>
            <tr>
                <th scope="row"><label for="fin">Fin</label></th>
                <td> <input type="time" name="fin" value=<?php echo $displayTimeFin ?>></td> 
            </tr>

        </tbody>
    </table>
    <br/>
    <b class="caption">Effectué sur</b>
        <?php
            echo "<div class='row'>";
                foreach (getSujets($connection) as $ligne) {
                    echo "<div class='col-6 aCheckbox'><input type='checkbox' value=" . $ligne[0] . " name='sujet".$ligne[0]."' id='sujet".$ligne[0]."'";
                    if (in_array($ligne[0], $listeSujetInterv)){
                        echo "checked";
                    }
                    echo "><label for='sujet".$ligne[0]."' class='label_Sujets label_Size'>" . $ligne[1] . "</label></div>";
                
                }
            echo "</div>";
        ?>

    <?php
    echo "<b class='caption'><label for='lesObservations'>Observations</b></label><br/>";
    echo "<div style='margin-left:auto;margin-right:auto;text-align:center;'>".'<textarea name="lesObservations">'.$infos['observations_interv'].'</textarea></div>';
    ?>

    <span style="display:flex">
        <input class="button1" type="submit" style="margin-left:10px;margin-bottom:20px" value="Confirmer">
        <a href="index.php?infoRapport=<?php echo $idInterv.'&'.$urlReturn?>" class="button1"  style="margin-left:auto; margin-right:20px;margin-bottom:20px">Annuler</a>
    </span>
</form>