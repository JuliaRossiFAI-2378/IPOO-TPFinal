CREATE DATABASE bdviajes; 
USE bdviajes;

CREATE TABLE empresa(
    idempresa bigint AUTO_INCREMENT,
    enombre varchar(150),
    edireccion varchar(150),
    PRIMARY KEY (idempresa)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE persona(
    nombre varchar(50),
    apellido varchar(50),
    telefono int,
    documento int PRIMARY KEY
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE responsable(
    numeroempleado bigint AUTO_INCREMENT,
    numerolicencia bigint,
    rdocumento int,
    PRIMARY KEY (numeroempleado),
    FOREIGN KEY (rdocumento) REFERENCES persona(documento) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
	
CREATE TABLE viaje(
    idviaje bigint AUTO_INCREMENT, 
	destino varchar(150),
    cantmaxpasajeros int,
	idempresa bigint,
    rnumeroempleado bigint,
    importe float,
    PRIMARY KEY (idviaje),
    FOREIGN KEY (idempresa) REFERENCES empresa (idempresa),
	FOREIGN KEY (rnumeroempleado) REFERENCES responsable (numeroempleado) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT = 1;
	
CREATE TABLE pasajero(
    pdocumento int,
	idviaje bigint,
    PRIMARY KEY (pdocumento),
    FOREIGN KEY (pdocumento) REFERENCES persona(documento),
	FOREIGN KEY (idviaje) REFERENCES viaje (idviaje) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8; 
 
  
