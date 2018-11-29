<?php
session_start();

include('../config/config.php');
include('../lib/app.lib.php');

userIsConnected();

$vue='addUser';
$title = 'Ajouter un utilisateur';

//Initialisation des erreurs à false
$erreur = '';

//Tableau correspondant aux valeurs à récupérer dans le formulaire.
$values = [
'nom'=>'',
'prenom'=>'',
'email'=>'',
'password'=>'',
'bio'=>'',
'avatar'=>''];

$tab_erreur =
[
'nom'=>'Le nom doit être rempli !',
'prenom'=>'Le prénom doit être rempli !',
'email'=>'L\'email doit être rempli !',
'password'=>'Le mot de passe ne peut être vide'
];

try
{

    if(array_key_exists('nom',$_POST))
    {
        foreach($values as $champ => $value)
        {
            if(isset($_POST[$champ]) && trim($_POST[$champ])!='')
                $values[$champ] = $_POST[$champ];
            elseif(isset($tab_erreur[$champ]))   
                $erreur.= '<br>'.$tab_erreur[$champ];
            else
                $values[$champ] = '';
        }

        if($_POST['password'] != $_POST['passwordConf'])
            $erreur.= '<br> Erreur confirmation mot de passe';

        if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
            $erreur.= '<br> Email erroné !';

        if($erreur =='')
        {
            $values['password'] = password_hash($_POST['password'],PASSWORD_DEFAULT);
            $values['dateCreated'] = date('Y-m-d h:i:s');
            $dbh = connexion();

            /**2 : Prépare ma requête SQL */
            $sth = $dbh->prepare('INSERT INTO user VALUES(NULL,:email,:password,:prenom,:nom,:dateCreated,:bio,:avatar)');

            /** 3 : executer la requête */
            $sth->execute($values);
        }

    }
}
catch(PDOException $e)
{
    $erreur.='Une erreur de connexion a eu lieu :'.$e->getMessage();
}


include('tpl/layout.phtml');

