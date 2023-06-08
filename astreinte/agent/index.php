<?php
session_start();
//Vérifie que l'utilisateur est autorisé sur cette partie du site
if ((!isset($_SESSION['nom'])) || ($_SESSION['type']!="agent")) {
        header('Location:../index.php');
		die();
}

//RECUPERE LA CONNEXION SQL ET LA RECHERCHE DE DOSSIERS
require 'confMySQL.php';
require('fileExplorer/fonctionExplorer.php');

//INITIALISE LES VARIABLES
require('var_init.php');
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="icon" type="image/x-icon" href="../image/logomfla.png" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="../css/style2.css" rel="stylesheet">
<link href="../css/rapport.css" rel="stylesheet">
<link href="../css/styleImport.css" rel="stylesheet">

<?php
	if ((isset($_REQUEST['infoRapport'])) || (isset($_REQUEST['modifRapport']))){
		echo '<link href="../css/css_infos.css" rel="stylesheet">
		<link href="../css/rapport.css" rel="stylesheet">';
	} else {
		echo '<link href="../css/css_liste.css" rel="stylesheet">';
	}
?>

<title><?php echo $namePage ?></title>
<script src="jquery.min.js"></script>
</head>
<body>

<?php
//FONCTIONS GET
require 'fonctions/fonctionsGet.php';

//MENU NAVIGATION
require 'navigation.php';

//MENU PARAMETRES
include 'param.php';

//inclure les fonctions
require 'planning/fonctionsGet.php';
require 'fonctions/fonctionsGetInterv.php';

//FONCTIONS ADMIN
if (($adminRight) && (isset($adm_function)) && ($adm_function)){
	include 'adm_files/fonctionsAdmin.php';
	include 'adm_files/form_adm.php';
}

if ((isset($_REQUEST['infoRapport'])) || (isset($_REQUEST['modifRapport']))){
	//Si l'utilisateur consulte ou modifie un rapport
	if ((isset($_REQUEST['modifRapport'])) && (isset($_SESSION['adminRight'])) && ($_SESSION['adminRight'])){
		include 'infoRapport/modifRapport.php';
	} else {
		include 'infoRapport/infoRapport.php';
	}
} else {

	if ((isset($_REQUEST['action']))){
		switch ($_REQUEST['action']) {
			case 'planning':
				//Si l'utilisateur consulte le planning
				echo '<div class="container principal">';
				include 'planning/planningSemaine.php';
				echo '</div>';	
				break;
			
			case 'new_rapport':
				//Si l'utilisateur fait un nouveau rapport
				include 'new_rapport/formulaire.php';
				break;
			
			case 'gestElements':
				//Si on cherche à gérer les infos
				if ((isset($_REQUEST['codeUpdate'])) || (isset($_REQUEST['codeDelete'])) || (isset($_REQUEST['codeAdd']))){
					include 'gestion_elements/codeUpdateDelete.php';
				}
				echo '<a href="index.php?action=planning" class="button1" style="margin:10px auto 10px auto">Gérer le planning</a>';
				if ($adminRight) {
					if ((isset($_REQUEST['gestion'])) && (!empty($_REQUEST['gestion']))){
						switch ($_REQUEST['gestion']) {
							case 'demandeur':
								require 'gestion_elements/gestionDemandeurs.php';
								break;
							case 'sujet':
								require 'gestion_elements/gestionSujets.php';
								break;
						}
					} else {
						require 'gestion_elements/gestionDemandeurs.php';
						echo '<br/>';
						require 'gestion_elements/gestionSujets.php';
					}
				}
				break;

			default:
				echo "<script type='text/javascript'>
				window.location.href = 'index.php';
				</script>";
				break;
		}


	} else {
		if (isset($_REQUEST['search_rapport'])){
			//SI L'UTILISATEUR CHERCHE UN RAPPORT
			require 'listes/caseLimitInterv.php';
			require 'listes/listeInterv v3.php';
		} else {
			if (isset($search)){
				//SI L'UTILISATEUR A RECHERCHE QUELQUE CHOSE
				echo '<div class="container principal"> 
				<div id="princip">';
				include 'adm_files/navig_adm.php';
				echo '<div class="row">';
				include 'fileExplorer/searchResults.php';
				echo '</div> </div>';
				
			} else {
				if ((isset($folder)) && (!empty($folder))){
					//SI L'UTILISATEUR PARCOURT UN DOSSIER/FICHIER
					
					if (!is_dir($folder)) {
					//Si on parcourt un fichier, on l'affiche dans un iframe
						$verifFilename = substr($folder, -9);
						if (($verifFilename!= "Thumbs.db") and ($verifFilename!= "index.php")){ //vérification qu'il soit autorisé d'y accéder
							echo '<div class="principal contain_iframe">';
							echo '<iframe name="iframe_a"
									title="Frame astreinte FlA"
									src="'.$folder.'"></iframe></div>';
						} else {
							$old_folder = substr($folder, 0, strrpos($folder, "/"));
							header('location: index.php?folder='.$old_folder);
							die();
						}
					} else {
						//Si on parcourt un dossier, on l'analyse et affiche son contenu
						echo '<div class="container principal"> 
						<div id="princip">';
						include 'adm_files/navig_adm.php';
						echo '<div class="row">';
						echo explore_dir_scan_html($folder);
						echo '</div></div>';
					}
				} else {
					//Si on ne parcourt rien, on affiche juste le contenu du dossier partage
					echo '<div class="container principal"> 
					<div id="princip">';
					include 'adm_files/navig_adm.php';
					echo '<div class="row">';
					echo explore_dir_scan_html('../partage');
					echo '</div></div>';
				}
			} 
		}
	}
}
echo "</div>"
?>
</body>
</html>
