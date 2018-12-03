<?php

function connexion()
{
    // Connexion avec création d'une variable dbh (database handler (gestionnaire de base de données)) 
    //qui utilise les constantes définies dans le fichier config.php / 
    //La deuxième ligne attribue des caractéristiques pour gérer les éventuelles erreurs : s'il n'y a pas d'erreur, cette ligne n'est pas nécessaire
    $dbh = new PDO(DB_DSN,DB_USER,DB_PASS);
    //On dit à PDO de nous envoyer une exception s'il n'arrive pas à se connecter ou s'il rencontre une erreur
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $dbh;
}

function verificationConnexion()
{
    if(!isset($_SESSION['connect']) || $_SESSION['connect'] != true)
    {
        header('location:login.php');
        exit();
    }
}


?>