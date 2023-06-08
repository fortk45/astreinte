
<!-- COLONNE BARRE DE RECHERCHE -->
<form action="research/research.php" method="post">

	<?php 
	//vérifier si on a déjà demandé une recherche pour garder le même type de recherche coché
	$search_type = 'motif';
	if (isset($_REQUEST['search_rapport'])){
		$search_type = $_REQUEST['search_rapport'];
	} else {
		if (isset($_REQUEST['search_rapport'])){
			$search_type = $_REQUEST['search_rapport'];
		}
	}

	if(isset($_REQUEST['search_value'])){
		$search_value = $_REQUEST['search_value'];
	} else {
		if (isset($_REQUEST['search_debut'])){
			$search_value_debut = $_REQUEST['search_debut'];
			$search_value_fin = $_REQUEST['search_fin'];
		}
	}
	
	require 'param_navbar_rapport.php';

	?>

	<div id="bloc_allSearchs">
		<div id="rechercher_par">Rechercher par :</div>
		<div id="allSearchsParams">
			<div class="searchParamChoice">
				<input type="radio" id="s_motif" name="search" value='motif' onclick='changeSearchText("motif")' <?php if (($search_type == 'motif') || (empty($search_type))){ echo "checked"; } ?>>
				<label for="s_motif">Motif</label>
			</div>

			<div class="searchParamChoice">
				<input type="radio" id="s_date" name="search" value='date' onclick='changeSearchDate()' <?php if ($search_type == 'date'){ echo "checked"; } ?>>
				<label for="s_date">Date</label>
			</div>

			<div class="searchParamChoice">
				<input type="radio" id="s_demandeur" name="search" value='demandeur' onclick='changeSearchDemandeur()' <?php if ($search_type == 'demandeur'){ echo "checked"; } ?>>
				<label for="s_demandeur">Demandeur</label>
			</div>
			
			<div class="searchParamChoice">
				<input type="radio" id="s_lieu" name="search" value='lieu' onclick='changeSearchText("lieu")' <?php if ($search_type == 'lieu'){ echo "checked"; } ?>>
				<label for="s_lieu">Lieu</label>
			</div>

			<div class="searchParamChoice">
				<input type="radio" id="s_sujet" name="search" value='sujet' onclick='changeSearchSujet()' <?php if ($search_type == 'sujet'){ echo "checked"; } ?>>
				<label for="s_sujet">Sujet</label>
			</div>
		</div>
	</div>
</form>