<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Aplicacion;
use Model\Permisos;
use Model\Usuarios;
use Model\AsignacionPermisos;

class AsignacionController extends ActiveRecord
{

    public static function index(Router $router)
    {
        $usuarios = Usuarios::where('us_situacion', 1);
        $aplicaciones = Aplicacion::where('ap_situacion', 1);
        $permisos = Permisos::where('per_situacion', 1);

        $router->render('asignacion/index', [
            'usuarios' => $usuarios,
            'aplicaciones' => $aplicaciones,
            'permisos' => $permisos
        ], 'layout/layouts');
    }

    public static function obtenerPermisosAPI()
    {
        getHeadersApi();

        try {
            if (empty($_GET['app_id'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de aplicación requerido'
                ]);
                return;
            }

            $app_id = filter_var($_GET['app_id'], FILTER_SANITIZE_NUMBER_INT);
            
            $sql = "SELECT per_id, per_nombre, per_clave, per_descripcion 
                    FROM permiso 
                    WHERE per_aplicacion = {$app_id} AND per_situacion = 1 
                    ORDER BY per_nombre ASC";
            
            $permisos = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode($permisos);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los permisos: ' . $e->getMessage()
            ]);
        }
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        error_log("Llegó a guardarAPI de asignación");
        error_log("POST data: " . print_r($_POST, true));

        try {
            // Validaciones
            if (empty($_POST['asig_usuario'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar un usuario'
                ]);
                return;
            }

            if (empty($_POST['asig_app'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar una aplicación'
                ]);
                return;
            }

            if (empty($_POST['asig_permiso'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar un permiso'
                ]);
                return;
            }

            if (empty($_POST['asig_motivo'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El motivo de la asignación es obligatorio'
                ]);
                return;
            }

            if (strlen($_POST['asig_motivo']) > 250) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El motivo no puede exceder 250 caracteres'
                ]);
                return;
            }

            // Verificar si ya existe la asignación
            $usuario_id = filter_var($_POST['asig_usuario'], FILTER_SANITIZE_NUMBER_INT);
            $app_id = filter_var($_POST['asig_app'], FILTER_SANITIZE_NUMBER_INT);
            $permiso_id = filter_var($_POST['asig_permiso'], FILTER_SANITIZE_NUMBER_INT);

            $yaExiste = AsignacionPermisos::VerificarPermiso($usuario_id, $app_id, $permiso_id);
            if ($yaExiste) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Este usuario ya tiene asignado este permiso para esta aplicación'
                ]);
                return;
            }

            $asignacion = new AsignacionPermisos([
                'asig_usuario' => $usuario_id,
                'asig_app' => $app_id,
                'asig_permiso' => $permiso_id,
                'asig_fecha' => date('Y-m-d'),
                'asig_usuario_asigno' => 1,
                'asig_motivo' => trim(htmlspecialchars($_POST['asig_motivo'])),
                'asig_situacion' => 1
            ]);

            $resultado = $asignacion->crear();

            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Permiso asignado exitosamente'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al asignar el permiso'
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

        error_log("Llegó a buscarAPI de asignación");

        try {
            $sql = "SELECT 
                        ap.asig_id,
                        ap.asig_usuario,
                        ap.asig_app,
                        ap.asig_permiso,
                        ap.asig_fecha,
                        ap.asig_motivo,
                        ap.asig_situacion,
                        u.us_nom1,
                        u.us_ape1,
                        u.us_correo,
                        a.ap_nombre_largo,
                        p.per_nombre,
                        p.per_clave,
                        p.per_descripcion
                    FROM asig_permisos ap
                    INNER JOIN usuarios u ON ap.asig_usuario = u.us_id
                    INNER JOIN aplicacion a ON ap.asig_app = a.ap_id
                    INNER JOIN permiso p ON ap.asig_permiso = p.per_id
                    WHERE ap.asig_situacion = 1
                    ORDER BY ap.asig_fecha DESC";

            $asignaciones = self::fetchArray($sql);
            
            error_log("Asignaciones encontradas: " . count($asignaciones));

            http_response_code(200);
            echo json_encode($asignaciones);

        } catch (Exception $e) {
            error_log("Error en buscarAPI: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar las asignaciones: ' . $e->getMessage()
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        try {
            // Validaciones
            if (empty($_POST['asig_id'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de asignación requerido'
                ]);
                return;
            }

            if (empty($_POST['asig_usuario'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar un usuario'
                ]);
                return;
            }

            if (empty($_POST['asig_app'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar una aplicación'
                ]);
                return;
            }

            if (empty($_POST['asig_permiso'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar un permiso'
                ]);
                return;
            }

            if (empty($_POST['asig_motivo'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El motivo de la asignación es obligatorio'
                ]);
                return;
            }

            if (strlen($_POST['asig_motivo']) > 250) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El motivo no puede exceder 250 caracteres'
                ]);
                return;
            }

            // Verificar si ya existe otra asignación igual (excluyendo la actual)
            $usuario_id = filter_var($_POST['asig_usuario'], FILTER_SANITIZE_NUMBER_INT);
            $app_id = filter_var($_POST['asig_app'], FILTER_SANITIZE_NUMBER_INT);
            $permiso_id = filter_var($_POST['asig_permiso'], FILTER_SANITIZE_NUMBER_INT);
            $asig_id = filter_var($_POST['asig_id'], FILTER_SANITIZE_NUMBER_INT);

            $sql = "SELECT COUNT(*) as total FROM asig_permisos 
                    WHERE asig_usuario = {$usuario_id} 
                    AND asig_app = {$app_id} 
                    AND asig_permiso = {$permiso_id} 
                    AND asig_situacion = 1 
                    AND asig_id != {$asig_id}";
            
            $resultado = self::fetchArray($sql);
            if ($resultado[0]['total'] > 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe otra asignación igual para este usuario'
                ]);
                return;
            }

            $asignacion = AsignacionPermisos::find($asig_id);
            if (!$asignacion) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Asignación no encontrada'
                ]);
                return;
            }

            $asignacion->sincronizar([
                'asig_usuario' => $usuario_id,
                'asig_app' => $app_id,
                'asig_permiso' => $permiso_id,
                'asig_motivo' => trim(htmlspecialchars($_POST['asig_motivo']))
            ]);

            $resultado = $asignacion->actualizar();

            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Asignación modificada exitosamente'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al modificar la asignación'
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
                    'mensaje' => 'ID de asignación requerido'
                ]);
                return;
            }

            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

            $asignacion = AsignacionPermisos::find($id);
            if (!$asignacion) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Asignación no encontrada'
                ]);
                return;
            }

            $resultado = AsignacionPermisos::EliminarAsignacion($id);

            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Asignación eliminada exitosamente'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al eliminar la asignación'
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