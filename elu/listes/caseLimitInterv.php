<?php
$textLimitExport = '';
$fmt = new IntlDateFormatter(
    'fr_FR',
    IntlDateFormatter::FULL,
    IntlDateFormatter::NONE);
    
if(isset($_REQUEST['search_rapport'])){

    if(isset($_REQUEST['search_value'])){
        $search_value = $_REQUEST['search_value'];
    } else {
        if (isset($_REQUEST['search_debut'])){
            $search_value_debut = $_REQUEST['search_debut'];
            $search_value_fin = $_REQUEST['search_fin'];
        }
    }

    switch ($_REQUEST['search_rapport']) {
        case 'all':
            $listeInterventions = getInterv($connection);
            break;

        case 'date':
            $listeInterventions = getIntervByDate($connection, $search_value_debut, $search_value_fin);
            break;

        case 'demandeur':
            if (!empty($search_value)){
                $listeInterventions = getIntervByDemandeur($connection, $search_value);
            } else {
                $listeInterventions = getInterv($connection);
            }
            break;

        case 'lieu':
            if (!empty($search_value)){
                $listeInterventions = getIntervByLieu($connection, $search_value);
            } else {
                $listeInterventions = getInterv($connection);
            }
            break;

        case 'motif':
            if (!empty($search_value)){
                $listeInterventions = getIntervByMotif($connection, $search_value);
            } else {
                $listeInterventions = getInterv($connection);
            }
            break;

        case 'sujet':
            if (!empty($search_value)){
                $listeInterventions = getIntervBySujet($connection, $search_value);
            } else {
                $listeInterventions = getInterv($connection);
            }
            break;

        case 'user':
            if (!empty($search_value)){
                $listeInterventions = getIntervByUser($connection, $search_value);
            } else {
                $listeInterventions = getInterv($connection);
            }
            break;

        default:
            $listeInterventions = getInterv($connection);
            break;

            break;
    }
} 
?>