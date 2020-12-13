<?php
require 'lpdo.php';

$connexion = new lpdo('localhost','root','','classes');
$connexion->connect('localhost','root','','classes');
//$connexion->close();
$connexion->execute("SELECT * FROM utilisateurs WHERE login='adm'");
$test = $connexion->getFields('utilisateurs');
var_dump($test);