<?php 

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;

class DashboardController {
    
    public static function index(Router $router) {

        session_start();
        isAuth();

        $proyectos = Proyecto::belongsTo('propietarioId',$_SESSION['id']);

        //MOSTRAR LA VISTA
        $router->render('dashboard/index',[
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
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

        $alertas = [];
        $usuario = Usuario::find($_SESSION['id']);

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
           $usuario->sincronizar($_POST);
            
           $alertas = $usuario->validarPerfil();

           if(empty($alertas)) {

                $existeUsuario = Usuario::where('email', $usuario->email);

                if($existeUsuario && $existeUsuario!== $usuario->id) {
                    Usuario::setAlerta('error','El correo ya existe, prueba uno nuevo');
                    $alertas = $usuario->getAlertas();
                } else {
                    //Actualizamos el usuario
                    $resultado = $usuario->guardar();

                    $_SESSION['nombre'] = $usuario->nombre;

                    if($resultado) {
                    Usuario::setAlerta('exito','Guardado Exitosamente');
                    $alertas = $usuario->getAlertas();
                    }

                }
                
           }
        }

        //MOSTRAR LA VISTA
        $router->render('dashboard/perfil',[
            'titulo' => 'Perfil',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
}