<table id="tableInterv" style="display:none">
    <thead>
        <tr>
            <th>Appel</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>Intervention</th>
            <th></th>
            <th></th>
            <th>Intervention sur</th>
            <?php
                foreach($listeSujets as $ligneSujet){
                    echo "<th></th>";
                }
            ?>
        </tr>
        <tr>
            <th>Date</th>
            <th>Heure d'appel</th>
            <th>Demandeur</th>
            <th>Lieu</th>
            <th style="border-right: solid #fff 2px;">Motif</th>
            <th>Début</th>
            <th>Fin</th>
            <th style="border-right: solid #fff 2px;">Durée</th>
            <?php
                foreach($listeSujets as $ligneSujet){
                    echo "<th>".$ligneSujet["libelle_sujet"]."</th>";
                }
            ?>
            <th style="border-left: solid #fff 2px;">Observations</th>            
        </tr>
    </thead>

    <tbody>
        <?php
        $dureeTotale = 0;
        foreach($listeInterventions as $ligne){
            $idDemandeur = $ligne['id_demandeur'];
            $sqlRequestDemandeur = $connection->prepare("SELECT `nom_demandeur` FROM `demandeur` WHERE `id_demandeur`=:id_demandeur");
            $sqlRequestDemandeur->bindParam(':id_demandeur', $idDemandeur);
            $sqlRequestDemandeur->execute();
            $nameDemandeur = $sqlRequestDemandeur->fetch();
            
            $timeFin = strtotime($ligne['heure_fin']); 
            $timeDebut = strtotime($ligne['heure_debut']);

            $date_appel = date('d/m/Y', strtotime($ligne['date_appel']));
            $heure_appel =  strtotime($ligne['heure_appel']);
            $displayHeureAppel = gmdate("H", $heure_appel)."h".gmdate("i", $heure_appel);

            $duree = gmdate("H", $timeFin-$timeDebut)."h".gmdate("i", $timeFin-$timeDebut);
            $dureeTotale = $dureeTotale + $timeFin-$timeDebut;
            $displayTimeDebut = gmdate("H", $timeDebut)."h".gmdate("i", $timeDebut);
            $displayTimeFin = gmdate("H", $timeFin)."h".gmdate("i", $timeFin);
            
            echo "<tr class='valuesTable'>";
            echo "<td>".$date_appel."</td>";
            echo "<td>".$displayHeureAppel."</td>";
            echo "<td>".$nameDemandeur[0]."</td>";
            echo "<td>".$ligne['lieu_appel']."</td>";
            echo "<td>".$ligne['motif_appel']."</td>";
            echo "<td>".$displayTimeDebut."</td>";
            echo "<td>".$displayTimeFin."</td>";
            echo "<td>".$duree."</td>";
            
            $id_interv = $ligne['id_interv'];
            $listeSujetInterv = getSujetsInterv($connection, $id_interv);

            foreach($listeSujets as $ligneSujets){

                if (in_array($ligneSujets['id_sujet'], $listeSujetInterv)){
                    echo "<td>X</td>";
                } else {
                    echo "<td></td>";
                }
                
            }
            echo "<td>".$ligne['observations_interv']."</td>";            
            echo "</tr>\n";
        }

        //CALCUL DES HEURES D'APPEL TOTAL
        echo "<tr>";
        echo "<td></td><td></td><td></td><td></td><td></td><td></td>";
        echo "<td>Durée totale</td>";
        echo "<td>".gmdate("H", $dureeTotale)."h".gmdate("i", $dureeTotale)."</td>";
        echo "</tr>";
        ?>
    </tbody>
</table>