<div class="container" id='parameterToggle' style="display: none;">
    <div class="row">
        <div class="col-12">
            <form action="" method="post" class="parameters_block">
            
                <!-- DISPLAY IFRAME -->
                <legend>Afficher les fichiers dans la même fenêtre ?</legend>
                <div class="paramAllChoices">
                    <div class="paramChoice">
                        <input type="radio" id="yes_iframe" name="iframe_option" value=1<?php if ($display_iframe){echo ' checked';} ?>>
                        <label for="yes_iframe">Oui</label>
                    </div>

                    <div class="paramChoice">
                        <input type="radio" id="no_iframe" name="iframe_option" value=0<?php if (!$display_iframe){echo ' checked';} ?>>
                        <label for="no_iframe">Non</label>
                    </div>
                </div>

                <br/>

                <!-- DISPLAY EMPTY DIRECTORY -->
                <legend>Afficher les dossiers vides ?</legend>
                <div class="paramAllChoices">
                    <div class="paramChoice">
                        <input type="radio" id="yes_empty" name="empty_dir" class="empty_dir" value=1<?php if ($display_empty){echo ' checked';} ?>>
                        <label for="yes_empty">Oui</label>
                    </div>

                    <div class="paramChoice">
                        <input type="radio" id="no_empty" name="empty_dir" class="empty_dir" value=0<?php if (!$display_empty){echo ' checked';} ?>>
                        <label for="no_empty">Non</label>
                    </div>
                </div>
                
                <?php 
                    if (file_exists('adm_files/options_adm.php') && ($adminRight)){
                        include 'adm_files/options_adm.php';
                    }
                ?>

                <div style="display:block; margin-right: auto;margin-left: auto; margin-top:20px;width:max-content">
                    <button type="submit" id="confirm_parametres">Modifier</button>
                </div>

            </form>
        </div>
    </div>
</div>