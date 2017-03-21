<?php
define('_LPP', 1);
include_once('template.php');

$vista = new Template();
$vista->title = "Lenguajes de Programación y Procesadores";
$vista->menuActivo = '';

$vista->contenido = $vista->render('vistaPortada.php');
echo $vista->render('vistaCuerpo.php');