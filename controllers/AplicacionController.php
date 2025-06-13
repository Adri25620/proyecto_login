<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Aplicacion;

class AplicacionController extends ActiveRecord
{
    public static function index(Router $router)
    {
        $router->render('aplicacion/index', [], 'layout/layouts');
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        try {
            if (empty($_POST['ap_nombre_largo'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El nombre largo es obligatorio'
                ]);
                return;
            }

            if (empty($_POST['ap_nombre_medium'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El nombre medio es obligatorio'
                ]);
                return;
            }

            if (empty($_POST['ap_nombre_corto'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El nombre corto es obligatorio'
                ]);
                return;
            }

            $aplicacion = new Aplicacion([
                'ap_nombre_largo' => ucwords(strtolower(trim(htmlspecialchars($_POST['ap_nombre_largo'])))),
                'ap_nombre_medium' => ucwords(strtolower(trim(htmlspecialchars($_POST['ap_nombre_medium'])))),
                'ap_nombre_corto' => ucwords(strtolower(trim(htmlspecialchars($_POST['ap_nombre_corto'])))),
                'ap_situacion' => 1
            ]);

            $resultado = $aplicacion->crear();

            if ($resultado['resultado'] == 1) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Aplicación registrada exitosamente',
                    'aplicacion_id' => $resultado['id']
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al registrar la aplicación'
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar la aplicación',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarAPI()
    {
        try {
            $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
            $fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

            $condiciones = ["ap_situacion = 1"];

            if ($fecha_inicio) {
                $condiciones[] = "ap_fecha_creacion >= '{$fecha_inicio}'";
            }

            if ($fecha_fin) {
                $condiciones[] = "ap_fecha_creacion <= '{$fecha_fin}'";
            }

            $where = implode(" AND ", $condiciones);
            $sql = "SELECT ap_id, ap_nombre_largo, ap_nombre_medium, ap_nombre_corto, ap_fecha_creacion, ap_situacion 
                    FROM aplicacion 
                    WHERE $where 
                    ORDER BY ap_fecha_creacion DESC";
            
            $aplicaciones = self::fetchArray($sql);

            if (empty($aplicaciones)) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se encontraron aplicaciones registradas',
                    'data' => []
                ]);
            } else {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Aplicaciones obtenidas satisfactoriamente',
                    'data' => $aplicaciones
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las aplicaciones',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        try {
            $aplicacion_id = $_POST['ap_id'];

            if (empty($aplicacion_id)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de aplicación requerido'
                ]);
                return;
            }

            if (empty($_POST['ap_nombre_largo']) || empty($_POST['ap_nombre_medium']) || empty($_POST['ap_nombre_corto'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Todos los campos son obligatorios'
                ]);
                return;
            }

            $aplicacion = Aplicacion::find($aplicacion_id);
            if (!$aplicacion) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Aplicación no encontrada'
                ]);
                return;
            }

            $datosActualizar = [
                'ap_nombre_largo' => ucwords(strtolower(trim(htmlspecialchars($_POST['ap_nombre_largo'])))),
                'ap_nombre_medium' => ucwords(strtolower(trim(htmlspecialchars($_POST['ap_nombre_medium'])))),
                'ap_nombre_corto' => ucwords(strtolower(trim(htmlspecialchars($_POST['ap_nombre_corto']))))
            ];

            $aplicacion->sincronizar($datosActualizar);
            $resultado = $aplicacion->guardar();

            if ($resultado['resultado']) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Aplicación modificada exitosamente'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al modificar aplicación'
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar la aplicación',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function eliminarAPI()
    {
        try {
            $aplicacion_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

            if (!$aplicacion_id) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de aplicación inválido'
                ]);
                return;
            }

            $aplicacion = Aplicacion::find($aplicacion_id);
            if (!$aplicacion) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Aplicación no encontrada'
                ]);
                return;
            }

            $resultado = Aplicacion::EliminarAplicaciones($aplicacion_id);

            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Aplicación eliminada exitosamente'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al eliminar aplicación'
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar la aplicación',
                'detalle' => $e->getMessage(),
            ]);
        }
    }
}