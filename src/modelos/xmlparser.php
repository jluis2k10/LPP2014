<?php
defined('_LPP') or die('Acceso restringido.');

/**
 * Clase de apoyo para utilizar el objeto Pregunta, en el cual iremos
 * almacenando los valores de cada uno de los elementos que leamos del
 * archivo XML.
 */
class pregunta {
    public $id;
    public $enunciado;
    public $nivel;
    public $orientado;
    public $soluciones;
    /* Indicará si la pregunta ya ha sido añadida al array que contiene todas las preguntas leídas */
    public $esta_en_preguntas;
    
    function pregunta() {
        $this->id = "";
        $this->enunciado = "";
        $this->nivel = "";
        $this->orientado = "";
        $this->soluciones = array();
    }
}

/**
 * Lee el fichero XML secuencialmente y va almacenando el valor de los
 * diferentes elementos en un objeto Pregunta. Cuando se encuentra con
 * el elemento que marca el final de una pregunta (</ejercicio>), ésta
 * se almacena en un array ($preguntas) hasta que se llega al final del
 * archivo XML.
 *
 * Finalmente existe un método para devolver el array $preguntas que
 * se ha ido generando.
 *
 * Para leer el archivo XML secuencialmente, se apoya en la extensión
 * XML Parser de PHP.
 */
class XMLParser {
    /* Objeto intérprete de XML */
    var $xmlparser;
    /* Objeto Pregunta, contiene la información que se va leyendo del archivo XML */
    var $pregunta;
    /* Array que almacena todas las preguntas según se van leyendo del XML */
    public $preguntas;
    /* La etiqueta del último elemento que se ha leído */
    var $ultimoElemento;
    /* Lleva la cuenta de la cantidad de preguntas que se han leído */
    var $contPregunta;
    /* Lleva la cuenta de la cantidad de soluciones que tiene una pregunta determinada */
    var $contSolucion;
    /* Array con las opciones (si las hay) que determinan qué preguntas del archivo XML elegir */
    var $opciones;
    
    /**
     * Constructor de la clase.
     */
    function __construct() {
        $this->xmlparser = xml_parser_create();
        $this->pregunta = new pregunta();
        $this->preguntas = array();
        $this->ultimoElemento = "";
        $this->contSolucion = 0;
        $this->contPregunta = 0;
        $this->opciones = array();
        
        xml_set_object($this->xmlparser, $this);
        xml_set_character_data_handler($this->xmlparser, "char");
        xml_set_element_handler($this->xmlparser, "start_tag", "end_tag");
    }

    /**
     * Abre el archivo XML especificado y comienza a parsearlo.
     *
     * @param $xmlfile Ruta del archivo que debemos abrir
     * @param $opciones Array de opciones (indica reglas de filtrado para las preguntas)
     */
    function parse_File($xmlfile, $opciones) {
        $this->opciones = $opciones;
        $fp = fopen($xmlfile, 'r');
        while ($xmldata = fread($fp, 4096)) {
            if (!xml_parse($this->xmlparser, $xmldata)) {
                print "ERROR: ";
                print xml_error_string(xml_get_error_code($this->xmlparser));
                print "Línea: ";
                print xml_get_current_line_number($this->xmlparser);
                print "Columna: ";
                print xml_get_current_column_number($this->xmlparser);
            }
        }
    }
    
    /**
     * Acciones a tomar con la aperura de una etiqueta nueva (aparición de
     * un nuevo elemento).
     */
    private function start_tag($xmlparser, $tag, $attributes) {
        $this->ultimoElemento = $tag;
        
        if ($tag == "EJERCICIO") {
            $this->contPregunta++;           // Pregunta encontrada
        } else if ($tag == "SOLUCION") {
            $this->contSolucion++;           // Solución encontrada
            array_push($this->pregunta->soluciones, array());
        }
        
        // Sabemos que sólo hay un atributo posible en todo el XML, que es el
        // identificador de cada pregunta, así que cada vez que aparezca un
        // atributo directamente actualizamos el valor $id del objeto Pregunta.
        if (!empty($attributes)) {
            foreach ($attributes as $attr => $val) {
                $this->pregunta->id = $val;
            }
        }
    }
    
