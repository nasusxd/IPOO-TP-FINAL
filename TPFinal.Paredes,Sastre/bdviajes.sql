CREATE DATABASE bdviajes; 

CREATE TABLE empresa(
    idempresa bigint AUTO_INCREMENT,
    enombre varchar(150),
    edireccion varchar(150),
    PRIMARY KEY (idempresa)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE responsable (
    pdocumento VARCHAR(15),
    rnumeroempleado bigint AUTO_INCREMENT,
    rnumerolicencia bigint,
    PRIMARY KEY (rnumeroempleado),
    FOREIGN KEY (pdocumento) REFERENCES persona (pdocumento)
    ON UPDATE CASCADE
    ON DELETE CASCADE
    )ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;;
	
CREATE TABLE viaje (
    idviaje bigint AUTO_INCREMENT, /*codigo de viaje*/
	vdestino varchar(150),
    vcantmaxpasajeros int,
	idempresa bigint,
    rnumeroempleado bigint,
    vimporte float,
    PRIMARY KEY (idviaje),
    FOREIGN KEY (idempresa) REFERENCES empresa (idempresa),
	FOREIGN KEY (rnumeroempleado) REFERENCES responsable (rnumeroempleado)
    ON UPDATE CASCADE
    ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT = 1;
	
CREATE TABLE pasajero ( 
    pnroDoc varchar(15),
    idviaje bigint,
    PRIMARY KEY (pnroDoc,idviaje),
    FOREIGN KEY (pnroDoc) REFERENCES persona (pdocumento)
    ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idviaje) REFERENCES viaje (idviaje)
    ON UPDATE CASCADE ON DELETE CASCADE  
    )ENGINE=InnoDB DEFAULT CHARSET=utf8; 

 
  CREATE Table persona (
    pdocumento varchar(15),
    pnombre varchar(150), 
    papellido varchar(150), 
	ptelefono int, 
    PRIMARY KEY (pdocumento)

  );
