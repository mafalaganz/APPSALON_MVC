<?php

namespace Controllers;      // Namespace utilizado para todos los archvivos den la carpeta controllers y que definimos en 

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController {
    public static function index() {

        $servicios = Servicio::all();       // Utilizamos el metodo all() para traernos en un arreglo almacenado en $servicios todos los registros de la tabla servicio
        echo json_encode($servicios);       // Convertimos el arreglo en un json para poder consumir la info en javascript, por ejemplo aplicando el metodo .json
    }

    public static function guardar() {
        
        // Almacena la Cita y devuelve el id
        $cita = new Cita($_POST);           // Instanciamos un objeto tipo Cita al que le pasamos los datos de $_POST
        $resultado = $cita->guardar();      // Guardamos la cita en la BD y la almacenamos en $resultado para poder visualizarla con json_encode

        $id = $resultado['id'];             // Tomamos del arreglo $resultado el valor del id de la posicion del id

        // Almacena los Servicios con el id de la cita
        $idServicios = explode(",", $_POST['servicios']);

        foreach($idServicios as $idServicio) {      // Iteramos idServicios
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];
            $citaServicio = new CitaServicio($args);    // Instanciamos un objeto tipo CitaServicio al que le pasamos por parametro citaId y servicioId
            $citaServicio->guardar();                   // Llamamaos a guardar para guardar el objeto CitaServicio en la tabla citasservicios
        }

        // Retornamos una respuesta
        echo json_encode(['resultado' => $resultado]);  // Transformamos el arreglo en JSON para poder ser leido por el JS del navegador
    }

    public static function eliminar() {
       
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $cita = Cita::find($id);                        // Buscamos el objeto cita por su id y lo almacenamos en $cita
        $cita->eliminar();                              // Eliminamos el objeto cita

        header('Location:' . $_SERVER['HTTP_REFERER']); // Redireccionamos al usuario a la pagina de donde venía

       }
    }
}