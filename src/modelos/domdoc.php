<?php
defined('_LPP') or die('Acceso restringido.');

/**
 * Esta clase se utiliza para validar un documento XML según las
 * especificaciones de algún XML-Schema y para generar o actualizar
 * documentos XML.
 *
 * Se apoya en los métodos de la extensión DOM de PHP.
 */
class domDoc {
    /* Objeto DOMDocument */
    var $dom;
    /* Variables correspondientes a cada uno de los elementos del XML */
    var $ejercicios, $ejercicio, $enunciado, $nivel_en, $orientado, $solucion, $lenguaje, $nivel_sol, $codigo, $linea;
    /* Para almacenar los posibles errores que se produzcan y devolverlos cuando se pida */
    var $errores;

    /**
     * Constructor de la clase.
     *
     * Si el objeto se inicializa con parámetros, quiere decir que estamos intentando
     * crear/actualizar una nueva pregunta en un archivo XML.
     */
    function __construct() {
        $this->errores = array();
        $params = func_get_args();
        libxml_use_internal_errors(true); // Queremos manejar nosotros los posibles errores
        $this->dom = new DOMDocument('1.0', 'UTF-8');
        $this->dom->formatOutput = true;
        $this->dom->preserveWhiteSpace = false;
        if (!empty($params)) {            
            $this->initElementos($params[0]);
        }
    }

    /**
     * Crear los objetos DOM necesarios en función del array de entrada, el cual a su vez
     * proviene de la información introducida por el usuario en el formulario.
     *
     * @param $posted Array que proviene del formulario rellenado con la información necesaria para
     *                añadir una pregunta al documento XML.
     */
    private function initElementos($posted) {
        // Comprobar si estamos creando un XML nuevo o actualizando uno ya existente
        // y actuar en consecuencia.
        if (file_exists('./cuestionariosXML/'.$posted['archivo'])) {
            $this->dom->load('./cuestionariosXML/'.$posted['archivo']);
            if($this->dom->hasChildNodes()) {
                $this->ejercicios = $this->dom->childNodes->item(0);
            }
        } else {
            $this->ejercicios = $this->dom->createElement('ejercicios');
        }

        // Elementos correspondientes al enunciado del ejercicio
        $this->ejercicio = $this->dom->createElement('ejercicio');
        $this->ejercicio->setAttribute('id', $posted['id']);
        $this->enunciado = $this->dom->createElement('enunciado', htmlentities($posted['enunciado'], ENT_XML1, 'UTF-8'));
        $this->nivel_en = $this->dom->createElement('nivel_en', $posted['nivel']);
        $this->orientado = $this->dom->createElement('orientadoALenguaje', $posted['orientado']);
        // Organizamos los elementos
        $this->ejercicios->appendChild($this->ejercicio);
        $this->ejercicio->appendChild($this->enunciado);
        $this->ejercicio->appendChild($this->nivel_en);
        $this->ejercicio->appendChild($this->orientado);

        // ¿Disponemos de al menos una solución para el ejercicio?
        if (isset($posted['lenguaje'])) {
            // Añadir todas las soluciones encontradas al árbol DOM que estamos generando
            for ($i=0; $i<count($posted['lenguaje']); $i++) {
                $this->solucion = $this->dom->createElement('solucion');
                $this->lenguaje = $this->dom->createElement('lenguaje', $posted['lenguaje'][$i]);
                $this->nivel_sol = $this->dom->createElement('nivel_sol', $posted['nivel_sol'][$i]);
                $this->codigo = $this->dom->createElement('codigo');
                
                $mis_lineas = preg_split("/\r\n|\n|\r/", $posted['codigo'][$i]); // Separar el código posteado en líneas
                foreach ($mis_lineas as $mi_linea) {
                    $this->linea = $this->dom->createElement('linea', htmlentities($mi_linea, ENT_XML1, 'UTF-8'));
                    $this->codigo->appendChild($this->linea);
                }
                
                $this->solucion->appendChild($this->lenguaje);
                $this->solucion->appendChild($this->nivel_sol);
                $this->solucion->appendChild($this->codigo);
                $this->ejercicio->appendChild($this->solucion);
            }
        }
    }

    /**
     * Guardar el objeto DOM en el archivo indicado. Antes de intentar guardarlo se
     * comprueba si valida el XML-Schema necesario.
     *
     * @param $archivo El archivo XML donde se debe guardar el objeto DOM
     * @return bool devuelve true si se guarda correctamente, false en caso contrario
     */
    public function saveXML($archivo) {
        $this->dom->appendChild($this->ejercicios);
        $this->ejercicios->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $this->ejercicios->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'noNamespaceSchemaLocation', '../preguntas.xsd');
        if ($this->dom->schemaValidate('./preguntas.xsd')) {
            $this->dom->save('./cuestionariosXML/'.$archivo);
            return true;
        }
        $this->store_xml_errors();
        return false;
    }

    /**
     * Guarda los posibles errores que se hayan generado al operar con el objeto DOM
     * en un array que se devolverá posteriormente para ser mostrado de manera
     * conveniente en alguna de las Vistas de la aplicación.
     */
    private function store_xml_errors() {
        $errors = libxml_get_errors();
        foreach ($errors as $error) {
            array_push($this->errores, $this->parse_xml_error($error));
        }
        libxml_clear_errors();
    }

    /**
     * Devuelve el error formateado convenientemente y listo para mostrarse en alguna
     * de las Vistas de la aplicación.
     *
     * @param $error El error que se ha producido y deseamos formatear
     * @return string El error formateado convenientemente
     */
    private function parse_xml_error($error) {
        $resp = '';
        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $resp .= '<h4><strong>Advertencia '.$error->code.':</strong></h4> ';
                break;
            case LIBXML_ERR_ERROR:
                $resp .= '<h4><strong>Error '.$error->code.':</strong></h4> ';
                break;
            case LIBXML_ERR_FATAL:
                $resp .= '<h4><strong>Error Fatal '.$error->code.':</strong></h4> ';
                break;
        }
        $resp .= '<p style="margin-left:20px">'.trim($error->message).'</p>';
        if ($error->file) {
            $resp .= '<p style="margin-left:20px">En el archivo <strong>'.$error->file.'</strong>';
        }
        $resp .= ' en la línea <strong>'.$error->line.'</strong></p>';
        return $resp;
    }

    /**
     * Devuelve un array con todos los errores que se hayan podido producir
     * al operar con el objeto DOM.
     *
     * @return array Todos los errores que se han producido.
     */
    public function get_errores() {
        return $this->errores;
    }

    /**
     * Comprueba si un XML valida cierto XML-Schema
     *
     * @param $xml Docmento XML que se quiere validar
     * @param $xsd XML-Schema sobre el que validar el documento XML
     * @return bool Devuelve true si se valida correctamente, false en caso contrario
     */
    public function XSDValido($xml, $xsd) {
        $this->dom->load($xml);
        if ($this->dom->schemaValidate($xsd)) {
            return true;
        }
        $this->store_xml_errors();
        return false;
    }
}