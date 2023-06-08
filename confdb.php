<?php
//ldap
$adServer='ldap.serveur-fleury.fr';
$tld="serveur-fleury.fr";
$MonLdap="ldap://".$adServer;
$ldap_dn="OU=Mairie Fleury,DC=serveur-fleury,DC=fr";

$ad_user="ldap_astreinte@".$tld;
$ad_password="WSn8A8ce8fpZzUg71Jhv";

$groupe_agent="astreinte_users";
$groupe_elu="astreinte_elus";

$ldap_dntest="OU=Applications,OU=Mairie Fleury,DC=serveur-fleury,DC=fr";

/* Mettre ci-dessous le CN du service ayant accès aux paramètres d'administration */
// $info_ou="CN=Service Informatique,OU=Gestion des groupes,DC=serveur-fleury,DC=fr";

/* Mettre ci-dessous la table avec les CN des service autorisés, chaque CN entre guillemets et séparé par une virgule */
$info_ou_table=["CN=Service Informatique,OU=Gestion des groupes,DC=serveur-fleury,DC=fr"]

?>