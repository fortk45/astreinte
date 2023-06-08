<?php
$seek_all = '';
if(isset($_REQUEST['search'])){
    switch ($_REQUEST['search']) {

        case 'demandeur':
            if (empty($_REQUEST['search_value_demand'])){
                $search_value = "";
                $seek_all = '&seek_all';
            } else {
                $search_value = htmlspecialchars($_REQUEST['search_value_demand']);
            }
            break;
        
        case 'lieu':
            $search_value = "";
            if (empty($_REQUEST['search_value_txt'])){
                $seek_all = '&seek_all';
            } else {
                $search_value = htmlspecialchars($_REQUEST['search_value_txt']);
            }
            break;

        case 'motif':
            $search_value = "";
            if (empty($_REQUEST['search_value_txt'])){
                $seek_all = '&seek_all';
            } else {
                $search_value = htmlspecialchars($_REQUEST['search_value_txt']);
            }
            break;

        case 'sujet':
            if (empty($_REQUEST['search_value_sujet'])){
                $search_value = "";
                $seek_all = '&seek_all';
            } else {
                $search_value = $_REQUEST['search_value_sujet'];
            }
            break;

        case 'date':
            if (empty($_REQUEST['search_debut'])){
                $search_value_debut = 'no_limit';
            } else {
                $search_value_debut = $_REQUEST['search_debut'];
            }

            if (empty($_REQUEST['search_fin'])){
                $search_value_fin = 'no_limit';
            } else {
                $search_value_fin = $_REQUEST['search_fin'];
            }

            if ((empty($_REQUEST['search_debut'])) && (empty($_REQUEST['search_fin']))){
                $seek_all = '&seek_all';
            }
            break;
    }
    if (isset($search_value)){
        header('Location:../index.php?search_rapport='.htmlspecialchars($_REQUEST['search']).'&search_value='.htmlspecialchars($search_value).$seek_all);
    } else {
        if (isset($search_value_debut)){
            header('Location:../index.php?search_rapport='.htmlspecialchars($_REQUEST['search']).
            "&search_debut=".htmlspecialchars($search_value_debut).
            "&search_fin=".htmlspecialchars($search_value_fin).$seek_all);
        }
    }
}

?>