<?php

namespace Model;

class HistorialAct extends ActiveRecord {

    public static $tabla = 'historial_act';
    public static $columnasDB = [
        'his_usuario_id',
        'his_fecha',
        'his_ruta',
        'his_ejecucion',
        'his_situacion'
    ];

    public static $idTabla = 'his_id';
    public $his_id;
    public $his_usuario_id;
    public $his_fecha;
    public $his_ruta;
    public $his_ejecucion;
    public $his_situacion;

    public function __construct($args = []){
        $this->his_id = $args['his_id'] ?? null;
        $this->his_usuario_id = $args['his_usuario_id'] ?? 0;
        $this->his_fecha = $args['his_fecha'] ?? date('Y-m-d H:i');
        $this->his_ruta = $args['his_ruta'] ?? 0;
        $this->his_ejecucion = $args['his_ejecucion'] ?? '';
        $this->his_situacion = $args['his_situacion'] ?? 1;
    }

    public static function EliminarHistorial($id){
        $sql = "DELETE FROM historial_act WHERE his_id = $id";
        return self::SQL($sql);
    }

    public static function ObtenerActivo(){
        $sql = "SELECT * FROM historial_act WHERE his_situacion = 1 ORDER BY his_fecha DESC";
        return self::SQL($sql);
    }

    public static function ObtenerPorUsuario($usuario_id){
        $sql = "SELECT * FROM historial_act WHERE his_usuario_id = $usuario_id AND his_situacion = 1 ORDER BY his_fecha DESC";
        return self::SQL($sql);
    }

    public static function ObtenerPorRuta($ruta_id){
        $sql = "SELECT * FROM historial_act WHERE his_ruta = $ruta_id AND his_situacion = 1 ORDER BY his_fecha DESC";
        return self::SQL($sql);
    }

    public static function ObtenerPorFechas($fecha_inicio, $fecha_fin){
        $sql = "SELECT * FROM historial_act WHERE his_fecha BETWEEN '$fecha_inicio' AND '$fecha_fin' AND his_situacion = 1 ORDER BY his_fecha DESC";
        return self::SQL($sql);
    }

    public static function RegistrarActividad($usuario_id, $ruta_id, $ejecucion){
        $sql = "INSERT INTO historial_act (his_usuario_id, his_ruta, his_ejecucion, his_fecha, his_situacion) 
                VALUES ($usuario_id, $ruta_id, '$ejecucion', CURRENT, 1)";
        return self::SQL($sql);
    }
}
