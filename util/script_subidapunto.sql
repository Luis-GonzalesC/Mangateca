CREATE SCHEMA subidapunto_bd;
USE subidapunto_bd;

CREATE TABLE usuarios(
	id INT PRIMARY KEY AUTO_INCREMENT,
	username VARCHAR(20) UNIQUE,
    correo VARCHAR(40) UNIQUE,
    contrasenia VARCHAR(255)
);

CREATE TABLE fabricante(
	id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(60),
    pais VARCHAR(30)
);

INSERT INTO fabricante (nombre, pais) VALUES ("Bandai", "Japon");

CREATE TABLE figuras(
	id INT PRIMARY KEY AUTO_INCREMENT,
    nombre_figura VARCHAR(100),
    nombre_anime VARCHAR(100),
    id_fabricante INT,
    tipo_figura VARCHAR(50),
    precio INT,
    imagen VARCHAR(60),
	CONSTRAINT FK_FIGURA_FABRICANTE FOREIGN KEY (id_fabricante) REFERENCES fabricante(id) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO figuras (nombre_figura, nombre_anime, id_fabricante, tipo_figura, precio, imagen)
	VALUES("Kakashi", "Naruto", 1, "LA CHIQUITA", 50, "imagenes/quefrio.jpg");

CREATE TABLE coleccion(
	id_usuario INT,
	id_figura INT,
    fecha_conseguida DATE,
	CONSTRAINT FK_COLECCION_USUARIO FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT FK_COLECCION_FIGURA FOREIGN KEY (id_figura) REFERENCES figuras(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE favoritos(
	id INT PRIMARY KEY AUTO_INCREMENT,
	id_usuario INT,
    id_figura INT,
    CONSTRAINT FK_FAVORITO_USUARIO FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT FK_FAVORITO_FIGURA FOREIGN KEY (id_figura) REFERENCES figuras(id) ON DELETE CASCADE ON UPDATE CASCADE
);
