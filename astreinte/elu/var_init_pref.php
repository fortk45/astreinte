<?php
//Par défaut, les préférences sont à "vrai"
$display_iframe = True;
$display_empty = True;
if ($adminRight){
    $adm_function = True;
}

if ($connection){
    // On vérifie si l'utilisateur est dans la base de données
    $username = $_SESSION['nom'];
    $requete = $connection->prepare("SELECT * FROM `preferences` WHERE `samaccountname`=:username");
    $requete->bindParam(':username', $username);
    $requete->execute();
    $answer = $requete->fetch();

    if (!$answer){
        // Si non, on crée une ligne pour l'utilisateur dans la BDD
        $createAccount = $connection->prepare("INSERT INTO `preferences` (`samaccountname`, `prenom`, `nom`, `categorie`) 
                                    VALUES (:username, :prenom, :nom, 'agent')"); 
        $createAccount->bindParam(':username', $username);
        $createAccount->bindParam(':prenom', $prenom);
        $createAccount->bindParam(':nom', $nom);
        $createAccount->execute();

        if ($adminRight){
            $_SESSION['adm_function'] = $adm_function;

			$createAccount = $connection->prepare("UPDATE `preferences` SET `display_admFunction`=:adm_function WHERE `samaccountname`=:samaccountname");
			$createAccount->bindParam(':adm_function', $adm_function);
			$createAccount->bindParam(':samaccountname', $samaccountname);
			$createAccount->execute();
        }
        
    } else {
        //Si oui, on vérifie si, dans ses préférences, il y a des valeurs négatives
        if (!$answer['display_iframe']){
            $display_iframe = False;
        }
        if(!$answer['display_empty']){
            $display_empty = False;
        }
        if ($adminRight){
            if(!$answer['display_admFunction']){
                $adm_function = False;
            }
        }
    }
}
//On entre dans une variable de session ses préférences
$_SESSION['display_iframe']= $display_iframe;
$_SESSION['display_empty'] = $display_empty;
if ($adminRight){
    $_SESSION['adm_function'] = $adm_function;
}
?>
