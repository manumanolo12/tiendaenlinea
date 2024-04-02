CREATE DATABASE IF NOT EXISTS IEU;
USE IEU;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
  id INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

-- Tabla de productos
CREATE TABLE IF NOT EXISTS productos (
  id INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(255) NOT NULL,
  precio DECIMAL(10,2) NOT NULL,
  disponibles INT NOT NULL,
  descripcion TEXT,
  imagen VARCHAR(255),
  categoria VARCHAR(100),
  PRIMARY KEY (id)
);

-- Tabla de carrito
CREATE TABLE IF NOT EXISTS carrito (
  id INT NOT NULL AUTO_INCREMENT,
  id_usuario INT,
  id_producto INT,
  cantidad INT,
  fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (id_usuario) REFERENCES usuarios (id),
  FOREIGN KEY (id_producto) REFERENCES productos (id)
);

