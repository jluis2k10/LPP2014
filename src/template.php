<?php
defined('_LPP') or die('Acceso restringido.');

class Template {
    private $vars = array();
    protected $dir_templates = 'vistas/';
    
    public function __construct($dir_templates = null) {
        if($dir_templates != null) {
            $this->dir_templates = $dir_templates;
        }
    }
    
    public function __get($clave) {
        return $this->vars[$name];
    }
    
    public function __set($clave, $valor) {
        if($clave == 'ver_template') {
            throw new Exception("No se puede asignar una variable con el nombre 'ver_template'!");
        }
        $this->vars[$clave] = $valor;
    }
    
    public function render($ver_template) {
        if(array_key_exists('ver_template', $this->vars)) {
            throw new Exception("No se puede asignar la variable con el nombre 'ver_template'!");
        }
        extract($this->vars);
        ob_start();
        if(file_exists($this->dir_templates.$ver_template)) {
            include($this->dir_templates.$ver_template);
        } else {
            throw new Exception("No se encuentra el template ".$ver_template." en el directorio ".$this->dir_templates);
        }       
        return ob_get_clean();
    }
}