<?php
require("agent/confMySQL.php");

$ldapConn = ldap_connect($MonLdap) or $this->msg = "ERROR CONNECT LDAP";
ldap_set_option($ldapConn,LDAP_OPT_PROTOCOL_VERSION,3);
ldap_set_option($ldapConn,LDAP_OPT_REFERRALS,0);
$ldapBind = ldap_bind($ldapConn,$ad_user,$ad_password);
$ldapResult = ldap_search($ldapConn,"OU=Utilisateurs,OU=Mairie Fleury,DC=serveur-fleury,DC=fr","(sn=*)");	//cherche sur le serveur ces informations
$entry = ldap_get_entries($ldapConn,$ldapResult);				//entre dans la variable $entry les résultats contenus dans $ldapConn et $ldapResult

 //compter nombre entrées
$nb = $entry["count"];

//préparer les tests
$grouptestAgent="CN=".$groupe_agent.",".$ldap_dntest;
$grouptestElu="CN=".$groupe_elu.",".$ldap_dntest;

//Faire un tableau avec les personnes déjà entrées dans la base de données
$tableAlready = [];
$requete1 = $connection->query("SELECT * FROM `preferences`");
$resultRequest1 = $requete1->fetchAll();
foreach ($resultRequest1 as $member) {
	$tableAlready[] = $member['samaccountname'];
}

for ($x=0; $x<$nb; $x++) {
	$samaccountname = "";
	$LastName = "";
	$FirstName = "";

	if (isset($entry[$x]["memberof"])){
		foreach ($entry[$x]["memberof"] as $key) {
			if (($key == $grouptestAgent) || ($key == $grouptestElu)){
				$samaccountname = $entry[$x]["samaccountname"][0];	//on entre dans la variable $samaccountname le nom du compte
				$FirstName = $entry[$x]["givenname"][0];		//on entre dans la variable $FirstName le prénom
				$LastName = $entry[$x]["sn"][0];				//on entre dans la variable $LastName le nom de famille
				if ($key == $grouptestAgent){
					$typeAgent = "agent";
				} else {
					$typeAgent = "elu";
				}
			}
		}
	}

	if (!empty($samaccountname)){

	    // On vérifie si l'utilisateur est dans la base de données
		if (in_array($samaccountname, $tableAlready)){
			$index = array_search($samaccountname, $tableAlready);
			unset($tableAlready[$index]);
		} else {

			// Si non, on crée une ligne pour l'utilisateur dans la BDD
			$createAccount = $connection->prepare("INSERT INTO `preferences` (`samaccountname`, `prenom`, `nom`, `categorie`, `display_admFunction`) 
										VALUES (:samaccountname, :prenom, :nom, :typeAgent, 1)"); 
			$createAccount->bindParam(':samaccountname', $samaccountname);
			$createAccount->bindParam(':prenom', $FirstName);
			$createAccount->bindParam(':nom', $LastName);
			$createAccount->bindParam(':typeAgent', $typeAgent);
			$createAccount->execute();
		}
	}
}
ldap_unbind($ldapConn);		//On se déconnecte de LDAP
?>