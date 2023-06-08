<script type="text/javascript">

    /**
     * Affiche la barre de recherche textuelle
     */
    function changeSearchText(paramWhich) {
        document.getElementById("searchbarForDate").style.display="none";
        document.getElementById("searchbarForDemandeur").style.display="none";
        document.getElementById("searchbarForSujet").style.display="none";
        document.getElementById("searchbarForText").style.display="block";
        if (paramWhich == "lieu"){
            document.getElementById("searchbar").setAttribute("list","datalistLieux");
        } else {
            document.getElementById("searchbar").setAttribute("list","none");
        }
    }

    /**
     * Affiche la barre de recherche pour les dates
     */
    function changeSearchDate() {
        document.getElementById("searchbarForText").style.display="none";
        document.getElementById("searchbarForDemandeur").style.display="none";
        document.getElementById("searchbarForSujet").style.display="none";
        document.getElementById("searchbarForDate").style.display="block";
    }

    /**
     * Affiche la barre de recherche pour les dates
     */
    function changeSearchDemandeur() {
        document.getElementById("searchbarForText").style.display="none";
        document.getElementById("searchbarForDate").style.display="none";
        document.getElementById("searchbarForSujet").style.display="none";
        document.getElementById("searchbarForDemandeur").style.display="block";
    }

    /**
     * Affiche la barre de recherche pour les sujets
     */
    function changeSearchSujet() {
        document.getElementById("searchbarForText").style.display="none";
        document.getElementById("searchbarForDate").style.display="none";
        document.getElementById("searchbarForDemandeur").style.display="none";
        document.getElementById("searchbarForSujet").style.display="block";
    }

</script>

<!-- LA NAVBAR DE TEXTE (motif, lieu) -->
<div id="searchbarForText"
<?php if (($search_type == 'lieu') || ($search_type == 'motif')  || (empty($search_type))){echo 'style="display:block"';}else{echo 'style="display:none"';}?> >
    <div style="display:flex;margin-top:auto;margin-bottom:auto;width:100%;padding-top:1%">
        <input id="searchbar" type="text" name="search_value_txt" placeholder="Chercher un rapport" 
                <?php        
                    if ((isset($search_value)) && (($search_type == 'lieu') || ($search_type == 'motif'))){
                        if ($search_type == "lieu"){
                            echo 'list="datalistLieux"';
                        }
                        echo ' value="'.$search_value.'"';
                    }?>
            style="width:100%"><button type="submit" class="loupe"><img src="../image/loupe.png" height=30px></button>

            <datalist id="datalistLieux">
                <?php
                    //La liste des lieux déjà entrés
                    foreach (getLieux($connection) as $unLieu){
                        echo '<option value="'.$unLieu[0].'">';
                    }
                ?>
            </datalist>
    </div>
</div>


<!-- LA NAVBAR DE DATE -->
<div id="searchbarForDate" 
<?php 
if ($search_type == 'date'){echo 'style="display:block"';} else {echo 'style="display:none"';}?> >
    <div class="row">
        <div class="col-12" style="font-weight:bold;margin-bottom:-10px;margin-top:8px">
            <div class="txtSearchDates">A partir de :</div>
        </div>

        <input class="col-11 searchbar_date" onkeyup="" type="date" name="search_debut"
                <?php if (isset($search_value_debut)){
                        echo ' value="'.$search_value_debut.'"';
                }?> >
        <div class="col-12" style="font-weight:bold;margin-bottom:-10px;margin-top: 10px;">
            <div class="txtSearchDates">Jusqu'à :</div>
        </div>
        <input class="col-9 searchbar_date" onkeyup="" type="date" name="search_fin" style="border-radius: 20px 0px 0px 20px;"
                <?php if (isset($search_value_fin)){
                        echo ' value="'.$search_value_fin.'"';
                }?> >
        <button type="submit" class="col-2 loupe" style="border-radius:0px 20px 20px 0px; width:auto"><img src="../image/loupe.png"></button>
    </div>
</div>




<!-- LA NAVBAR DE DEMANDEUR -->
<?php
?>
<div id="searchbarForDemandeur" 
<?php if ($search_type == 'demandeur'){echo 'style="display:block"';}else{echo 'style="display:none"';}?> >
    <div style="display:flex;margin-top:auto;margin-bottom:auto;padding-top:1%">
        <select class='searchbar_list' name='search_value_demand'>
            <option disabled selected value="">Sélectionner le demandeur</option>
            <?php 
            foreach (getDemandeurs($connection) as $ligne) {
                echo "<option value='".$ligne[0]."'";
                if ((isset($search_value)) && ($ligne[0] == $search_value)){
                    echo "selected";
                }
                echo ">".$ligne[1]."</option>";
            }?>
        </select><button type="submit" class="loupe"><img src="../image/loupe.png" height=30px></button>
    </div>
</div>    
        
<!-- LA NAVBAR DE SUJET -->

<div id="searchbarForSujet" 
<?php if ($search_type == 'sujet'){echo 'style="display:block"';}else{echo 'style="display:none"';}?> >
    <div style="display:flex;margin-top:auto;margin-bottom:auto;padding-top:1%">
        <select class='searchbar_list' name='search_value_sujet'>
            <option disabled selected value="">Sélectionner le sujet</option>
            <?php foreach (getSujets($connection) as $ligne) {
                echo "<option value='".$ligne[0]."'";
                if ((isset($search_value)) && ($ligne[0] == $search_value)){
                    echo "selected";
                }
                echo ">".$ligne[1]."</option>";
            }?>
        </select><button type="submit" class="loupe"><img src="../image/loupe.png" height=30px></button>
    </div>
</div> 