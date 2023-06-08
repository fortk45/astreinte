<?php
session_start();

//si l'utilisateur connecté passe sur cette page, il est déconnecté
if (isset($_SESSION['nom'])) {
	unset($_SESSION['nom']);
	session_destroy();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="fd3.css">
        <meta>
        <link rel="icon" type="image/x-icon" href="image/logomfla.png" />
	<title>Portail d'astreintes de la ville de Fleury-les-Aubrais</title>
</head>
<body>

<?php
//configuration
$message = "";
require("confdb.php"); //configuration connection à LDAP et les groupes élu/agent
//require("ldapQuery.php"); //récupérer la fonction pour obtenir les groupes de l'utilisateur

if ( isset($_POST["user"]) && isset($_POST["pass"]) && (!is_null($_POST["user"])) && (!is_null($_POST["pass"]))) {
	$username = $_POST["user"];
	$mot2pass = MD5($_POST["pass"]);
        include 'agent/confMySQL.php';
        $requete = $connection->prepare("SELECT `id_preferences`,`prenom`,`nom`,`categorie`,`adminRight` FROM `preferences` WHERE `samaccountname`=:username AND `pass`=:pass");
        $requete->bindParam(':username', $username);
        $requete->bindParam(':pass', $mot2pass);
        $requete->execute();
        $answer = $requete->fetch();

        if ($answer){
                if ($answer['adminRight']){
                        $_SESSION['adminRight'] = True;
                }
                $_SESSION['nom']=$_POST["user"];
                $_SESSION['type']=$answer['categorie'];
                $_SESSION['prenom']=$answer['prenom'];
                $_SESSION['nomFamille']=$answer['nom'];
                header('Location:'.$_SESSION['type'].'/.');
        
        } else {
                //Si l'utilisateur n'est pas dans un groupe autorisé
                $message = "Identifiants incorrects";
        }


        /*$monuser = $username."@".$tld;

        //connection à l'active directory
        $ldapConn = ldap_connect($adServer) or $this->msg = "ERROR CONNECT LDAP";
        ldap_set_option($ldapConn,LDAP_OPT_PROTOCOL_VERSION,3);
        ldap_set_option($ldapConn,LDAP_OPT_REFERRALS,0);

        tentative d'authentification
        $authentifie = @ldap_bind($ldapConn,$monuser,$mot2pass); //or die('Erreur de bind'); //authentification
        ldap_unbind($ldapConn);*/

        //s'il arrive à s'authentifier
        //if ($authentifie) {

                /*
                //code pour vérifier le groupe de l'utilisateur
                $grouptestAgent="CN=".$groupe_agent.",".$ldap_dntest;
                $grouptestElu="CN=".$groupe_elu.",".$ldap_dntest;
                $test=get_groups($username);
                if ($test){
                        $result=count($test);
                        $UserDansGroupAgent=false;
                        $UserDansGroupElu=false;
                        for ($i = 0; $i < $result; $i++) {
                                if (in_array($test[$i], $info_ou_table)){
                                        //Si l'utilisateur est dans un groupe autorisé à administrer l'astreinte
                                        $_SESSION['adminRight'] = True;
                                }
                        }

                        for ($i = 0; $i < $result; $i++) {
                                switch ($test[$i]) {
                                        case $grouptestAgent:
                                                $_SESSION['adminRight'] = True;    //retirer cette ligne si les agents ne sont pas censés avoir les droits d'administration
                                                $_SESSION['nom']=$_POST["user"];
                                                $_SESSION['type']="agent";
                                                header('Location:agent/.');        //rediriger vers index l'agent
                                                break;
                                        
                                        case $grouptestElu:
                                                $_SESSION['nom']=$_POST["user"];
                                                $_SESSION['type']="elu";
                                                header('Location:elu/.');        //rediriger vers index l'élu
                                                break;
                                        default:
                                                //Si l'utilisateur n'est pas dans un groupe autorisé
                                                $message = "Accès refusé : pour plus d'information, veuillez vous rapprocher de votre DSI. <br/>
                                                                (Code : 375)";
                                                break;
                                }
                        }
                } else {
                        //Si l'utilisateur n'est pas dans un groupe autorisé
                        $message = "Accès refusé : pour plus d'information, veuillez vous rapprocher de votre DSI. <br/>
                        (Code : 375)";
                }*/
        /*} else {
                //Si les identifiants/mot de passe sont incorrects
                $message = "Accès refusé : pour plus d'information, veuillez vous rapprocher de votre DSI. (code=1490)";
        }*/
} else {
        //si l'utilisateur n'a rien envoyés
        $message = "Veuillez saisir votre identifiant et votre mot de passe";

        // ajoute les utilisateurs autorisés dans la BDD
        //(mis ici pour que ce soit fait le moins souvent possible, mais vérifié à chaque connexion)
        //include 'script_maj_user.php';
}
?>

<!-- logo Fleury-les-Aubrais -->
<img class="displayed" src="image/logomfla2.png" width="170px" alt="logo de la ville de Fleury-les-Aubrais" />

<!-- Le formulaire de connection -->
<div class="login">
	<h1>Portail d'astreintes</h1>
	<br>
   	<form action="index.php" method="post">
        	<input type="text" name="user" placeholder="Nom d'utilisateur" required="required" />	
	        <input type="password" name="pass" placeholder="Mot de passe" required="required" />
        	<button type="submit" class="btn btn-primary btn-block btn-large button1">Se connecter</button> 
	</form>
<?php
//afficher le message (d'erreur ou d'information)
if ($message != "") {
        print("<br/><div class='msgAccueil'>".$message."</div>"); 
}
?>
</div>
</body>
</html>