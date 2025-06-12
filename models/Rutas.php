<?php

namespace Model;

class Rutas extends ActiveRecord {

    public static $tabla = 'rutas';
    public static $columnasDB = [
        'ruta_app',
        'ruta_nombre',
        'ruta_descripcion',
        'ruta_situacion'
    ];

    public static $idTabla = 'ruta_id';
    public $ruta_id;
    public $ruta_app;
    public $ruta_nombre;
    public $ruta_descripcion;
    public $ruta_situacion;

    public function __construct($args = []){
        $this->ruta_id = $args['ruta_id'] ?? null;
        $this->ruta_app = $args['ruta_app'] ?? 0;
        $this->ruta_nombre = $args['ruta_nombre'] ?? '';
        $this->ruta_descripcion = $args['ruta_descripcion'] ?? '';
        $this->ruta_situacion = $args['ruta_situacion'] ?? 1;
    }

    public static function EliminarRuta($id){
        $sql = "DELETE FROM rutas WHERE ruta_id = $id";
        return self::SQL($sql);
    }

    public static function ObtenerActivas(){
        $sql = "SELECT * FROM rutas WHERE ruta_situacion = 1 ORDER BY ruta_nombre";
        return self::SQL($sql);
    }

    public static function ObtenerPorAplicacion($app_id){
        $sql = "SELECT * FROM rutas WHERE ruta_app = $app_id AND ruta_situacion = 1 ORDER BY ruta_nombre";
        return self::SQL($sql);
    }

    public static function BuscarPorNombre($nombre){
        $sql = "SELECT * FROM rutas WHERE ruta_nombre = '$nombre' AND ruta_situacion = 1";
        return self::SQL($sql);
    }

    public static function ObtenerConAplicacion($ruta_id){
        $sql = "SELECT r.*, a.ap_nombre_largo, a.ap_nombre_medium, a.ap_nombre_corto 
                FROM rutas r 
                INNER JOIN aplicacion a ON r.ruta_app = a.ap_id 
                WHERE r.ruta_id = $ruta_id AND r.ruta_situacion = 1";
        return self::SQL($sql);
    }
}
