create database login

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
us_fecha_creacion datetime year to minute default current year to minute,
us_fecha_contra datetime year to minute default current year to minute,
us_fotografia LVARCHAR (2056),
us_situacion SMALLINT DEFAULT 1
);

CREATE TABLE aplicacion(
ap_id SERIAL PRIMARY KEY,
ap_nombre_largo VARCHAR (250),
ap_nombre_medium VARCHAR (150),
ap_nombre_corto VARCHAR (50),
ap_fecha_creacion datetime year to minute default current year to minute,
ap_situacion SMALLINT DEFAULT 1
);

CREATE TABLE permiso(
per_id SERIAL PRIMARY KEY, 
per_aplicacion INT NOT NULL,
per_nombre VARCHAR (150) NOT NULL,
per_clave VARCHAR (250) NOT NULL,
per_descripcion VARCHAR (250) NOT NULL,
per_fecha datetime year to minute default current year to minute,
per_situacion SMALLINT DEFAULT 1,
FOREIGN KEY (per_aplicacion) REFERENCES aplicacion(ap_id)
);


CREATE TABLE asig_permisos(
asig_id SERIAL PRIMARY KEY,
asig_usuario INT,
asig_app INT,
asig_permiso INT,
asig_fecha datetime year to minute default current year to minute,
asig_quitar_fechaPermiso datetime year to minute default current year to minute,
asig_usuario_asigno INT,
asig_motivo VARCHAR (250),
asig_situacion SMALLINT DEFAULT 1,
FOREIGN KEY (asig_usuario) REFERENCES usuario(us_id),
FOREIGN KEY (asig_app) REFERENCES aplicacion(ap_id),
FOREIGN KEY (asig_permiso) REFERENCES permiso(per_id)
);

CREATE TABLE historial_act(
his_id SERIAL PRIMARY KEY,
his_usuario_id INT,
his_fecha datetime year to minute default current year to minute,
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