<?php

namespace Model;

use Model\ActiveRecord;

class Aplicacion extends ActiveRecord {
    
    public static $tabla = 'aplicacion';
    public static $idTabla = 'ap_id';
    public static $columnasDB = [
        'ap_nombre_largo',
        'ap_nombre_medium',
        'ap_nombre_corto',
        'ap_fecha_creacion',
        'ap_situacion'
    ];
    
    public $ap_id;
    public $ap_nombre_largo;
    public $ap_nombre_medium;
    public $ap_nombre_corto;
    public $ap_fecha_creacion;
    public $ap_situacion;
    
    public function __construct($aplicacion = []){
        $this->ap_id = $aplicacion['ap_id'] ?? null;
        $this->ap_nombre_largo = $aplicacion['ap_nombre_largo'] ?? '';
        $this->ap_nombre_medium = $aplicacion['ap_nombre_medium'] ?? '';
        $this->ap_nombre_corto = $aplicacion['ap_nombre_corto'] ?? '';
        $this->ap_fecha_creacion = $aplicacion['ap_fecha_creacion'] ?? '';
        $this->ap_situacion = $aplicacion['ap_situacion'] ?? 1;
    }

    public static function EliminarAplicaciones($id){
        $sql = "UPDATE aplicacion SET ap_situacion = 0 WHERE ap_id = $id";
        return self::SQL($sql);
    }
}
