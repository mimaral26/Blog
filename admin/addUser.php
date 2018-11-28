<?php
//idée est de récupérer les données du formulaire et de le poster dans la BDD

include('../config/config.php');
include('../lib/bdd.lib.php');


$vue='addUser.phtml';
$title = 'Inscription de nouveaux auteurs';
$erreur = false;
/** On essaie de se connecter et de faire notre requête
 * Principe des exception en programmation
 * Je vous expliquerai tout ça mais vous pouvez déjà lire ceci :
 * https://www.pierre-giraud.com/php-mysql/cours-complet/php-exceptions.php
 * http://php.net/manual/fr/language.exceptions.php
 */
$tableAuteurs=
[
    'nom' => '',
    'prenom' => '',
    'email' => '',
    'motDePasse' => '',
    'bio' => '',
    'avatar' => '',
    'identifiant' => '',
    'role' => '',
]
 
try
{
   if(array_key_exists('nom',$_POST))
   {
        if($_POST['motDePasse'] === $_POST['confirmerMotDePasse'])
        {
           //var_dump($_POST); 
            if (empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['email']) || empty($_POST['identifiant']))
	        {
                echo "ERREUR : tous les champs n'ont pas ete renseignés.";
	        else
        
                $tableAuteurs=
            [
                'nom' => $_POST['nom'],
                'prenom' => $_POST['prenom'],
                'email' => $_POST['email'],
                'motDePasse' => password_hash($_POST['motDePasse'], PASSWORD_DEFAULT),
                'bio' => $_POST['bio'],
                'avatar' => $_POST['avatar'],
                'identifiant' => $_POST['identifiant'],
                'role' => $_POST['role'],
            ];
            
            //var_dump($tableAuteurs);
            }   
            /** 1 : connexion au serveur de BDD - SGBDR */
            $dbh = connexion();

            /** 2 : Prépare ma requête SQL pour insérer les données du formulaire */ 
            $sth = $dbh->prepare('INSERT INTO blog_auteurs (aut_id, aut_nom, aut_prenom, aut_email, aut_password, aut_biographie, aut_avatar, aut_username, aut_role) VALUES (NULL, :nom, :prenom, :email, :motDePasse, :bio, :avatar, :identifiant, :role)');
        
            /** 3 : executer la requête */
            $sth->execute($tableAuteurs);
            
            /** 4 : recupérer les résultats*/ 
            /*On utilise FETCH car un seul résultat attendu*/
            $tableAuteurs = $sth->fetchAll(PDO::FETCH_ASSOC);
        }   
   }   
    catch(PDOException $e)
    {
    /*Si une exception est envoyée par PDO (exemple : serveur de BDD innaccessible) on arrive ici*/
    $erreur = 'Une erreur de connexion a eu lieu :'.$e->getMessage();
    }
}

/* On inclu la vue pour afficher les résultats */
include('tpl/layout.phtml');
?>




