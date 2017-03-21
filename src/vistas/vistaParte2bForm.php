<?php
defined('_LPP') or die('Acceso restringido');
?>
<h3>Generar Cuestionario</h3>
<form id="cuestionario" class="form-horizontal" action="?page=parte2b" method="post">
    <div class="form-group">
        <label for="lenguaje" class="col-sm-3 control-label">Lenguaje</label>
        <div class="col-sm-2">
            <select id="lenguaje" name="lenguaje" class="form-control">
                <option value="Cualquiera">Cualquiera</option>
                <?php foreach($lenguajes as $lenguaje): ?>
                    <option value="<?=$lenguaje?>"><?=$lenguaje?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="nivel" class="col-sm-3 control-label">Nivel</label>
        <div class="col-sm-6">
            <label class="checkbox-inline">
                <input type="checkbox" id="nivel1" name="nivel[]" value="1"/> Principiante
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" id="nivel2" name="nivel[]" value="2"/> Intermedio
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" id="nivel3" name="nivel[]" value="3"/> Avanzado
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" id="nivel4" name="nivel[]" value="4"/> Superior
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="orientado" class="col-sm-3 control-label">¿Orientado a Lenguaje?</label>
        <div class="col-sm-3">
            <label class="radio-inline">
                <input type="radio" name="orientado" id="orientado-0" value="indiferente" checked="checked"/>
                Indiferente
            </label>
            <label class="radio-inline">
                <input type="radio" name="orientado" id="orientado-1" value="true"/>
                Sí
            </label>
            <label class="radio-inline">
                <input type="radio" name="orientado" id="orientado-2" value="false"/>
                No
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="ver_soluciones" class="col-sm-3 control-label">¿Mostrar soluciones?</label>
        <div class="col-sm-2">
            <label class="radio-inline">
                <input type="radio" name="ver_soluciones" id="soluciones-0" value="true" checked="checked"/>
                Sí
            </label>
            <label class="radio-inline">
                <input type="radio" name="ver_soluciones" id="soluciones-1" value="false"/>
                No
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="ver_lenguaje" class="col-sm-3 control-label">¿Mostrar lenguaje de programación?</label>
        <div class="col-sm-2">
            <label class="radio-inline">
                <input type="radio" name="ver_lenguaje" id="lenguaje-0" value="true" checked="checked"/>
                Sí
            </label>
            <label class="radio-inline">
                <input type="radio" name="ver_lenguaje" id="lenguaje-1" value="false"/>
                No
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="ver_nivel" class="col-sm-3 control-label">¿Mostrar nivel de dificultad?</label>
        <div class="col-sm-2">
            <label class="radio-inline">
                <input type="radio" name="ver_nivel" id="nivel-0" value="true" checked="checked"/>
                Sí
            </label>
            <label class="radio-inline">
                <input type="radio" name="ver_nivel" id="nivel-1" value="false"/>
                No
            </label>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-3"></div>
        <div class="col-sm-2">
            <button type="submit" name="enviar" class="enviar btn btn-primary btn-lg">Generar</button>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function() {
        $('#cuestionario')
            // Validación del formulario
            .formValidation({
                framework: 'bootstrap',
                excluded: [':disabled'],
                icon: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    'nivel[]': {
                        validators: {
                            notEmpty: {
                                message: 'Debe seleccionar al menos un nivel de dificultad.'
                            }
                        }
                    }
                }
            })
    });
</script>