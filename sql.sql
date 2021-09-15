CREATE TABLE formmaker_formulario (
  id int NOT NULL IDENTITY(1, 1) PRIMARY KEY,
  id_usuario INT NULL,
  string VARCHAR(4000) NULL,
  estado VARCHAR(50) NULL,
  deleted_at VARCHAR(45) NULL,
  fecha_alta DATETIME DEFAULT GETDATE()
);
CREATE TABLE formmaker_respuestas (
  id int NOT NULL IDENTITY(1, 1) PRIMARY KEY,
  id_formulario INT NULL,
  deleted_at VARCHAR(45) NULL,
  fecha_alta DATETIME DEFAULT GETDATE()
);

CREATE TABLE formmaker_log (
	id int NOT NULL IDENTITY(1, 1) PRIMARY KEY,
	id_usuario INT NULL,
	id_formulario INT NULL,
	error VARCHAR(200) NULL,
	class VARCHAR(200) NULL,
	metodo VARCHAR(200) NULL,
	fecha_alta DATETIME DEFAULT GETDATE()
);