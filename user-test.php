<?php
require 'user-pdo.php';
$adam = new Userpdo();
//$adam->register('adm','655957','adam69006@hotmail.fr','adam','fraga');
$infoAdam = $adam->connect('adm','655957');
$adam->refresh();
