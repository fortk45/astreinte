<?php
$ftp_server = "192.168.89.42";		//mettre l'adresse du serveur
$ftp_user_name = "";			//mettre le nom d'utilisateur
$ftp_user_pass = "";				//mettre le mot de passe
$image_dir = "partage/";				//mettre le chemin vers le répertoire des images

$conn = ftp_connect($ftp_server);
$login = ftp_login($conn, $ftp_user_name, $ftp_user_pass);
$mode = ftp_pasv($conn, TRUE);
?>