
<?php
session_start();//pour s'assurer qu'on utilise la même session


include('../config/config.php');
include('../lib/bdd.lib.php');


$_SESSION['connect'] = false;
//détruit une variable de session
unset($_SESSION['connect']);

header('Location:login.php');


exit();
?>