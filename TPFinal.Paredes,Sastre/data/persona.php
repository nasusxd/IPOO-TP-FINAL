<?php
include_once "BaseDatos.php";
class Persona{

	private $pDocumento;
	private $pNombre;
	private $pApellido;
	private $pTelefono;
	private $mensajeoperacion;


	public function __construct(){
	    
		$this->pDocumento = " ";
		$this->pNombre = " ";
		$this->pApellido = " ";
		$this->pTelefono =  0;
		

		
	}
    
    /**
	 * Get the value of pDocumento
	 */
	public function getPDocumento() {
		return $this->pDocumento;
	}

	/**
	 * Set the value of pDocumento
	 */
	public function setPDocumento($nroDoc) {
		$this->pDocumento = $nroDoc;
	}
	
	  /**
	 * Get the value of pDocumento
	 */
	public function getPNombre() {
		return $this->pNombre;
	}

	public function setPNombre($nombre) {
		$this->pNombre = $nombre;
	}
	  /**
	 * Get the value of pDocumento
	 */
	public function getPApellido() {
		return $this->pApellido;
	}

	public function setPApellido($apellido) {
		$this->pApellido = $apellido;
	}
	
	public function getPTelefono() {
		return $this->pTelefono;
	}

	public function setPTelefono($telefono) {
		$this->pTelefono = $telefono;
	}
	public function getmensajeoperacion() {
		return $this->mensajeoperacion;
	}

	public function setmensajeoperacion($mensaje) {
		$this->mensajeoperacion = $mensaje;
	}
	
	public function cargar($nroDoc,$nombre,$apellido,$telefono){	
	    
		$this->setPDocumento($nroDoc);
		$this->setPNombre($nombre);
		$this->setPApellido($apellido);
		$this->setPTelefono($telefono);
		
    }
	

		

	/**
	 * Recupera los datos de una persona por dni
	 * @param int $dni
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($dni){
		$base=new BaseDatos();
		$consultaPersona="Select * from persona where nrodoc=".$dni;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPersona)){
				if($row2=$base->Registro()){
				    $this->setPDocumento($dni);
					$this->setPNombre($row2['nombre']);
					$this->setPApellido($row2['apellido']);
					$this->setPTelefono($row2['telefono']);
					$resp= true;
				}				
			
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());
		 	
		 }		
		 return $resp;
	}	
    

	public function listar($condicion = ""){
        $arregloPersona = null;
        $base = new BaseDatos();
        $consultaPersonas = "SELECT * FROM persona";
        if ($condicion != "") {
            $consultaPersonas .= ' WHERE ' . $condicion;
        }
        $consultaPersonas .= " ORDER BY apellido";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaPersonas)) {
                $arregloPersona = array();
                while ($row2 = $base->Registro()) {
                    $nroDoc = $row2['nrodoc'];
                    $nombre = $row2['nombre'];
                    $apellido = $row2['apellido'];
                    $telefono = $row2['telefono'];

                    $perso = new Persona();
                    $perso->cargar($nroDoc, $nombre, $apellido, $telefono);
                    array_push($arregloPersona, $perso);
                }
            } else {
               
                $this->setMensajeOperacion($base->getError());
            }
        } else {
           
            $this->setMensajeOperacion($base->getError());
        }

        return $arregloPersona;
    }
	

	
	public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$consulta="INSERT INTO persona(pdocumento,pnombre,papellido,telefono) 
				VALUES (".$this->getPDocumento()."','".$this->getPNombre()."','".$this->getPApellido()."','".$this->getPTelefono().")";
		    if($base->iniciar()){
				if($base->ejecutar($consulta)){
					$resp = true;
				}else{
					$this->setmensajeoperacion($base->getError());
				}
			}else{
				$this->setmensajeoperacion($base->getError());
			}
			return $resp;
		}
		
	
	
	
	
	public function modificar(){
	    $resp =false; 
	    $base=new BaseDatos();
		$consultaModifica="UPDATE persona SET apellido='".$this->getPApellido()."',nombre='".$this->getPNombre()."',nrodoc='". $this->getPDocumento()."',telefono='".$this->getPTelefono()." WHERE id".$this->getPDocumento();
		if($base->Iniciar()){
			if($base->Ejecutar($consultaModifica)){
			    $resp=  true;
			}else{
				$this->setmensajeoperacion($base->getError());
				
			}
		}else{
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp;
	}
	
	public function eliminar(){
		$base=new BaseDatos();
		$resp=false;
		if($base->Iniciar()){
				$consultaBorra="DELETE FROM persona WHERE nrodoc=".$this->getPDocumento();
				if($base->Ejecutar($consultaBorra)){
				    $resp=  true;
				}else{
						$this->setmensajeoperacion($base->getError());
					
				}
		}else{
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp; 
	}

	public function __toString() {
        return "\nNombre: " . $this->getPNombre() .
            "\nApellido: " . $this->getPApellido() .
            "\nDNI: " . $this->getPDocumento() .
            "\nTelefono: " . $this->getPTelefono() . "\n";
    }

	
}
