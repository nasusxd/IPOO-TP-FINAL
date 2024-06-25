<?php
class Viaje
{
    private $idviaje;
    private $vdestino;
    private $vcantmaxpasajeros;
    private $objEmpresa;
    private $objResponsable;
    private $vimporte;
    private $arrayObjPasajero;
    private $mensajeError;
    public function __construct()
    {
        $this->idviaje = null;
        $this->vdestino = "";
        $this->vcantmaxpasajeros = null;
        $this->objEmpresa = new empresa();
        $this->objResponsable = new responsable();
        $this->vimporte = null;
        $this->arrayObjPasajero = [];
    }

    public function cargar($idviaje, $vDestino, $vCantidadMax, $objEmpresa, $objResponsable, $vImporte)
    {
        if ($idviaje != null) {
            $this->setIdviaje($idviaje);
        }

        $this->setVDestino($vDestino);
        $this->setVcantmaxpasajeros($vCantidadMax);
        $this->setObjEmpresa($objEmpresa);
        $this->setObjResponsable($objResponsable);
        $this->setVImporte($vImporte);
    }



    public function getIdviaje()
    {
        return $this->idviaje;
    }


    public function setIdviaje($idviaje)
    {
        $this->idviaje = $idviaje;
    }


    public function getVdestino()
    {
        return $this->vdestino;
    }


    public function setVdestino($vdestino)
    {
        $this->vdestino = $vdestino;
    }

    public function getVcantmaxpasajeros()
    {
        return $this->vcantmaxpasajeros;
    }


    public function setVcantmaxpasajeros($vcantmaxpasajeros)
    {
        $this->vcantmaxpasajeros = $vcantmaxpasajeros;
    }


    public function getObjEmpresa()
    {
        return $this->objEmpresa;
    }


    public function setObjEmpresa($objEmpresa)
    {
        $this->objEmpresa = $objEmpresa;
    }


    public function getObjResponsable()
    {
        return $this->objResponsable;
    }

    public function setObjResponsable($objResponsable)
    {
        $this->objResponsable = $objResponsable;
    }


    public function getVimporte()
    {
        return $this->vimporte;
    }


    public function setVimporte($vimporte)
    {
        $this->vimporte = $vimporte;
    }


    public function getArrayObjPasajero()
    {
        return $this->arrayObjPasajero;
    }


    public function setArrayObjPasajero($arrayObjPasajero)
    {
        $this->arrayObjPasajero = $arrayObjPasajero;
    }

    public function getMensajeError()
    {
        return $this->mensajeError;
    }


    public function setMensajeError($mensajeError)
    {
        $this->mensajeError = $mensajeError;
    }

    public function insertar()
    {
        $baseDatos = new BaseDatos();
        $resp = false;

        $consulta = "INSERT INTO viaje (vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte)
        VALUES ('" . $this->getVDestino() . "'," . $this->getVcantmaxpasajeros() . "," . $this->getObjEmpresa()->getidempresa() . "," . $this->getObjResponsable()->getNumEmpleado() . "," . $this->getVImporte() . ")";


        if ($baseDatos->iniciar()) {

            if ($id = $baseDatos->devuelveIDInsercion($consulta)) {
                $this->setIdviaje($id);
                $resp = true;
            } else {
                $this->setMensajeError($baseDatos->getError());
            }
        } else {
            $this->setMensajeError($baseDatos->getError());
        }
        return $resp;
    }


    public function Buscar($id)
    {
        $baseDatos = new BaseDatos();
        $consulta = "SELECT * FROM viaje WHERE idviaje=" . $id;
        $resp = false;
        if ($baseDatos->iniciar()) {
            if ($baseDatos->ejecutar($consulta)) {
                if ($row2 = $baseDatos->registro($consulta)) {
                    $objEmpresa = new empresa();
                    $objEmpresa->buscar($row2['idempresa']);
                    $objResponsable = new responsable();
                    $objResponsable->buscar($row2['rnumeroempleado']);
                    $this->setIdviaje($id);
                    $this->setVdestino($row2['vdestino']);
                    $this->setVcantmaxpasajeros($row2['vcantmaxpasajeros']);
                    $this->setObjEmpresa($objEmpresa);
                    $this->setObjResponsable($objResponsable);
                    $this->setVimporte($row2['vimporte']);
                    $resp = true;
                }
            } else {
                $this->setMensajeError($baseDatos->getError());
            }
        } else {
            $this->setMensajeError($baseDatos->getError());
        }

        return $resp;
    }

