<?php

namespace Model;

use Model\ActiveRecord;

class Usuarios extends ActiveRecord {
    
    public static $tabla = 'usuario';
    public static $idTabla = 'us_id';
    public static $columnasDB = [
        'us_nom1',
        'us_nom2',
        'us_ape1',
        'us_ape2',
        'us_tel',
        'us_direc',
        'us_dpi',
        'us_correo',
        'us_contra',
        'us_token',
        'us_fecha_creacion',
        'us_fecha_contra',
        'us_fotografia',
        'us_situacion'
    ];
    
    public $us_id;
    public $us_nom1;
    public $us_nom2;
    public $us_ape1;
    public $us_ape2;
    public $us_tel;
    public $us_direc;
    public $us_dpi;
    public $us_correo;
    public $us_contra;
    public $us_token;
    public $us_fecha_creacion;
    public $us_fecha_contra;
    public $us_fotografia;
    public $us_situacion;
    
    public function __construct($usuario = [])
    {
        $this->us_id = $usuario['us_id'] ?? null;
        $this->us_nom1 = $usuario['us_nom1'] ?? '';
        $this->us_nom2 = $usuario['us_nom2'] ?? '';
        $this->us_ape1 = $usuario['us_ape1'] ?? '';
        $this->us_ape2 = $usuario['us_ape2'] ?? '';
        $this->us_tel = $usuario['us_tel'] ?? 0;
        $this->us_direc = $usuario['us_direc'] ?? '';
        $this->us_dpi = $usuario['us_dpi'] ?? '';
        $this->us_correo = $usuario['us_correo'] ?? '';
        $this->us_contra = $usuario['us_contra'] ?? '';
        $this->us_token = $usuario['us_token'] ?? '';
        $this->us_fecha_creacion = $usuario['us_fecha_creacion'] ?? '';
        $this->us_fecha_contra = $usuario['us_fecha_contra'] ?? '';
        $this->us_fotografia = $usuario['us_fotografia'] ?? null;
        $this->us_situacion = $usuario['us_situacion'] ?? 1;
    }

    public static function EliminarUsuarios($id){
        $sql = "DELETE FROM usuario WHERE us_id = $id";
        return self::SQL($sql);
    }
}
