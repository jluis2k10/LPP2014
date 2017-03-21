<?php
defined('_LPP') or die('Acceso restringido.');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title><?=$title?></title>
    <link rel="stylesheet" href="./libs/bootstrap/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="./libs/bootstrap/css/bootstrap-theme.min.css"/>
    <link rel="stylesheet" href="./libs/prettify/prettify.css"/>
    <link rel="stylesheet" href="./libs/summernote/summernote.css"/>
    <link rel="stylesheet" href="./libs/summernote/summernote-bs3.css"/>
    <link rel="stylesheet" href="./libs/fontawesome/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="./libs/formValidation/formValidation.min.css"/>
    <link rel="stylesheet" href="./libs/codemirror/codemirror.css"/>
    <link rel="stylesheet" href="./libs/codemirror/neat.css"/>
    <link rel="stylesheet" href="./vistas/css/main.css"/>
    <script src="./libs/jquery-2.1.3.min.js"></script>
    <script src="./libs/bootstrap/js/bootstrap.min.js"></script>
    <script src="./libs/prettify/prettify.js"></script>
    <script src="./libs/summernote/summernote.min.js"></script>
    <script src="./libs/formValidation/formValidation.min.js"></script>
    <script src="./libs/formValidation/bootstrap.min.js"></script>
    <script src="./libs/codemirror/codemirror.js"></script>
    <script src="./libs/codemirror/active-line.js"></script>
    <script src="./libs/codemirror/mode/clike/clike.js"></script>
    
</head>

<body onload="prettyPrint()">
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="/">Inicio PED</a>
            </div>
            <div>
                <ul class="nav navbar-nav">
                    <li <?=($menuActivo == 'p1a') ? 'class="active"' : ''?>><a href="parte1a.php">Parte 1a</a></li>
                    <li <?=($menuActivo == 'p1b') ? 'class="active"' : ''?>><a href="parte1b.php">Parte 1b</a></li>
                    <li <?=($menuActivo == 'p2a') ? 'class="active"' : ''?>><a href="parte2a.php">Parte 2a</a></li>
                    <li <?=($menuActivo == 'p2b') ? 'class="active"' : ''?>><a href="parte2b.php">Parte 2b</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <?=$contenido?>
    </div>
</body>
</html> 