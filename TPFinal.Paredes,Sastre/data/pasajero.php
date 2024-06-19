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

  
    public function modificar() {
        $baseDatos = new BaseDatos();
        $resp = false;
        $objViaje = $this->getObjViaje();
        $idViaje = $objViaje->getIdviaje();
        $consulta = "UPDATE pasajero SET  idviaje = '{$idViaje}' WHERE pnroDoc =  '{$this->getPdocumento()}'";
        if ($baseDatos->iniciar()) {
            if ($baseDatos->ejecutar($consulta)) {
                $resp = true;
            } else {
                $this->setMensajeError($baseDatos->getERROR());
            }
        } else {
            $this->setMensajeError($baseDatos->getERROR());
        }
        return $resp;
    }


    public function eliminar(){
        $baseDatos = new BaseDatos();
        $resp = false;
        $consulta = "DELETE FROM pasajero WHERE pnroDoc = ".$this->getPDocumento();
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
		$consulta="SELECT * FROM pasajero WHERE pnroDoc = ".$documento;
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
		    $consultaPasajero .= " WHERE ".$condicion;
		}
		if($baseDatos->iniciar()){
			if($baseDatos->ejecutar($consultaPasajero)){
                $resp = [];				
				while($pasajero=$baseDatos->registro()){	
					$objPasajero = new pasajero();
					$objPasajero->buscar($pasajero['pnroDoc']);
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
        $cadena="\n>>>>>>>>>>>>>>>>>>>>>>>>>>Datos Pasajero<<<<<<<<<<<<<<<<<<<<<<<<<\n";
        $cadena .= parent::__toString();

         $cadena .=  "El codigo del viaje es: ".$this->getObjViaje()->getIdViaje()."\n";
         $cadena.=">>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<\n";
         return $cadena;
    }
                
}
