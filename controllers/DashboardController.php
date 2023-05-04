<?php 

namespace Controllers;

use Model\Proyecto;
use MVC\Router;

class DashboardController {
    
    public static function index(Router $router) {

        session_start();
        isAuth();

        //MOSTRAR LA VISTA
        $router->render('dashboard/index',[
            'titulo' => 'Proyectos'
        ]);
    }

    public static function crear_proyecto(Router $router) {

        session_start();
        isAuth();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $proyecto = new Proyecto($_POST);
            $alertas = $proyecto->validarProyecto();

            if(empty($alertas)) {
                //Generar un URL unica
                $hash = md5(uniqid());
                $proyecto->url = $hash;

                //Almacenar el creador del proyecto
                $proyecto->propietarioId = $_SESSION['id'];

                //Guardar proyecto
                $proyecto->guardar();   

                //Redireccionar
                header('Location: /proyecto' . '?id=' . $proyecto->url);
            }
        }

        //MOSTRAR LA VISTA
        $router->render('dashboard/crear-proyecto',[
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }

    public static function proyecto(Router $router) {

        session_start();
        isAuth();

        $token = $_GET['id'];
        $proyecto = Proyecto::where('url',$token);

        if($proyecto->propietarioId !== $_SESSION['id'] ) {
            header('Location: /dashboard');
        }

        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto
        ]);
    }

    public static function perfil(Router $router) {

        session_start();
        isAuth();

        //MOSTRAR LA VISTA
        $router->render('dashboard/crear-proyecto',[
            'titulo' => 'Perfil'
        ]);
    }
}