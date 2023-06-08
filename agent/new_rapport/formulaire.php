<script src="new_rapport/forms.js"></script>

<?php 
    // Paramétrer le fuseau horaire
    date_default_timezone_set('Europe/Paris');
?>

<?php
include 'newElementFromForm.php';
?>

<form action="new_rapport/sendForm.php" method="post" class="parameters_block" id="formRapportPrincipal">
    <legend>Nouveau rapport</legend>
    <div class="paramAllChoicesAdm">
        <div class="row"> 

            <!-- DATE ET HEURE D'APPEL -->
            <label for="heureAppel" class="col-4">Date d'appel</label>
            <div class="col-8">
                <input type="date" id="dateInterv" name="dateInterv" value=<?php echo date('Y-m-d') ?> required></input>
            </div>

            <label for="heureAppel" class="col-4">Heure d'appel</label>
            <div class="col-8">
                <input type="time" id="heureAppel" name="heureAppel" required></input>
            </div>

            
            <!-- APPEL INFOS -->

            <label for="lieuInterv" class="col-4" style="margin-top:20px!important">Lieu</label>
            <div class="col-8">
                <input type="text" list="liste_lieux" id="lieuInterv" name="lieuInterv" maxlength="255" style="width:96%" required></input>
            </div>

            <datalist id="liste_lieux">
                <?php
                    foreach (getLieux($connection) as $unLieu){
                        echo '<option value="'.$unLieu[0].'">';
                    }
                ?>
            </datalist>
            
            <label for="motifInterv" class="col-4">Motif d'appel</label>
            <div class="col-8">
                <input type="text" id="motifInterv" name="motifInterv" maxlength="255" style="width:96%" required></input>
            </div>

            <label for="demandeur" class="col-4">Demandeur</label>
            <div class="col-8" style="margin-top:10px!important">
                <select name='demandeur' id='demandeur' style='width:96%' required>
                    <option disabled selected value="">Sélectionner le demandeur</option>
                    <?php foreach (getDemandeurs($connection) as $ligne) {
                        echo "<option value=" . $ligne[0] . ">" . $ligne[1] . "</option>";
                    }?>
                </select>
                <button type="button" class="addFormRapport toggleNewDemandeur">Ajouter un demandeur</button>
            </div>      

            <!-- DUREE INTERVENTION -->

            <div class="col-12" style="margin-top:25px;text-align:center;font-weight:bold">Durée de l'intervention</div>
            <div class="col-6">
                <div style="margin-right:10px;margin-left:auto;width: max-content;">
                <label for="heureDebut">De...</label>           
                <input type="time" id="heureDebut" name="heureDebut"></input>
                </div>
            </div>

            <div class="col-6">            
                <label for="heureFin">... À :</label>
                <input type="time" id="heureFin" name="heureFin"></input>
            </div>

            
            <!-- INTERVENTION SUR -->

            <div class="col-12" style="margin-top:20px">
                <b>Intervention sur...</b>
                <br/>
                <div class="row" id="lesCheckbox"> 
                    <?php
                        foreach (getSujets($connection) as $ligne) {
                            echo "<div class='col-6 col-sm-4 aCheckbox'><input type='checkbox' value=" . $ligne[0] . " name='sujet".$ligne[0]."' id='sujet".$ligne[0]."'/><label for='sujet".$ligne[0]."' class='label_Sujets label_Size'>" . $ligne[1] . "</label></div>";
                        
                        }
                    ?>
                </div>
            </div>
            <button type="button" class="addFormRapport toggleNewSujet" style="margin-top:10px">Ajouter un nouvel élément</button>



            <!-- OBSERVATIONS -->

            <div class="col-12" style="margin-top:10px">
            <label for="observations">
                Observations</label>
                <br/>
                <textarea id="observations" name="observations" style="width:100%"></textarea>
            </div>

            <div class="paramChoiceAdm col-12">
                <button type="submit" class="button1 sendRapport">Envoyer</button>
            </div>
        </div>
        
    </div>
</form>
