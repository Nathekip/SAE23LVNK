<?php

include('../Modele/users.php');
include('../Vue/fonctions.php');

newUsers();
echo "<pre>";
$user = readUsers();
print_r($user);
?>
