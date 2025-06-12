CREATE TABLE usuario(
us_id SERIAL PRIMARY KEY,
us_nom1 VARCHAR (50),
us_nom2 VARCHAR (50),
us_ape1 VARCHAR (50),
us_ape2 VARCHAR (50),
us_tel INT, 
us_direc VARCHAR (150),
us_dpi VARCHAR (13),
us_correo VARCHAR (100),
us_contra LVARCHAR (1056),
us_token LVARCHAR (1056),
us_fecha_creacion DATE DEFAULT TODAY,
us_fecha_contra DATE DEFAULT TODAY,
us_fotografia LVARCHAR (2056),
us_situacion SMALLINT DEFAULT 1
);

CREATE TABLE aplicacion(
ap_id SERIAL PRIMARY KEY,
ap_nombre_largo VARCHAR (250),
ap_nombre_medium VARCHAR (150),
ap_nombre_corto VARCHAR (50),
ap_fecha_creacion DATE DEFAULT TODAY,
ap_situacion SMALLINT DEFAULT 1
);

CREATE TABLE permiso (
per_id SERIAL PRIMARY KEY,
per_usuario INTEGER,
per_aplicacion INTEGER,
per_nombre VARCHAR(150),
per_clave VARCHAR(250),
per_desc VARCHAR(250),
per_tipo VARCHAR(50) DEFAULT 'FUNCIONAL',  
per_fecha DATE DEFAULT TODAY,
per_usuario_asign INTEGER,   
per_motivo VARCHAR(250),               
per_situacion SMALLINT DEFAULT 1,
FOREIGN KEY (per_usuario) REFERENCES usuario(us_id),
FOREIGN KEY (per_aplicacion) REFERENCES aplicacion(ap_id),
FOREIGN KEY (per_usuario_asign) REFERENCES usuario(us_id)
);

CREATE TABLE asig_permisos(
asig_id SERIAL PRIMARY KEY,
asig_usuario_id INT,
asig_app_id INT,
asig_permiso_id INT,
asig_fecha DATE DEFAULT TODAY,
asig_usuario_asigno INT,
asig_motivo VARCHAR (250),
asig_situacion SMALLINT DEFAULT 1,
FOREIGN KEY (asig_usuario_id) REFERENCES usuario(us_id),
FOREIGN KEY (asig_app_id) REFERENCES aplicacion(ap_id),
FOREIGN KEY (asig_permiso_id) REFERENCES permiso(per_id)
);

CREATE TABLE historial_act(
his_id SERIAL PRIMARY KEY,
his_usuario_id INT,
his_fecha DATETIME YEAR TO MINUTE,
his_ruta INT,
his_ejecucion LVARCHAR (1056),
his_situacion SMALLINT DEFAULT 1,
FOREIGN KEY (his_usuario_id) REFERENCES usuario(us_id),
FOREIGN KEY (his_ruta) REFERENCES rutas(ruta_id)
);

CREATE TABLE rutas(
ruta_id SERIAL PRIMARY KEY,
ruta_app INT,
ruta_nombre LVARCHAR (1056),
ruta_descripcion VARCHAR (250),
ruta_situacion SMALLINT DEFAULT 1,
FOREIGN KEY (ruta_app) REFERENCES aplicacion(ap_id)
);
