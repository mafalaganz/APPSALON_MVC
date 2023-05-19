<?php

namespace Model; 

class Usuario extends ActiveRecord {
    // Base de datos

    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = []) {

        $this->id = $args['id'] ?? null;            // Pasamos al atributo id el valor contenido en el arreglo $args de la posicion ['id] y si no tiene valor el atributo toma como valor un null
        $this->nombre = $args['nombre'] ?? '';      // Pasamos al atributo id el valor contenido en el arreglo $args de la posicion ['id] y si no tiene valor el atributo toma un string vacío
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    // Mensajes de validación para la creación de una cuenta
    public function validarNuevaCuenta() {
        if(!$this->nombre) {                                                 // Si no existe dato en la posicion nombre del objeto se ejecutará el codigo entre {}
            self::$alertas['error'] [] = 'El Nombre del es obligatorio';     // Se agregará en la posicion ['error'] del arreglo el string indicado
        }

        if(!$this->apellido) {                                               // Si no existe dato en la posicion apellido del objeto se ejecutará el codigo entre {}
            self::$alertas['error'] [] = 'El Apellido es obligatorio';       // Se agregará en la posicion ['error'] del arreglo el string indicado
        }

        if(!$this->email) {                                                  // Si no existe dato en la posicion email del objeto se ejecutará el codigo entre {}
            self::$alertas['error'] [] = 'El email es obligatorio';          // Se agregará en la posicion ['error'] del arreglo el string indicado
        }

        if(!$this->password) {                                               // Si no existe dato en la posicion password del objeto se ejecutará el codigo entre {}
            self::$alertas['error'] [] = 'El password es obligatorio';       // Se agregará en la posicion ['error'] del arreglo el string indicado
        }

        if(strlen($this->password) < 6) {                                                   //  Si la longitud del password es inferior a 6 caracteres se ejecutará el código entre {}
            self::$alertas['error'] [] = 'El password debe contener al menos 6 caracteres'; //  Se agregará en la posicion ['error'] del arreglo el string indicado
        }

        return self::$alertas;                                               // El metodo devolverá el valor del arreglo $alertas
    }

    public function validarLogin() {

        if(!$this->email) {                                                  // Si no existe dato en la posicion email del objeto se ejecutará el codigo entre {}
            self::$alertas['error'] [] = 'El email es obligatorio';          // Se agregará en la posicion ['error'] del arreglo el string indicado
        }

        if(!$this->password) {                                               // Si no existe dato en la posicion password del objeto se ejecutará el codigo entre {}
            self::$alertas['error'] [] = 'El password es obligatorio';       // Se agregará en la posicion ['error'] del arreglo el string indicado
        }

        return self::$alertas;                                               // El metodo devolverá el valor del arreglo $alertas

    }

    public function validarEmail() {

        if(!$this->email) {                                                  // Si no existe dato en la posicion email del objeto se ejecutará el codigo entre {}
            self::$alertas['error'] [] = 'El email es obligatorio';          // Se agregará en la posicion ['error'] del arreglo el string indicado
        }

        return self::$alertas;                                               // El metodo devolverá el valor del arreglo $alertas
    }

    public function validarPassword() {
        if(!$this->password) {                                                              // Si no existe dato en la posicion password del objeto se ejecutará el codigo entre {}  
            self::$alertas['error'][] = 'El Password es obligatorio';                       // Se agregará en la posicion ['error'] del arreglo el string indicado
        }

        if(strlen($this->password) < 6) {                                                  //  Si la longitud del password es inferior a 6 caracteres se ejecutará el código entre {}
            self::$alertas['error'][] = 'El Password debe tener al menos 6 caracteres';     //  Se agregará en la posicion ['error'] del arreglo el string indicado
        }
        
        return self::$alertas;
    }

    // Revisa si el usuario ya existe
    public function existeUsuario() {
        $query = " SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";        // Sentencia SQL de la consulta a la BD

        $resultado = self::$db->query($query);  // Llamada a $db, variable con la funcion de conexión a la BD y los parametros necesarios de la BD, le aplicamos el metodo query() al que le 
                                                // pasamos la consulta $query 

        if($resultado->num_rows) {              // Si se cumple la condición se agregara un mensaje al arreglo $alertas
            self::$alertas['error'][] = 'El Usuario ya está registrado';
        }                                     

        return $resultado;                      // Retornamos $resultado a desde donde se produce la llamada al metodo.
    }

    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);  //Hasheamos el valor del password que tenemos en la posición password y guardamos el valor en dicha posición
    }

    public function crearToken() {
        $this->token = uniqid();                // Con la función uniqid() generamos un token que almacenamos en la posición token de nuestro objeto o instancia
    }

    public function comprobarPasswordAndVerificado($password) {

        $resultado = password_verify($password, $this->password);   // Comparamos el password que el usuario escribio en el formulario (1er parametro) con el password que tenemos para el 
                                                                    // usuario en la BD (2º parametro)
        
        if(!$resultado || !$this->confirmado) {
            self::$alertas['error'][] = 'Password Incorrecto o tu cuenta no ha sido confirmada'; 
        } else {
            return true;
        }              
    }
}