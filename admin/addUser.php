<?php
//idée est de récupérer les données du formulaire et de le poster dans la BDD

session_start();

//le fichier config.php contient les identifiants 
//de connexion à la base de données sous forme de variables
include('../config/config.php');

//Le fichier bdd.lib.php contient la fonction connexion qui 
//permet de générer un nouveau PDO (PHP Data Object) et se 
//connecter en utilisant les identifiants de config.php
include('../lib/bdd.lib.php');

verificationConnexion();

// On inclut dans une variable la vue du fichier qui sera affiché si tout fonctionne
$vue ='addUser.phtml';
$title = 'Inscription de nouveaux auteurs';
//Initialisation des erreurs à false
$erreur = '';
/** On essaie de se connecter et de faire notre requête
 * Principe des exception en programmation
 * Je vous expliquerai tout ça mais vous pouvez déjà lire ceci :
 * https://www.pierre-giraud.com/php-mysql/cours-complet/php-exceptions.php
 * http://php.net/manual/fr/language.exceptions.php
 */
//Tableau correspondant aux valeurs à récupérer dans le formulaire.
$tableAuteurs =
[
    'nom' => '',
    'prenom' => '',
    'email' => '',
    'motDePasse' => '',
    'bio' => '',
    'avatar' => '',
    'identifiant' => '',
    'role' => '',
];

$tab_erreur =
[
'nom'=>'Le nom doit être rempli !',
'prenom'=>'Le prénom doit être rempli !',
'email'=>'L\'email doit être rempli !',
'password'=>'Le mot de passe ne peut être vide'
];
// Tentative d'exécution 'try' qui sous-entend qu'une alternative 'catch' 
//peut être précisée (afin notamment d'éviter d'afficher le mot de passe en clair en cas d'échec) / La flèche correspond à l'équivalent du "." de Javascript suivie d'une fonction qui est propre à l'objet PDO
try
{
    // Vérification de la présence d'un nom rempli dans le formulaire
    if(array_key_exists('nom',$_POST))
   {
        // if($_POST['motDePasse'] === $_POST['confirmerMotDePasse'])
        // {
        //    //var_dump($_POST); 
        //     if (empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['email']) || empty($_POST['identifiant']));
	    //     {
        //         echo "ERREUR : tous les champs n'ont pas ete renseignés.";
        //     else
        foreach($tableAuteurs as $champ => $value)
        {
            if(isset($_POST[$champ]) && trim($_POST[$champ])!='')
                $tableAuteurs[$champ] = $_POST[$champ];
                elseif(isset($tab_erreur[$champ]))   
                    $erreur.= '<br>'.$tab_erreur[$champ];
                else
                $tableAuteurs[$champ] = '';
        }

        if($_POST['motDePasse'] != $_POST['confirmerMotDePasse'])
        $erreur .= '<br> Erreur confirmation mot de passe';
        
        if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
        $erreur .= '<br> Email erroné !';
     
        if($erreur =='')
        {
            // Hashage du mot de passe
            $motDePassehache = password_hash($_POST['motDePasse'], PASSWORD_DEFAULT);
            
            // Modification du tableau des résultats en précisant le mot de passe haché
           
            $tableAuteurs['motDePasse'] = $motDePassehache;
            
            //$tableAuteurs['dateCreated'] = date('Y-m-d h:i:s');
            
            //     $tableAuteurs=
            // [
            //     'nom' => $_POST['nom'],
            //     'prenom' => $_POST['prenom'],
            //     'email' => $_POST['email'],
            //     'motDePasse' => password_hash($_POST['motDePasse'], PASSWORD_DEFAULT),
            //     'bio' => $_POST['bio'],
            //     'avatar' => $_POST['avatar'],
            //     'identifiant' => $_POST['identifiant'],
            //     'role' => $_POST['role'],
            // ];
            //var_dump($tableAuteurs);  
            
            /** 1 : connexion au serveur de BDD - SGBDR */
            $dbh = connexion();

            /** 2 : Prépare ma requête SQL pour insérer les données du formulaire */ 
            $sth = $dbh->prepare('INSERT INTO blog_auteurs (aut_id, aut_nom, aut_prenom, aut_email, aut_password, aut_biographie, aut_avatar, aut_username, aut_role) VALUES (NULL, :nom, :prenom, :email, :motDePasse, :bio, :avatar, :identifiant, :role)');
        
            /** 3 : executer la requête */
            $sth->execute($tableAuteurs);
            
            /** 4 : recupérer les résultats*/ 
            /*On utilise FETCH car un seul résultat attendu*/
            //$tableAuteurs = $sth->fetchAll(PDO::FETCH_ASSOC);
        }   
    }
}

catch(PDOException $e)
{
    /*Si une exception est envoyée par PDO (exemple : serveur de BDD innaccessible) on arrive ici*/
    $erreur = 'Une erreur de connexion a eu lieu :'.$e->getMessage();
}

/* On inclut la vue pour afficher les résultats */
include('tpl/layout.phtml');
?>




