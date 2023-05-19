<?php

namespace Controllers;          // Agregamos el nombre del namespace que tendran todos los controladores ubicados en la carpeta controllers

use MVC\Router;

class CitaController {
    public static function index(Router $router) {      // Metodo asociado a /cita

        session_start();                                // Iniciamos sesión para tener disponible la superglobal $_SESSION

        isAuth();                                       // Llamada a la función que comprueba si el usuario está autenticado

        $router->render('cita/index', [                 // Le pasamos a render() nuestro objeto $router. A render() les pasamos la ubicación de la vista index.php y los datos que en ella
                                                        // vayamos a usar

        'nombre' => $_SESSION['nombre'],
        'id' => $_SESSION['id']
        ]);
    }
}