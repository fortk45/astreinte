<?php
//Suite texte du haut du PDF (à compléter)
if (isset($idWeek)){
    $iterAgent = 0;
    $pdfListElu = "";
    foreach (getFromWeekAndRole($connection, $idWeek, 'elu') as $anAgent) {
        if ($iterAgent != 0){
            $pdfListElu .= " - ";
        }
        $pdfListElu .= $anAgent['prenom'].' '.$anAgent['nom'];
        $iterAgent++;
    }

    $iterAgent = 0;
    $pdfListCadre = "";
    $tableCadre = [];
    foreach (getFromWeekAndRole($connection, $idWeek, 'cadre') as $anAgent) {
        if ($iterAgent != 0){
            $pdfListCadre .= ",";
        }
        $pdfListCadre .= $anAgent['prenom'].' '.$anAgent['nom'];
        $tableCadre[] = $anAgent['id_preferences'];
        $iterAgent++;
    }

    $iterAgent = 0;
    $AM1list = "";
    $tableAM1 = [];
    foreach (getFromWeekAndRole($connection, $idWeek, 'AM1') as $anAgent) {
        if ($iterAgent != 0){
            $AM1list .= ",";
        }
        $AM1list .= $anAgent['prenom'].' '.$anAgent['nom'];
        $tableAM1[] = $anAgent['id_preferences'];
        $iterAgent++;
    }

    $iterAgent = 0;
    $AM2list = "";
    $tableAM2 = [];
    foreach (getFromWeekAndRole($connection, $idWeek, 'AM2') as $anAgent) {
        if ($iterAgent != 0){
            $AM2list .= ",";
        }
        $AM2list .= $anAgent['prenom'].' '.$anAgent['nom'];
        $tableAM2[] = $anAgent['id_preferences'];
        $iterAgent++;
    }
}

/*Pour écrire en dur la liste des cadres et leurs dates, retirer des commentaires la variable ci-dessous
    et compléter en tant que texte séparé par une virgule (exemple : "Cadre 1,Cadre 2";)*/
//$pdfListCadre = ""; 
?>