    /**
     * En función de cuál sea el último elemento que se haya leído,
     * actualizaremos el campo del objeto Pregunta que corresponda.
     */
    private function char($xmlparser, $data) {
        switch ($this->ultimoElemento) {
            case "ENUNCIADO":
                $this->pregunta->enunciado .= $data;
                break;
            case "NIVEL_EN":
                $this->pregunta->nivel = $data;
                break;
            case "ORIENTADOALENGUAJE":
                $this->pregunta->orientado = $data;
                break;
            case "LENGUAJE":
                $this->pregunta->soluciones[$this->contSolucion-1]["lenguaje"] = $data;
                break;
            case "NIVEL_SOL":
                $this->pregunta->soluciones[$this->contSolucion-1]["nivel"] = $data;
                break;
            case "LINEA":
                if (!isset($this->pregunta->soluciones[$this->contSolucion-1]["codigo"])) {
                    $this->pregunta->soluciones[$this->contSolucion-1]["codigo"] = htmlspecialchars($data);
                } else {
                    $this->pregunta->soluciones[$this->contSolucion-1]["codigo"] .= htmlspecialchars($data);
                }
                break;
        }        
    }
    
     /**
     * Acciones a tomar con el cierre de una etiqueta.
     * 
     * Si se cierra un elemento <linea> se añade un salto de línea a la solución del objeto Pregunta.
     *
     * Si se cierra un elemento <ejercicio> tenemos dos opciones:
     *    1.  Si existen opciones, comprobaremos si la pregunta leída del XML cumple con los requisitos
     *        que éstas indican y, de ser así, se añadirá al array de preguntas.
     *    2.  Si no existen opciones, simplemente la añadimos directamente al array de preguntas.
     * Finalmente habrá que resetear el objeto Pregunta para empezar de cero el proceso en caso de que
     * el XML contenga más preguntas.
     */
    private function end_tag($xmlparser, $tag) {
        $this->ultimoElemento = '/'.$tag;
        
        if ($this->ultimoElemento == '/LINEA') {
            $this->pregunta->soluciones[$this->contSolucion - 1]['codigo'] .= "\r\n";
        } else if ($this->ultimoElemento == '/EJERCICIO') {
            if (isset($this->opciones)) {
                $this->filtrar_pregunta();
            } else {
                array_push($this->preguntas, $this->pregunta);
            }
            //Reseteamos objeto Pregunta            
            $this->pregunta = new pregunta();
            $this->contSolucion = 0;
        }
    }

    /**
     * Este método comprueba si la pregunta cumple con las especificaciones contenidas en $opciones y,
     * de ser así, la añade al array de preguntas.
     */
    private function filtrar_pregunta() {

        if ( (in_array($this->pregunta->nivel, $this->opciones['nivel'])) &&
             ($this->opciones['orientado'] == 'indiferente' || $this->opciones['orientado'] == $this->pregunta->orientado)) {

            if ($this->opciones['ver_sol'] == 'true') {

                if ($this->pregunta->orientado == 'false') {
                    foreach ($this->pregunta->soluciones as $key => $solucion) {
                        if ($solucion['lenguaje'] != 'Pseudocodigo' ||
                            ($this->opciones['lenguaje'] != 'Cualquiera' && $this->opciones['lenguaje'] != $solucion['lenguaje'])) {
                            // Eliminar todas las soluciones que no estén en pseudocódigo
                            unset($this->pregunta->soluciones[$key]);
                        }
                    }
                } else {
                    foreach ($this->pregunta->soluciones as $key => $solucion) {
                        if ( ($solucion['lenguaje'] != $this->opciones['lenguaje'] && $this->opciones['lenguaje'] != 'Cualquiera') ||
                             ($solucion['lenguaje'] == 'Pseudocodigo')) {
                            // Eliminar todas las soluciones que no sean las del lenguaje indicado
                            unset($this->pregunta->soluciones[$key]);
                        }
                    }
                }
                if (!empty($this->pregunta->soluciones)) {
                    // Si quedan soluciones, añadir la pregunta
                    array_push($this->preguntas, $this->pregunta);
                }

            } else {
                // Si no se deben mostrar las soluciones, simplemente incluimos todas las preguntas del nivel indicado
                array_push($this->preguntas, $this->pregunta);
            }
        }
    }

    /**
     * Devuelve un array con todas las preguntas que se han leído del XML.
     *
     * @return array El array con las preguntas leídas (y filtradas si es necesario) del archivo XML
     */
    function get_preguntas() {
        return $this->preguntas;
    }
    
    /**
     * Cerrar parser.
     */
    function close_Parser() {
        xml_parser_free($this->xmlparser);
    }
}