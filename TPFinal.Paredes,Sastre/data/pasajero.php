<?php
class Pasajero{
    private $nombre;
    private $apellido;
    private $documento;
    private $telefono;
    private $objViaje;
    private $mensajeError;


    public function setTelefono($telefono){
        $this->telefono = $telefono;
    }

  
    public function setDocumento($documento){
        $this->documento = $documento;
    }

    
    public function setApellido($apellido){
        $this->apellido = $apellido;
    }

   
    public function setNombre($nombre){
        $this->nombre = $nombre;
    }

 
    public function setObjViaje($objViaje){
        $this->objViaje = $objViaje;
    }

   
    public function setMensajeError($mensajeError){
        $this->mensajeError = $mensajeError;
    }



    public function getDocumento(){
        return $this->documento;
    }

   
    public function getTelefono(){
        return $this->telefono;
    }

 
    public function getApellido(){
        return $this->apellido;
    }

    public function getNombre(){
        return $this->nombre;
    }

  
    public function getObjViaje(){
        return $this->objViaje;
    }

    public function getMensajeError(){
        return $this->mensajeError;
    }


    public function __construct()
    {
        $this->nombre = "";
        $this->apellido = "";
        $this->documento = "";
        $this->telefono = "";
        $this->objViaje = "";
    }

    public function cargar($nombre, $apellido, $documento, $telefono, $objViaje){
        		
        $this->setNombre($nombre);
        $this->setApellido($apellido);
        $this->setDocumento($documento);
        $this->setTelefono($telefono);
        $this->setObjViaje($objViaje);
    }

    
    public function insertar(){
        $baseDatos = new BaseDatos();
        $resp = false;
        $consulta = "INSERT INTO pasajero (pdocumento, pnombre, papellido, ptelefono, idviaje) 
                    VALUES (".$this->getDocumento().",'".$this->getNombre()."','".$this->getApellido()."',".$this->getTelefono().",".$this->getObjViaje()->getIdViaje().")";
        if($baseDatos->iniciar()){
            if($baseDatos->ejecutar($consulta)){
                $resp = true;
            }else{
                $this->setMensajeError($baseDatos->getERROR());
            }
        }else{
            $this->setMensajeError($baseDatos->getERROR());
        }
        return $resp;
    }

  
    public function modificar(){
        $baseDatos = new BaseDatos();
        $resp = false;
        $consulta = "UPDATE pasajero 
                    SET pdocumento = ".$this->getDocumento().", 
                    pnombre = '".$this->getNombre()."', 
                    papellido ='".$this->getApellido()."', 
                    ptelefono = ".$this->getTelefono().", 
                    idviaje = ".$this->getObjViaje()->getIdViaje()." WHERE pdocumento = ".$this->getDocumento();
        if($baseDatos->iniciar()){
            if($baseDatos->ejecutar($consulta)){
                $resp = true;
            }else{
                $this->setMensajeError($baseDatos->getERROR());
            }
        }else{
            $this->setMensajeError($baseDatos->getERROR());
        }
        return $resp;
    }

    public function eliminar(){
        $baseDatos = new BaseDatos();
        $resp = false;
        $consulta = "DELETE FROM pasajero WHERE pdocumento = ".$this->getDocumento();
        if($baseDatos->iniciar()){
            if($baseDatos->ejecutar($consulta)){
                $resp = true;
            }else{
                $this->setMensajeError($baseDatos->getERROR());
            }
        }else{
            $this->setMensajeError($baseDatos->getERROR());
        }
        return $resp;
    }

    public function buscar($documento){
        $baseDatos = new BaseDatos();
		$consulta="SELECT * FROM pasajero WHERE pdocumento = ".$documento;
		$resp = false;
		if($baseDatos->iniciar()){
			if($baseDatos->ejecutar($consulta)){
				if($pasajero=$baseDatos->registro()){					
				    $this->setDocumento($documento);
					$this->setNombre($pasajero['pnombre']);
					$this->setApellido($pasajero['papellido']);
					$this->setTelefono($pasajero['ptelefono']);
                    $objViaje = new viaje();
                    $objViaje->buscar($pasajero['idviaje']);
                    $this->setObjViaje($objViaje);
					$resp= true;
				}
		 	}else{
                $this->setMensajeError($baseDatos->getERROR());
			}
		 }else{
            $this->setMensajeError($baseDatos->getERROR());
		 }		
		 return $resp;
	}

    public function listar($condicion){
	    $resp = null;
        $baseDatos = new BaseDatos();
		$consultaPasajero="SELECT * FROM pasajero ";
		if($condicion != ""){
		    $consultaPasajero .= " where ".$condicion;
		}
		if($baseDatos->iniciar()){
			if($baseDatos->ejecutar($consultaPasajero)){
                $resp = [];				
				while($pasajero=$baseDatos->registro()){	
					$objPasajero = new Pasajero();
					$objPasajero->buscar($pasajero['pdocumento']);
                    array_push($resp, $objPasajero);
				}
		 	}else{
                $resp = false;
                $this->setMensajeError($baseDatos->getERROR());
			}
		 }else{
            $resp = false;
            $this->setMensajeError($baseDatos->getERROR());
		 }		
		 return $resp;
	}

    public function __toString()
    {
        return ("El nombre del pasajero es: ".$this->getNombre()."\n".
                "El apellido del pasajero es: ".$this->getApellido()."\n".
                "El documento del pasajero es: ".$this->getDocumento()."\n".
                "El codigo del viaje es: ".$this->getObjViaje()->getIdViaje()."\n".
                "El telefono del pasajero es: ".$this->getTelefono()."\n");
    }
    


}
?>