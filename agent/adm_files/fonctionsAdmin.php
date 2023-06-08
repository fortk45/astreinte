<?php
//Vérifier que l'utilisateur a les droits d'administration
if (($adminRight) && (isset($adm_function)) && ($adm_function)){
?>

    <script src="adm_files/scriptsAdmin.js"></script>
    <?php 

    /**
     * Génère un bouton pour supprimer le fichier (non terminé)
     *
     * @param String $dir Répertoire d'où vient le fichier
     * @param String $entry Nom du fichier
     * @param String $typeOfFile s'il s'agit d'un fichier ou d'un répertoire
     *
     * @return String
     */
    function gestionFichiers($dir, $entry, $typeOfFile){
        return "<div class='button1' style='margin-top:10px;margin-bottom:20px'>
                    Supprimer
                    </div>";
    }

    /**
     * Supprime les sous-répertoires d'un répertoire, puis supprime celui-ci
     * 
     * @param String $dirToDelete Répertoire à traîter
     * 
     */
    function deleteEmptyDirectory($dirToDelete){
        foreach (glob($dirToDelete."/*") as $i) {
            if (is_dir($i)){
                deleteEmptyDirectory($i);
            } else {
                if ((strpos($i, 'Thumbs.db')) || (strpos($i, 'index.php'))){
                    unlink($i);
                }
            }
        }
        rmdir($dirToDelete);
    }

    //On détecte quel est le dossier actuel
    if ((isset($folder)) && (!empty($folder))){
        $dirActuel=$folder;
    } else {
        $dirActuel="../partage";
    }

    // Extensions de fichiers autorisées
    $allowed = array("pdf" => "image/pdf", "jpg" => "image/jpg", 
    "jpeg" => "image/jpeg", "png" => "image/png", 
    "webp" => "image/webp", "bmp" => "image/bmp");
    
    // Extensions de fichiers autorisées (deuxième tableau)
    $allowed2 = array("pdf", "jpg", "jpeg", "png", "webp", "bmp");

    //Pour les essais avant l'impotation
    $unacceptable = ['\\','/',':','*','?','"','<','>','|'];

    
    /* Si un fichier envoyé est détecté, on l'importe */
    if (isset($_FILES["filetoImport"])){
        $msgImport="";
        $msgColor="";

        // Récupérez les données du fichier
        $filename = $_FILES["filetoImport"]["name"];
        
        if (file_exists($dirActuel."/".$filename)){
            $msgImport = "Un fichier du même nom existe déjà au même emplacement";
            $msgColor = "color:var(--bs-red)";
        } else {
            $testMatch = false;
            foreach($unacceptable as $testMatchElement){
                if (strpos ($filename, $testMatchElement)){
                    $testMatch = true;
                }
            }
            if ($testMatch){
                $msgImport = "Le nom du fichier ne peut contenir les caractères suivants :<br/><br/>";
                $msgImport .= '\ / : * ? " < > |';
                $msgColor = "color:var(--bs-red)";
            } else {
            
                $filetmpname = $_FILES["filetoImport"]["tmp_name"];
                $filesize = $_FILES["filetoImport"]["size"];

                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
                if(!array_key_exists($ext, $allowed)){
                    $msgImport = "Format non autorisé. Veuillez importer un document PDF, JPG, JPEG, PNG ou WEBP.";
                    $msgColor = "color:var(--bs-red)";
                } else {
                    // Vérifiez la taille maximale du fichier (2 Mo)
                    if ($filesize < 2000000) {
                        // Définissez le chemin de stockage du fichier
                        $fileDestination = $dirActuel."/".$filename;

                        // Déplacez le fichier de sa source temporaire à sa destination finale
                        move_uploaded_file($filetmpname, $fileDestination);

                        $msgImport = "Téléchargement réussi";
                        $msgColor = "color:var(--bs-green)";
                    } else {
                        $msgImport = "Le fichier est trop volumineux (taille maximale: 2 Mo).";
                        $msgColor = "color:var(--bs-red)";
                    }
                }
            }
        }
        $msgModif = "<div style='".$msgColor."' class='msgImport'>".$msgImport."</div>";
    }

    /* SI UN NOUVEAU DOSSIER EST CREE */
    if (isset($_REQUEST['newDir'])){
        $newDirToCreate = $_REQUEST['newDir'];
        $testMatch = false;
        foreach($unacceptable as $testMatchElement){
            if (strpos ($newDirToCreate, $testMatchElement)){
                $testMatch = true;
            }
        }
        if ($testMatch){
            $msgModif = "<div class='msgImport' style='color:var(--bs-red)'>Le nom du dossier ne peut contenir les caractères suivants :<br/>";
            $msgModif .= '\ / : * ? " < > |</div>';
        } else {
            if (file_exists($dirActuel."/".$newDirToCreate)){
                $msgModif = "<div class='msgImport' style='color:var(--bs-red)'>Un dossier du même nom existe déjà</div>";
            } else {
                mkdir($dirActuel."/".$newDirToCreate);
                $msgModif = "<div class='msgImport' style='color:var(--bs-green)'>Dossier créé</div>";
 
                //Créer un fichier index.php pour le protéger
                $phpfile = fopen($dirActuel."/".$newDirToCreate."/index.php", "w");
                $txt = "<?php header('Location:../');die(); ?>";
                fwrite($phpfile, $txt);
                fclose($phpfile);
            }
        }
    }

    /* SI ON DEMANDE LA SUPPRESSION D'UN FICHIER OU DOSSIER */
    if (isset($_POST['toDelete'])){
        //On vérifie qu'il ne s'agisse pas de la malette élus
        $searchMalette = $connection->query("SELECT `value_param` FROM `parametres` WHERE `nom_param`='dossier_elu'");
        $malette = "../partage/".($searchMalette->fetch())[0];
        $toDelete = $_POST['toDelete'];

        if($malette == $toDelete){
            $msgModif = "<div class='msgImport' style='color:var(--bs-red)'>Impossible de supprimer le dossier : utilisé par les élus</div>";
        } else {
            $typeToDelete = $_POST['typeToDelete'];
            if ($typeToDelete == "dossier"){
                deleteEmptyDirectory($toDelete);
                $msgModif = "<div class='msgImport' style='color:var(--bs-green)'>Dossier supprimé</div>";
            } else {
                unlink($toDelete);
                $msgModif = "<div class='msgImport' style='color:var(--bs-green)'>Fichier supprimé</div>";
            }
        }
    }

    /* SI ON DEMANDE LE RENOMMAGE D'UN FICHIER OU DOSSIER */
    if ((isset($_POST['toRename']))){
        $toRename = $_REQUEST['toRename'];
        $newName = $_REQUEST['newName'];
        $testMatch = false;
        foreach($unacceptable as $testMatchElement){
            if (strpos ($newName, $testMatchElement)){
                $testMatch = true;
            }
        }
        if ($testMatch){
            $msgModif = "<div class='msgImport' style='color:var(--bs-red)'>Le nom du fichier ne peut contenir les caractères suivants :<br/>";
            $msgModif .= '\ / : * ? " < > |</div>';
        } else {
            $pathElement = $_REQUEST['pathElement'];
            $typeToRename = $_REQUEST['typeToRename'];
            $no_change = false;

            if (file_exists($pathElement."/".$newName)){
                $msgModif = "<div class='msgImport' style='color:var(--bs-red)'>Un ".$typeToRename." du même nom existe déjà au même emplacement</div>";
                $no_change = true;
            }
            
            if ($typeToRename == "fichier"){
                $extens_rename = strtolower(substr(strrchr($newName, '.'), 1));
                if (!in_array($extens_rename, $allowed2)){
                    $no_change = true;
                    $msgModif = "<div class='msgImport' style='color:var(--bs-red)'>Nouvelle extension non autorisée</div>";
                }
            }
            if (!$no_change){
                //On vérifie s'il s'agissait de la malette élus
                $searchMalette = $connection->query("SELECT `value_param` FROM `parametres` WHERE `nom_param`='dossier_elu'");
                $malette = "../partage/".($searchMalette->fetch())[0];
                if ($malette == $toRename){
                    $modifMalette = $connection->prepare("UPDATE `parametres` SET `value_param`=:value_param WHERE `nom_param`= 'dossier_elu'"); 
                    $modifMalette->bindParam(':value_param', $newName);
                    $modifMalette->execute();
                }
                rename($toRename, $pathElement."/".$newName);
                $msgModif = "<div class='msgImport' style='color:var(--bs-green)'>Le ".$typeToRename." a été renommé</div>";
            }
        }
    }
}

?>