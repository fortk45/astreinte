<div class='container'>
    <div class='row'>
        <?php
        if (isset($_REQUEST['modifySujet'])){
            //Modification d'un sujet
            echo "<div class='col-12 nbResults' style='font-size:2rem;'>Modification du sujet</div>";
            foreach(getSujets($connection) as $ligne){
                if ($ligne['id_sujet'] == $_REQUEST['modifySujet']){
                    $idSujet = htmlspecialchars($ligne['id_sujet']);
                    $nomSujet = htmlspecialchars($ligne['libelle_sujet']);
                }
            }
            if (!isset($nomSujet)){
                echo "<script type='text/javascript'>
                    window.location.href = 'index.php?action=gestElements';
                    </script>";
            }
            echo '<div class="bloc_contain">
                        <form action="gestion_elements/editElements.php" method="get" class="bloc_choice" style="padding-top:10px;width: max-content;margin-left: auto;margin-right: auto;">
                            <input type="text" value="'.$nomSujet.'" name="nomSujet" class="bloc_text" style="font-size:1.2rem;filter:none;margin:10px;border-radius:10px">
                            <input type="hidden" value="'.$idSujet.'" name="idSujet">
                            <div class="row" style="padding-left:20px;margin-top:10px">
                                <input type="submit" class="col-6 button1" style="margin-bottom:10px">
                                <a href="index.php?action=gestElements" class="col-6 button1" style="margin-right:20px;margin-left:auto;margin-bottom:10px">Annuler</a>
                            </div>
                        </form>
                    </div>';
        } else {
            if (isset($_REQUEST['deleteSujet'])){
                //Suppression d'un demandeur
                foreach(getSujets($connection) as $ligne){
                    if ($ligne['id_sujet'] == $_REQUEST['deleteSujet']){
                        $idSujet = htmlspecialchars($ligne['id_sujet']);
                        $nomSujet = htmlspecialchars($ligne['libelle_sujet']);
                    }
                }
                if (!isset($nomSujet)){
                    echo "<script type='text/javascript'>
                        window.location.href = 'index.php?action=gestElements';
                        </script>";
                }

                $verifNotUsed = (getIntervBySujet($connection, $idSujet));
                if (!empty($verifNotUsed)){
                    echo "<script type='text/javascript'>
                        window.location.href = 'index.php?action=gestElements&codeDelete=7';
                        </script>";
                } else {
                    //Bloc de confirmation par l'utilisateur de la suppression du sujet
                    echo '<div class="bloc_contain" style="max-width: 30rem;margin-left: auto;margin-right: auto;">
                                <form action="gestion_elements/deleteElements.php" class="bloc_choice" style="padding-top:10px">
                                    <div class="bloc_text" style="font-size:1.2rem;word-wrap:anywhere">Êtes-vous sûr de vouloir supprimer le sujet '.$nomSujet.'</div>
                                    <input type="hidden" value="'.$idSujet.'" name="idSujet">
                                    <div class="row" style="display:block ruby;margin-top:10px">
                                        <input type="submit" class="col-6 button1" style="margin-bottom:10px" value="Oui">
                                        <a href="index.php?action=gestElements" class="col-6 button1" style="margin-bottom:10px;margin-left:30px">Non</a>
                                    </div>
                                </form>
                            </div>';
                }
            } else {
                //Liste des sujets
                echo "<div class='col-12 nbResults' style='font-size:2rem;'>Liste des sujets</div>";
                foreach(getSujets($connection) as $ligne){
                    $idSujet = htmlspecialchars($ligne['id_sujet']);
                    $nomSujet = htmlspecialchars($ligne['libelle_sujet']);

                    echo '<div class="col-6 col-lg-4 col-xl-2 bloc_contain">
                                <div class="bloc_choice" style="padding-top:10px">
                                    <div class="bloc_text" style="font-size:1.2rem">'.$nomSujet.'</div>
                                    <div class="row">
                                        <a href="index.php?action=gestElements&gestion=sujet&modifySujet='.$idSujet.'" class="col-6 button1" style="margin-right:auto;margin-left:auto;margin-bottom:10px">Modifier</a>';
                                $verifNotUsed = (getIntervBySujet($connection, $idSujet));
                                if (empty($verifNotUsed)){        
                                        echo '<a href="index.php?action=gestElements&gestion=sujet&deleteSujet='.$idSujet.'" class="col-6 button1" style="margin-right:auto;margin-left:auto;margin-bottom:10px">Supprimer</a>';
                                    }
                                    echo '</div>
                                </div>
                            </div>';
                }
                //Mettre en fin une place pour ajouter un sujet
                echo '<div class="col-6 col-lg-4 col-xl-2 bloc_contain">
                        <form action="gestion_elements/addElements.php" class="bloc_choice" style="padding-top:10px">
                            <input type="text" style="margin-top:10px;border-radius: 5px;padding:5px" name="nomSujet" placeholder="Ajouter un sujet">
                            <input type="submit" class="button1" style="margin-top:10px;margin-left:auto;margin-right:auto" value="Ajouter">
                        </form>
                    </div>';
            }
        }


        ?>
    </div>
</div>