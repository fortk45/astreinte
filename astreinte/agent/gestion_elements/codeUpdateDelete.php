<?php 
echo "<div class='col-12 nbResults' style='font-size:1.3rem;margin-bottom: 10px;color:";

if (isset($_REQUEST['codeAdd'])) {
    $codeAdd = $_REQUEST['codeAdd'];
    switch ($codeAdd) {
        case '1':
            echo "green'>Demandeur ajouté";
            break;
        case '2':
            echo "green'>Sujet ajouté";
            break;
        case '4':
            echo "#991818'>Erreur : Demandeur non ajouté";
            break;
        case '5':
            echo "#991818'>Erreur : Sujet non ajouté";
            break;
        default:
            echo "red'>";
            break;
    }
}

if (isset($_REQUEST['codeUpdate'])) {
    $codeUpdate = $_REQUEST['codeUpdate'];
    switch ($codeUpdate) {
        case '1':
            echo "green'>Demandeur mis à jour";
            break;
        case '2':
            echo "green'>Sujet mis à jour";
            break;
        case '4':
            echo "#991818'>Erreur : Demandeur non mis à jour";
            break;
        case '5':
            echo "#991818'>Erreur : Sujet non mis à jour";
            break;
        default:
            echo "red'>";
            break;
    }
}

if (isset($_REQUEST['codeDelete'])) {
    $codeDelete = $_REQUEST['codeDelete'];
    switch ($codeDelete) {
        case '1':
            echo "green'>Demandeur supprimé";
            break;
        case '2':
            echo "green'>Sujet supprimé";
            break;
        case '4':
            echo "#991818'>Erreur : Demandeur non supprimé";
            break;
        case '5':
            echo "#991818'>Erreur : Sujet non supprimé";
            break;
        case '6':
            echo "#991818'>Suppression impossible : Le demandeur est lié à des rapports";
            break;
        case '7':
            echo "#991818'>Suppression impossible : Le sujet est lié à des rapports";
            break;
        default:
            echo "red'>";
            break;
    }
}
echo "</div>";
?>