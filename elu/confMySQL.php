<?php
/*$sql_serveur="pr-bdd-01";
$sql_user="astreintesuser@pr-www-03";
$sql_password="SPZ8kSdD4z6aJtXWH2nH";
$sql_bdd="astreintes";
$dns = 'mysql:host='.$sql_serveur.';dbname='.$sql_bdd;*/

$sql_serveur="localhost";
$sql_user="root";
$sql_password="";
$sql_bdd="astreintes";
$dns = 'mysql:host='.$sql_serveur.';dbname='.$sql_bdd;

//$connection = mysqli_connect($sql_serveur, "$sql_user", "$sql_password", "$sql_bdd");
$connection =  new PDO($dns, $sql_user, $sql_password);

?>