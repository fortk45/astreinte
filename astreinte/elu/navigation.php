<script type="text/javascript">

/*SCRIPT POUR FAIRE APPARAÎTRE LE MENU DES PARAMETRES*/
$(document).ready(function(){
  $(".parametersButton").click(function(){
    $("#parameterToggle").toggle();
	$(".principal").toggle();
  });
});
</script>

<!-- BARRE DE NAVIGATION -->
<div class="container" id="conteneur" style="display:block">
	<div class="row">

		<!--La colonne Astreinte/Déconnexion-->
		<div class='col-12 col-md-11'>
			<div class="row">

				<!-- COLONNE BOUTON OPTION/RETOUR -->
				<div class="col-6 col-sm-5" style='display:flex'>
					<a class="button1 parametersButton" id="parametres2">
						<img src="../image/Gear-icon.png" class="Gear-icon">  Options
					</a>
					<?php 
						if (isset($fromSearch)){
							echo '<a href="index.php?search='.htmlspecialchars($fromSearch).'" class="button1" id="retour" style="margin-left:10px">Retour</a>';
						} else {
							if ((isset($folder)) && (!empty($folder)) && ($folder != "../partage/".$malette)){
								$old_folder=substr($folder, 0, strrpos($folder, "/"));
								echo '<a href="index.php?folder='.$old_folder.'" class="button1" id="retour" style="margin-left:10px">Retour</a>';
							} else {
								if (isset($search)){
									echo '<a href="index.php" class="button1" id="retour" style="margin-left:10px">Retour</a>';
								} else {
									if ((isset($_REQUEST['infoRapport'])) || (isset($_REQUEST['modifRapport']))){
										//Bouton 'RETOUR' pour revenir à la recherche

										if (isset($_REQUEST['search_rapport'])){
											//si on cherchait par critère
											$urlReturn = "search_rapport=".$_REQUEST['search_rapport'];
											if (isset($_REQUEST['search_value'])){
												$urlReturn .= "&search_value=".$_REQUEST['search_value'];
											}
											if ((isset($_REQUEST['search_debut'])) && (isset($_REQUEST['search_fin']))){
												$urlReturn .= "&search_debut=".$_REQUEST['search_debut']."&search_fin=".$_REQUEST['search_fin'];
											}
											echo '<a href="index.php?'.$urlReturn.'" class="button1" id="retourFromInfos"  style="margin-left:10px">Retour</a>';
										} else {
											//si on cherchait par semaine
											echo '<a href="index.php';
											$urlReturn = "";
											if ((isset($_REQUEST['w_plan'])) && (isset($_REQUEST['y_plan']))){
												$urlReturn = 'w_plan='.$_REQUEST['w_plan'].'&y_plan='.$_REQUEST['y_plan'];
											} 
											echo "?action=planning&".$urlReturn.'" class="button1" id="retour" style="margin-left:10px">Retour</a>';
										}
									}
								}
							}
						}
					?>
				</div>


				<!-- COLONNE RAPPORT (PETIT ECRAN) -->
				<div class="d-block d-sm-none col-2">
					<?php 
					if ((isset($_REQUEST['action'])) && ($_REQUEST['action'] == "new_rapport")) {
						echo '<a href="index.php" class="button1" style="margin-left:-20px">Fichiers</a>';
					} else {
						echo '<a href="index.php?action=new_rapport" class="button1" style="margin-left:-20px">Rapport</a>';
					}
					?>
				</div>	
				
				<!-- COLONNE DECONNEXION (PETIT ECRAN) -->
				<div class='d-block d-sm-none col-3'>
					<a href="../index.php" class="button1" id="deconnexion2">Déconnexion</a>
				</div>

				<!-- LOGO (PETIT ECRAN) -->
				<span class='d-block d-sm-none col-3'>
					<img class="displayed" src="../image/icon-fla.png" style="width: 60px;margin-left: 0px;" alt="logo de la ville de Fleury-les-Aubrais">
				</span>

                <!-- COLONNE TEXTE ASTREINTES -->
				<div class="col-6 col-sm-4">
					<div id="welcome">
						<h1>
							<a href="index.php">Astreintes</a>
						</h1>	
					</div>
				</div>

				<!-- COLONNE PLANNING (PETIT ECRAN) -->
				<div class='d-block d-sm-none col-3'>
					<?php
						if ((isset($_REQUEST['action'])) && ($_REQUEST['action'] == "planning")){
							echo '<a href="index.php" class="button1" id="planning2" style="margin-bottom:15%">Fichiers</a>';
						} else {
							echo '<a href="index.php?action=planning" class="button1" id="planning2" style="margin-bottom:15%">Planning</a>';
						}
					?>
				</div>

				<!-- COLONNE DECONNEXION (MOYEN/GRAND ECRAN) -->
				<div class='d-none d-sm-block col-3'>
					<a href="../index.php" class="button1" id="deconnexion">Déconnexion</a>
				</div>
		
				<!-- COLONNE RAPPORT (MOYEN/GRAND ECRAN) -->
				<div class="d-none d-sm-block col-sm-3 col-lg-4" style="margin-top:10px">
					<?php 
					if ((isset($_REQUEST['action'])) && ($_REQUEST['action'] == "new_rapport")) {
						echo '<a href="index.php" class="button1"> Fichiers</a>';
					} else {
						echo '<a href="index.php?action=new_rapport" class="button1"> Rapport</a>';
					}
					?>
				</div>	

				<!-- COLONNE BARRE DE RECHERCHE RECHERCHE -->
				<div class='col-12 col-sm-6 col-md-6 col-lg-5'>
					<?php
						if (((isset($_REQUEST['action'])) && ($_REQUEST['action'] == "planning")) || (isset($_REQUEST['search_rapport'])) || (isset($_REQUEST['infoRapport'])) || (isset($_REQUEST['modifRapport']))){
							require 'navbar/navbar_rapport.php';
						} else {
							require 'navbar/navbar_files.php';
						}
					?>
				</div>

				<!-- COLONNE PLANNING (MOYEN/GRAND ECRAN) -->
				<div class="d-none col-sm-3 d-sm-block" style="margin-top:10px">
					<?php
						if ((isset($_REQUEST['action'])) && ($_REQUEST['action'] == "planning")){
							echo '<a href="index.php" class="button1" id="planning" style="margin:auto">Fichiers</a>';
						} else {
							echo '<a href="index.php?action=planning" class="button1" id="planning" style="margin:auto">Planning</a>';
						}
					?>
				</div>	

			</div>
		</div>

		<!-- COLONNE LOGO FLEURY (disparaît si la page est trop petite) -->
		<div class='d-none d-md-block col-md-1' style="margin-top: 15px;">
			<img class="displayed" src="../image/icon-fla.png" alt="logo de la ville de Fleury-les-Aubrais">
		</div>

	</div>
</div>