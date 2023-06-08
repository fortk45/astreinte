<?php

//récupérer les groupes de l'utilisateur

/**
 * Récupère les groupes d'un utilisateur
 * 
 * @param String $username le samaccountname de l'utilisateur
 * 
 */
function get_groups($username) {

        require("confdb.php");
        $ldap = ldap_connect($MonLdap) or die("Could not connect to LDAP");

        $bind = @ldap_bind($ldap, $ad_user, $ad_password) or die("Could not bind to LDAP");

        if ($bind) {
                // Search AD
                $results = ldap_search($ldap,$ldap_dn,"(samaccountname=$username)",array("memberof","primarygroupid","givenname","sn"));
                $entries = ldap_get_entries($ldap, $results);

                // No information found, bad user
                if($entries['count'] == 0) return false;

                // Get groups and primary group token
                $output = $entries[0]['memberof'];
                $token = $entries[0]['primarygroupid'][0];
                
                //données prenom
                if ((isset($entries[0]['givenname'][0])) && (!is_null($entries[0]['givenname'][0]))){
                        $_SESSION['prenom'] = $entries[0]['givenname'][0];
                } else {
                        $_SESSION['prenom'] = "";
                }

                //données nom
                if ((isset($entries[0]['sn'][0])) && (!is_null($entries[0]['sn'][0]))){
                        $_SESSION['nomFamille'] = $entries[0]['sn'][0];
                } else {
                        $_SESSION['nomFamille'] = "";
                }

                // Remove extraneous first entry
                array_shift($output);

                // We need to look up the primary group, get list of all groups
                $results2 = ldap_search($ldap,$ldap_dn,"(objectcategory=group)",array("distinguishedname","primarygrouptoken"));
                $entries2 = ldap_get_entries($ldap, $results2);

                // Remove extraneous first entry
                array_shift($entries2);

                // Loop through and find group with a matching primary group token
                foreach($entries2 as $e) {
                        if($e['primarygrouptoken'][0] == $token) {
                                // Primary group found, add it to output array
                                $output[] = $e['distinguishedname'][0];
                                // Break loop
                                break;
                        }
                }
                return $output;
        }
}


// LES FONCTIONS CI-DESSOUS NE SONT PAS UTILISES //

//récupérer les distinguished name de l'utilisateur
function getDN($ad, $samaccountname, $basedn){

        $result = ldap_search($ad, $basedn, "(samaccountname={$samaccountname})", array('dn'));
        if (! $result){
                return '';
        }

        $entries = ldap_get_entries($ad, $result);
        if ($entries['count'] > 0){
                return $entries[0]['dn'];
        }

        return '';
}

//récupérer le common name du DN
function getCN($dn){

        preg_match('/[^,]*/', $dn, $matchs, PREG_OFFSET_CAPTURE, 3);
        return $matchs[0][0];
}

//trouver le groupe de l'utilisateur
function checkGroup($ad, $userdn, $groupdn){
        $result = ldap_read($ad, $userdn, "(memberof={$groupdn})", array('members'));
        if (! $result){
                return false;
        }

        $entries = ldap_get_entries($ad, $result);

        return ($entries['count'] > 0);
}

function checkGroupEx($ad, $userdn, $groupdn){

        $result = ldap_read($ad, $userdn, '(objectclass=*)', array('memberof'));
        if (! $result) {
                return false;
        }

        $entries = ldap_get_entries($ad, $result);
        if ($entries['count'] <= 0){
                return false;
        }

        if (empty($entries[0]['memberof'])){
                return false;
        }

        for ($i = 0; $i < $entries[0]['memberof']['count']; $i ++){
                if ($entries[0]['memberof'][$i] == $groupdn){
                        return true;
                }
                elseif (checkGroupEx($ad, $entries[0]['memberof'][$i], $groupdn)){
                        return true;
                }
        }

        return false;
}

?>