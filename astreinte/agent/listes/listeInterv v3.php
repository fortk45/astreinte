<?php

$listeSujets = getSujets($connection);
$nbSujets = getNbSujets($connection);

$getInfos = "";
$src_icone = "../../image/info.png";

if ((isset($search_value)) || (isset($search_value_debut))){
    if (isset($search_value)){
        $getInfos = "&search_rapport=".$_REQUEST['search_rapport']."&search_value=".$search_value;
    }
    if (isset($search_value_debut)){
        $getInfos = "&search_rapport=".$_REQUEST['search_rapport']."&search_debut=".$search_value_debut."&search_fin=".$search_value_fin;
    }
} else {
    $getInfos = "&w_plan=".$week_planning."&y_plan=".$year_planning;
}
$src_icone = "../image/info.png";

include 'listes/completePDF.php';
include 'listes/exportjs.php';


?>
<script src = "https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
<script src = "https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.21/jspdf.plugin.autotable.min.js"></script>
<script src="listes/export.js"></script>


<div id="btnExport">
    <!--<button class="button1" onclick="exportTableToCSV('rapports.csv')">Exporter le tableau en csv</button>-->
    <?php if (isset($idWeek)){?>
        <button class="button1" onclick="exportToPDF(<?php echo '\''.$textLimitExport.'\',\''.$pdfListElu.'\',\''.$pdfListCadre.'\',\''.$AM1list.'\',\''.$AM2list.'\'' ?>)">Exporter le tableau en pdf</button>
    <?php } ?>
</div>

<table style="margin-botom:10px">
    <thead>
        <tr>
            <th rowspan="2" style="border-top: solid #fff 2px;width:min-content">Voir plus</th>
            <th colspan="3" class="surCategories">Appel</th>
            <th colspan="2" class="surCategories" style="border-right:0px">Intervention</th>
        </tr>
        <tr>
            <th >Appel</th>
            <th>Demandeur</th>
            <th>Lieu</th>
            <th style="border-right: solid #fff 2px;">Motif</th>
            <th style="border-right: solid #fff 2px;">Effectué sur</th>       
        </tr>
    </thead>

    <tbody>
        <?php

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
            $displayTimeDebut = gmdate("H", $timeDebut)."h".gmdate("i", $timeDebut);
            $displayTimeFin = gmdate("H", $timeFin)."h".gmdate("i", $timeFin);

            $id_interv = $ligne['id_interv'];

            echo "<tr class='valuesTable'>";
            echo "<td><a href='index.php?infoRapport=".$id_interv.$getInfos."' id='parametres2' style='padding:0px!important;'><img src='".$src_icone."' width='30px;'></a></td>";
            echo "<td>".$date_appel."<br/>À ".$displayHeureAppel."</td>";
            echo "<td>".$nameDemandeur[0]."</td>";
            echo "<td>".$ligne['lieu_appel']."</td>";
            echo "<td>".$ligne['motif_appel']."</td>";
            
            $listeSujetInterv = getSujetsInterv($connection, $id_interv);
            echo "<td class='sujetsList'><ul style='padding-left: 10px;margin-bottom: 5px;'>";
            foreach($listeSujets as $ligneSujets){

                if (in_array($ligneSujets['id_sujet'], $listeSujetInterv)){
                    echo "<li>".$ligneSujets['libelle_sujet']."</li>";
                }
            }
            echo "</ul></td>";
            echo "</tr>\n";
        }
        ?>
        
    </tbody>
</table>

<br/>

<?php include 'listeInterv_CSV.php' ?>