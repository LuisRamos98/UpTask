<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\DashboardController;
use Controllers\LoginController;
use Controllers\TareaController;
use MVC\Router;
$router = new Router();

//Login
$router->get("/",[LoginController::class,'login']);
$router->post("/",[LoginController::class,'login']);
$router->get("/logout",[LoginController::class,'logout']);

//crear
$router->get("/crear",[LoginController::class,'crear']);
$router->post("/crear",[LoginController::class,'crear']);

//Formulario de olvide el password
$router->get("/olvide",[LoginController::class,'olvide']);
$router->post("/olvide",[LoginController::class,'olvide']);

//Colocar el nuevo password
$router->get("/restablecer",[LoginController::class,'restablecer']);
$router->post("/restablecer",[LoginController::class,'restablecer']);

//Confirmación de Cuenta
$router->get("/mensaje",[LoginController::class,'mensaje']);
$router->get("/confirmar",[LoginController::class,'confirmar']);
$router->post("/confirmar",[LoginController::class,'confirmar']);

//ZONA PRIVADA
$router->get("/dashboard",[DashboardController::class,'index']);
$router->get("/crear-proyecto",[DashboardController::class,'crear_proyecto']);
$router->post("/crear-proyecto",[DashboardController::class,'crear_proyecto']);
$router->get("/proyecto",[DashboardController::class,'proyecto']);
$router->get("/perfil",[DashboardController::class,'perfil']);

//API PARA LAS TAREAS
$router->get("/api/tareas",[TareaController::class,'index']);
$router->post("/api/tarea",[TareaController::class,'crear']);
$router->post("/api/tareas/actualizar",[TareaController::class,'actualizar']);
$router->post("/api/tareas/eliminar",[TareaController::class,'eliminar']);


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();