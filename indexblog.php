<DOCTYPE <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Blog</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="blodAdmin.css" />
    <script src="blogAdmin.js"></script>
</head>
<body>
    <form method="post" action="add_user.php">
        <div>
            <h2>Login</h2>
        </div>
        <div>
            <label for="identifiant">Identifiant:</label>
            <input id="identifiant" type="text" name="identifiant" placeholder="identifiant">
        </div>
        <div>
            <label for="mot de passe"> Mot de passe :  </label>
            <input id="mot de passe" type="mot de passe" name="mot de passe" placeholder="mot de passe">
        </div>
        <div>
            <input type="submit" value="Soumettre">
        </div>
    </form>
</body>
</html>




























include('config/config.php');
include('lib/bdd.lib.php');


$vue='index.phtml';
$title = 'Dashboard';

$activeMenu = 'home';

/** On essaie de se connecter et de faire notre requête
 * Principe des exception en programmation
 * Je vous expliquerai tout ça mais vous pouvez déjà lire ceci :
 * https://www.pierre-giraud.com/php-mysql/cours-complet/php-exceptions.php
 * http://php.net/manual/fr/language.exceptions.php
 * 
 */

try
{
    /** 1 : connexion au serveur de BDD - SGBDR */
    $dbh = connexion();

    /** 2 : Prépare ma requête SQL - 10 dernière commandes */
    $sth = $dbh->prepare('SELECT * FROM orders ORDER BY orderDate DESC LIMIT 1,10');
    $sth->execute();
    $orders = $sth->fetchAll(PDO::FETCH_ASSOC);

    /** 2 : Prépare ma requête SQL - Nombre de commande */
    $sth = $dbh->prepare('SELECT count(orderNumber) nbOrders FROM orders');
    $sth->execute();
    $nbOrders = $sth->fetch(PDO::FETCH_ASSOC);

    /** 2 : Prépare ma requête SQL - Nombre de clients */
    $sth = $dbh->prepare('SELECT count(customerNumber) as nbCustomers FROM customers');
    $sth->execute();
    $nbCustomers = $sth->fetch(PDO::FETCH_ASSOC);

    /** 2 : Prépare ma requête SQL - 10 dernièr clients */
    $sth = $dbh->prepare('SELECT * FROM customers ORDER BY customerNumber DESC LIMIT 1,10');
    $sth->execute();
    $clients = $sth->fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e)
{
    $vue = 'erreur.phtml';
    //Si une exception est envoyée par PDO (exemple : serveur de BDD innaccessible) on arrive ici
    $messageErreur =  'Une erreur de connexion a eu lieu :'.$e->getMessage();
}

/** On inclu la vue pour afficher les résultats */
include('tpl/layout.phtml');




