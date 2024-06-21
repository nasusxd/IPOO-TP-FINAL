<?php
class responsable extends persona
{

    private $numEmpleado;
    private $numLicencia;
    private $mensajeError;


    public function setNumLicencia($numLicencia)
    {
        $this->numLicencia = $numLicencia;
    }

    public function setNumEmpleado($numEmpleado)
    {
        $this->numEmpleado = $numEmpleado;
    }




    public function setMensajeError($mensajeError)
    {
        $this->mensajeError = $mensajeError;
    }




    public function getNumEmpleado()
    {
        return $this->numEmpleado;
    }


    public function getNumLicencia()
    {
        return $this->numLicencia;
    }


    public function getMensajeError()
    {
        return $this->mensajeError;
    }



    public function __construct()
    {
        parent::__construct();
        $this->numLicencia = null;
        $this->numEmpleado = null;
    }

    public function cargar($nroDoc, $nombre, $apellido, $telefono, $numLicencia = null)
    {
        parent::cargar($nroDoc, $nombre, $apellido, $telefono);
        $this->setNumLicencia($numLicencia);
    }

    public function insertar()
    {
        $baseDatos = new BaseDatos();
        $resp = false;
        if (parent::insertar()) {
            $consulta = "INSERT INTO responsable (pdocumento,rnumerolicencia) 
                    VALUES ('" . $this->getPDocumento() . "'," . $this->getNumLicencia() . ")";
            if ($baseDatos->iniciar()) {
                if ($id = $baseDatos->devuelveIDInsercion($consulta)) {
                    $this->setNumEmpleado($id);
                    $resp = true;
                } else {
                    $this->setMensajeError($baseDatos->getError());
                }
            } else {
                $this->setMensajeError($baseDatos->getError());
            }
        } else {
            echo "error al insertar";
        }
        return $resp;
    }


    public function modificar()
    {
        $baseDatos = new BaseDatos();
        if (parent::modificar()) {
            $resp = false;
            $consulta = "UPDATE responsable 
                    SET rnumerolicencia = " . $this->getNumLicencia() . " WHERE rnumeroempleado = " . $this->getNumEmpleado();
            if ($baseDatos->iniciar()) {
                if ($baseDatos->ejecutar($consulta)) {
                    $resp = true;
                } else {
                    $this->setMensajeError($baseDatos->getERROR());
                }
            } else {
                $this->setMensajeError($baseDatos->getERROR());
            }
        }else {echo "Error al modificar";}

        return $resp;
    }

    public function eliminar()
    {
        $base = new BaseDatos();
        $resp = false;
        if ($base->iniciar()) {
            $consultaBorra = 'DELETE FROM responsable WHERE rnumeroempleado =' . $this->getNumEmpleado();
            if ($base->ejecutar($consultaBorra)) {
                if (parent::eliminar()) {
                    $resp = true;
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }

        return $resp;
    }

    public function buscar($dni)
    {
        $baseDatos = new BaseDatos();
        $consulta = "SELECT * FROM responsable WHERE pdocumento = " . $dni;

        $resp = false;
        if ($baseDatos->iniciar()) {
            if ($baseDatos->ejecutar($consulta)) {
                if ($responsable = $baseDatos->registro()) {
                    parent::Buscar($dni);
                    $this->setNumLicencia($responsable['rnumerolicencia']);
                    $this->setNumEmpleado($responsable['rnumeroempleado']);
                    $resp = true;
                }
            } else {
                $this->setMensajeError($baseDatos->getERROR());
            }
        } else {
            $this->setMensajeError($baseDatos->getERROR());
        }
        return $resp;
    }


    public function buscarPorId($numEmpleado)
    {
        $baseDatos = new BaseDatos();
        $consulta = "SELECT * FROM responsable WHERE rnumeroempleado= " .$numEmpleado;

        $resp = false;
        if ($baseDatos->iniciar()) {
            if ($baseDatos->ejecutar($consulta)) {
                if ($responsable = $baseDatos->registro()) {
                    $this->setNumLicencia($responsable['rnumerolicencia']);
                    $this->setNumEmpleado($numEmpleado);
                    $resp = true;
                }
            } else {
                $this->setMensajeError($baseDatos->getERROR());
            }
        } else {
            $this->setMensajeError($baseDatos->getERROR());
        }
        return $resp;
    }

    public function listar($condicion = "")
    {
        $resp = null;
        $baseDatos = new BaseDatos();
        $consultaResponsable = "SELECT * FROM responsable";
        if ($condicion != "") {
            $consultaResponsable .= ' WHERE ' . $condicion;
        }
        $consultaResponsable .=" ORDER BY rnumeroempleado";
        if ($baseDatos->iniciar()) {
            if ($baseDatos->ejecutar($consultaResponsable)) {
                $resp = [];
                while ($responsable = $baseDatos->registro()) {
                    $objResponsable = new responsable();
                    $objResponsable->buscar($responsable['pdocumento']);
                    
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

    public function __toString()
    {
        $cadena = "\n>>>>>>>>>>>>>>>>>>>>>>>>>>Datos Responsable<<<<<<<<<<<<<<<<<<<<<<<<<\n";
        $cadena .= parent::__toString();
        $cadena .= "El numero de empleado es: " . $this->getNumEmpleado() . "\n" .
            "El numero de licencia es: " . $this->getNumLicencia() . "\n";
        $cadena .= ">>>>>>>>>>>>>>>>>>>>>>>>>><<<<<<<<<<<<<<<<<<<<<<<<<\n";
        return $cadena;
    }
}
