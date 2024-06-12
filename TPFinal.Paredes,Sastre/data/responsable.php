<?php
class responsable extends Persona{
   
    private $nroDoc;
    private $numEmpleado;
    private $numLicencia;
    private $mensajeError;


    public function __construct()
	{
        
        
		$this->numLicencia = "";
		$this->numEmpleado = "";
	}


    /**************************************/
	/**************** SET *****************/
	/**************************************/

    /**
     * Establece el valor de numLicencia
     */ 
    public function setNumLicencia($numLicencia){
        $this->numLicencia = $numLicencia;
    }

    /**
     * Establece el valor de numEmpleado
     */ 
    public function setNumEmpleado($numEmpleado){
        $this->numEmpleado = $numEmpleado;
    }

    /**
     * Establece el valor de mensajeError
     */ 
    public function setMensajeError($mensajeError){
        $this->mensajeError = $mensajeError;
    }


	/**************************************/
	/**************** GET *****************/
	/**************************************/


    /**
     * Obtiene el valor de numEmpleado
     */ 
    public function getNumEmpleado(){
        return $this->numEmpleado;
    }

    /**
     * Obtiene el valor de numLicencia
     */ 
    public function getNumLicencia(){
        return $this->numLicencia;
    }

        /**
     * Obtiene el valor de mensajeError
     */ 
    public function getMensajeError(){
        return $this->mensajeError;
    }


	/**************************************/
	/************** FUNCIONES *************/
	/**************************************/

    public function cargar($nombre, $apellido, $numLicencia, $numEmpleado){		
        $this->setNombre($nombre);
        $this->setApellido($apellido);
        $this->setNumLicencia($numLicencia);
        $this->setNumEmpleado($numEmpleado);
    }

    /**
     * Este modulo agrega un pasajero de la BD.
    */
    public function insertar(){
        $baseDatos = new BaseDatos();
        $resp = false;
        $consulta = "INSERT INTO responsable (rnumerolicencia, rnombre, rapellido) 
                    VALUES (".$this->getNumLicencia().",'".$this->getNombre()."','".$this->getApellido()."')";
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

    /**
     * Este modulo modifica un pasajero de la BD.
    */
    public function modificar(){
        $baseDatos = new BaseDatos();
        $resp = false;
        $consulta = "UPDATE responsable 
                    SET rnumerolicencia = ".$this->getNumLicencia().", 
                    rnombre = '".$this->getNombre()."', 
                    rapellido ='".$this->getApellido()."' WHERE rnumeroempleado = ".$this->getNumEmpleado();
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

    /**
     * Este modulo modifica un pasajero de la BD.
    */
    public function eliminar(){
        $baseDatos = new BaseDatos();
        $resp = false;
        $consulta = "DELETE FROM responsable WHERE rnumeroempleado = ".$this->getNumEmpleado();
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

    public function buscar($nroEmpleado){
        $baseDatos = new BaseDatos();
		$consulta="SELECT * FROM responsable WHERE rnumeroempleado = ".$nroEmpleado;
		$resp = false;
		if($baseDatos->iniciar()){
			if($baseDatos->ejecutar($consulta)){
				if($responsable=$baseDatos->registro()){					
				    $this->setNombre($responsable['rnombre']);
					$this->setApellido($responsable['rapellido']);
					$this->setNumLicencia($responsable['rnumerolicencia']);
					$this->setNumEmpleado($nroEmpleado);
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
		$consultaResponsable="SELECT * FROM responsable ";
		if($condicion != ""){
		    $consultaResponsable .=' where '.$condicion;
		}
		if($baseDatos->iniciar()){
			if($baseDatos->ejecutar($consultaResponsable)){
                $resp = [];		
				while($responsable=$baseDatos->registro()){		
                    $objResponsable = new responsable();
                    $objResponsable->buscar($responsable['rnumeroempleado']);
                    array_push($resp, $objResponsable);
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
		return ("El nombre del responsable del viaje es: ".$this->getNombre()."\n".
				"El apellido del responsable del viaje es: ".$this->getApellido()."\n".
				"El numero de empleado es: ".$this->getNumEmpleado()."\n".
				"El numero de licencia es: ".$this->getNumLicencia()."\n");
	}



}
?>