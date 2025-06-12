<?php

namespace Model;

use Model\ActiveRecord;

class Permisos extends ActiveRecord {
    
    public static $tabla = 'permiso';
    public static $idTabla = 'per_id';
    public static $columnasDB = [
        'per_usuario',
        'per_aplicacion',
        'per_nombre',
        'per_clave',
        'per_desc',
        'per_tipo',
        'per_fecha',
        'per_usuario_asign',
        'per_motivo',
        'per_situacion'
    ];
    
    public $per_id;
    public $per_usuario;
    public $per_aplicacion;
    public $per_nombre;
    public $per_clave;
    public $per_desc;
    public $per_tipo;
    public $per_fecha;
    public $per_usuario_asign;
    public $per_motivo;
    public $per_situacion;
    
    public function __construct($permiso = [])
    {
        $this->per_id = $permiso['per_id'] ?? null;
        $this->per_usuario = $permiso['per_usuario'] ?? 0;
        $this->per_aplicacion = $permiso['per_aplicacion'] ?? 0;
        $this->per_nombre = $permiso['per_nombre'] ?? '';
        $this->per_clave = $permiso['per_clave'] ?? '';
        $this->per_desc = $permiso['per_desc'] ?? '';
        $this->per_tipo = $permiso['per_tipo'] ?? 'FUNCIONAL';
        $this->per_fecha = $permiso['per_fecha'] ?? '';
        $this->per_usuario_asign = $permiso['per_usuario_asign'] ?? 0;
        $this->per_motivo = $permiso['per_motivo'] ?? '';
        $this->per_situacion = $permiso['per_situacion'] ?? 1;
    }

    public static function EliminarPermiso($id){
        $sql = "DELETE FROM permiso WHERE per_id = $id";
        return self::SQL($sql);
    }
}
