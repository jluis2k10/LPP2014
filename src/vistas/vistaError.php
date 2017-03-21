<?php
defined('_LPP') or die('Acceso restringido');
?>
<h2><?=$errorHeader?></h2>
<h4>Encontrados los siguientes problemas</h4>
<?php foreach($errores as $error): ?>
    <div class="alert alert-danger">
        <?=$error?>
    </div>
<?php endforeach; ?>
<a href="javascript:history.go(-1)">[Volver Atrás]</a>