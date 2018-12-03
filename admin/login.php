

<?php
session_start();//toujours en haut du fichier PHP car le buffer Apache doit-être vide, en
//effet cette instruction modifie les Header de la réponse HTTP en y plaçant un cookie de sessions

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

var_dump($_SESSION);

//Ici il faut afficher le formulaire de login et tester les données quand il est posté !
try
{
    //Procédure de vérification pour voir d'il s'agit d'un formulaire déjà posté ou non
    if(array_key_exists('email',$_POST))
    {
        //Mise en place des valleurs récupérées dans un tableau
        $login =
        [
        'email' => $_POST['email'],
        'motDePasse' => $_POST['motDePasse']
        ];
        /** 1 : connexion au serveur de BDD - SGBDR à partir de la fonction connexion*/
        $dbh = connexion();

        /** 2 : Prépare ma requête SQL pour insérer les données du formulaire (attention de ne pas 
         * mettre de paramètres dynamiques de type variable), les comonnes de la table vont devenir
         * les index d'un tableau associatif*/ 
        $sth = $dbh->prepare('SELECT * FROM blog_auteurs WHERE aut_email = :email');

        /** 3 : executer la requête 
        */
        $sth->execute(['email' => $_POST['email']]);

        /** 4 : recupérer les résultats dans la variable $user (l'attribut fetch précise que l'on veut le
         * résultats sous la forme d'un tableau associatif*/
        $user = $sth->fetch(PDO::FETCH_ASSOC);
        var_dump($user);

        //si la variable $user est false, cela veut dire que l'e-mail ne correspond pas
            if ($user == false) {
               $erreur= 'E-mail erroné';
             }
            // s'il y a correspondance entre les mails, on vérifie alors le mot de passe (inutile de hasher
            //au préalable le mot de passe puisque la fonction password_verify le fait elle même)
                else {
                    
                    if (password_verify($login["motDePasse"],$user["aut_password"])) { 
                        
                        //si utilisateur est présent dans la base et user et mot de passe sont identique :
                        $_SESSION['connect'] = true; //on met dans la session que l’utilisateur est bien connecté !
                        $_SESSION['aut_id'] = $user['aut_id'];
                        //redirection
                        header('Location:listUser.php');
                        exit();//après une redirection il faut sortir
                    }
                    //SINON ERREUR
                    else {
                        $erreur ="Mot de Passe erroné";
                    }
                }
            }
        }
catch(PDOException $e)
 {
    /*Si une exception est envoyée par PDO (exemple : serveur de BDD innaccessible) on arrive ici*/
    $erreur = 'Une erreur de connexion a eu lieu :'.$e->getMessage();
 }

// /* On inclut la vue pour afficher les résultats */
include('tpl/layout.phtml');