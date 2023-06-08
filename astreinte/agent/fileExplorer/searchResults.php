<?php
if (isset($search)){
        
    /**
     * Cherche toutes les occurences d'une recherche
     *
     * @param String $dir : Dossier à analyser
     * @param String $search : Terme cherché
     * 
     * @return Array contenant le résultat de la recherche et le nombre d'occurence
     */
    function scannerDeFiles($dir, $search, $nbIter = 0){
        if ((isset($_SESSION['adminRight'])) && ($_SESSION['adminRight']) && (isset($_SESSION['adm_function'])) && ($_SESSION['adm_function'] == 1)){
            $adm_function = True;
        } else {
            $adm_function = False;
        }

        //initialisation des variables
        
        $nb_results = 0;
        $resultTable = [];
        //pour chaque élément du dossier analysé
        foreach(scandir($dir) as $entry){
            $results = "";
            if($entry!='..' && $entry!='.' && $entry!='Thumbs.db' && $entry!='index.php' ){

                //s'il s'agit d'un dossier
                if(is_dir($dir."/".$entry)){
                    //le scanner pour récupérer tous les fichiers correspondants à la recherche
                    $result_Temp = scannerDeFiles($dir."/".$entry, $search, $nbIter);
                    if ($result_Temp[1] != 0){
                        foreach ($result_Temp[0] as $resultLine) {
                            $resultTable[] = $resultLine;
                        }
                    }
                    $nb_results = $nb_results+$result_Temp[1];
                    $nbIter = $result_Temp[2];
                    empty($result_Temp);
                
                //s'il s'agit d'un fichier    
                } else {
                    if ((strpos(strtolower($entry), strtolower($search))) !== false){
                        //récupérer son extension
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
                            //récupérer son nom
                            $entryname = substr($entry, 0, strrpos($entry, "."));

                            //PREPARATION DE L'AFFICHAGE DU RESULTAT
                            $results .= '<div class="col-6 col-md-4 col-lg-3 bloc_contain">';
                                if ($adm_function){
                                    //si admin : préparer l'affichage des bouttons pour gérer le fichier
                                    $dirGuillemets = str_replace("'", "\'",$dir);
                                    $entryGuillemets = str_replace("'", "\'",$entry);
                                    $results .= '<div class="paramfiles_button" onclick="renameDisplay('."'".$dirGuillemets."','".$entryGuillemets."','fichier'".')"><span class="tooltiptext">Renommer</span></div>';
                                    $results .= '<div class="paramfiles_button paramdelete_button" onclick="deleteDisplay('."'".$dirGuillemets."','".$entryGuillemets."','fichier'".')"><span class="tooltiptext">Supprimer</span></div>';
                                }
                                $results .= '<a ';
                                if (($extension == 'pdf') && ($_SESSION['display_iframe'])){
                                    $results .= 'href="index.php?folder='.$dir.'/'.$entry.'&fromSearch='.$search.'"';
                                } else {
                                    $results .= 'href="'.$dir.'/'.$entry.'" target="_blank"';
                                }
                                $results .= ' class="choicelink"><div class="col-12 bloc_choice">';  
                                    $results .= "<img class=img_bloc src='".$icone."'";
                                    $results .= '/> <div class=bloc_text>'.str_replace("_", " ", $entryname).'</div>';
                            $results .= "</div></a></div>\n";
                            $resultTable[$nbIter][0] = strtolower($entryname);
                            $resultTable[$nbIter][1] = $results;
                            $nb_results++;
                            $nbIter++;
                    }
                }
            }
        }

        //Retourne les résultats de la recherche et le nombre de résultats
        return [$resultTable, $nb_results, $nbIter]; 
    }


    //lancement de la recherche
    $scan = scannerDeFiles("../partage", $search);
    $scanResults = $scan[0];        //récupère les résultats
    $nbScanResults = $scan[1];      //récupère le nombre de résultats
    ?>
    <div class="col-12">
        <div class="row">
            <div class="col-12" style="margin-left:auto;margin-right:auto;width:max-content">
                <div class="nbResults">
                    <?php echo $nbScanResults.' résultats pour la recherche "'.$search.'"' ?>
                </div>
            </div>
        </div>
    </div>

    <?php
    //affichage des résultats
    sort($scanResults);
    foreach ($scanResults as $resultLigne) {
        echo $resultLigne[1];
    }}
?>