    public function modificar()
    {
        $baseDatos = new BaseDatos();
        $resp = false;
        $consulta = "UPDATE viaje  SET 
                    vdestino='" . $this->getVdestino() . "', 
                    vcantmaxpasajeros='" . $this->getVcantmaxpasajeros() . "', vimporte=" . $this->getVimporte() . " 
                WHERE idviaje=" . $this->getIdviaje();

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
    public function eliminar()
    {
        $baseDatos = new BaseDatos();
        $resp = false;
        $consulta = "DELETE FROM viaje WHERE idviaje=" . $this->getIdviaje();
        if ($baseDatos->Iniciar()) {
            $this->eliminarPasajerosPorViaje($this->getIdviaje());
            if ($baseDatos->Ejecutar($consulta)) {
                $resp = true;
            } else {
                $this->setMensajeError($baseDatos->getError());
            }
        } else {
            $this->setMensajeError($baseDatos->getError());
        }
        return $resp;
    }
    //eliminar pasajero por id de viaje
    public function eliminarPasajerosPorViaje($idViaje)
    {
        $base = new BaseDatos();
        $resp = false;
        if ($base->Iniciar()) {
            $consultaBorraPasajeros = "DELETE pasajero, persona 
            FROM pasajero
            INNER JOIN persona ON pasajero.pnroDoc = persona.pdocumento
            WHERE pasajero.idviaje = " . $idViaje;
            if ($base->Ejecutar($consultaBorraPasajeros)) {
                $resp = true;
            } else {
                $this->setMensajeError($base->getError());
            }
        } else {
            $this->setMensajeError($base->getError());
        }
        return $resp;
    }

    public function listar($condicion = "")
    {
        $arregloViaje = null;
        $baseDatos = new BaseDatos();
        $consulta = "SELECT * FROM viaje";
        if ($condicion != "") {
            $consulta = $consulta . ' WHERE ' . $condicion;
        }
        $consulta .= " ORDER BY idviaje";
        $inicio = $baseDatos->Iniciar();
        if ($inicio) {
            if ($baseDatos->Ejecutar($consulta)) {
                $arregloViaje = array();
                while ($row2 = $baseDatos->Registro()) {
                    $id = $row2['idviaje'];
                    $destino = $row2['vdestino'];
                    $cantmax = $row2['vcantmaxpasajeros'];
                    $idEmpresa = $row2['idempresa'];
                    $numeroEmpleado = $row2['rnumeroempleado'];
                    $importe = $row2['vimporte'];
                    $viaje = new Viaje();
                    $viaje->cargar($id, $destino, $cantmax, $idEmpresa, $numeroEmpleado, $importe);
                    array_push($arregloViaje, $viaje);
                }
            } else {
                $this->setMensajeError($baseDatos->getError());
            }
        } else {
            $this->setMensajeError($baseDatos->getError());
        }
        return $arregloViaje;
    }
    public function __toString()
    {
        $objPasajero = new pasajero();
        $condicion = "idviaje=" . $this->getIdviaje();
        $lista = $objPasajero->listar($condicion);
        if (count($lista) > 0) {
            $list = "\n>>>>>>>>>>>>>>>>>>>>>>>>>>Lista de pasajeros<<<<<<<<<<<<<<<<<<<<<<<<<\n";
            $list .= coleccion_a_cadena($lista);
        } else {
            $list = "\nLISTA DE PASAJEROS VACIA\n";
        }
        $cadena = "\n>>>>>>>>>>>>>>>>>>>>>>>>>>Datos Viaje<<<<<<<<<<<<<<<<<<<<<<<<<\n";
        $cadena .= "Id del viaje: " . $this->getIdviaje() . "\n";
        $cadena .=     " Destino: " . $this->getVdestino() . "\n";
        $cadena .=     "Cantidad de pasajeros maxima: " . $this->getVcantmaxpasajeros();
        $cadena .=   "\nDatos de la empresa a la empresa: \n" .
            $this->getObjEmpresa() . "\n";
        $cadena .=    "El numero de Responsable: " . $this->getObjResponsable() . "\n";
        $cadena .=   "El importe del viaje es de: " . $this->getVimporte() . " pesos\n";
        $cadena .= $list;
        return $cadena;
    }

    public function coleccion_a_cadena($coleccion)
    {
        $cadena = "----------------------------------------------\n";
        foreach ($coleccion as $objeto) {
            $cadena .= $objeto;
            $cadena .= "----------------------------------------------\n";
        }
        return $cadena;
    }
}
