<div class="parameters_block" id="newDemandeur" style="display: none;">
    <legend>Ajouter un demandeur</legend>
    <div class="paramAllChoicesAdm">
        <div class="row"> 

            <input type="text" id="nameNewDemandeur" name="nameNewDemandeur" maxlength="255" 
                    style="width:96%;margin-left:auto;margin-right:auto" 
                    placeholder="Entrez le nom du demandeur"></input>

            <div class="paramChoiceAdm col-6">
                <button type="button" class="button1 sendRapport" onclick="ajoutDemandeur(nameNewDemandeur.value)">Ajouter</button>
            </div>
            <div class="paramChoiceAdm col-6">
                <button type="button" class="button1 sendRapport toggleNewDemandeur">Annuler</button>
            </div>
        </div>
        
    </div>
</div>

<div class="parameters_block" id="newSujet" style="display: none;">
    <legend>Ajouter un élément</legend>
    <div class="paramAllChoicesAdm">
        <div class="row"> 

            <input type="text" id="nameNewElement" name="nameNewElement" maxlength="255" 
                    style="width:96%;margin-left:auto;margin-right:auto" 
                    placeholder="Entrez le nom de l'élément sur lequel intervenir"></input>

            <div class="paramChoiceAdm col-6">
                <button type="button" class="button1 sendRapport" onclick="ajoutSujet(nameNewElement.value)">Ajouter</button>
            </div>
            <div class="paramChoiceAdm col-6">
                <button type="button" class="button1 sendRapport toggleNewSujet">Annuler</button>
            </div>
        </div>
        
    </div>
</div>