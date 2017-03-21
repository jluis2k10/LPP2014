<?php
defined('_LPP') or die('Acceso restringido');
?>
<div class="alert alert-success">El archivo XML se ha actualizado correctamente</div>
<h2>Fichero /xml/<?=$xmlfile?></h2>
<p>Acceso directo: <a href="./cuestionariosXML/<?=$xmlfile?>" title="<?=$xmlfile?>"><?=$xmlfile?></a></p>
<pre class="prettyprint linenums:1"><?=$xmldoc?></pre>
