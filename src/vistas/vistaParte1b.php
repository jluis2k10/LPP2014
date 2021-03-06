<?php
defined('_LPP') or die('Acceso restringido');
?>
<?php if (isset($preguntas[0])): ?>
    <div class="col-md-9">
    <h2>Fichero /testParte1b.xml</h2>
    <p>Acceso directo: <a href="./testParte1b.xml" title="testParte1b.xml">testParte1b.xml</a></p>
    <p>&nbsp;</p>    
    <?php $i=1; ?>
        <?php foreach($preguntas as $pregunta): ?>
            <span class="numPregunta" id="<?=$pregunta->id?>"></span>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Pregunta <?=$i?>
                        <?php
                        switch ($pregunta->nivel) {
                            case "1":
                                echo ' <span class="label label-success">' . $pregunta->id . '</span>';
                                break;
                            case "2":
                                echo ' <span class="label label-info">' . $pregunta->id . '</span>';
                                break;
                            case "3":
                                echo ' <span class="label label-warning">' . $pregunta->id . '</span>';
                                break;
                            case "4":
                                echo ' <span class="label label-danger">' . $pregunta->id . '</span>';
                                break;
                        }
                        switch ($pregunta->orientado) {
                            case "true":
                                echo '<span class="label orientado">Orientado a lenguaje</span>';
                                break;
                            case "false":
                                echo '<span class="label orientado">No orientado a lenguaje</span>';
                                break;
                        }
                        ?>
                    </h4>
                </div>
                <div class="panel-body">
                    <?=$pregunta->enunciado?>
                </div>
                <?php if (isset($pregunta->soluciones[0])): ?>
                    <?php $j=1; ?>
                    <div class="panel-footer">
                        <?php foreach($pregunta->soluciones as $solucion): ?>
                            <h5>Solución <?=$j?>
                                <?php
                                switch ($solucion['nivel']) {
                                case "1":
                                    echo ' <span class="label label-success">'.$solucion['lenguaje'].'</span>';
                                    break;
                                case "2":
                                    echo ' <span class="label label-info">'.$solucion['lenguaje'].'</span>';
                                    break;
                                case "3":
                                    echo ' <span class="label label-warning">'.$solucion['lenguaje'].'</span>';
                                    break;
                                case "4":
                                    echo ' <span class="label label-danger">'.$solucion['lenguaje'].'</span>';
                                    break;
                            }
                                ?>
                            </h5>
                            <pre class="prettyprint linenums:1"><?=$solucion['codigo']?></pre>
                            <?php $j++; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php $i++; ?>            
        <?php endforeach; ?>
    </div>
    <div class="col-md-3" id="colDcha">
        <ul class="nav nav-stacked" id="sidebar">
            <?php $i=1; ?>
            <?php foreach($preguntas as $pregunta): ?>
                <li><a href="#<?=$pregunta->id?>">Pregunta <?=$i?></a></li>
                <?php $i++; ?>
            <?php endforeach; ?>
        </ul>
    </div>
    <script type="text/javascript">
        $('#sidebar').affix({
            offset: {
                top: 0
            }
        });

        var $body   = $(document.body);
        var navHeight = $('.navbar').outerHeight(true) + 200;

        $body.scrollspy({
	       target: '#colDcha',
	       offset: navHeight
        });
    </script>
<?php endif; ?>