<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\Permisos;
use Model\Aplicacion;
use MVC\Router;

class PermisosController extends ActiveRecord
{

    public function index(Router $router)
    {
        $aplicaciones = Aplicacion::where('ap_situacion', 1);
        
        $router->render('permisos/index', [
            'aplicaciones' => $aplicaciones
        ], 'layout/layouts');
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        error_log("Llegó a guardarAPI");
        error_log("POST data: " . print_r($_POST, true));

        try {
   
            if (empty($_POST['per_aplicacion'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar una aplicación'
                ]);
                return;
            }

            if (empty($_POST['per_nombre'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El nombre del permiso es obligatorio'
                ]);
                return;
            }

            if (strlen($_POST['per_nombre']) > 150) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El nombre del permiso no puede exceder 150 caracteres'
                ]);
                return;
            }

            if (empty($_POST['per_clave'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La clave del permiso es obligatoria'
                ]);
                return;
            }

            if (strlen($_POST['per_clave']) > 250) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La clave del permiso no puede exceder 250 caracteres'
                ]);
                return;
            }

            if (empty($_POST['per_descripcion'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La descripción del permiso es obligatoria'
                ]);
                return;
            }

            if (strlen($_POST['per_descripcion']) > 250) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La descripción no puede exceder 250 caracteres'
                ]);
                return;
            }

            $permisoExistente = Permisos::where('per_clave', $_POST['per_clave']);
            if (!empty($permisoExistente)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe un permiso con esta clave'
                ]);
                return;
            }

            $permiso = new Permisos([
                'per_aplicacion' => filter_var($_POST['per_aplicacion'], FILTER_SANITIZE_NUMBER_INT),
                'per_nombre' => ucwords(strtolower(trim(htmlspecialchars($_POST['per_nombre'])))),
                'per_clave' => strtoupper(trim(htmlspecialchars($_POST['per_clave']))),
                'per_descripcion' => trim(htmlspecialchars($_POST['per_descripcion'])),
                'per_situacion' => '1'
            ]);

            $resultado = $permiso->crear();

            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Permiso registrado exitosamente'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al registrar el permiso'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error en el servidor: ' . $e->getMessage()
            ]);
        }
    }

    public static function buscarAPI()
    {
        getHeadersApi();

        try {
            $sql = "SELECT p.per_id, p.per_aplicacion, p.per_nombre, p.per_clave, p.per_descripcion, p.per_fecha, p.per_situacion,
                           a.ap_nombre_largo, a.ap_nombre_medium, a.ap_nombre_corto
                    FROM permiso p 
                    INNER JOIN aplicacion a ON p.per_aplicacion = a.ap_id
                    WHERE p.per_situacion = 1
                    ORDER BY p.per_fecha DESC";
            $permisos = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode($permisos);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar los permisos'
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        try {

            if (empty($_POST['per_id'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de permiso requerido'
                ]);
                return;
            }

            if (empty($_POST['per_aplicacion'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar una aplicación'
                ]);
                return;
            }

            if (empty($_POST['per_nombre'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El nombre del permiso es obligatorio'
                ]);
                return;
            }

            if (strlen($_POST['per_nombre']) > 150) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El nombre del permiso no puede exceder 150 caracteres'
                ]);
                return;
            }

            if (empty($_POST['per_clave'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La clave del permiso es obligatoria'
                ]);
                return;
            }

            if (strlen($_POST['per_clave']) > 250) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La clave del permiso no puede exceder 250 caracteres'
                ]);
                return;
            }

            if (empty($_POST['per_descripcion'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La descripción del permiso es obligatoria'
                ]);
                return;
            }

            if (strlen($_POST['per_descripcion']) > 250) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La descripción no puede exceder 250 caracteres'
                ]);
                return;
            }

            $sql = "SELECT * FROM permiso WHERE per_clave = '{$_POST['per_clave']}' AND per_situacion = '1' AND per_id != {$_POST['per_id']}";
            $permisoExistente = self::fetchArray($sql);

            if (!empty($permisoExistente)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe otro permiso con esta clave'
                ]);
                return;
            }

            $permiso = Permisos::find($_POST['per_id']);
            if (!$permiso) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Permiso no encontrado'
                ]);
                return;
            }

            $permiso->sincronizar([
                'per_aplicacion' => filter_var($_POST['per_aplicacion'], FILTER_SANITIZE_NUMBER_INT),
                'per_nombre' => ucwords(strtolower(trim(htmlspecialchars($_POST['per_nombre'])))),
                'per_clave' => strtoupper(trim(htmlspecialchars($_POST['per_clave']))),
                'per_descripcion' => trim(htmlspecialchars($_POST['per_descripcion']))
            ]);

            $resultado = $permiso->actualizar();

            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Permiso modificado exitosamente'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al modificar el permiso'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error en el servidor: ' . $e->getMessage()
            ]);
        }
    }

    public static function eliminarAPI()
    {
        getHeadersApi();

        try {
  
            if (empty($_GET['id'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de permiso requerido'
                ]);
                return;
            }

            $id = $_GET['id'];

            $permiso = Permisos::find($id);
            if (!$permiso) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Permiso no encontrado'
                ]);
                return;
            }

            $resultado = Permisos::EliminarPermiso($id);

            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Permiso eliminado exitosamente'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al eliminar el permiso'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error en el servidor: ' . $e->getMessage()
            ]);
        }
    }
}
