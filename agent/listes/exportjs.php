<?php 

//Préparation des colonnes
echo '<script type="text/javascript">

    var columns0 = [
        {title: "Appel", dataKey: "appel"},
        {title: "Intervention", dataKey: "interv"},
        {title: "Observations", dataKey: "observ"}
    ];
    var rows0 = [];
    var columns = [
        {title: "Date", dataKey: "date"},
        {title: "Heure", dataKey: "heure"}, 
        {title: "Demandeur", dataKey: "demandeur"},
        {title: "Lieu", dataKey: "lieu"},
        {title: "Motif de l\'appel", dataKey: "motif"},
        {title: "de (h)", dataKey: "from"},
        {title: "à (h)", dataKey: "to"},
        {title: "Durée", dataKey: "duree"},
        
        {title: "de (h)", dataKey: "from2"},
        {title: "à (h)", dataKey: "to2"},
        {title: "Durée", dataKey: "duree2"},
        {title: "de (h)", dataKey: "from3"},
        {title: "à (h)", dataKey: "to3"},
        {title: "Durée", dataKey: "duree3"},

        {title: "Intervention sur", dataKey: "intervention_sur"},';
    echo '{title: "Observations", dataKey: "observations"}
    ];
    ';
echo 'var rows = [';

//remettre si la durée totale est retirée
/*$nbReiteration = 0;*/
$dureeTotale = 0;
$dureeTotaleCadre = 0;
$dureeTotaleAM1 = 0;
$dureeTotaleAM2 = 0;

$typeAgent = "";
$listAgentAlready = [];
$lastAgent = 0;

$listeIntervChrono = array_reverse($listeInterventions);
foreach($listeIntervChrono as $ligne){

    if ($lastAgent != $ligne['id_preferences']){
        //Si le cadre d'astreinte est différent
        $lastAgent = $ligne['id_preferences'];
        echo '{
            "demandeur": "Cadre d\'astreinte",
            "lieu": "'.getNameFromUser($connection, $ligne['id_preferences']).'"},';
        $typeAgent = "";
        if (in_array($ligne['id_preferences'], $tableCadre)){
            $typeAgent = "cadre";
        } else {
            if (in_array($ligne['id_preferences'], $tableAM1)){
                $typeAgent = "AM1";
            } else {
                if (in_array($ligne['id_preferences'], $tableAM2)){
                    $typeAgent = "AM2";
                }
            }
        }

    }

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

    //la durée réelle si besoin
    $dureeReelle = gmdate("H", $timeFin-$timeDebut)."h".gmdate("i", $timeFin-$timeDebut);

    //calcul au quart d'heure
    $calculQuarts = 0;
    $calculQuarts = ceil(gmdate("i", $timeFin-$timeDebut)/15);
    $calculQuarts = $calculQuarts + gmdate("H", $timeFin-$timeDebut)*4;
    $HourFromQuarts = floor($calculQuarts / 4);
    if (!in_array($ligne['id_preferences'], $listAgentAlready)){
        $listAgentAlready[] = $ligne['id_preferences'];
        if ($HourFromQuarts == 0){
            $duree = "01h00";
            $calculQuarts = 4;
        } else {
            $minutesFromQuarts = ($calculQuarts % 4)*15;
            $duree = sprintf('%02d', $HourFromQuarts)."h".sprintf('%02d', $minutesFromQuarts);
        }
    } else {
        $minutesFromQuarts = ($calculQuarts % 4)*15;
        $duree = sprintf('%02d', $HourFromQuarts)."h".sprintf('%02d', $minutesFromQuarts);
    }

    
    switch ($typeAgent) {
        case 'cadre':
            $dureeTotaleCadre = $dureeTotaleCadre + $calculQuarts;
            $fromKey = "from";
            $toKey = "to";
            $dureeKey = "duree";
            break;
        case 'AM1':
            $dureeTotaleAM1 = $dureeTotaleAM1 + $calculQuarts;
            $fromKey = "from2";
            $toKey = "to2";
            $dureeKey = "duree2";
            break;
        case 'AM2':
            $dureeTotaleAM2 = $dureeTotaleAM2 + $calculQuarts;
            $fromKey = "from3";
            $toKey = "to3";
            $dureeKey = "duree3";
            break;
        default:
            $dureeTotaleCadre = $dureeTotaleCadre + $calculQuarts;
            $fromKey = "from";
            $toKey = "to";
            $dureeKey = "duree";
            break;
    }

    $displayTimeDebut = gmdate("H", $timeDebut)."h".gmdate("i", $timeDebut);
    $displayTimeFin = gmdate("H", $timeFin)."h".gmdate("i", $timeFin);
    
    $id_interv = $ligne['id_interv'];
    $listeSujetInterv = getSujetsInterv($connection, $id_interv);

    //Préparation de chaque ligne retournée
    echo '{
        "date": "'.$date_appel.'",
        "heure": "'.$displayHeureAppel.'",
        "demandeur": "'.$nameDemandeur[0].'", 
        "lieu": "'.$ligne['lieu_appel'].'", 
        "motif": "'.$ligne['motif_appel'].'",';
        
        if ($duree == '00h00'){
            echo '"'.$fromKey.'": "",
            "'.$toKey.'": "",
            "'.$dureeKey.'": "",';
        } else {
            echo '"'.$fromKey.'": "'.$displayTimeDebut.'",
            "'.$toKey.'": "'.$displayTimeFin.'",
            "'.$dureeKey.'": "'.$duree.'",';
        }
        echo '"intervention_sur": "';

    $nbReiterSujets = 0;

    foreach($listeSujets as $ligneSujets){
        if (in_array($ligneSujets['id_sujet'], $listeSujetInterv)){
            if ($nbReiterSujets != 0){
                echo '\n';
            }
            echo "-".$ligneSujets['libelle_sujet'];
            $nbReiterSujets++;
        }
    }

    echo '",
        "observations": "'.$ligne['observations_interv'].'"
        },';
}

