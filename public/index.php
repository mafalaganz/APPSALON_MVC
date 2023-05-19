<?php 

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\APIController;
use Controllers\CitaController;
use Controllers\LoginController;
use Controllers\AdminController;
use Controllers\ServicioController;

$router = new Router();

// Iniciar Sesión
$router->get('/', [LoginController::class, 'login']);                               // Ruta de URL para mostrar el formulario de login
$router->post('/', [LoginController::class, 'login']);                              // Ruta de URL para enviar el formulario de login
$router->get('/logout', [LoginController::class, 'logout']);                        // Ruta de URL para cerrar sesión

// Recuperar Password
$router->get('/olvide', [LoginController::class, 'olvide']);                        // Ruta de URL para mostrar el formulario de recuperar password
$router->post('/olvide', [LoginController::class, 'olvide']);                       // Ruta de URL para enviar el formulario de recuperar password
$router->get('/recuperar', [LoginController::class, 'recuperar']);                  // Ruta de URL donde se envia al usuario al clickar el enlace que se le envia al correo  
                                                                                    // y donde escribira un nuevo password
$router->post('/recuperar', [LoginController::class, 'recuperar']);                 // Ruta de URL para enviar el nuevo password e insertarlo en el usuario previamente
                                                                                    // validado

// Crear Cuenta
$router->get('/crear-cuenta', [LoginController::class, 'crear']);                   // Ruta de URL para mostrar el formulario de crear cuenta
$router->post('/crear-cuenta', [LoginController::class, 'crear']);                  // Ruta de URL para enviar el formulario de crear cuenta

// Confirmar Cuenta
$router->get('/confirmar-cuenta', [LoginController::class, 'confirmar']);           // Ruta de URL para confirmar la cuenta que ha creado el usuario
$router->get('/mensaje', [LoginController::class, 'mensaje']);                      // Ruta de URL para que redirecciona al usuario a la pagina que indica que debe irse al email para confirmar
                                                                                    // la cuenta de usuario

// AREA PRIVADA

$router->get('/cita', [CitaController::class, 'index']);                            // Ruta de URL para que el usuario cree una cita
$router->get('/admin', [AdminController::class, 'index']);                          // Ruta de URL para que el usuario cree una cita

// API de Citas

$router->get('/api/servicios', [APIController::class, 'index']);                    // Ruta de URL (endpoint) de servicios de nuestra API
$router->post('/api/citas', [APIController::class, 'guardar']);                     // Ruta de URL (endpoint) que leera los datos que enviemos en nuestro FormData()
$router->post('/api/eliminar', [APIController::class, 'eliminar']);                 // Ruta del URL (endpoint) que enviará los datos de la cita que eliminaremos 

// CRUD de Servicios
$router->get('/servicios', [ServicioController::class, 'index']);                   // Ruta de URL (endpoint) de Servicios
$router->get('/servicios/crear', [ServicioController::class, 'crear']);             // Ruta de URL (endpoint) de Servicios que muestra el formulario para crear un servicio
$router->post('/servicios/crear', [ServicioController::class, 'crear']);            // Ruta de URL (endpoint) de Servicios que lee los datos del formulario para crear un servicio
$router->get('/servicios/actualizar', [ServicioController::class, 'actualizar']);   // Ruta de URL (endpoint) de Servicios que muestra el formulario para actualizar un servicio 
$router->post('/servicios/actualizar', [ServicioController::class, 'actualizar']);  // Ruta de URL (endpoint) de Servicios que lee los datos del formulario para actualizar un servicio 
$router->post('/servicios/eliminar', [ServicioController::class, 'eliminar']);      // Ruta de URL (endpoint) de Servicios que lee los datos del formulario para actualizar un servicio 

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();