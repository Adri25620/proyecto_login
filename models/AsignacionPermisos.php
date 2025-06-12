<?php

namespace Model;

class AsignacionPermisos extends ActiveRecord {

    public static $tabla = 'asig_permisos';
    public static $columnasDB = [
        'asig_usuario_id',
        'asig_app_id',
        'asig_permiso_id',
        'asig_fecha',
        'asig_usuario_asigno',
        'asig_motivo',
        'asig_situacion'
    ];

    public static $idTabla = 'asig_id';
    public $asig_id;
    public $asig_usuario_id;
    public $asig_app_id;
    public $asig_permiso_id;
    public $asig_fecha;
    public $asig_usuario_asigno;
    public $asig_motivo;
    public $asig_situacion;

    public function __construct($args = []){
        $this->asig_id = $args['asig_id'] ?? null;
        $this->asig_usuario_id = $args['asig_usuario_id'] ?? 0;
        $this->asig_app_id = $args['asig_app_id'] ?? 0;
        $this->asig_permiso_id = $args['asig_permiso_id'] ?? 0;
        $this->asig_fecha = $args['asig_fecha'] ?? date('Y-m-d');
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
        return self::SQL($sql);
    }

    public static function ObtenerPorUsuario($usuario_id){
        $sql = "SELECT * FROM asig_permisos WHERE asig_usuario_id = $usuario_id AND asig_situacion = 1";
        return self::SQL($sql);
    }

    public static function ObtenerPorUsuarioApp($usuario_id, $app_id){
        $sql = "SELECT * FROM asig_permisos WHERE asig_usuario_id = $usuario_id AND asig_app_id = $app_id AND asig_situacion = 1";
        return self::SQL($sql);
    }

    public static function VerificarPermiso($usuario_id, $app_id, $permiso_id){
        $sql = "SELECT COUNT(*) as total FROM asig_permisos WHERE asig_usuario_id = $usuario_id AND asig_app_id = $app_id AND asig_permiso_id = $permiso_id AND asig_situacion = 1";
        $resultado = self::SQL($sql);
        $fila = $resultado->fetch_assoc();
        return $fila['total'] > 0;
    }
}
