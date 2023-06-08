<?php
// -----------------------------
// Explorateur de dossier / fichiers


/**
 * Vérifie si le répertoire et ses sous-répertoires sont vides
 *
 * @param String $dir Dossier à analyser
 * 
 * @return Array (valeur 1 : si vrai : le dossier ou ses sous-dossiers n'est pas vide. Si faux : le dossier et ses sous-dossiers sont vides; valeur 2 : nombre de fichiers)
 */
function verifNotEmpty($dir){
    $encountered = false; 
    $nbFile = 0;
    foreach (glob($dir."/*") as $i) {   
        $verifI = substr($i, -9);
        if(is_dir($i)){
            $testThatDir = verifNotEmpty($i);
            if ($testThatDir[0]){
                $encountered = true;
                $nbFile = $nbFile+$testThatDir[1];
            }
        } else {
            if (($verifI != "Thumbs.db") && ($verifI != "index.php")) { 
                $encountered = true;
                $nbFile++;
            }
        }
    }
    if ($encountered){
        return [true,$nbFile];
    } else {
        return [false,0];
    }
}


/**
 * Affiche le contenu d'un répertoire
 *
 * @param String $dir Répertoire à analyser
 * 
 * @return String
 */
function explore_dir_scan_html($dir)
{
    $allowed = array("pdf", "jpg", "jpeg", "png", "webp", "bmp");
    
    if ((isset($_SESSION['adminRight'])) && ($_SESSION['adminRight']) && (isset($_SESSION['adm_function'])) && ($_SESSION['adm_function'] == 1)){
        $adm_function = True;
    } else {
        $adm_function = False;
    }

    if ($handle = opendir($dir)) {
        $resultsDir = "";
        $resultsFiles = "";
        $couleur=true;
        while (false !== ($entry = readdir($handle))) {
            $empty = False;
            $nbFilesThatDir = 0;

            //s'il s'agit d'un dossier
            if(is_dir($dir.'/'.$entry)) {
                if($entry!='..' && $entry!='.') {
                    //vérifier si le dossier est vide
                    $testThatDir = verifNotEmpty($dir.'/'.$entry);
                    if (!$testThatDir[0]){
                        $empty = True;
                    } else {
                        $nbFilesThatDir = $testThatDir[1];
                    }
                    if ((!$empty) || ($_SESSION['display_empty'])){
                        //PREPARATION DE L'AFFICHAGE DU DOSSIER
                        $resultsDir .= '<div class="col-6 col-md-4 col-lg-3 bloc_contain">';

                            if ($adm_function){
                                //si admin : préparer l'affichage des bouttons pour gérer le dossier
                                $dirGuillemets = str_replace("'", "\'",$dir);
                                $entryGuillemets = str_replace("'", "\'",$entry);
                                $resultsDir .= '<div class="paramfiles_button" onclick="renameDisplay('."'".$dirGuillemets."','".$entryGuillemets."','dossier'".')"><span class="tooltiptext">Renommer</span></div>';
                                if ($empty){
                                    $resultsDir .= '<div class="paramfiles_button paramdelete_button" onclick="deleteDisplay('."'".$dirGuillemets."','".$entryGuillemets."','dossier'".')"><span class="tooltiptext">Supprimer</span></div>';
                                }
                            }
                        
                            $resultsDir .= '<a href="index.php?folder='.$dir.'/'.$entry.'" 
                                class="choicelink"><div class="col-12 bloc_choice"';
                            if ($empty){ $resultsDir .= " style='background-color:#abababe8'";}
                            $resultsDir .= '>';  
                            
                            $resultsDir .= "<img class=img_bloc src='../image/folder.png' alt='dossier' "; 
                            if ($empty){$resultsDir .= "style='filter:none;'";}

                            $resultsDir .= '/><div class=bloc_text>'.str_replace("_", " ", $entry).'</div>';
                            if($empty){
                                $resultsDir .= "<div class='bloc_text' style='padding-top:0px'>(Vide)</div>";
                            } else {
                                $resultsDir .= "<div class='bloc_text' style='padding-top:0px'>(".$nbFilesThatDir." fichiers )</div>";
                            }
                        $resultsDir .= "</div></a></div>\n";
                    }
                }

            //s'il s'agit d'un fichier
            } else { 
                if (($entry!== "Thumbs.db") and ($entry!== "index.php")){
                    //Récupération de l'extension du fichier
                    $extension = strtolower(substr(strrchr($entry, '.'), 1));
                    switch ($extension) {
                        case "pdf":
                            $icone = "../image/icon-pdf.png";
                            break;
                        case "jpg":
                            $icone = "../image/icon-jpg.png";
                            break;
                        case "jpeg":
                            $icone = "../image/icon-jpeg.png";
                            break;
                        case "png":
                            $icone = "../image/icon-png.png";
                            break;
                        case "webp":
                            $icone = "../image/icon-webp.png";
                            break;
                        case "bmp":
                            $icone = "../image/icon-bmp.png";
                            break;
                        //CAS NON IMPORTABLES MAIS JE LES LAISSE CAR IL Y A ENCORE DES FICHIERS
                        // DE CES EXTENSIONS 
                        case "ods":
                            $icone = "../image/ods.png";
                            break;
                        case "odt":
                            $icone = "../image/odt.png";
                            break;
                        case "xlsx":
                            $icone = "../image/ods.png";
                            break;
                        case "docx":
                            $icone = "../image/odt.png";
                            break;
                        default:
                            $icone = "";
                    }
                    //PREPARATION DE L'AFFICHAGE DU FICHIER
                    $entryname = substr($entry, 0, strrpos($entry, "."));
                    $resultsFiles .= '<div class="col-6 col-md-4 col-lg-3 bloc_contain">';

                        if ($adm_function){
                            //si admin : préparer l'affichage des bouttons pour gérer le fichier
                            $dirGuillemets = str_replace("'", "\'",$dir);
                            $entryGuillemets = str_replace("'", "\'",$entry);
                            $resultsFiles .= '<div class="paramfiles_button" onclick="renameDisplay('."'".$dirGuillemets."','".$entryGuillemets."','fichier'".')"><span class="tooltiptext">Renommer</span></div>';
                            $resultsFiles .= '<div class="paramfiles_button paramdelete_button" onclick="deleteDisplay('."'".$dirGuillemets."','".$entryGuillemets."','fichier'".')"><span class="tooltiptext">Supprimer</span></div>';
                        }
                        
                        $resultsFiles .= '<a ';

                        if ((in_array(strtolower($extension), $allowed)) && ($_SESSION['display_iframe'])){
                            $resultsFiles .= 'href="index.php?folder='.$dir.'/'.$entry.'"';
                        } else {
                            $resultsFiles .= 'href="'.$dir.'/'.$entry.'" target="_blank"';
                        }
                        $resultsFiles .= ' class="choicelink"><div class="col-12 bloc_choice">';  
                        $resultsFiles .= "<img class=img_bloc src='".$icone."' alt='fichier'";
                        $resultsFiles .= '/> <div class=bloc_text>'.str_replace("_", " ", $entryname).'</div>';
                    $resultsFiles .= "</div></a></div>\n";
                }
            }
        
        }
        //Retourne les résultats de la recherche, en affichant d'abord tous les dossiers puis tous les fichiers
        return $resultsDir.$resultsFiles;
    }
};

?>
