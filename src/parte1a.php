<?php
header('Content-Type: text/html; charset=utf-8');
define('_LPP', 1);
include_once('template.php');

$vista = new Template();
$vista->title = "Parte 1a | Lenguajes de Programación y Procesadores";
$vista->menuActivo = 'p1a';
$vista->xsd = file_get_contents('./preguntas.xsd', FILE_USE_INCLUDE_PATH);

$vista->contenido = $vista->render('vistaParte1a.php');
echo $vista->render('vistaCuerpo.php');