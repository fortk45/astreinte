<?php 
if ($adminRight && isset($adm_function) && $adm_function) {
    echo '<a href="index.php?action=gestElements" class="button1" style="margin:10px auto 10px auto">Gérer les demandeurs et sujets d\'intervention</a>';
}

$fromGestion = true;

//La préparation des variables en rapport avec la semaine et les jours de la semaine
$week_planning = date('W');
$year_planning = date('Y');
if ((isset($_REQUEST['w_plan'])) && (!empty($_REQUEST['w_plan']))){
    $week_planning = $_REQUEST['w_plan'];
}
if ((isset($_REQUEST['y_plan'])) && (!empty($_REQUEST['y_plan']))){
    $year_planning = $_REQUEST['y_plan'];
}

$w_start = new DateTime();
$w_start->setISODate($year_planning,$week_planning);
$w_start_string = $w_start->format('d/m/Y');
$laDate = $w_start->format('Y-m-d');

$w_end = new DateTime();
$w_end->setISODate($year_planning,$week_planning,7);
$w_end_string = $w_end->format('d/m/Y');

$previous_week = '&w_plan='.($week_planning-1).'&y_plan='.$year_planning;
$next_week = '&w_plan='.($week_planning+1).'&y_plan='.$year_planning;

if ($week_planning == 1){
    $previous_week = "&w_plan=52&y_plan=".($year_planning-1);
}
if ($week_planning == 52){
    $next_week = "&w_plan=1&y_plan=".($year_planning+1);
} 

$idWeek = getIDweek($connection, $week_planning, $year_planning);


    echo '<div class="nbResults weekBandeau">';

        //Le numéro de la semaine et les flèches pour changer de semaine
            echo '<span style="display:flex;margin-left:auto;margin-right:auto;width:max-content">
                <a href="index.php?action=planning'.$previous_week.'">
                    <img src="../image/arrow.png" class="arrow" style="margin-right:30px">
                </a>
                <h2>Semaine '.$week_planning.' de '.$year_planning.'</h2>
                <a href="index.php?action=planning'.$next_week.'">
                    <img src="../image/arrow.png" class="arrow" style="rotate:180deg;margin-left:30px">
                </a>
            </span>
            <div style="font-size:20px;font-weight:normal">Du '.$w_start_string.' au '.$w_end_string.'</div>'
            ?>

        <form action="planning/changedate.php" method="get">
            <label for="dateChange">Aller à la semaine du </label>
            <input type="date" name="dateChange" value="<?php echo $laDate;?>"><button type="submit" class="loupe" style="border-radius:20px;border:solid 1px;padding:2px 10px 2px 10px"><img src="../image/loupe.png" style="height:20px;width:20px"></button>
        </form> 
    </div>

<?php



/* LA LISTE DES AGENTS */

echo '<div style="text-align:center">';

if ($adminRight && isset($adm_function) && $adm_function) {
    /* AJOUTER UN AGENT */
    echo '<form action="planning/addInPlanning.php?week='.$week_planning.'&year='.$year_planning.'" method="post" class="bloc_listAgents">
        <div class="name_listAgents">Ajouter un agent</div>
        <select name="newAgent" id="newAgent" required>
            <option disabled selected value="">Sélectionner la personne à ajouter</option>';
            foreach (getNotFromWeek($connection, $idWeek) as $anUser) {
                echo "<option value=" . $anUser['id_preferences'] . ">" . $anUser['prenom'].' '.$anUser['nom'] . "</option>";
            }
        echo '</select>
        <br/>
        <select name="categSelect" id="categSelect" style="margin-top:10px" required>
            <option disabled selected value="">Sélectionner son rôle</option>
            <option value="elu">Elu</option>
            <option value="cadre">Cadre</option>
            <option value="AM1">AM1</option>
            <option value="AM2">AM2</option>
        </select>';

        echo '<input type="submit" class="button1" value="Ajouter">';
    echo '</form>';
}

    /* LISTE DES AGENTS */
    $removeButton = '';
    if ($adminRight && isset($adm_function) && $adm_function) {
        $removeButton1 = '<a href="planning/removeAfromW.php?a=';
        $removeButton2 = '&w='.$week_planning.'&y='.$year_planning.'"><img src="../image/croix.png" class="Croix-icon"></a>';
    }

    echo '<div class="bloc_listAgents">
            <div class="name_listAgents">Elus :</div>';

        foreach (getFromWeekAndRole($connection, $idWeek, 'elu') as $anUser) {
            echo '<div>';
            if ($adminRight && isset($adm_function) && $adm_function){
                echo $removeButton1 .$anUser['id_preferences'].$removeButton2;
            }
            echo $anUser['prenom'].' '.$anUser['nom'].'</div>';
        }
    echo '</div>';


    echo '<div class="bloc_listAgents">
        <div class="name_listAgents">Cadres :</div>';

        foreach (getFromWeekAndRole($connection, $idWeek, 'cadre') as $anUser) {
            echo '<div>';
            if ($adminRight && isset($adm_function) && $adm_function){
                echo $removeButton1 .$anUser['id_preferences'].$removeButton2;
            }
            echo $anUser['prenom'].' '.$anUser['nom'].'</div>';
        }
    echo '</div>';


    echo '<div class="bloc_listAgents">
        <div class="name_listAgents">AM1 :</div>';

        foreach (getFromWeekAndRole($connection, $idWeek, 'AM1') as $anUser) {
            echo '<div>';
            if ($adminRight && isset($adm_function) && $adm_function){
                echo $removeButton1 .$anUser['id_preferences'].$removeButton2;
            }
            echo $removeButton.$anUser['prenom'].' '.$anUser['nom'].'</div>';
        }
    echo '</div>';


    echo '<div class="bloc_listAgents">
        <div class="name_listAgents">AM2 :</div>';

        foreach (getFromWeekAndRole($connection, $idWeek, 'AM2') as $anUser) {
            echo '<div>';
            if ($adminRight && isset($adm_function) && $adm_function){
                echo $removeButton1 .$anUser['id_preferences'].$removeButton2;
            }
            echo $removeButton.$anUser['prenom'].' '.$anUser['nom'].'</div>';
        }
    echo '</div>';

echo '</div>';

$fmt = new IntlDateFormatter(
    'fr_FR',
    IntlDateFormatter::FULL,
    IntlDateFormatter::NONE);
    

//ON PREPARE L'AFFICHAGE DU TABLEAU DES RAPPORTS DE LA SEMAINE
$infosWeek = getIntervByWeek($connection, $laDate);
$listeInterventions = $infosWeek[0];

if (!empty($listeInterventions)){
    $firstDayWeek = $fmt->format(strtotime($infosWeek[2]));
    $lastDayWeek = $fmt->format(strtotime($infosWeek[3]));
    $textLimitExport = "Semaine :"."\t".$infosWeek[1]."\t\t\t\t Astreintes du ".$firstDayWeek."\t"."au"."\t".$lastDayWeek;
    //AFFICHAGE DU TABLEAU
    include 'listes/listeInterv v3.php';

} else {
    echo "<br/><div class='nbResults'>Le tableau des rapports s'affichera ici lorsqu'un rapport aura été entré</div>";
}
?>