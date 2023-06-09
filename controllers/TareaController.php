<?php

namespace Controllers;

use Model\Proyecto;
use Model\Tarea;

class TareaController {

    public static function index() {

        $proyectoId = $_GET['id'];
        
        if(!$proyectoId) {
            header('Location: /dashboard');
            return;
        }

        session_start();
        
        $proyecto = Proyecto::where('url', $proyectoId);
        if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) header('Location: /404');

        $tareas = Tarea::belongsTo('proyectoId',$proyecto->id);

        echo json_encode([
            'tareas' => $tareas,
        ]);
    }

    public static function crear() {
        session_start();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $proyecto = Proyecto::where('url',$_POST['proyectoId']);

            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al agregar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }

            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();
            if(!$resultado) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al agregar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }

            $respuesta = [
                'tipo' => 'exito',
                'id' => $resultado['id'],
                'mensaje' => 'Tarea Agregada Correctamente',
                'proyectoId' => $tarea->proyectoId
            ];
            echo json_encode($respuesta);
        }
    }

    public static function actualizar() {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $proyecto = Proyecto::where('url',$_POST['proyectoId']);
            
            session_start();
            
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al agregar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }

            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();
            
            if($resultado) {
                $respuesta = [
                    'id' => $tarea->id,
                    'tipo' => 'exito',
                    'mensaje' => 'Actualizado Correctamente',
                    'proyectoId' => $tarea->proyectoId
                ];

                echo json_encode($respuesta);
            }
        }
    }

    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $proyecto = Proyecto::where('url',$_POST['proyectoId']);
            
            session_start();
            
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al agregar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }

            $tarea = Tarea::where('id',$_POST['id']);
            $resultado = $tarea->eliminar();

            if($resultado) {
                $respuesta = [
                    'id' => $tarea->id,
                    'tipo' => 'exito',
                    'mensaje' => 'Eliminado Correctamente',
                    'proyectoId' => $tarea->proyectoId
                ];

                echo json_encode($respuesta);
            }
            

        }
    }
}