/** 
 * Gère l'affichage du menu d'ajout de demandeur ou sujet
 */
$(document).ready(function(){

    $(".toggleNewDemandeur").click(function(){
        document.getElementById("nameNewDemandeur").value = "";
        $("#newDemandeur").toggle();
        $("#formRapportPrincipal").toggle();
    });

    $(".toggleNewSujet").click(function(){
        document.getElementById("nameNewElement").value = "";
        $("#newSujet").toggle();
        $("#formRapportPrincipal").toggle();

    });
});

/** 
 * Ajoute le nouveau demandeur à la liste
 */
function ajoutDemandeur(nomDemandeur){
    if (nomDemandeur !== ""){    
        let newOption = new Option(nomDemandeur,'newDemand_'+nomDemandeur);
        const select = document.querySelector('#demandeur'); 
        select.add(newOption,undefined);
        document.getElementById('demandeur').value='newDemand_'+nomDemandeur;
        
        //Raffiche le formulaire principal
        document.getElementById('newDemandeur').style.display = "none";
        document.getElementById('formRapportPrincipal').style.display = "block";
        document.getElementById('nameNewDemandeur').value = "";
    } else {
        alert("Veuillez entrer un nom ou annuler")
    }
}

/** 
 * Ajoute le nouveau sujet à la liste
 */
function ajoutSujet(nomSujet){
    if (nomSujet !== ""){    
        //sélectionne l'ID des checkbox pour y ajouter des options
        var lesCheckbox = document.getElementById("lesCheckbox");
        
        //Ajoute la div comprenant la nouvelle option
        var laNewDiv = document.createElement("div"); 
        laNewDiv.className += ("col-6 col-sm-4"); 
        lesCheckbox.appendChild(laNewDiv);
        
        //Prépare la nouvelle option
        var checkbox = document.createElement('input');
        checkbox.type = "checkbox";
        checkbox.name = 'newSujet_'+nomSujet;
        checkbox.value = nomSujet;
        checkbox.id = 'newSujet_'+nomSujet;
        checkbox.checked = true;

        //Prépare le label de la nouvelle option
        var label = document.createElement('label');
        label.htmlFor = 'newSujet_'+nomSujet;
        label.appendChild(document.createTextNode(nomSujet));
        label.className += ("label_Sujets label_Size");

        //Ajoute l'option et le label
        laNewDiv.appendChild(checkbox);
        laNewDiv.appendChild(label);

        //Raffiche le formulaire principal
        document.getElementById('newSujet').style.display = "none";
        document.getElementById('formRapportPrincipal').style.display = "block";
        document.getElementById('nameNewElement').value = "";
    } else {
        alert("Veuillez entrer un élément ou annuler")
    }
}