-- ============================
-- Crear base de datos
-- ============================
CREATE DATABASE IF NOT EXISTS dbaapos
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE dbaapos;

-- ============================
-- Tabla roles
-- ============================
CREATE TABLE roles (
  id_rol INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL UNIQUE,
  descripcion VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================
-- Tabla usuarios
-- ============================
CREATE TABLE usuarios (
  id_usuario INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  apellido VARCHAR(50) NOT NULL,
  usuario VARCHAR(50) NOT NULL UNIQUE,
  pass VARCHAR(255) NOT NULL,
  id_rol INT NOT NULL,
  estado BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT fk_usuario_rol
    FOREIGN KEY (id_rol)
    REFERENCES roles(id_rol)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
);

-- ============================
-- Datos iniciales de roles
-- ============================
INSERT INTO roles (nombre, descripcion) VALUES
('ADMIN', 'Administrador General'),
('RIFA', 'Gestor de Rifa'),
('DONACIONES', 'Gestor de Donaciones');

-- ============================
-- Datos iniciales de usuarios
-- ============================
INSERT INTO usuarios (nombre, apellido, usuario, pass, id_rol) VALUES
('Anibal', 'Morales', 'anibal', '$2y$12$Q7xoGD8J73dvmhJFMoFUp.CQsSfj7rC6GqeQJxWpxz9UozHkPJhC6',
 (SELECT id_rol FROM roles WHERE nombre = 'ADMIN')),

('Nancy', 'Aguilar', 'naguilar', '$2y$12$Q7xoGD8J73dvmhJFMoFUp.CQsSfj7rC6GqeQJxWpxz9UozHkPJhC6',
 (SELECT id_rol FROM roles WHERE nombre = 'DONACIONES'));



-- =========================
-- CATALOGOS (COMBOBOX)
-- =========================

CREATE TABLE IF NOT EXISTS ubicaciones (
  id_ubicacion INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(80) NOT NULL UNIQUE,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS tipos_donacion (
  id_tipo_donacion INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(80) NOT NULL UNIQUE,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS proyectos (
  id_proyecto INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL UNIQUE,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- TABLA PRINCIPAL: DONACIONES
-- =========================

CREATE TABLE IF NOT EXISTS donaciones (
  id_donacion INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

  -- Usuario que registra (tu tabla usuarios usa id_usuario)
  id_usuario INT NOT NULL,

  -- Datos de la donación
  fecha_despachada DATE NULL,
  empresa VARCHAR(180) NULL,
  nit VARCHAR(50) NULL,
  contacto VARCHAR(120) NULL,
  telefono VARCHAR(50) NULL,
  correo VARCHAR(150) NULL,
  unidades INT UNSIGNED NULL,
  descripcion TEXT NULL,
  valor_total_donacion DECIMAL(12,2) NULL,

  -- Entrega / Recepción
  id_ubicacion INT UNSIGNED NULL,          -- Ubicar en
  fecha_recibe DATE NULL,
  quien_recibe VARCHAR(120) NULL,
  id_tipo_donacion INT UNSIGNED NULL,      -- Tipo de donación
  unidades_entrega INT UNSIGNED NULL,
  persona_gestiono VARCHAR(120) NULL,

  -- Mercado / logística
  precio_mercado_unidad DECIMAL(12,2) NULL,
  total_mercado DECIMAL(12,2) NULL,
  referencia_mercado VARCHAR(180) NULL,
  costo_logistica DECIMAL(12,2) NULL,
  descripcion_logistica TEXT NULL,

  -- Proyecto / impacto
  id_proyecto INT UNSIGNED NULL,
  impacto_personas INT UNSIGNED NULL,
  comentarios TEXT NULL,

  -- Documentos / referencias
  recibo_empresa TINYINT(1) NOT NULL DEFAULT 0, -- 0 = No, 1 = Sí
  ref_osshp VARCHAR(80) NULL,
  fecha_ref_osshp DATE NULL,
  ref_sat VARCHAR(80) NULL,
  fecha_ref_sat DATE NULL,

  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  -- Índices
  INDEX idx_don_usuario (id_usuario),
  INDEX idx_don_fecha (fecha_despachada),
  INDEX idx_don_ubic (id_ubicacion),
  INDEX idx_don_tipo (id_tipo_donacion),
  INDEX idx_don_proy (id_proyecto),

  -- Foreign Keys
  CONSTRAINT fk_don_usuario
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
    ON UPDATE CASCADE ON DELETE RESTRICT,

  CONSTRAINT fk_don_ubicacion
    FOREIGN KEY (id_ubicacion) REFERENCES ubicaciones(id_ubicacion)
    ON UPDATE CASCADE ON DELETE SET NULL,

  CONSTRAINT fk_don_tipo
    FOREIGN KEY (id_tipo_donacion) REFERENCES tipos_donacion(id_tipo_donacion)
    ON UPDATE CASCADE ON DELETE SET NULL,

  CONSTRAINT fk_don_proyecto
    FOREIGN KEY (id_proyecto) REFERENCES proyectos(id_proyecto)
    ON UPDATE CASCADE ON DELETE SET NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- SEED (VALORES PARA COMBOBOX)
-- =========================

INSERT INTO ubicaciones (nombre) VALUES
  ('OSSHP'),
  ('Mst. Bodeguitas')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

INSERT INTO tipos_donacion (nombre) VALUES
  ('Monetaria'),
  ('Alimentos'),
  ('Medicamentos')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

INSERT INTO proyectos (nombre) VALUES
  ('Calidad de Vida'),
  ('Digitalizacion')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);






INSERT INTO donaciones (
  id_usuario, fecha_despachada, empresa, nit, contacto, telefono, correo,
  unidades, descripcion, valor_total_donacion,
  id_ubicacion, fecha_recibe, quien_recibe, id_tipo_donacion, unidades_entrega, persona_gestiono,
  precio_mercado_unidad, total_mercado, referencia_mercado,
  costo_logistica, descripcion_logistica,
  id_proyecto, impacto_personas, comentarios,
  recibo_empresa, ref_osshp, fecha_ref_osshp, ref_sat, fecha_ref_sat
) VALUES
-- 1
(2,'2026-01-01','Supermercados La Canasta','1234567-8','Carlos Pérez','555-1001','contacto@canasta.com',
100,'Donación de víveres básicos',1500.00,
1,'2026-01-02','María López',2,100,'Nancy Aguilar',
15.00,1500.00,'Mercado Central',
120.00,'Transporte local',
1,250,'Apoyo a familias vulnerables',
1,'OSSHP-001','2026-01-02','SAT-001','2026-01-03'),

-- 2
(2,'2026-01-03','Farmacias SaludPlus','2233445-6','Ana Gómez','555-1002','info@saludplus.com',
50,'Medicamentos genéricos',4200.00,
1,'2026-01-04','Luis Méndez',3,50,'Nancy Aguilar',
84.00,4200.00,'Distribuidor autorizado',
200.00,'Entrega con cadena de frío',
1,120,'Medicamentos esenciales',
1,'OSSHP-002','2026-01-04','SAT-002','2026-01-05'),

-- 3
(2,'2026-01-05','Banco Solidario','9988776-5','Jorge Ramírez','555-1003','donaciones@banco.com',
1,'Aporte económico',10000.00,
2,'2026-01-05','Oficina Central',1,1,'Nancy Aguilar',
10000.00,10000.00,'Transferencia bancaria',
0.00,'Sin logística',
2,500,'Financiamiento de proyecto digital',
1,'OSSHP-003','2026-01-05','SAT-003','2026-01-06'),

-- 4
(2,'2026-01-06','Panadería El Trigal','4455667-1','Rosa Castillo','555-1004','ventas@trigal.com',
200,'Pan y productos horneados',1800.00,
1,'2026-01-06','Pedro Salazar',2,200,'Nancy Aguilar',
9.00,1800.00,'Precio promedio local',
80.00,'Entrega diaria',
1,300,'Apoyo alimentario',
0,NULL,NULL,NULL,NULL),

-- 5
(2,'2026-01-07','Tecnología ABC','3344556-2','Miguel Torres','555-1005','contacto@abc.com',
10,'Tablets educativas',7500.00,
2,'2026-01-08','Departamento IT',1,10,'Nancy Aguilar',
750.00,7500.00,'Proveedor ABC',
150.00,'Transporte especializado',
2,180,'Educación digital',
1,'OSSHP-004','2026-01-08','SAT-004','2026-01-09'),

-- 6
(2,'2026-01-08','Agroindustrias Verdes','5566778-9','Laura Jiménez','555-1006','info@agroverdes.com',
300,'Verduras frescas',2100.00,
1,'2026-01-09','Comedor Social',2,300,'Nancy Aguilar',
7.00,2100.00,'Mercado mayorista',
140.00,'Transporte refrigerado',
1,400,'Nutrición comunitaria',
1,'OSSHP-005','2026-01-09','SAT-005','2026-01-10'),

-- 7
(2,'2026-01-09','Clínica Vida','1122334-5','Dr. Roberto Díaz','555-1007','clinica@vida.com',
30,'Kits médicos',3600.00,
1,'2026-01-10','Enfermería',3,30,'Nancy Aguilar',
120.00,3600.00,'Proveedor médico',
220.00,'Entrega segura',
1,90,'Atención primaria',
1,'OSSHP-006','2026-01-10','SAT-006','2026-01-11'),

-- 8
(2,'2026-01-10','Restaurante Buen Sabor','6677889-0','Elena Ruiz','555-1008','contacto@buensabor.com',
150,'Comidas preparadas',2250.00,
2,'2026-01-10','Centro Comunitario',2,150,'Nancy Aguilar',
15.00,2250.00,'Costo promedio',
60.00,'Entrega inmediata',
1,220,'Alimentación directa',
0,NULL,NULL,NULL,NULL),

-- 9
(2,'2026-01-11','Librería Saber','7788990-1','Daniel Mora','555-1009','ventas@saber.com',
80,'Libros educativos',3200.00,
2,'2026-01-12','Biblioteca',1,80,'Nancy Aguilar',
40.00,3200.00,'Proveedor editorial',
90.00,'Envío terrestre',
2,160,'Fomento lectura',
1,'OSSHP-007','2026-01-12','SAT-007','2026-01-13'),

-- 10
(2,'2026-01-12','Industria Textil Maya','8899001-2','Patricia León','555-1010','info@maya.com',
400,'Ropa nueva',4800.00,
1,'2026-01-13','Bodega Central',2,400,'Nancy Aguilar',
12.00,4800.00,'Costo fábrica',
180.00,'Clasificación y transporte',
1,350,'Vestimenta digna',
1,'OSSHP-008','2026-01-13','SAT-008','2026-01-14'),

-- 11
(2,'2026-01-13','ElectroHogar','9900112-3','Sergio Pineda','555-1011','ventas@electrohogar.com',
15,'Electrodomésticos',9000.00,
2,'2026-01-14','Área Social',1,15,'Nancy Aguilar',
600.00,9000.00,'Proveedor oficial',
300.00,'Transporte pesado',
2,75,'Apoyo a hogares',
1,'OSSHP-009','2026-01-14','SAT-009','2026-01-15'),

-- 12
(2,'2026-01-14','Farmacia Popular','1011121-4','Julia Ramos','555-1012','farmacia@popular.com',
40,'Vitaminas y suplementos',2800.00,
1,'2026-01-15','Centro de Salud',3,40,'Nancy Aguilar',
70.00,2800.00,'Precio regulado',
110.00,'Distribución local',
1,130,'Salud preventiva',
0,NULL,NULL,NULL,NULL),

-- 13
(2,'2026-01-15','Cooperativa Agrícola','1213141-5','Mario López','555-1013','coop@agricola.com',
500,'Granos básicos',3500.00,
1,'2026-01-16','Almacén',2,500,'Nancy Aguilar',
7.00,3500.00,'Mercado regional',
160.00,'Carga y descarga',
1,420,'Seguridad alimentaria',
1,'OSSHP-010','2026-01-16','SAT-010','2026-01-17'),

-- 14
(2,'2026-01-16','Empresa Digital XYZ','1314151-6','Andrea Fuentes','555-1014','info@xyz.com',
5,'Licencias de software',12500.00,
2,'2026-01-16','Departamento IT',1,5,'Nancy Aguilar',
2500.00,12500.00,'Proveedor internacional',
0.00,'Entrega digital',
2,60,'Transformación digital',
1,'OSSHP-011','2026-01-16','SAT-011','2026-01-17'),

-- 15
(2,'2026-01-17','Hotel Esperanza','1415161-7','Raúl Ortega','555-1015','hotel@esperanza.com',
200,'Kits de higiene',2600.00,
1,'2026-01-18','Centro Comunitario',2,200,'Nancy Aguilar',
13.00,2600.00,'Costo proveedor',
95.00,'Distribución urbana',
1,310,'Higiene y salud',
0,NULL,NULL,NULL,NULL);


ALTER TABLE proyectos
ADD COLUMN descripcion VARCHAR(255) NULL;


ALTER TABLE tipos_donacion
ADD COLUMN descripcion VARCHAR(255) NULL;

CREATE TABLE calidadvida_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  rubro VARCHAR(150) NOT NULL,
  monto DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  ejecutado DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  en_proceso DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  pendiente DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE calidadvida_items
ADD COLUMN no_documento VARCHAR(50) NULL AFTER pendiente,
ADD COLUMN descripcion TEXT NULL AFTER no_documento;

CREATE TABLE pacientes (
  id_paciente INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

  nombre VARCHAR(150) NOT NULL,
  dpi VARCHAR(20) NOT NULL UNIQUE,
  sexo ENUM('MASCULINO','FEMENINO') NOT NULL,
  edad TINYINT UNSIGNED NOT NULL,
  carnet VARCHAR(50) NULL,

  telefono VARCHAR(25) NULL,
  correo VARCHAR(150) NULL,

  departamento VARCHAR(80) NOT NULL,
  municipio VARCHAR(80) NOT NULL,

  tipo_consulta ENUM('CONSULTA GENERAL','CONSULTA ESPECIALIZADA') NOT NULL,
  empresa ENUM('EMPRESA','MUNICIPALIDAD','REFIRIENTE') NOT NULL,
  nombre_empresa VARCHAR(150) NOT NULL,

  referido_por VARCHAR(150) NULL,
  telefono_referente VARCHAR(25) NULL,
  tipo_contacto ENUM('Call Center','Celular Personal','Redes Sociales','Referencia Personal') NOT NULL,
  tipo_consulta_referente ENUM('CONSULTA GENERAL','CONSULTA ESPECIALIZADA') NULL,

  descripcion TEXT NULL,

  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE medios (
  id_medio INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  medio VARCHAR(150) NOT NULL,
  tipo ENUM('Local','Nacional','Internacional') NOT NULL,
  nombre VARCHAR(150) NOT NULL,
  nombre_completo VARCHAR(150) NULL,
  telefono VARCHAR(25) NULL,
  contacto_cargo VARCHAR(150) NULL,
  celular_contacto VARCHAR(25) NULL,
  direccion VARCHAR(255) NULL,
  email VARCHAR(150) NULL,
  website VARCHAR(255) NULL,
  redsocial VARCHAR(255) NULL,
  observaciones TEXT NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE avances (
  id_avance INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_proyecto INT UNSIGNED NOT NULL,
  fecha DATE NOT NULL,
  descripcion TEXT NOT NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_avances_proyecto_fecha (id_proyecto, fecha),
  CONSTRAINT fk_avances_proyectos
    FOREIGN KEY (id_proyecto) REFERENCES proyectos(id_proyecto)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE donaciones
ADD COLUMN bloqueado TINYINT(1) NOT NULL DEFAULT 0;








ALTER TABLE avances
  ADD COLUMN user_id BIGINT UNSIGNED NULL AFTER descripcion;

ALTER TABLE avances
  ADD CONSTRAINT fk_avances_user_id
  FOREIGN KEY (user_id) REFERENCES usuarios(id_usuario);







CREATE TABLE proyecto_usuario (
  id_proyecto INT NOT NULL,
  id_usuario  INT NOT NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (id_proyecto, id_usuario),
  KEY idx_pu_usuario (id_usuario),
  KEY idx_pu_proyecto (id_proyecto)
) ENGINE=InnoDB;

INSERT IGNORE INTO proyecto_usuario (id_proyecto, id_usuario) VALUES (1, 2);
INSERT IGNORE INTO proyecto_usuario (id_proyecto, id_usuario) VALUES (2, 1);



--- server
CREATE DATABASE dbaapos
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

CREATE USER 'aapos'@'localhost'
IDENTIFIED BY '@Anib@l25';

GRANT ALL PRIVILEGES ON dbaapos.* TO 'aapos'@'localhost';

FLUSH PRIVILEGES;

EXIT;




CREATE TABLE rubros (
  id_rubro INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL UNIQUE,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE documentos_ingresos (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,

  oficina VARCHAR(20) NOT NULL,
  id_proyecto INT NOT NULL,
  id_rubro INT NULL,

  tipo_documento VARCHAR(30) NOT NULL,
  fecha_documento DATE NOT NULL,

  no_documento VARCHAR(50) NULL,
  serie VARCHAR(50) NULL,

  empresa_nombre VARCHAR(150) NULL,
  nit VARCHAR(50) NULL,
  telefono VARCHAR(30) NULL,
  direccion VARCHAR(255) NULL,
  correo VARCHAR(120) NULL,
  contacto VARCHAR(120) NULL,

  descripcion TEXT NULL,

  monto DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  descuento DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  pagada TINYINT(1) NOT NULL DEFAULT 0,

  archivo_path VARCHAR(255) NULL,
  archivo_original VARCHAR(255) NULL,
  archivo_mime VARCHAR(120) NULL,

  user_id INT NULL,

  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX idx_oficina (oficina),
  INDEX idx_fecha (fecha_documento),
  INDEX idx_proyecto (id_proyecto),
  INDEX idx_rubro (id_rubro),
  INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE documentos_ingresos
  MODIFY id_proyecto INT UNSIGNED NOT NULL;



ALTER TABLE documentos_ingresos
  MODIFY id_rubro INT UNSIGNED NULL;






ALTER TABLE documentos_ingresos
  ADD CONSTRAINT fk_doc_ing_proyecto
    FOREIGN KEY (id_proyecto) REFERENCES proyectos(id_proyecto)
    ON UPDATE CASCADE
    ON DELETE RESTRICT;

ALTER TABLE documentos_ingresos
  ADD CONSTRAINT fk_doc_ing_rubro
    FOREIGN KEY (id_rubro) REFERENCES rubros(id_rubro)
    ON UPDATE CASCADE
    ON DELETE SET NULL;



CREATE TABLE contactos (
  id_contacto INT UNSIGNED NOT NULL AUTO_INCREMENT,
  id_proyecto INT UNSIGNED NOT NULL,

  tipo VARCHAR(30) NOT NULL,              -- Empresa/Fundacion/Persona/ONG
  nombre VARCHAR(150) NOT NULL,
  telefono VARCHAR(30) NULL,
  extension VARCHAR(10) NULL,
  correo VARCHAR(120) NULL,
  direccion VARCHAR(255) NULL,
  nit VARCHAR(30) NULL,
  motivo VARCHAR(255) NULL,

  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,

  PRIMARY KEY (id_contacto),
  INDEX idx_contactos_proyecto (id_proyecto),
  CONSTRAINT fk_contactos_proyectos
    FOREIGN KEY (id_proyecto) REFERENCES proyectos(id_proyecto)
) ;


ALTER TABLE contactos
ADD COLUMN email VARCHAR(150) NULL AFTER correo,
ADD COLUMN sitio_web VARCHAR(150) NULL AFTER email;


ALTER TABLE documentos_ingresos
ADD no_documento_pago VARCHAR(100) NULL,
ADD fecha_pago DATE NULL;


ALTER TABLE contactos ADD contacto VARCHAR(150) NULL AFTER nombre;


ALTER TABLE pacientes
  MODIFY nombre VARCHAR(150) NULL,
  MODIFY dpi VARCHAR(20) NULL,
  MODIFY sexo ENUM('MASCULINO','FEMENINO') NULL,
  MODIFY edad TINYINT NULL,
  MODIFY departamento VARCHAR(80) NULL,
  MODIFY municipio VARCHAR(80) NULL,
  MODIFY tipo_consulta ENUM('CONSULTA GENERAL','CONSULTA ESPECIALIZADA') NULL,
  MODIFY empresa ENUM('EMPRESA','MUNICIPALIDAD','REFIRIENTE') NULL,
  MODIFY nombre_empresa VARCHAR(150) NULL,
  MODIFY tipo_contacto ENUM('Call Center','Celular Personal','Redes Sociales','Referencia Personal') NULL;


ALTER TABLE pacientes
  ADD tipo_operacion VARCHAR(120) NULL AFTER tipo_consulta;



ALTER TABLE proyectos
ADD direccion VARCHAR(255) NULL AFTER descripcion;
