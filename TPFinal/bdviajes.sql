CREATE DATABASE bdviajes; 
USE bdviajes;

CREATE TABLE empresas(
    idempresa bigint AUTO_INCREMENT,
    enombre varchar(150),
    edireccion varchar(150),
    PRIMARY KEY (idempresa)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE personas(
    nombre varchar(50),
    apellido varchar(50),
    telefono int,
    documento int PRIMARY KEY
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE responsables(
    numeroempleado bigint AUTO_INCREMENT,
    numerolicencia bigint,
    rdocumento int,
    PRIMARY KEY (numeroempleado),
    FOREIGN KEY (rdocumento) REFERENCES personas(documento) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
	
CREATE TABLE viajes(
    idviaje bigint AUTO_INCREMENT, 
	destino varchar(150),
    cantmaxpasajeros int,
	idempresa bigint,
    rnumeroempleado bigint,
    importe float,
    PRIMARY KEY (idviaje),
    FOREIGN KEY (idempresa) REFERENCES empresas (idempresa) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (rnumeroempleado) REFERENCES responsables (numeroempleado) ON UPDATE CASCADE ON DELETE SET NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT = 1;
	
CREATE TABLE pasajeros(
    pdocumento int,
	idviaje bigint,
    PRIMARY KEY (pdocumento),
    FOREIGN KEY (pdocumento) REFERENCES personas(documento) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (idviaje) REFERENCES viajes (idviaje) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8; 
 
  
