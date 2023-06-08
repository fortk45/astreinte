<?php
if ($adminRight){
?>
    <br/>

    <!-- DISPLAY ADMIN -->
    <legend>Afficher les fonctons d'administration ?</legend>
    <div class="paramAllChoices">
        <div class="paramChoice">
            <input type="radio" id="yes_adm" name="adm_function" class="adm_function" value=1<?php if ($adm_function){echo ' checked';} ?>>
            <label for="yes_adm">Oui</label>
        </div>

        <div class="paramChoice">
            <input type="radio" id="no_adm" name="adm_function" class="adm_function" value=0<?php if (!$adm_function){echo ' checked';} ?>>
            <label for="no_adm">Non</label>
        </div>
    </div>

<?php
}
?>