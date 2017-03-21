<?php
defined('_LPP') or die('Acceso restringido');
?>
<form id="pregunta" class="form-horizontal" action="?page=parte2a" method="post">
    <h3>Problema</h3>
    <div class="form-group">
        <label for="archivo" class="col-sm-1 control-label">Archivo</label>
        <div class="col-sm-5">
            <div class="input-group">
                <div class="input-group-btn">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Guardar en... <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <?php foreach($archivos as $archivo): ?>
                            <li><a href="#"><?=$archivo?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div><!-- /btn-group -->
                <input type="text" id="archivo" name="archivo" class="form-control" placeholder="Nombre del archivo XML"/>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="id" class="col-sm-1 control-label">ID</label>
        <div class="col-sm-2">
            <input type="text" class="form-control" id="id" name="id" placeholder="ID"/>
        </div>
        <label for="nivel" class="col-sm-1 control-label">Nivel</label>
        <div class="col-sm-2">
            <select id="nivel" name="nivel" class="form-control">
                <option value="1">Principiante</option>
                <option value="2">Intermedio</option>
                <option value="3">Avanzado</option>
                <option value="4">Superior</option>
            </select>
        </div>
        <label for="orientado" class="col-sm-2 control-label">Orientado a Lenguaje</label>
        <div class="col-sm-2">
            <label class="radio-inline">
                <input type="radio" name="orientado" id="orientado-0" value="true" checked="checked"/>
                Sí
            </label>
            <label class="radio-inline">
                <input type="radio" name="orientado" id="orientado-1" value="false"/>
                No
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="enunciado" class="col-sm-1 control-label">Enunciado</label>
        <div class="col-sm-10">
            <textarea name="enunciado" id="enunciado" class="form-control"></textarea>
        </div>
    </div>
    <h3>Soluciones <button type="button" class="btn btn-default addButton"><i class="fa fa-plus"></i></button></h3>
    <!-- Elementos opcionales para añadir una solución al ejercicio -->
    <div class="solucion hide" id="opcional">
        <div class="form-group">
            <label for="lenguaje" class="col-sm-1 control-label">Lenguaje</label>
            <div class="col-sm-2">
                <select id="lenguaje" name="lenguaje[]" class="form-control" disabled="true">
                    <?php foreach($lenguajes as $lenguaje): ?>
                        <option value="<?=$lenguaje?>"><?=$lenguaje?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <label for="nivel_sol" class="col-sm-1 control-label">Nivel</label>
            <div class="col-sm-2">
                <select id="nivel_sol" name="nivel_sol[]" class="form-control" disabled="true">
                    <option value="1">Principiante</option>
                    <option value="2">Intermedio</option>
                    <option value="3">Avanzado</option>
                    <option value="4">Superior</option>
                </select>
            </div>
            <div class="col-sm-1">
                <button type="button" class="btn btn-default removeButton"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="form-group">
            <label for="codigo" class="col-sm-1 control-label">Código</label>
            <div class="col-sm-10">
                <textarea name="codigo[]" id="codigo" cols="100" rows="5" class="form-control codigo" disabled="true"></textarea>
            </div>
        </div>
        <hr />
    </div>
    <!-- /Elementos opcionales para añadir una solución al ejercicio -->
    <button type="submit" name="enviar" class="enviar btn btn-primary btn-lg">Añadir Pregunta</button>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        // Pasar automáticamente el elemento seleccionado del dropdown al campo del input "Archivo"
        $(".dropdown-menu li a").click(function(){
            var archivo = $(this).text();
            $('[name="archivo"]').val(archivo);
            validateArchivo();
        });

        // Función auxiliar invocada cada vez que se actualiza el contenido del editor del enunciado
        function validateEditor() {
            $('#pregunta').formValidation('revalidateField', 'enunciado');
        }
        // Función auxiliar invocada cada vez que se actualiza el contenido del editor de código
        function validateCodigo() {
            $('#pregunta').formValidation('revalidateField', 'codigo[]');
        }
        // Función invocada cada vez que se actualiza el contenido del campo "Archivo"
        function validateArchivo() {
            $('#pregunta').formValidation('revalidateField', 'archivo');
        }

        // Validación del formulario y clonación de los campos para la solución
        $('#pregunta')
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
                    archivo: {
                        validators: {
                            notEmpty: {
                                message: 'El nombre del archivo es obligatorio. Debe tener la extensión .xml'
                            },
                            regexp: {
                                regexp: /([^\s]+(\.(xml))$)/i,
                                message: 'El nombre del archivo es obligatorio. Debe tener la extensión .xml'
                            }
                        }
                    },
                    id: {
                        validators: {
                            notEmpty: {
                                message: 'Obligatorio. Debe comenzar por una letra.'
                            },
                            regexp: {
                                regexp: /^[a-zA-Z].*$/i,
                                message: 'Obligatorio. Debe comenzar por una letra.'
                            }
                        }
                    },
                    enunciado: {
                        validators: {
                            callback: {
                                message: 'El enunciado es obligatorio.',
                                callback: function(value, validator, $field) {
                                    var code = $('[name="enunciado"]').code();
                                    // <p><br></p> Se genera a veces por SummerCode cuando está vacío
                                    return (code !== '' && code !== '<p><br></p>');
                                }
                            }
                        }
                    },
                    'codigo[]': {
                        validators: {
                            notEmpty: {
                                message: 'El código de la solución es obligatorio.'
                            }
                        }
                    }
                }
            })

            // Manejador para cuando se pulsa el botón "+"
            .on('click', '.addButton', function() {
                // Primero se clona el div #opcional (es donde se encunetra, oculto en un
                // primer momento, la parte del formulario correspondiente a la Solución.
                var $template = $('#opcional');
                var $clone    = $template
                        .clone()
                        .removeClass('hide') // Hacerlo visible
                        .removeAttr('id')
                        .insertBefore($template),
                    $opcionales = $clone.find('[name="lenguaje[]"],[name="nivel_sol[]"],[name="codigo[]"]');

                // Activar los campos recién clonados (eliminar atributo "disabled") y añadir el <textarea>
                // del editor de código a la lista de elementos que se deben validar.
                $opcionales.removeAttr('disabled');
                $codigo = $clone.find('[name="codigo[]"]');
                $('#pregunta').formValidation('addField', $codigo); // Para que pueda validarse

                // Inicializar Codemirror para la <textarea> del editor de código.
                var cEditor = CodeMirror.fromTextArea($codigo[0], {
                    lineNumbers: true,
                    matchBrackets: true,
                    mode: "text/x-csrc",
                    styleActiveLine: true,
                    theme: 'neat'
                });
                // Cuando se escribe algo en el editor de código se lanza este evento
                cEditor.on("change", function() {
                    cEditor.save(); // Copiar el contenido al textarea para poder validar
                    validateCodigo();
                });
            })

            // Manejador para cuando se pulsa el botón "-"
            .on('click', '.removeButton', function() {
                // Encontrar el div ".solucion" donde está el botón "-" y eliminarlo
                var $row    = $(this).parents('.solucion');
                $codigo = $row.find('[name="codigo[]"]');
                $row.remove();
                $('#pregunta').formValidation('removeField', $codigo); // No queremos que intente validar un campo que ya no existe!
            })

            // Transformar la textarea del enunciado al editor Summernote
            .find('[name="enunciado"]')
            .summernote({
                height: 150,
                onkeyup: function() {
                    validateEditor(); // Lanzar evento de validación con cada actualización del editor
                },
                onpaste: function() {
                    validateEditor(); // Idem
                }
            });
    });
</script>