echo '];
    </script>';


//Changer le format de la date
if (isset($_REQUEST['search'])){
    $fmt_d = new IntlDateFormatter(
        'fr_FR',
        IntlDateFormatter::NONE,
        IntlDateFormatter::NONE);

    $fmt_dM = new IntlDateFormatter(
        'fr_FR',
        IntlDateFormatter::NONE,
        IntlDateFormatter::NONE);

    $fmt_dMY = new IntlDateFormatter(
        'fr_FR',
        IntlDateFormatter::NONE,
        IntlDateFormatter::NONE);

    $fmt_d->setPattern('d');
    $fmt_dM->setPattern('d MMMM');

    if ($_REQUEST['search'] == 'week'){
        $fmt_dMY->setPattern('d MMMM');
    }
    if (($_REQUEST['search'] == 'date')){
        $fmt_dMY->setPattern('d MMMM YYYY');
    }
}

//Calcul des durées au quart d'heure par type d'agent
$dureeCadre = "";
$dureeAM1 = "";
$dureeAM2 = "";

if ((isset($dureeTotaleCadre)) && ($dureeTotaleCadre != 0)){
    $HourFromQuarts = floor($dureeTotaleCadre / 4);
    $minutesFromQuarts = ($dureeTotaleCadre % 4)*15;
    $dureeCadre = sprintf('%02d', $HourFromQuarts)."h".sprintf('%02d', $minutesFromQuarts);
}

if ((isset($dureeTotaleAM1)) && ($dureeTotaleAM1 != 0)){
    $HourFromQuarts = floor($dureeTotaleAM1 / 4);
    $minutesFromQuarts = ($dureeTotaleAM1 % 4)*15;
    $dureeAM1 = sprintf('%02d', $HourFromQuarts)."h".sprintf('%02d', $minutesFromQuarts);
}

if ((isset($dureeTotaleAM2)) && ($dureeTotaleAM2 != 0)){
    $HourFromQuarts = floor($dureeTotaleAM2 / 4);
    $minutesFromQuarts = ($dureeTotaleAM2 % 4)*15;
    $dureeAM2 = sprintf('%02d', $HourFromQuarts)."h".sprintf('%02d', $minutesFromQuarts);
}

echo '
<script type="text/javascript">

var columns2 = [
    {title: "", dataKey: "from"},
    {title: "", dataKey: "to"},
    {title: "", dataKey: "from2"},
    {title: "", dataKey: "to2"},
    {title: "", dataKey: "from3"},
    {title: "", dataKey: "to3"}
];
var rows2 = [{
        "from": "heures d\'appel",
        "to": "'.$dureeCadre.'",
        "from2": "heures d\'appel",
        "to2": "'.$dureeAM1.'",
        "from3": "heures d\'appel",
        "to3": "'.$dureeAM2.'",
},
{
    "from": "PAYER",
    "from2": "PAYER",
    "from3": "PAYER"
},
{
    "from": "RECUPERER",
    "from2": "RECUPERER",
    "from3": "RECUPERER"
},
{
    "from": "Frais kilométriques",
    "from2": "Frais kilométriques",
    "from3": "Frais kilométriques"
}];
</script>'
?>