<?php
header('Content-Type: text/html; charset=utf-8');
define('_LPP', 1);
include_once('template.php');
include_once('modelos/xmlparser.php');
include_once('modelos/domdoc.php');

$vista = new Template();
$vista->title = 'Parte 2b | Lenguajes de Programación y Procesadores';
$vista->menuActivo = 'p2b';

if (isset($_POST['enviar'])) {
    $preguntas = array();
    $domDoc = new domDoc();

    $opciones = array(
        'lenguaje'  => $_POST['lenguaje'],
        'nivel'     => $_POST['nivel'],
        'orientado' => $_POST['orientado'],
        'ver_sol'   => $_POST['ver_soluciones'],
        'ver_leng'  => $_POST['ver_lenguaje'],
        'ver_nivel' => $_POST['ver_nivel']
    );

    foreach(glob('./cuestionariosXML/*.xml') as $archivo) {
        $xmlparser = new XMLParser();
        if ($domDoc->XSDValido($archivo, 'preguntas.xsd')) {
            $xmlparser->parse_File($archivo, $opciones);
            $preguntas = array_merge($preguntas, $xmlparser->get_preguntas());
        }
        $xmlparser->close_Parser();
    }
    if (!empty($preguntas)) {
        shuffle($preguntas);
        $vista->preguntas = $preguntas;
        $vista->opciones = $opciones;
        $vista->contenido = $vista->render('vistaParte2b.php');
    } else {
        $vista->errorHeader = 'Cuestiones seleccionadas';
        $vista->errores = array('No se han encontrado problemas que cumplan con el criterio de selección indicado.');
        $vista->contenido = $vista->render('vistaError.php');
    }
} else {
    $vista->lenguajes = array(
        'C', 'Java', 'C++', 'Objective-C', 'C#', 'JavaScript', 'PHP', 'Python',
        'Visual Basic .NET', 'Visual Basic', 'Delphi', 'Perl', 'PL/SQL', 'F#',
        'Transact-SQL', 'ABAP', 'MATLAB', 'R', 'Pascal', 'Ruby', 'Pseudocodigo');
    $vista->contenido = $vista->render('vistaParte2bForm.php');
}
echo $vista->render('vistaCuerpo.php');