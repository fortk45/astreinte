/**
 * Fait apparaître le bouton d'importation du fichier une fois sélectionné 
 */
    function importAFile() {
        if (document.getElementById("filetoImport").value !== 'undefined'){
            valueTxt = document.getElementById("filetoImport").value;
            extFileToImport = valueTxt.substr(valueTxt.lastIndexOf('.')+1);
            if (['pdf', "jpg", "jpeg", "png", "webp", "bmp"].includes(extFileToImport.toLowerCase())){
                document.getElementById("importationToggle").style.display="block";
                document.getElementById("importationToggle").textContent="Importer "+valueTxt.substr(valueTxt.lastIndexOf('\\')+1);
            } else {
                console.log(extFileToImport);
                document.getElementById("filetoImport").value="";
                document.getElementById("importationToggle").textContent="";
                if (document.getElementById("importationToggle").style.display=="block"){
                    document.getElementById("importationToggle").style.display="none";
                }
            }
        }
    }


/**
 * Fait apparaître le bouton pour sélectionner le fichier à importer 
 */
$(document).ready(function(){
	$("#importation").click(function(){
		$("#importationToggle").toggle();
	});
});
    

/**
 * Fait apparaître le bouton Nouveau Dossier
 */
$(document).ready(function(){
    $("#newDirectory").click(function(){
        var x = document.getElementById("newDirToggle");
        var y = document.getElementById("newDirectory");
        x.style.display = "inline-flex";
        y.style.display = "none";
    });
});


/**
 * Remplit le formulaire de suppression d'un fichier ou répertoire
 * 
 * @param {string} dir
 * @param {string} entry
 * @param {string} theTypeToDelete
 */
function deleteDisplay(dir, entry, theTypeToDelete) {
    var verifDelete = document.getElementById("verifDelete");
    var legendDelete = document.getElementById("legendDelete");
    var toDelete = document.getElementById("toDelete");
    var typeToDelete = document.getElementById("typeToDelete");

    if (verifDelete.style.display == "block"){
        //Masquer le menu de suppression
        verifDelete.style.display = "none";
        document.getElementById("princip").style.display = "block";
    } else {
        //Afficher le menu de suppression et entrer les informations
        legendDelete.textContent = "Êtes-vous sûr de vouloir supprimer le "+theTypeToDelete+" "+entry+" ?";
        toDelete.value = dir+"/"+entry;
        typeToDelete.value = theTypeToDelete;
        verifDelete.style.display = "block";
        document.getElementById("princip").style.display = "none";
    }
}

/**
 * Remplit le formulaire de renommage d'un fichier ou répertoire
 * 
 * @param {string} dir
 * @param {string} entry
 * @param {string} theTypeToRename
 */
function renameDisplay(dir, entry, theTypeToRename){
    var verifRename = document.getElementById("verifRename");
    var toRename = document.getElementById("toRename");
    var newName = document.getElementById("newName");
    var pathElement =  document.getElementById("pathElement");
    var legendRename = document.getElementById("legendRename");
    var typeToRename = document.getElementById("typeToRename");
    
    if (verifRename.style.display == "block"){
        //Masquer le menu de renommage
        verifRename.style.display = "none";
        document.getElementById("princip").style.display = "block";
    } else {
        //Afficher le menu de renommage et entrer les informations
        legendRename.value = "Entrez le nouveau nom du "+theTypeToRename;
        newName.value = entry;
        toRename.value = dir+"/"+entry;
        pathElement.value = dir;
        typeToRename.value = theTypeToRename;
       
        verifRename.style.display = "block";
        document.getElementById("princip").style.display = "none";
    }

}