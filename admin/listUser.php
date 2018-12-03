<?php

session_start();

// Le fichier config.php contient les identifiants de connexion à la base de 
//données sous forme de variables
include('../config/config.php');

// Le fichier bdd.lib.php contient la fonction connexion qui permet de générer 
//un nouveau PDO (PHP Data Object) et se connecter en utilisant les identifiants
//de config.php
include('../lib/bdd.lib.php');

verificationConnexion();

// On inclut dans une variable la vue du fichier qui sera affiché si tout 
//fonctionne
$vue = 'listUser.phtml';


// Tentative d'exécution 'try' qui sous-entend qu'une alternative 'catch' 
//peut être précisée (afin notamment d'éviter d'afficher le mot de passe 
//en clair en cas d'échec) / La flèche correspond à l'équivalent du "." 
//de Javascript suivie d'une fonction qui est propre à l'objet PDO
try {
     
    // Etape 1 : connexion à la base de données à partir de la fonction connexion du fichier bdd.lib.php
    $dbh = connexion();
                
    // Etape 2 : préparation de la requête sachant que le NULL correspond à la colonne aut_id qui nécessite une auto-incrémentation (obligatoire) / Tous les champs qui ont une spécificité "not null" doivent impérativement être pris en compte dans la requête (équivalent de champs obligatoires) / C'est à l'étape suivante que l'on indiquera qu'il faut utiliser le tableau $resultats : utilisation des index de ce tableau en les précédant de ":"
    $sth = $dbh->prepare('SELECT aut_id, aut_name, aut_firstname, aut_email FROM auteur');
                
    // Etape 3 : exécution de la requête (pas de paramètres supplémentaires à transmettre dans les parenthèses)
    $sth->execute();

    // Etape 4 : stockage des résultats dans la variable $results (l'attribut fetchAll permet de préciser que l'on veut le résultat sous la forme d'un tableau ASSOCIATIF, on aurait pu mettre FETCH_NUM pour avoir un tableau avec des index classés par numéros)
    $user = $sth->fetchAll(PDO::FETCH_ASSOC);

}

// Alternative en cas d'échec au 'try'
catch(PDOException $e) {
    $vue = 'erreur.phtml';
    //Si une exception est envoyée par PDO (exemple : serveur de BDD innaccessible) on arrive ici
    $messageErreur =  'Une erreur de connexion a eu lieu :'.$e->getMessage();
}


include('tpl/layout.phtml');