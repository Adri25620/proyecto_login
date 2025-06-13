<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\Usuarios;
use MVC\Router;

class RegistroController extends ActiveRecord
{
    public function index(Router $router)
    {
        session_start();
        
        $router->render('usuarios/index', [], 'layout/layouts');
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        try {
            if (empty($_POST['us_nom1'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El primer nombre es obligatorio'
                ]);
                return;
            }

            if (empty($_POST['us_ape1'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El primer apellido es obligatorio'
                ]);
                return;
            }

            if (empty($_POST['us_tel']) || strlen($_POST['us_tel']) != 8) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El teléfono debe tener exactamente 8 dígitos'
                ]);
                return;
            }

            if (empty($_POST['us_dpi']) || strlen($_POST['us_dpi']) != 13) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El DPI debe tener exactamente 13 dígitos'
                ]);
                return;
            }

            if (empty($_POST['us_direc'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La dirección es obligatoria'
                ]);
                return;
            }

            if (empty($_POST['us_correo']) || !filter_var($_POST['us_correo'], FILTER_VALIDATE_EMAIL)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El correo electrónico es obligatorio y debe ser válido'
                ]);
                return;
            }

            if (empty($_POST['us_contra']) || strlen($_POST['us_contra']) < 8) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La contraseña debe tener al menos 8 caracteres'
                ]);
                return;
            }

            if (!preg_match('/[A-Z]/', $_POST['us_contra'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La contraseña debe contener al menos una letra mayúscula'
                ]);
                return;
            }

            if (!preg_match('/[a-z]/', $_POST['us_contra'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La contraseña debe contener al menos una letra minúscula'
                ]);
                return;
            }

            if (!preg_match('/[0-9]/', $_POST['us_contra'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La contraseña debe contener al menos un número'
                ]);
                return;
            }

            if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};:\'"\\|,.<>\/?]/', $_POST['us_contra'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La contraseña debe contener al menos un carácter especial'
                ]);
                return;
            }

            if ($_POST['us_contra'] !== $_POST['us_confirmar_contra']) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Las contraseñas no coinciden'
                ]);
                return;
            }



            $fotografiaRuta = '';
            try {
                $fotografiaRuta = self::procesarFotografia();
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => $e->getMessage()
                ]);
                return;
            }

            $usuario = new Usuarios([
                'us_nom1' => ucwords(strtolower(trim(htmlspecialchars($_POST['us_nom1'])))),
                'us_nom2' => !empty($_POST['us_nom2']) ? ucwords(strtolower(trim(htmlspecialchars($_POST['us_nom2'])))) : '',
                'us_ape1' => ucwords(strtolower(trim(htmlspecialchars($_POST['us_ape1'])))),
                'us_ape2' => !empty($_POST['us_ape2']) ? ucwords(strtolower(trim(htmlspecialchars($_POST['us_ape2'])))) : '',
                'us_tel' => filter_var($_POST['us_tel'], FILTER_SANITIZE_NUMBER_INT),
                'us_direc' => trim(htmlspecialchars($_POST['us_direc'])),
                'us_dpi' => filter_var($_POST['us_dpi'], FILTER_SANITIZE_NUMBER_INT),
                'us_correo' => filter_var($_POST['us_correo'], FILTER_SANITIZE_EMAIL),
                'us_contra' => password_hash($_POST['us_contra'], PASSWORD_DEFAULT),
                'us_token' => bin2hex(random_bytes(32)),
                'us_fotografia' => $fotografiaRuta,
                'us_situacion' => 1
            ]);

            $resultado = $usuario->crear();

            if ($resultado['resultado'] == 1) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Usuario registrado exitosamente',
                    'usuario_id' => $resultado['id']
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al registrar el usuario'
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar el usuario',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarAPI()
    {
        try {
            $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
            $fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

            $condiciones = ["us_situacion = 1"];

            if ($fecha_inicio) {
                $condiciones[] = "us_fecha_creacion >= '{$fecha_inicio}'";
            }

            if ($fecha_fin) {
                $condiciones[] = "us_fecha_creacion <= '{$fecha_fin}'";
            }

            $where = implode(" AND ", $condiciones);
            $sql = "SELECT us_id, us_nom1, us_nom2, us_ape1, us_ape2, us_tel, us_direc, us_dpi, us_correo, us_fecha_creacion, us_fotografia, us_situacion 
                    FROM usuario 
                    WHERE $where 
                    ORDER BY us_fecha_creacion DESC";
            
            $usuarios = self::fetchArray($sql);

            foreach ($usuarios as &$usuario) {
                if (!empty($usuario['us_fotografia'])) {
                    $usuario['foto_url'] = 'data:image/jpeg;base64,' . $usuario['us_fotografia'];
                } else {
                    $usuario['foto_url'] = null;
                }
                $usuario['nombre_completo'] = trim($usuario['us_nom1'] . ' ' . $usuario['us_nom2'] . ' ' . $usuario['us_ape1'] . ' ' . $usuario['us_ape2']);
            }

            if (empty($usuarios)) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se encontraron usuarios registrados',
                    'data' => []
                ]);
            } else {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Usuarios obtenidos satisfactoriamente',
                    'data' => $usuarios
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los usuarios',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        try {
            $usuario_id = $_POST['us_id'];

            if (empty($usuario_id)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de usuario requerido'
                ]);
                return;
            }

            if (empty($_POST['us_nom1']) || empty($_POST['us_ape1'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Primer nombre y primer apellido son obligatorios'
                ]);
                return;
            }

            if (empty($_POST['us_tel']) || strlen($_POST['us_tel']) != 8) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El teléfono debe tener exactamente 8 dígitos'
                ]);
                return;
            }

            if (empty($_POST['us_dpi']) || strlen($_POST['us_dpi']) != 13) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El DPI debe tener exactamente 13 dígitos'
                ]);
                return;
            }

            if (empty($_POST['us_correo']) || !filter_var($_POST['us_correo'], FILTER_VALIDATE_EMAIL)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El correo electrónico es obligatorio y debe ser válido'
                ]);
                return;
            }

            $usuario = Usuarios::find($usuario_id);
            if (!$usuario) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Usuario no encontrado'
                ]);
                return;
            }



            $datosActualizar = [
                'us_nom1' => ucwords(strtolower(trim(htmlspecialchars($_POST['us_nom1'])))),
                'us_nom2' => !empty($_POST['us_nom2']) ? ucwords(strtolower(trim(htmlspecialchars($_POST['us_nom2'])))) : '',
                'us_ape1' => ucwords(strtolower(trim(htmlspecialchars($_POST['us_ape1'])))),
                'us_ape2' => !empty($_POST['us_ape2']) ? ucwords(strtolower(trim(htmlspecialchars($_POST['us_ape2'])))) : '',
                'us_tel' => filter_var($_POST['us_tel'], FILTER_SANITIZE_NUMBER_INT),
                'us_direc' => trim(htmlspecialchars($_POST['us_direc'])),
                'us_dpi' => filter_var($_POST['us_dpi'], FILTER_SANITIZE_NUMBER_INT),
                'us_correo' => filter_var($_POST['us_correo'], FILTER_SANITIZE_EMAIL)
            ];

            if (isset($_FILES['us_fotografia']) && $_FILES['us_fotografia']['error'] !== UPLOAD_ERR_NO_FILE) {
                try {
                    $fotografiaRuta = self::procesarFotografia();
                    if (!empty($fotografiaRuta)) {
                        $datosActualizar['us_fotografia'] = $fotografiaRuta;
                    }
                } catch (Exception $e) {
                    error_log('Error procesando fotografía en modificación: ' . $e->getMessage());
                }
            }

            $usuario->sincronizar($datosActualizar);
            $resultado = $usuario->guardar();

            if ($resultado['resultado']) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Usuario modificado exitosamente'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al modificar usuario'
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar el usuario',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function eliminarAPI()
    {
        try {
            $usuario_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

            if (!$usuario_id) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de usuario inválido'
                ]);
                return;
            }

            $usuario = Usuarios::find($usuario_id);
            if (!$usuario) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Usuario no encontrado'
                ]);
                return;
            }

            $resultado = Usuarios::EliminarUsuarios($usuario_id);

            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Usuario eliminado exitosamente'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al eliminar usuario'
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar el usuario',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    private static function procesarFotografia()
    {
        if (!isset($_FILES['us_fotografia']) || $_FILES['us_fotografia']['error'] === UPLOAD_ERR_NO_FILE) {
            return '';
        }

        $archivo = $_FILES['us_fotografia'];

        if ($archivo['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Error al subir el archivo: ' . $archivo['error']);
        }

        $tamañoMaximo = 2 * 1024 * 1024;
        if ($archivo['size'] > $tamañoMaximo) {
            throw new Exception('El archivo es muy grande. Máximo permitido: 2MB');
        }

        $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $tipoMime = finfo_file($finfo, $archivo['tmp_name']);
        finfo_close($finfo);

        if (!in_array($tipoMime, $tiposPermitidos)) {
            throw new Exception('Tipo de archivo no permitido. Solo se permiten: JPG, JPEG, PNG');
        }

        $directorioDestino = __DIR__ . '/../storage/fotosUsuarios/';

        if (!is_dir($directorioDestino)) {
            if (!mkdir($directorioDestino, 0755, true)) {
                throw new Exception('No se pudo crear el directorio de fotografías');
            }
        }

        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombreArchivo = 'usuario_' . uniqid() . '_' . time() . '.' . $extension;
        $rutaCompleta = $directorioDestino . $nombreArchivo;

        if (!move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
            throw new Exception('Error al guardar el archivo en el servidor');
        }

        $fotoBase64 = base64_encode(file_get_contents($rutaCompleta));

        return $fotoBase64;
    }
}