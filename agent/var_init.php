<?php
//RECUPERER LES VARIABLES AVEC LES INFOS DE L'UTILISATEUR
$samaccountname = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];
$nom = $_SESSION['nomFamille'];
if ((isset($_SESSION['adminRight'])) && ($_SESSION['adminRight'])){
	$adminRight = True;
} else {
	$adminRight = False;
}
$namePage = "Portail d'Astreintes";

//SI L'UTILISATEUR A CHANGE SES PREFERENCES
if ((isset($_REQUEST['iframe_option'])) || (isset($_REQUEST['empty_dir']))){
	$display_iframe = True;
    $display_empty = True;
	if ($adminRight){
		$adm_function = True;
	}

	//Changement des préférences d'affichage des pdf
	if (isset($_REQUEST['iframe_option'])){
		if ($_REQUEST['iframe_option'] == 1){
			$display_iframe = true;
		} else {
			$display_iframe = false;
		}
		$_SESSION['display_iframe'] = $display_iframe;

		$createAccount = $connection->prepare("UPDATE `preferences` SET `display_iframe`=:display_iframe WHERE `samaccountname`=:samaccountname");
		$createAccount->bindParam(':display_iframe', $display_iframe);
		$createAccount->bindParam(':samaccountname', $samaccountname);
		$createAccount->execute();
	}

	//Changement des préférences d'affichage des répertoires vides
	if (isset($_REQUEST['empty_dir'])){
		if ($_REQUEST['empty_dir'] == 1){
			$display_empty = true;
		} else {
			$display_empty = false;
		}
		$_SESSION['display_empty'] = $display_empty;

		$createAccount = $connection->prepare("UPDATE `preferences` SET `display_empty`=:display_empty WHERE `samaccountname`=:samaccountname");
		$createAccount->bindParam(':display_empty', $display_empty);
		$createAccount->bindParam(':samaccountname', $samaccountname);
		$createAccount->execute();
	}

	//Changement des préférences d'affichage des fonctions admin
	if ($adminRight){
		if (isset($_REQUEST['adm_function'])){
			if ($_REQUEST['adm_function'] == 1){
				$adm_function = true;
			} else {
				$adm_function = false;
			}
			$_SESSION['adm_function'] = $adm_function;

			$createAccount = $connection->prepare("UPDATE `preferences` SET `display_admFunction`=:adm_function WHERE `samaccountname`=:samaccountname");
			$createAccount->bindParam(':adm_function', $adm_function);
			$createAccount->bindParam(':samaccountname', $samaccountname);
			$createAccount->execute();
		}
	}
}

//SI LES VARIABLES DE SESSION SONT BIEN ENTREES DANS LES VARIABLES DE SESSION
if ((isset($_SESSION['display_iframe'])) && (isset($_SESSION['display_iframe']))){
    $display_iframe = $_SESSION['display_iframe'];
    $display_empty = $_SESSION['display_empty'];
	if (($adminRight) && (isset($_SESSION['adm_function']))){
		$adm_function = $_SESSION['adm_function'];
	}
} else {
    //SINON, RECUPERATION PREFERENCES UTILISATEUR DANS LA BASE DE DONNEES
    require('var_init_pref.php');
}

//ON INITIALISE LA VARIABLE DE LA PAGE PARAMETRE
if (isset($_REQUEST['param'])){
	$param = true;
}

//ON INITIALISE LA VARIABLE AVEC LA RECHERCHE
if ((isset($_REQUEST['search'])) && (!empty($_REQUEST['search']))){
	$search = htmlspecialchars($_REQUEST['search']);
}

/* ON INITIALISE LA VARIABLE POUR QU'EN APPUYANT SUR "RETOUR"
	APRES UNE RECHERCHE, ON REVIENNE A LA RECHERCHE */
if (isset($_REQUEST['fromSearch'])){
	$fromSearch = htmlspecialchars($_REQUEST['fromSearch']);
}

//ON INITIALISE LA VARIABLE DU DOSSIER OU ON RECHERCHE
if (isset($_REQUEST['folder'])){
	//on vérifie que le dossier dans lequel on cherche est bien partage
	if(substr($_REQUEST['folder'], 0, 11) == "../partage/"){
		$folder = htmlspecialchars($_REQUEST['folder']);
		if (!file_exists($folder)){
			$old_folder=substr($folder, 0, strrpos($folder, "/"));
			header('location: index.php?folder='.$old_folder);
			die();
		}
		if (is_file($folder)){
			$namePage="Astreintes - ".substr($folder, strrpos($folder, "/")+1);
		}
	} else {
		header('location: index.php');
		die();
	}
}

//Les noms de page
if (isset($_REQUEST['action'])){
	switch ($_REQUEST['action']) {
		case 'planning':
			$namePage="Astreintes - Planning";
			break;

		case 'new_rapport':
			$namePage="Astreintes - Nouveau rapport";
			break;
		case 'gestElements':
			$namePage="Astreintes - Gestion";
			break;
	}
} else {
	switch($_SERVER['QUERY_STRING']) {
		case 'infoRapport':
			$namePage="Astreintes - infoRapport";
			break;
	}
	/*
	if (isset($_REQUEST['infoRapport'])){
		$namePage="Astreintes - Rapport";
	}
	if (isset($_REQUEST['infoRapport'])){
	}*/
}
?>