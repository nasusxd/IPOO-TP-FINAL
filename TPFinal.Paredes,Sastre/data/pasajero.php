<?php
class pasajero extends persona{
    
    private $objViaje;
    private $mensajeError;


    public function __construct(){
        parent::__construct();
        $this->objViaje = "";

    }
  
   

 
    public function setObjViaje($objViaje){
        $this->objViaje = $objViaje;
    }

   
    public function setMensajeError($mensajeError){
        $this->mensajeError = $mensajeError;
    }
  
    public function getObjViaje(){
        return $this->objViaje;
    }

    public function getMensajeError(){
        return $this->mensajeError;
    }



    public function cargar($nroDoc,$nombre,$apellido,$telefono,$objViaje=null){
        parent:: cargar($nroDoc,$nombre,$apellido,$telefono,);
        $this->setObjViaje($objViaje);
    }

    
    public function insertar(){
        $baseDatos = new BaseDatos();
        $resp = false;
        $consulta = "INSERT INTO pasajero (pnroDoc, idviaje) 
                    VALUES ('".$this->getPDocumento()."',".$this->getObjViaje()->getIdViaje().")";
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
                    SET pdocumento = '".$this->getPDocumento()."', 
                    pnombre = '".$this->getPNombre()."', 
                    papellido ='".$this->getPApellido()."', 
                    ptelefono = ".$this->getPTelefono().", 
                    idviaje = ".$this->getObjViaje()->getIdViaje()." WHERE pdocumento = ".$this->getPDocumento();
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
        $consulta = "DELETE FROM pasajero WHERE nroDoc = ".$this->getPDocumento();
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
		$consulta="SELECT * FROM pasajero WHERE nroDoc = ".$documento;
		$resp = false;
		if($baseDatos->iniciar()){
			if($baseDatos->ejecutar($consulta)){
				if($pasajero=$baseDatos->registro()){					
				    $this->setPDocumento($documento);
                    $objViaje = new viaje();
                    $objViaje->buscar($pasajero['idviaje']);
                    $this->setObjViaje($objViaje);
					$resp= true;
				}
		 	}else{
                $this->setMensajeError($baseDatos->getError());
			}
		 }else{
            $this->setMensajeError($baseDatos->getError());
		 }		
		 return $resp;
	}

    public function listar($condicion=""){
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
					$objPasajero = new pasajero();
					$objPasajero->buscar($pasajero['pdocumento']);
                    array_push($resp, $objPasajero);
				}
		 	}else{
                
                $this->setMensajeError($baseDatos->getError());
			}
		 }else{
           
            $this->setMensajeError($baseDatos->getError());
		 }		
		 return $resp;
	}
    

    public function __toString(){
        $cadena=">>>>>>>>>>>>>>>>>>>>>>>>>>Datos Pasajero<<<<<<<<<<<<<<<<<<<<<<<<<";
        $cadena .= parent::__toString();

         $cadena .=  "El codigo del viaje es: ".$this->getObjViaje()->getIdViaje()."\n";
         $cadena.=">>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<";
         return $cadena;
    }
                
}
