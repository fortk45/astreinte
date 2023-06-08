<?php
/* PAGE POUR CHANGER LA DATE DU PLANNING CONSULTE */
$dateChange = $_REQUEST['dateChange'];
$theDate = new DateTime($dateChange);
$week = $theDate->format("W");
$year = $theDate->format("Y");

header('location: ../index.php?action=planning&w_plan='.$week.'&y_plan='.$year)

?>