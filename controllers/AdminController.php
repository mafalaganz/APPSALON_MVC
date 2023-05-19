<?php

namespace Controllers;

use MVC\Router;
use Model\AdminCita;

class AdminController {
    public static function index(Router $router) {
        session_start();                                            // Pasamos las variable del inicio de sesión ($_SESSION)

        isAdmin();                                                  // Llamada a la función que verifica si el usuario es un administrador 

        $fecha = $_GET['fecha'] ?? date('Y-m-d');                   // Almacenamos la fecha de $_GET en $fecha, y si la superglobal no tiene fecha tomamos
                                                                    // la del servidor que es la fecha del dia actual que usamos en la BD (Año/mes/día)            
        $fechas = explode('-', $fecha);                             // Separamos los valores de la fecha (dia, mes y año) y lo almacenamos en $fecha
        
        if( !checkdate($fechas[1], $fechas[2], $fechas[0])) {       // Chequeamos la fecha del Query String con la funcion checkdate 
            header('Location: /404');                               // y si no es valida redirigimos al usuario a la pagina 404.
        }     

        // Consultar la Base de Datos
        $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuarioId=usuarios.id  ";
        $consulta .= " LEFT OUTER JOIN citasServicios ";
        $consulta .= " ON citasServicios.citaId=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citasServicios.servicioId ";
        $consulta .= " WHERE fecha =  '${fecha}' ";

        $citas = AdminCita::SQL($consulta);

        $router->render('admin/index', [
            'nombre' => $_SESSION['nombre'],             // Pasamos a la vista la variable obtenida de $_SESSION['nombre']
            'citas' => $citas,
            'fecha' => $fecha
        ]);
    }
}

