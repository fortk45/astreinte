<?php
if (($adminRight) && (isset($adm_function)) && ($adm_function)){
?>

<!-- MENUS SUPPRESSION -->

<div class="container" id='verifDelete' style="display: none;">
        <form action="" method="post" class="parameters_block">
        
            <legend id="legendDelete">Êtes-vous sûr de vouloir supprimer </legend>
            <div class="paramAllChoicesAdm">
                <div class="row">
                    <div class="paramChoiceAdm col-6">
                        <button type="submit" class="renameButtons" name="toDelete" id="toDelete" value="">Oui</button>
                    </div>

                    <input type="hidden" name="typeToDelete" id="typeToDelete" value="">   

                    <div class="paramChoiceAdm col-6">
                        <button onclick="deleteDisplay('','','fichier')" class="renameButtons">Non</button>
                    </div>
                </div>
            </div>

        </form>
</div>


<!-- MENUS RENOMMAGE -->

<div class="container" id='verifRename' style="display: none;">
    
    <form action="" method="post" class="parameters_block">
        <legend id="legendRename">Entrez le nouveau nom</legend>
        <div class="paramAllChoicesAdm">
            <div class="row">
                <div class="col-12">
                    <input type="text" name="newName" id="newName" value="" placeholder="entrez le nouveau nom" style="width:100%">
                </div>

                <input type="hidden" name="pathElement" id="pathElement" value="">
                <input type="hidden" name="typeToRename" id="typeToRename" value="">

                <div class="row" style="margin:0px">
                    <div class="paramChoiceAdm col-6">
                        <button type="submit" class="renameButtons" name="toRename" id="toRename" value="">Valider</button>
                    </div>

                    <div class="paramChoiceAdm col-6">
                        <button onclick="renameDisplay('','','fichier')" class="renameButtons">Annuler</button>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

<?php
}
?>