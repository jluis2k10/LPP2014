<?php
header('Content-Type: text/html; charset=utf-8');
define('_LPP', 1);
include_once('template.php');
include_once('modelos/domdoc.php');
include_once('modelos/xmlparser.php');

$vista = new Template();
$domDoc = new domDoc();

$vista->title = 'Parte 1b | Lenguajes de Programación y Procesadores';
$vista->menuActivo = 'p1b';

if($domDoc->XSDValido('testParte1b.xml', 'preguntas.xsd')) {
    $xmlparser = new XMLParser();
    $xmlparser->parse_File("./testParte1b.xml", null);
    $vista->preguntas = $xmlparser->get_preguntas();
    $vista->contenido = $vista->render('vistaParte1b.php');
    $xmlparser->close_Parser();
} else {
    $vista->errorHeader = 'XML no válido según las especificaciones del XSD';
    $vista->errores = $domDoc->get_errores();
    $vista->contenido = $vista->render('vistaError.php');
}

echo $vista->render('vistaCuerpo.php');