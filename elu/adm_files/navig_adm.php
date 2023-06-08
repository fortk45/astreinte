<?php
if (($adminRight) && (isset($adm_function)) && ($adm_function)){
?>
    <!-- Boutons pour importer un fichier -->
    <div class="row">
        <div class="d-none d-lg-block col-4"></div>
        <div class="col-12 col-md-6 col-lg-4" style="margin-bottom:auto">
            <form action="" method="post" enctype="multipart/form-data" style="margin-bottom:1%">
                <div row>
                    <div class="col-12">
                        <label for="filetoImport" class="button1 label-file">Importer un fichier</label>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="button1" id="importationToggle" style="display: none;width:inherit;word-wrap:break-word">Importer</button> 
                        <input id="filetoImport" name="filetoImport" class="input-file" 
                        accept=".pdf,.jpg,.jpeg,.png,.webp, .bmp"
                        type="file" onchange="importAFile()">
                    </div>
                </div>
            </form>    
        </div>       
        
        <!-- Boutons pour créer un dossier -->
        <?php
            if (!isset($search)){
        ?>
        <div class="col-12 col-md-6 col-lg-4" style="margin-bottom:auto">
            <button class="button1" id="newDirectory">Nouveau dossier</button> 
            <div style="display:flex">
                <form action=""  method="post" id="newDirToggle" style="display:none;margin-left:auto;margin-right:auto">
                    <input type="text" placeholder="Nom du nouveau dossier" id="newDir" name="newDir" class="mkdirText">
                    <button type="submit" class="loupe mkdir" style="display:inline">Créer</button>
                </form>
            </div>
        </div>
        <?php
            }
        ?>

    </div>
<?php
if (isset($msgModif)){
    echo $msgModif;
};
}
?>