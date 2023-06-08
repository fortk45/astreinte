<?php
/**
 * Récupère toutes les interventions liés à un demandeur
 *
 * @param PDO $connection
 * 
 * @param Int $id_demandeur
 * 
 * @return Array
 */
function getIntervByDemandeur($connection, $id_demandeur){
    $sqlListe = $connection->prepare("SELECT * FROM `intervention` WHERE `id_demandeur`=:id_demandeur ORDER BY `date_appel` DESC, `heure_appel` DESC");
    $sqlListe->bindParam(':id_demandeur', $id_demandeur);
    $sqlListe->execute();
    $lignes = $sqlListe->fetchAll();
    return $lignes;
}

/**
 * Récupère toutes les interventions dont le lieu comprends la valeur entrée
 *
 * @param PDO $connection
 * 
 * @param Int $name_lieu la valeur entrée par l'utilisateur
 * 
 * @return Array
 */
function getIntervByLieu($connection, $name_lieu){
    $lieu = '%'.$name_lieu.'%';
    $sqlListe = $connection->prepare("SELECT * FROM `intervention` WHERE `lieu_appel` LIKE :name_lieu ORDER BY `date_appel` DESC, `heure_appel` DESC");
    $sqlListe->bindParam(':name_lieu', $lieu);
    $sqlListe->execute();
    $lignes = $sqlListe->fetchAll();
    return $lignes;
}

/**
 * Récupère toutes les interventions dont le motif comprends la valeur entrée
 *
 * @param PDO $connection
 * 
 * @param String $name_motif la valeur entrée par l'utilisateur
 * 
 * @return Array
 */
function getIntervByMotif($connection, $name_motif){
    $motif = '%'.$name_motif.'%';
    $sqlListe = $connection->prepare("SELECT * FROM `intervention` WHERE `motif_appel` LIKE :motif ORDER BY `date_appel` DESC, `heure_appel` DESC");
    $sqlListe->bindParam(':motif', $motif);
    $sqlListe->execute();
    $lignes = $sqlListe->fetchAll();
    return $lignes;
}

/**
 * Récupère toutes les interventions liées à un sujet
 *
 * @param PDO $connection
 * 
 * @param String $id_sujet
 * 
 * @return Array
 */
function getIntervBySujet($connection, $id_sujet){
    $lignes = [];
    $sqlListe = $connection->prepare("SELECT `id_interv` FROM `effectue_sur` WHERE `id_sujet`=:id_sujet");
    $sqlListe->bindParam(':id_sujet', $id_sujet);
    $sqlListe->execute();
    $results = $sqlListe->fetchAll();
    foreach ($results as $aResult) {
        $lignes[] = getAnInterv($connection, $aResult['id_interv']);
    }
    return $lignes;
}

/**
 * Récupère toutes les interventions liées à un cadre
 *
 * @param PDO $connection
 * 
 * @param Int $id_user l'ID du cadre
 * 
 * @return Array
 */
function getIntervByUser($connection, $id_user){
    $sqlListe = $connection->prepare("SELECT * FROM `intervention` WHERE `id_preferences`=:id_user ORDER BY `date_appel` DESC, `heure_appel` DESC");
    $sqlListe->bindParam(':id_user', $id_user);
    $sqlListe->execute();
    $lignes = $sqlListe->fetchAll();
    return $lignes;
}

/**
 * Récupère toutes les interventions entre deux dates
 *
 * @param PDO $connection
 * 
 * @param Date $dateDebut la date de début
 * 
 * @param Date $dateFin la date de fin
 * 
 * @return Array
 */
function getIntervByDate($connection, $dateDebut, $dateFin){
    if ($dateDebut == 'no_limit'){
        if ($dateFin == 'no_limit'){
        //Si toute période non définie
        $sqlListe = $connection->query("SELECT * FROM `intervention` ORDER BY `date_appel` DESC, `heure_appel` DESC");
        $lignes = $sqlListe->fetchAll();

        } else {
            //Si non défini à date précise (donc avant dateFin)
            $sqlListe = $connection->prepare("SELECT * FROM `intervention` WHERE `date_appel` <= :dateFin ORDER BY `date_appel` DESC, `heure_appel` DESC");
            $sqlListe->bindParam(':dateFin', $dateFin);
            $sqlListe->execute();
            $lignes = $sqlListe->fetchAll();

        }
    } else {
        if ($dateFin == 'no_limit'){
        //Si date précise à non défini (donc après dateDebut)
            $sqlListe = $connection->prepare("SELECT * FROM `intervention` WHERE `date_appel` >= :dateDebut ORDER BY `date_appel` DESC, `heure_appel` DESC");
            $sqlListe->bindParam(':dateDebut', $dateDebut);
            $sqlListe->execute();
            $lignes = $sqlListe->fetchAll();

        } else {
            //si deux dates précises
            $sqlListe = $connection->prepare("SELECT * FROM `intervention` WHERE `date_appel` BETWEEN :dateDebut AND :dateFin ORDER BY `date_appel` DESC, `heure_appel` DESC");
            $sqlListe->bindParam(':dateDebut', $dateDebut);
            $sqlListe->bindParam(':dateFin', $dateFin);
            $sqlListe->execute();
            $lignes = $sqlListe->fetchAll();
        }
    }
    return $lignes;

}

/**
 * Récupère les jours de début et de fin de la semaine
 *
 * @param PDO $connection
 * 
 * @param Int $week le numéro de semaine
 * 
 * @param Int $year l'année
 * 
 * @return Array
 */
function getStartAndEndDate($week, $year) {
    $dto = new DateTime();
    $dto->setISODate($year, $week);
    $ret['week_start'] = $dto->format('Y-m-d');
    $dto->modify('+6 days');
    $ret['week_end'] = $dto->format('Y-m-d');
    return $ret;
}

/**
 * Récupère toutes les interventions de la semaine du jour entré
 *
 * @param PDO $connection
 * 
 * @param Date $date
 * 
 * @return Array contenant la liste des interventions, le numéro de semaine, le premier et le dernier jour de la semaine 
 */
function getIntervByWeek($connection, $date){
    $theDate = new DateTime($date);
    $week = $theDate->format("W");
    $year = $theDate->format("Y");
    $week_array = getStartAndEndDate($week, $year);
    $dateDebut = $week_array['week_start'];
    $dateFin = $week_array['week_end'];
    
    $sqlListe = $connection->prepare("SELECT * FROM `intervention` WHERE `date_appel` BETWEEN :dateDebut AND :dateFin ORDER BY `date_appel` DESC, `heure_appel` DESC");
    $sqlListe->bindParam(':dateDebut', $dateDebut);
    $sqlListe->bindParam(':dateFin', $dateFin);
    $sqlListe->execute();
    $lignes[0] = $sqlListe->fetchAll();
    $lignes[1] = $week;
    $lignes[2] = $dateDebut;
    $lignes[3] = $dateFin;
    return $lignes;

}

?>