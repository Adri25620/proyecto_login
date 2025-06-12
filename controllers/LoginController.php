<?php

namespace Controllers;

use Model\ActiveRecord;
use MVC\Router;
use Exception;

class LoginController extends ActiveRecord
{

    public function index(Router $router)
    {
        $router->render('login/index', [], 'layout/login');

    }


    public static function inicio(Router $router)
    {
        hasPermission(['ADMIN']);
        hasPermissionApi(['ADMIN']);
        $router->render('login/index', [], 'layout/layouts');
    }



    public static function logout()
    {
        isAuth();
        $_SESSION = [];
        $login = $_ENV['APP_NAME'];
        header("Location: /$login");
    }



    public static function login() {
        getHeadersApi();
        
        try {
            $dpi = htmlspecialchars($_POST['us_dpi']);
            $contrasena = htmlspecialchars($_POST['us_contra']);

            $queryExisteUser = "SELECT us_id, us_nom1, us_contra FROM usuario WHERE us_dpi = '$dpi' AND us_situacion = 1";

            $existeUsuario = ActiveRecord::fetchArray($queryExisteUser)[0];

            if ($existeUsuario) {
                $passDB = $existeUsuario['us_contra'];

                if (password_verify($contrasena, $passDB)) {
                    session_start();

                    $nombreUser = $existeUsuario['us_nom1'];
                    
                    $_SESSION['user'] = $nombreUser;
                    $_SESSION['dpi'] = $dpi;
                    
                    // COMENTAR ESTO HASTA QUE TENGAS DATOS DE PERMISOS:
                    /*
                    $usuarioId = $existeUsuario['us_id'];
                    $sqlpermisos = "SELECT p.permiso_nombre FROM asig_permisos ap
                                  INNER JOIN permiso p ON p.permiso_id = ap.asignacion_permiso_id
                                  WHERE ap.asig_usuario_id = $usuarioId 
                                  AND ap.asignacion_situacion = 1";

                    $permiso = ActiveRecord::fetchArray($sqlpermisos)[0]['permiso_nombre'];
                    $_SESSION['rol'] = $permiso;
                    */

                    echo json_encode([
                        'codigo' => 1,
                        'mensaje' => 'Usuario logueado exitosamente',
                    ]);
                } else {
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'La contraseña que ingreso es incorrecta',
                    ]);
                }
            } else {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El usuario que intenta loguearse NO EXISTE',
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al intentar loguearse',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    
}