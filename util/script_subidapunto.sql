CREATE SCHEMA subidapunto_bd;
USE subidapunto_bd;

CREATE TABLE usuarios(
	id INT PRIMARY KEY AUTO_INCREMENT,
	username VARCHAR(20) UNIQUE,
    correo VARCHAR(40) UNIQUE,
    contrasenia VARCHAR(255)
);

CREATE TABLE mangas(
	id INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(100),
    autor VARCHAR(100),
    capitulos INT,
    volumen INT,
    score FLOAT,
    fecha_agregada DATE,
    imagen VARCHAR(255)
);
SELECT * FROM mangas;
CREATE TABLE coleccion(
	id INT PRIMARY KEY AUTO_INCREMENT,
	id_usuario INT,
	CONSTRAINT FK_COLECCION_USUARIO FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE pertenece(
	id_manga INT,
    id_coleccion INT,
	CONSTRAINT FK_PERTENECE_MANGA FOREIGN KEY (id_manga) REFERENCES mangas(id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT FK_PERTENECE_COLECCION FOREIGN KEY (id_coleccion) REFERENCES coleccion(id) ON DELETE CASCADE ON UPDATE CASCADE
);
SELECT * FROM favoritos;
CREATE TABLE favoritos(
	id INT PRIMARY KEY AUTO_INCREMENT,
	id_usuario INT,
    id_manga INT,
    CONSTRAINT FK_FAVORITO_USUARIO FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT FK_FAVORITO_MANGA FOREIGN KEY (id_manga) REFERENCES mangas(id) ON DELETE CASCADE ON UPDATE CASCADE
);

SELECT favoritos.id_manga FROM favoritos JOIN mangas ON favoritos.id_manga = mangas.id WHERE titulo = "One Piece";