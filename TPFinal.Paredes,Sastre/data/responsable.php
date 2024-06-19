<?php
class responsable extends persona{
    
    private $numEmpleado;
    private $numLicencia;
    private $mensajeError;

  
    public function setNumLicencia($numLicencia){
        $this->numLicencia = $numLicencia;
    }

    public function setNumEmpleado($numEmpleado){
        $this->numEmpleado = $numEmpleado;
    }

  

   
    public function setMensajeError($mensajeError){
        $this->mensajeError = $mensajeError;
    }



 
    public function getNumEmpleado(){
        return $this->numEmpleado;
    }

  
    public function getNumLicencia(){
        return $this->numLicencia;
    }

  
    public function getMensajeError(){
        return $this->mensajeError;
    }



	public function __construct()
	{
        parent::__construct();
		$this->numLicencia = null;
		$this->numEmpleado = null;
	}

    public function cargar($nroDoc,$nombre, $apellido,$telefono, $numLicencia=null){		
      parent:: cargar($nroDoc,$nombre,$apellido,$telefono);
        $this->setNumLicencia($numLicencia);
        
    }
    
    public function insertar(){
        $baseDatos = new BaseDatos();
        $resp = false;
        $consulta = "INSERT INTO responsable (pdocumento,rnumerolicencia) 
                    VALUES ('".$this->getPDocumento()."',".$this->getNumLicencia().")";
        if($baseDatos->iniciar()){
            if ($id = $baseDatos->devuelveIDInsercion($consulta)){
                $this->setNumEmpleado($id);
                $resp = true;
            } else {
                $this->setMensajeError($baseDatos->getError());
            }
        } else {
            $this->setMensajeError($baseDatos->getError());
        }
        return $resp;
    }

   
    public function modificar(){
        $baseDatos = new BaseDatos();
        $resp = false;
        $consulta = "UPDATE responsable 
                    SET rnumerolicencia = ".$this->getNumLicencia()." WHERE rnumeroempleado = ".$this->getNumEmpleado();
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

    public function listar($condicion = ""){
        $resp = null;
        $baseDatos = new BaseDatos();
        $consultaResponsable = "SELECT * FROM responsable";
        if ($condicion != "") {
            $consultaResponsable .= ' WHERE ' . $condicion;
        }
        if ($baseDatos->iniciar()) {
            if ($baseDatos->ejecutar($consultaResponsable)) {
                $resp = [];        
                while ($responsable = $baseDatos->registro()) {        
                    $objResponsable = new responsable();
                    $objResponsable->buscar($responsable['rnumeroempleado']);
                    array_push($resp, $objResponsable);
                }
            } else {
               
                $this->setMensajeError($baseDatos->getERROR());
            }
        } else {
           
            $this->setMensajeError($baseDatos->getERROR());
        }        
        return $resp;
    }

	public function __toString(){$cadena="\n>>>>>>>>>>>>>>>>>>>>>>>>>>Datos Responsable<<<<<<<<<<<<<<<<<<<<<<<<<";
        $cadena .= parent::__toString();
        $cadena .="El numero de empleado es: ".$this->getNumEmpleado()."\n".
				"El numero de licencia es: ".$this->getNumLicencia()."\n";
                $cadena.=">>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<\n";
		return $cadena;
				
	}



}
