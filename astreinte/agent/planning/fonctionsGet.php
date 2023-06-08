<?php

/**
 * Récupère tous les utilisateurs liés à une semaine
 *
 * @param PDO $connection
 * @param Int $idWeek
 * 
 * @return Array
 */
function getAllFromWeek($connection, $idWeek){
    $searchFromWeek = $connection->prepare("SELECT * FROM `preferences` WHERE `id_preferences` IN (SELECT `id_preferences` FROM `associer` WHERE `id_semaine`=:id_week) ORDER BY id_preferences asc");
    $searchFromWeek->bindParam(':id_week', $idWeek);
    $searchFromWeek->execute();
    $lignes = $searchFromWeek->fetchAll();
    return $lignes;
}

/**
 * Récupère tous les utilisateurs non liés à une semaine
 *
 * @param PDO $connection
 * @param Int $idWeek
 * 
 * @return Array
 */
function getNotFromWeek($connection, $idWeek){
    $searchFromWeek = $connection->prepare("SELECT * FROM `preferences` WHERE `id_preferences` NOT IN (SELECT `id_preferences` FROM `associer` WHERE `id_semaine`=:id_week) ORDER BY id_preferences asc");
    $searchFromWeek->bindParam(':id_week', $idWeek);
    $searchFromWeek->execute();
    $lignes = $searchFromWeek->fetchAll();
    return $lignes;
}

if (isset($declareLeGetIDWeek)){
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
}