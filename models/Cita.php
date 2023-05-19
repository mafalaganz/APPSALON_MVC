<?php

namespace Model;

class Cita extends ActiveRecord {
    // Base de datos
    protected static $tabla = 'citas';
    protected static $columnasDB = ['id', 'fecha', 'hora', 'usuarioId'];

    public $id;
    public $fecha;
    public $hora;
    public $usuarioId;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;                // Almacenamos en el atributo $id el valor tomado de $args['id'] y si está vacío almacenamos un null
        $this->fecha = $args['fecha'] ?? '';            // Almacenamos en el atributo $fecha el valor tomado de $args['fecha'] y si está vacío almacenamos un string vacio
        $this->hora = $args['hora'] ?? '';              // Almacenamos en el atributo $hora el valor tomado de $args['hora'] y si está vacío almacenamos un string vacio
        $this->usuarioId = $args['usuarioId'] ?? '';    // Almacenamos en el atributo $usuarioId el valor tomado de $args['usuarioId'] y si está vacío almacenamos un string vacio
    }
}