<div class='container'>
    <div class='row'>
        <?php
        if (isset($_REQUEST['modifyDemandeur'])){
            //Modification d'un demandeur
            echo "<div class='col-12 nbResults' style='font-size:2rem;'>Modification du demandeur</div>";
            foreach(getDemandeurs($connection) as $ligne){
                if ($ligne['id_demandeur'] == $_REQUEST['modifyDemandeur']){
                    $idDemandeur = htmlspecialchars($ligne['id_demandeur']);
                    $nomDemandeur = htmlspecialchars($ligne['nom_demandeur']);
                }
            }
            if (!isset($nomDemandeur)){
                echo "<script type='text/javascript'>
                    window.location.href = 'index.php?action=gestElements';
                    </script>";
            }
            echo '<div class="bloc_contain">
                        <form action="gestion_elements/editElements.php" method="get" class="bloc_choice" style="padding-top:10px;width: max-content;margin-left: auto;margin-right: auto;">
                            <input type="text" value="'.$nomDemandeur.'" name="nomDemandeur" class="bloc_text" style="font-size:1.2rem;filter:none;margin:10px;border-radius:10px">
                            <input type="hidden" value="'.$idDemandeur.'" name="idDemandeur">
                            <div class="row" style="padding-left:20px;margin-top:10px">
                                <input type="submit" class="col-6 button1" style="margin-bottom:10px">
                                <a href="index.php?action=gestElements" class="col-6 button1" style="margin-right:20px;margin-left:auto;margin-bottom:10px">Annuler</a>
                            </div>
                        </form>
                    </div>';
        } else {
            if (isset($_REQUEST['deleteDemandeur'])){
                //Suppression d'un demandeur
                foreach(getDemandeurs($connection) as $ligne){
                    if ($ligne['id_demandeur'] == $_REQUEST['deleteDemandeur']){
                        $idDemandeur = htmlspecialchars($ligne['id_demandeur']);
                        $nomDemandeur = htmlspecialchars($ligne['nom_demandeur']);
                    }
                }
                if (!isset($nomDemandeur)){
                    echo "<script type='text/javascript'>
                        window.location.href = 'index.php?action=gestElements';
                        </script>";
                }

                $verifNotUsed = (getIntervByDemandeur($connection, $idDemandeur));
                if (!empty($verifNotUsed)){
                    echo "<script type='text/javascript'>
                        window.location.href = 'index.php?action=gestElements&codeDelete=7';
                        </script>";
                } else {
                    //Bloc de confirmation par l'utilisateur de la suppression du demandeur
                    echo '<div class="bloc_contain" style="max-width: 30rem;margin-left: auto;margin-right: auto;">
                                <form action="gestion_elements/deleteElements.php" class="bloc_choice" style="padding-top:10px">
                                    <div class="bloc_text" style="font-size:1.2rem;word-wrap:anywhere">Êtes-vous sûr de vouloir supprimer le demandeur '.$nomDemandeur.'</div>
                                    <input type="hidden" value="'.$idDemandeur.'" name="idDemandeur">
                                    <div class="row" style="display:block ruby;margin-top:10px">
                                    <input type="submit" class="col-6 button1" style="margin-bottom:10px" value="Oui">
                                        <a href="index.php?action=gestElements" class="col-6 button1" style="margin-bottom:10px;margin-left:30px">Non</a>
                                    </div>
                                </form>
                            </div>';
                }
            } else {
                //Liste des demandeurs
                echo "<div class='col-12 nbResults' style='font-size:2rem;'>Liste des demandeurs</div>";
                foreach(getDemandeurs($connection) as $ligne){
                    $idDemandeur = htmlspecialchars($ligne['id_demandeur']);
                    $nomDemandeur = htmlspecialchars($ligne['nom_demandeur']);

                    echo '<div class="col-6 col-lg-4 col-xl-2 bloc_contain">
                                <div class="bloc_choice" style="padding-top:10px">
                                    <div class="bloc_text" style="font-size:1.2rem">'.$nomDemandeur.'</div>
                                    <div class="row">
                                        <a href="index.php?action=gestElements&gestion=demandeur&modifyDemandeur='.$idDemandeur.'" class="col-6 button1" style="margin-right:auto;margin-left:auto;margin-bottom:10px">Modifier</a>';
                                $verifNotUsed = (getIntervByDemandeur($connection, $idDemandeur));
                                if (empty($verifNotUsed)){        
                                        echo '<a href="index.php?action=gestElements&gestion=demandeur&deleteDemandeur='.$idDemandeur.'" class="col-6 button1" style="margin-right:auto;margin-left:auto;margin-bottom:10px">Supprimer</a>';
                                    }
                                    echo '</div>
                                </div>
                            </div>';
                }
                //Mettre en fin une place pour ajouter un demandeur
                echo '<div class="col-6 col-lg-4 col-xl-2 bloc_contain">
                        <form action="gestion_elements/addElements.php" class="bloc_choice" style="padding-top:10px">
                            <input type="text" style="margin-top:10px;border-radius: 5px;padding:5px" name="nomDemandeur" placeholder="Ajouter un demandeur">
                            <input type="submit" class="button1" style="margin-top:10px;margin-left:auto;margin-right:auto" value="Ajouter">
                        </form>
                    </div>';
            }
        }

        ?>
    </div>
</div>