<?php

namespace Controllers;                                                                     // Agregamos el nombre del namespace que tendran todos los controladores ubicados en la carpeta controllers

use Classes\Email;
use Model\ActiveRecord;
use MVC\Router;
use Model\Usuario;

class LoginController {
    public static function login(Router $router) {
        $alertas = [];                                                                      // Agregamos el arreglo vacío $alertas. Al enviar el formulario si hay algun campo vacio se ira llenando 
                                                                                            // con los errores.
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);                                                    // Instanciamos un objeto del modelo Usuario, $auth, y le pasamos lo que el usuario escriba y tenemos 
                                                                                            // en $_POST
            $alertas = $auth->validarLogin();                                               // Almacenamos el resultado del metodo aplicado al objeto $auth en $alertas.

            if(empty($alertas)) {
                // Comprobar que existe el usuario
                $usuario = Usuario::where('email', $auth->email);                           // Comprobamos si en la BD existe el usuario. Buscamos en la columna email el email proporcionado por el 
                                                                                            // usuario 
                if($usuario) {
                    //Verificar el password
                    if($usuario->comprobarPasswordAndVerificado($auth->password)) {         // Llamamos al metodo para nuestro objeto $usuario, resultado de la consulta a la BD
                        // Autenticar el usuario
                        session_start();                                                    // Función con la que iniciamos sesión y nos da acceso a $_SESSION que confeccionaremos a nuestro gusto

                        $_SESSION['id'] = $usuario->id;                                     // Almacenamos en $_SESSION el valor del id, tomado de nuestro BD de datos y almacenado como atributo 
                                                                                            // de $usuario
                        $_SESSION['nombre'] = $usuario->nombre . " ". $usuario->apellido;   // Almacenamos en $_SESSION el valor del nombre
                        $_SESSION['email'] = $usuario->email;                               // Almacenamos en $_SESSION el valor del email
                        $_SESSION['login'] = true;                                          // Almacenamos en $_SESSION el valor del login

                        // Redireccionamiento

                        if($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;                   // Almacenamos en $_SESSION el valor 1, procedente del atributo admin del objeto $usuario (espejo del
                                                                                            // registro del usuario de la BD). Si el objeto no tiene valor almacenamos un null
                            header('Location: /admin');                                     // Si el usuario no es admin le redireccinamos a /cita
                            
                        } else {
                            header('Location: /cita');                                      // Si el usuario no es admin le redireccinamos a /cita
                        }
                    }     
                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado');           // Agregamos un mensaje de error al arreglo $alertas
                }
            }
        }

        $alertas = Usuario::getAlertas();                                           // Recuperamos a $alertas los mensajes de error almacenados en $alertas y que nos devuelve el metodo

        $router->render('auth/login', [
            'alertas'=> $alertas                                                    // Pasamos los mensajes de alerta a la vista /    
        ]);
        
    }

    public static function logout() {
        session_start();                                            // Función con la que iniciamos sesión y nos da acceso a $_SESSION que confeccionaremos a nuestro gusto
        $_SESSION = [];                                             // Asignamos al arreglo de sesión un string vacío
        header('Location: /');                                      // Redireccionamos al usuario a la pagina principal
    }                                      

    public static function olvide(Router $router) {

        $alertas = [];                                              // Arreglo vacío cuando el usuario visita /olvide por primera vez
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $auth = new Usuario($_POST);                            // Tomamos los datos procedentes de $_POST y los almacenamos en el objeto de tipo Usuario $auth (solo se enviará el email desde
                                                                    // el formulario y solo el atributo email se pasara de $_POST a $auth, el resto de campos del objeto estarán vacíos)

            $alertas = $auth->validarEmail();                       // Validaremos mediante el metodo que se ha enviado en el formulario un email valido por parte del usuario, y si no se añadirá un
                                                                    // mensaje en el arreglo $alertas que nos devolvera el metodo y almacenaremos en $alertas

            if(empty($alertas)) {                                   // Si no existen mensajes de error realizaremos las siguientes tareas
                $usuario = Usuario::where('email', $auth->email);   // Llamada al metodo where() de ActiveRecord. Busqueda en la columna email el valor recogido del formulario y que tenemos
                                                                    // en $auth->email 
                if($usuario && $usuario->confirmado === "1") {

                    // Generar token

                    $usuario->crearToken();                         // Aplicamos el metodo crearToken a nuestro objeto $usuario de forma que su campo token pasa de tener el valor 0 a tener un valor
                    $usuario->guardar();                            // Aplicamos el metodo guardar() a nuestro objeto $usuario, de forma que como tenemos un id, se actualiza el registro en la BD.

                    // Enviar el email

                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token); // Instanciamos un objeto tipo Email
                    $email->EnviarInstrucciones();                                          // Aplicamos el metodo al objeto $email

                    // Alerta de exito
                    Usuario::setAlerta('exito', 'Revisa tu email'); // Establecemos un mensaje en el arreglo $exito del arreglo $alertas con el mensaje indicado

                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no está confirmado');   // Establecemos un mensaje en el arreglo $error del arreglo $alertas con el mensaje indicado
                }
            }
        }

        $alertas = Usuario::getAlertas();                           // LLamamos al metodo getAlertas() para mostrar todas las alertas almacenadas en $alertas

        $router->render('auth/olvide-password', [
            'alertas' => $alertas                                   // Pasamos los mensajes de alerta a la vista /olvide   
        ]);
    }

    public static function recuperar(Router $router) {

        $alertas = [];                                          // Arreglo vacío cuando el usuario visita /recuperar por primera vez
        $error = false;                                         // Variable que creamos para en función de su valor interrumpir la ejecución de la vista de /recuperar

        $token = s($_GET['token']);                             // Leemos la info del atributo token de $_GET, la sanitizamos con s() y la almacenamos en $token

        // Buscar usuario por su token

        $usuario = Usuario::where('token', $token);             // LLamada al metodo where() de ActiveRecord desde Usuario para buscar los datos del usuario para el token de la URL

        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token No Valido');     // Establecemos un mensaje en el arreglo $error del arreglo $alertas con el mensaje indicado
            $error = true;                                      // Variable que creamos para en función de su valor interrumpir la ejecución de la vista de /recuperar
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Leer el nuevo password y guardarlo
            
            $password = new Usuario($_POST);                    // Tomamos el objeto de $_POST y lo almacenamos en $password
            $alertas = $password->validarPassword();            // Llamamos al metodo validarPassword a traves del objeto $password y la respuesta la almacenaremos en $alertas

            if(empty($alertas)) {
                $usuario->password = null;                      // Dejamos a null el valor del atributo password en nuestro objeto $usuario (tomamos inicialmente los datos de la BD y los
                                                                // almacenamos en memoria) eliminando el password antiguo

                $usuario->password = $password->password;       // Asignamos el valor del atributo password de $password (password introducido por el usuario en el formulario sin hashear) 
                                                                // al atributo password de $usuario

                $usuario->hashPassword();                       // Hasheamos el password contenido en el atributo password del objeto $usuario
                $usuario->token = null;                         // Dejamos a null el valor del atributo token del objeto $usuario una vez indicado el nuevo password

                $resultado = $usuario->guardar();               // Actualizamos el objeto $usario con el nuevo password en la BD y el valor devuelto por el metodo lo almacenamos en $resultado
                if($resultado) {
                    header('Location: /');                      // Si se actualizo el registro del usuario en la BD lo redirijimos a la pagina principal para que inicie sesión
                }
            }
        }

        $alertas = Usuario::getAlertas();                       // LLamamos al metodo getAlertas() para mostrar todas las alertas almacenadas en $alertas

        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,                              // Pasamos los mensajes de alerta a la vista /recuperar
            'error' => $error                                   // Pasamos la variable a la vista /recuperar
        ]);
    }
    
    public static function crear(Router $router) {

        $usuario = new Usuario;                                 //Instanciamos para tener un objeto tipo usuario disponible y poder pasarlo hacia la vista en el render()

        // Alertas vacias
        $alertas = [];                                          // Arreglo vacío cuando el usuario visita /crear-cuenta por primera vez

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $usuario->sincronizar($_POST);                      // Sincronizamos los datos de nuestro objeto vacío con los datos nuevos que nos llegan via $_POST
            $alertas = $usuario->validarNuevaCuenta();          // Llamamos al metodo desde nuestra instancia u objeto

            // Revisar que $alertas esté vacío
            if(empty($alertas)) {                               // Revisamos que $alertas este vacío con la función empty() que nos dice si un arreglo está vacío o no
                // Verificar que el usuario no esté registrado
                $resultado = $usuario->existeUsuario();         // El resultado de aplicar al objeto $usuario el metodo se almacena en $resultado

                if ($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();           // Traemos mediante el metodo las alertas en el arreglo $alertas y las almacenamos en $alertas
                                                                // Vemos que hemos vuelto a crear $alertas, el cual es distinto que el anterior al if del empty.
                } else {
                        // Hashear el Password
                        $usuario->hashPassword();               // Aplicamos a nuestro objeto el metodo para hashear el password

                        // Generar un Token único
                        $usuario->crearToken();                 // Aplicamos a nuestro objeto el metodo indicado para agregar a la posición token del objeto un token

                        // Enviar el Email
                        $email = new Email($usuario->nombre, $usuario->email, $usuario->token);  // Creamos una instancia u objeto de tipo Email

                        $email->enviarConfirmacion();     
                        
                        // Crear el Usuario

                        $resultado = $usuario->guardar();       // Aplicamos al objeto $usuario el metodo guardar()
                        if($resultado) {                        // Si guardar nos devuelve un valor, $resultado, se mostrará el mensaje indicado
                            header('Location: /mensaje');
                        }
                }
            }
        }         

        $router->render('auth/crear-cuenta', [
            'usuario'=> $usuario,                               // Pasamos nuestro objeto a la vista de /crear-cuenta
            'alertas'=> $alertas                                // Pasamos los mensajes de alerta a la vista /crear-cuenta    
            ]);
         
    }

    public static function  mensaje(Router $router) {

        $router->render('auth/mensaje');                        // Pasamos nuestro objeto al metodo render(), el cual nos permitirá mostrar la vista de nuestra pagina /mensaje
    }

    public static function confirmar(Router $router) {
        $alertas = [];                                          // Pasamos al metodo el arreglo $alertas, el cual tenemos originalmente en el modelo ActiveRecord
        $token = s($_GET['token']);                             // Tomamos el valor de la posición token de $_GET y lo almacenamos en la variable $token
        $usuario = Usuario::where('token', $token);             // LLamada al metodo where() de ActiveRecord desde Usuario para buscar los datos del usuario para el token de la URL
        
        if(empty($usuario)) {
            // Mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no Válido');     // Establecemos el tipo de alerta y mensaje a mostrar, lo cual se almacena en $alertas.
        } else {
            // Modificar a usuario confirmado
            $usuario->confirmado = "1";                                         // Cambiamos el valor del atributo confimado del objeto usuario a "1".
            $usuario->token = null;                                             // Eliminamos el valor del atributo token dejándolo a null.
            $usuario->guardar();                                                // Guardamos los cambios realizados en los atributos de nuestro objeto en memoria en la BD.
            Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente');     // Creamos el arreglo $exito dentro del arreglo $alertas con la posición tipo string para el mensaje mostrada.
        }

        // Obtener alertas
        $alertas = Usuario::getAlertas();                       // Recuperamos las alertas almacenadas en $alertas del metodo y las guardamos en $alertas.
        
        // Rendirizar la vista
        $router->render('auth/confirmar-cuenta', [              
            'alertas' => $alertas                               // Pasamos a nuestra vista /confirmar-cuenta la variable

        ]);
    }
}