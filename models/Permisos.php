<?php

namespace Model;

use Model\ActiveRecord;

class Permisos extends ActiveRecord {
    
    public static $tabla = 'permiso';
    public static $idTabla = 'per_id';
    public static $columnasDB = [
        'per_aplicacion',
        'per_nombre',
        'per_clave',
        'per_descripcion',
        'per_fecha',
        'per_situacion'
    ];
    
    public $per_id;
    public $per_aplicacion;
    public $per_nombre;
    public $per_clave;
    public $per_descripcion;
    public $per_fecha;
    public $per_situacion;
    
    public function __construct($permiso = [])
    {
        $this->per_id = $permiso['per_id'] ?? null;
        $this->per_aplicacion = $permiso['per_aplicacion'] ?? 0;
        $this->per_nombre = $permiso['per_nombre'] ?? '';
        $this->per_clave = $permiso['per_clave'] ?? '';
        $this->per_descripcion = $permiso['per_descripcion'] ?? '';
        $this->per_fecha = $permiso['per_fecha'] ?? '';
        $this->per_situacion = $permiso['per_situacion'] ?? 1;
    }

    public static function EliminarPermiso($id){
        $sql = "DELETE FROM permiso WHERE per_id = $id";
        return self::SQL($sql);
    }
}