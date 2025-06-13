<?php

namespace Model;

class AsignacionPermisos extends ActiveRecord {

    public static $tabla = 'asig_permisos';
    public static $columnasDB = [
        'asig_usuario',
        'asig_app',
        'asig_permiso',
        'asig_quitar_fechaPermiso',
        'asig_usuario_asigno',
        'asig_motivo',
        'asig_situacion'
    ];

    public static $idTabla = 'asig_id';
    public $asig_id;
    public $asig_usuario;
    public $asig_app;
    public $asig_permiso;
    public $asig_fecha;
    public $asig_quitar_fechaPermiso;
    public $asig_usuario_asigno;
    public $asig_motivo;
    public $asig_situacion;

    public function __construct($args = []){
        $this->asig_id = $args['asig_id'] ?? null;
        $this->asig_usuario = $args['asig_usuario'] ?? 0;
        $this->asig_app = $args['asig_app'] ?? 0;
        $this->asig_permiso = $args['asig_permiso'] ?? 0;
        $this->asig_fecha = $args['asig_fecha'] ?? date('Y-m-d');
        $this->asig_quitar_fechaPermiso = $args['asig_quitar_fechaPermiso'] ?? null;
        $this->asig_usuario_asigno = $args['asig_usuario_asigno'] ?? 0;
        $this->asig_motivo = $args['asig_motivo'] ?? '';
        $this->asig_situacion = $args['asig_situacion'] ?? 1;
    }

    public static function EliminarAsignacion($id){
        $sql = "DELETE FROM asig_permisos WHERE asig_id = $id";
        return self::SQL($sql);
    }

    public static function ObtenerActivas(){
        $sql = "SELECT * FROM asig_permisos WHERE asig_situacion = 1 ORDER BY asig_fecha DESC";
        return self::fetchArray($sql);
    }

    public static function ObtenerPorUsuario($usuario_id){
        $sql = "SELECT * FROM asig_permisos WHERE asig_usuario = $usuario_id AND asig_situacion = 1";
        return self::fetchArray($sql);
    }

    public static function ObtenerPorUsuarioApp($usuario_id, $app_id){
        $sql = "SELECT * FROM asig_permisos WHERE asig_usuario = $usuario_id AND asig_app = $app_id AND asig_situacion = 1";
        return self::fetchArray($sql);
    }

    public static function VerificarPermiso($usuario_id, $app_id, $permiso_id){
        $sql = "SELECT COUNT(*) as total FROM asig_permisos WHERE asig_usuario = $usuario_id AND asig_app = $app_id AND asig_permiso = $permiso_id AND asig_situacion = 1";
        $resultado = self::fetchArray($sql);
        return $resultado[0]['total'] > 0;
    }
}