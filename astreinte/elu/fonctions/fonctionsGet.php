<?php

/**
 * Récupère toutes les informations de toutes les interventions
 *
 * @param PDO $connection
 * 
 * @return Array
 */
function getInterv($connection){
    $sqlListe = $connection->query("SELECT * FROM `intervention` ORDER BY `date_appel` DESC, `heure_appel` DESC");
    $lignes = $sqlListe->fetchAll();
    return $lignes;
}

/**
 * Récupère toutes les informations de toutes les interventions de l'utilisateur
 *
 * @param PDO $connection
 * 
 * @return Array
 */
function getIntervFromUser($connection){
    $id_preferences = getIDUser($connection);
    $sqlListe = $connection->query("SELECT * FROM `intervention` WHERE `id_preferences`=$id_preferences ORDER BY `date_appel` DESC, `heure_appel` DESC");
    $lignes = $sqlListe->fetchAll();
    return $lignes;
}

/**
 * Récupère toutes les informations d'une intervention
 *
 * @param PDO $connection
 * @param Int $id_interv l'id de l'intervention
 * 
 * @return Array
 */
function getAnInterv($connection, $id_interv){
    $sqlLinterv = $connection->prepare("SELECT * FROM `intervention` WHERE `id_interv`=:id_interv");
    $sqlLinterv->bindParam(':id_interv', $id_interv);
    $sqlLinterv->execute();
    $lignes = $sqlLinterv->fetchAll();
    return $lignes[0];
}

/**
 * Récupère tous les sujets
 *
 * @param PDO $connection
 * 
 * @return Array
 */
function getSujets($connection){
    $sqlListe = $connection->query("SELECT * FROM `sujet` ORDER BY libelle_sujet asc");
    $lignes = $sqlListe->fetchAll();
    return $lignes;
}

/**
 * Récupère le nombre de sujets
 *
 * @param PDO $connection
 * 
 * @return Array
 */
function getNbSujets($connection){
    $sqlListe = $connection->query("SELECT count(`id_sujet`) AS 'nb' FROM `sujet`");
    $lignes = $sqlListe->fetch();
    return $lignes[0];
}

/**
 * Récupère tous les demandeurs
 *
 * @param PDO $connection
 * 
 * @return Array
 */
function getDemandeurs($connection){
    $sqlListe = $connection->query("SELECT * FROM `demandeur` ORDER BY nom_demandeur asc");
    $lignes = $sqlListe->fetchAll();
    return $lignes;
    
}

function getLieux($connection){
    $sqlListe = $connection->query("SELECT DISTINCT `lieu_appel` FROM `intervention` ORDER BY `lieu_appel` asc");
    $lignes = $sqlListe->fetchAll();
    return $lignes;
}

/**
 * Récupère tous les sujets d'une intervention
 *
 * @param PDO $connection
 * @param Int $id_interv l'id de l'intervention
 * 
 * @return Array
 */
function getSujetsInterv($connection, $id_interv){
    $sqlRequestSujets = $connection->prepare("SELECT `id_sujet` FROM `effectue_sur` WHERE `id_interv`=:id_interv");
    $sqlRequestSujets->bindParam(':id_interv', $id_interv);
    $sqlRequestSujets->execute();
    $results = $sqlRequestSujets->fetchAll();
    $lignes = [];
    foreach($results as $aResult){
        $lignes[] = $aResult['id_sujet'];
    }
    return $lignes;
}

/**
 * Récupère l'ID de l'utilisateur
 *
 * @param PDO $connection
 * 
 * @return Array
 */
function getIDUser($connection){
    $userSamaccountname = $_SESSION['nom'];
    $searchID = "SELECT `id_preferences` FROM preferences WHERE `samaccountname`='$userSamaccountname'";
    $q = $connection->query($searchID);
    $lignes = $q->fetchAll();
    $id_preferences = $lignes[0][0];
    return $id_preferences;
}

/**
 * Récupère le nom d'un utilisateur
 *
 * @param PDO $connection
 * @param Int $idUser l'ID de l'utilisateur recherché
 * 
 * @return Array
 */
function getNameFromUser($connection, $idUser){
    $searchName = $connection->prepare("SELECT `prenom`, `nom` FROM preferences WHERE `id_preferences`=:idUser");
    $searchName->bindParam(':idUser', $idUser);
    $searchName->execute();
    $lignes = $searchName->fetchAll();
    $name = $lignes[0][0].' '.$lignes[0][1];
    return $name;
}

/**
 * Récupère l'ID d'une semaine
 * 
 * @param PDO $connection
 * @param Int $week la semaine
 * @param Int $year l'année
 * 
 * @return Int
 */
function getIDweek($connection, $week, $year){
    $searchIDweek = $connection->prepare("SELECT `id_semaine` FROM `semaine` WHERE `semaine` = :theWeek && `annee` = :theYear");
    $searchIDweek->bindParam(':theWeek', $week);
    $searchIDweek->bindParam(':theYear', $year);
    $searchIDweek->execute();
    $lignes = $searchIDweek->fetch();
    if (!$lignes){
        return '0';
    } else {
        return $lignes[0];
    }  
}

/**
 * Récupère tous les élus liés à une semaine
 *
 * @param PDO $connection
 * 
 * @return Array
 */
function getFromWeekAndRole($connection, $idWeek, $categ){
    $searchFromWeek = $connection->prepare("SELECT * FROM `preferences` WHERE `id_preferences` IN (SELECT `id_preferences` FROM `associer` WHERE `id_semaine`=:id_week AND `categ`=:categ) ORDER BY id_preferences asc");
    $searchFromWeek->bindParam(':id_week', $idWeek);
    $searchFromWeek->bindParam(':categ', $categ);
    $searchFromWeek->execute();    
    $lignes = $searchFromWeek->fetchAll();
    return $lignes;
}


/**
 * Récupère tous les utilisateurs
 *
 * @param PDO $connection
 * 
 * @return Array
 */
function getAllUsers($connection){
    $searchAllUsers = $connection->query("SELECT * FROM preferences ORDER BY id_preferences asc");
    $lignes = $searchAllUsers->fetchAll();
    return $lignes;
}
?>