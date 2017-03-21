<?php
header('Content-Type: text/html; charset=utf-8');
define('_LPP', 1);
include_once('template.php');
include_once('./modelos/domdoc.php');

$vista = new Template();
$vista->title = "Parte 2a | Lenguajes de Programación y Procesadores";
$vista->menuActivo = 'p2a';

if (isset($_POST['enviar'])) {
    $_POST['archivo'] = basename($_POST['archivo']); // Un poco de seguridad nunca viene mal
    $domDoc = new domDoc($_POST);
    if ($domDoc->saveXML($_POST['archivo'])) {
        $xml = file_get_contents('./cuestionariosXML/'.htmlentities($_POST['archivo']), FILE_USE_INCLUDE_PATH);
        $vista->xmlfile = htmlentities($_POST['archivo']);
        $vista->xmldoc = htmlentities($xml);
        $vista->contenido = $vista->render('vistaParte2a.php');
    } else {
        $vista->errorHeader = 'Se ha producido un error validando el archivo XML';
        $vista->errores = $domDoc->get_errores();
        $vista->contenido = $vista->render('vistaError.php');
    }
} else {
    $vista->lenguajes = array(
        'C', 'Java', 'C++', 'Objective-C', 'C#', 'JavaScript', 'PHP', 'Python',
        'Visual Basic .NET', 'Visual Basic', 'Delphi', 'Perl', 'PL/SQL', 'F#',
        'Transact-SQL', 'ABAP', 'MATLAB', 'R', 'Pascal', 'Ruby', 'Pseudocodigo');

    $archivos = array();
    foreach(glob('./cuestionariosXML/*.xml') as $archivo) {
        array_push($archivos, basename($archivo));
    }
    $vista->archivos = $archivos;
    $vista->contenido = $vista->render('vistaParte2aForm.php');
}
echo $vista->render('vistaCuerpo.php');