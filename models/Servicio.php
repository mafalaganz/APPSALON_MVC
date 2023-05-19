<?php

namespace Model;

class Servicio extends ActiveRecord {
    // Base de Datos
    protected static $tabla = 'servicios';  // Agregamos la variable $tabla con el cotenido de la tabla servicios de la BD
    protected static $columnasDB = ['id', 'nombre', 'precio'];

    public $id;
    public $nombre;
    public $precio;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;            // Almacenamos en el atributo $id el valor tomado de $args['id'] y si está vacío almacenamos un null
        $this->nombre = $args['nombre'] ?? '';      // Almacenamos en el atributo $nombre el valor tomado de $args['nombre'] y si está vacío almacenamos un string vacio
        $this->precio = $args['precio'] ?? '';      // Almacenamos en el atributo $precio el valor tomado de $args['precio'] y si está vacío almacenamos un string vacio

    }

    public function validar()
    {
        if(!$this->nombre) {
            self::$alertas['error'] [] = 'El nombre del Servicio es Obligatorio';   // Si no hay datos en el campo nombre en el arreglo error del arreglo alertas escribiremos al final
        }                                                                           // de dicho arreglo error el string indicado

        if(!$this->precio) {
            self::$alertas['error'] [] = 'El precio del Servicio es Obligatorio';   // Si no hay datos en el campo precio en el arreglo error del arreglo alertas escribiremos al final
        }                                                                           // de dicho arreglo error el string indicado

        if(!is_numeric($this->precio)) {
            self::$alertas['error'] [] = 'El precio no es valido';                  // Si el dato en el campo precio no es numerico en el arreglo error del arreglo alertas escribiremos al final
        }                                                                           // de dicho arreglo error el string indicado

        return self::$alertas;
    }
}