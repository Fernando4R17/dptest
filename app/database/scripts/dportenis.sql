CREATE DATABASE IF NOT EXISTS dportenis;
USE dportenis;

CREATE TABLE menus (
  id_menu INT AUTO_INCREMENT PRIMARY KEY,
  id_parent INT DEFAULT NULL,
  status TINYINT DEFAULT 1,
  name VARCHAR(100) NOT NULL,
  description TEXT,
  FOREIGN KEY (id_parent) REFERENCES menus(id_menu) ON DELETE SET NULL
) ;

INSERT INTO menus (name, description) VALUES
('Catálogos', 'Listado de Catálogos');
SET @cat_id = LAST_INSERT_ID();

INSERT INTO menus (name, description) VALUES
('Áreas', 'Listado de Áreas');
SET @areas_id = LAST_INSERT_ID();

-- Insert child menus under "Catálogos"
INSERT INTO menus (name, id_parent, description) VALUES
('Países', @cat_id, 'Listado de Países'),
('Estados', @cat_id, 'Listado de Estados'),
('Ciudades', @cat_id, 'Listado de Ciudades');

-- Insert child menus under "Áreas"
INSERT INTO menus (name, id_parent, description) VALUES
('Finanzas', @areas_id, 'Trabajadores de Finanzas'),
('TI', @areas_id, 'Trabajadores de TI');

select * from menus;