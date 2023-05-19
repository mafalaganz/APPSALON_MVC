<?php

namespace MVC;

class Router
{
    public array $getRoutes = [];                   // Creamos el arreglo $getRoutes
    public array $postRoutes = [];                  // Creamos el arreglo $postRoutes


    public function get($url, $fn)                  // Establecemos que nuestro objeto $router tenga como llave $url (url visitada por el usuario) y como valor $fn
    {
        $this->getRoutes[$url] = $fn;               
    }

    public function post($url, $fn)                 // Establecemos que nuestro objeto $router tenga como llave $url (url visitada por el usuario) y como valor $fn
    {
        $this->postRoutes[$url] = $fn;
    }

    public function comprobarRutas()                // Funcion que comprueba si esta en el routing la url visitada por el usuario en el navegador
    {
        
        // Proteger Rutas...
        // session_start();                            // Iniciamos sesion

        // Arreglo de rutas protegidas...
        // $rutas_protegidas = ['/admin', '/propiedades/crear', '/propiedades/actualizar', '/propiedades/eliminar', '/vendedores/crear', '/vendedores/actualizar', '/vendedores/eliminar'];

        // $auth = $_SESSION['login'] ?? null;

        $currentUrl = $_SERVER['REQUEST_URI'] === '' ? '/' :    // Tomamos de $_SERVER la url de la pagina que el usuario esta visitando en su navegador si el valor es distinto a string vacio
        $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];                   // Tomamos de $_SERVER si la pagina que el usuario esta visitando en su navegador es GET o POST

        if ($method === 'GET') {
            $fn = $this->getRoutes[$currentUrl] ?? null;    // Compruebo si existe una posicion cuya llave es la url que estoy visitando en el navegador, y si existe hago que
                                                            // esa posición tome como valor $fn
        } else {
            $fn = $this->postRoutes[$currentUrl] ?? null;   // Compruebo si existe una posicion cuya llave es la url que estoy visitando en el navegador, y si existe hago que
                                                            // esa posición tome como valor $fn
        }


        if ( $fn ) {
            // La URL existe y hay una función asociada
            // Call user fn va a llamar una función cuando no sabemos cual sera
            call_user_func($fn, $this); // Le pasamos a la función por parametro la funcion asociada a la pagina que estamos visitando en el navegador ($fn)
                                        // y la ruta que estamos visitando en el navegador ($this)
                                        // This es para pasar argumentos
        } else {
            echo "Página No Encontrada o Ruta no válida";   // Si $fn no existe, indica que la url visitada no esta en el routing, por lo que mostramos un mensaje informandolo
        }
    }

    public function render($view, $datos = [])
    {

        // Leer lo que le pasamos  a la vista
        foreach ($datos as $key => $value) {
            $$key = $value;  // Doble signo de dolar significa: variable variable, básicamente nuestra variable sigue siendo la original, pero al asignarla a otra no la reescribe, mantiene su valor, de esta forma el nombre de la variable se asigna dinamicamente
        }

        ob_start(); // Almacenamiento en memoria durante un momento...

        // entonces incluimos la vista en el layout
        include_once __DIR__ . "/views/$view.php";
        $contenido = ob_get_clean(); // Limpia el Buffer
        include_once __DIR__ . '/views/layout.php';
    }
}
