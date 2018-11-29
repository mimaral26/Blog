

<?php
session_start();

include('../config/config.php');
include('../lib/bdd.lib.php');

$vue ='login.phtml';
$title = 'Connexion';
//Initialisation des erreurs à false
$erreur = '';

$login =
[
    'email' => '',
    'motDePasse' => ''
];

//Ici il faut afficher le formulaire de login et tester les données quand il est posté !
try
{
    if(array_key_exists('email',$_POST))
    {
        $login =
        [
        'email' => $_POST['email'],
        'motDePasse' => $_POST['motDePasse']
        ];
        /** 1 : connexion au serveur de BDD - SGBDR */
        $dbh = connexion();

        /** 2 : Prépare ma requête SQL pour insérer les données du formulaire */ 
        $sth = $dbh->prepare('SELECT * FROM blog_auteurs WHERE aut_email = :email');

        /** 3 : executer la requête */
        $sth->execute(array(['email' => $_POST['email']]));

        /** 4 : recupérer les résultats */
        $results = $sth->fetch(PDO::FETCH_ASSOC);

        var_dump($results);
    
         //if ('email' = $_POST['email'])
        // $erreur .= '<br> Erreur confirmation mot de passe';
        //if($erreur =='')
       // {
    }

}
