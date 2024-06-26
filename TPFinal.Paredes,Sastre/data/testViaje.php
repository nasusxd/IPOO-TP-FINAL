<?php


include_once "persona.php";
include_once "pasajero.php";
include_once "responsable.php";
include_once "empresa.php";
include_once "viaje.php";
include_once "BaseDatos.php";

//FUNCIONES


function opcionesPrincipales()
{
    echo "\n*+*+*+*+*+*+*+*+*+*+*+* \n";
    echo "\n menu principal";
    echo "\n1.Crear Empresa";
    echo "\n2.Administrar Empresa";
    echo "\n3.Ver lista de Empresas";
    echo "\n4.Viajes";
    echo "\n5.Administrar Responsables";
    echo "\n0.Salir";
    echo "\n*+*+*+*+*+*+*+*+*+*+*+* \n";
    echo "Elija una opcion: ";
}

function principal()
{
    do {
        opcionesPrincipales();
        echo "\n ingrese una opcion: ";
        $op = trim(fgets(STDIN));
        switch ($op) {
            case 1:
                $objEmpresa = new empresa();
                echo "Ingrese el nombre de la empresa: ";
                $nombre = trim(fgets(STDIN));
                echo "Ingrese la dirección de la empresa: ";
                $direccion = trim(fgets(STDIN));

                $objEmpresa->setNombre($nombre);
                $objEmpresa->setDireccion($direccion);
                $objEmpresa->cargar($nombre, $direccion);
                if ($objEmpresa->insertar()) {
                    echo "\n!!!Empresa insertada con éxito¡¡¡.\n";
                } else {
                    echo "\nError al insertar la empresa: " . $objEmpresa->getMensajeError() . "\n";
                }
                break;

            case 2:

                if (listarEmpresas()) {
                    echo "\ningrese el ID de la empresa: ";
                    $objEmpresa = new empresa();

                    $id = trim(fgets(STDIN));
                    if ($objEmpresa->buscar($id)) {
                        menuEmpresa($objEmpresa);
                    } else {
                        echo "\nNo se a encontrado una empresa con ese id\n";
                    }
                }

                break;

            case 3:
                listarEmpresas();
                break;
            case 4:
                menuViajes();
                break;
            case 5:
                menuResponsable();


                break;
            case 0:
                echo "Saliendo...";
                break;
            default:
                echo "\nIngrese un numero de la lista";
                break;
        }
    } while ($op != 0);
}
//MENU PARA EMPRESAS
function menuEmpresa($objEmpresa)
{
    do {
        echo "\n*+*+*+*+*+*+*+*+*+*+*+* \n";
        echo "\n menu Empresa";
        echo "\n Empresa actual:" . $objEmpresa->getNombre();
        echo "\n1.Cambiar nombre";
        echo "\n2.Cambiar direccion";
        echo "\n3.Eliminar Empresa";
        echo "\n0.volver atras";
        echo "\n*+*+*+*+*+*+*+*+*+*+*+* \n";
        echo "Elija una opcion: ";
        $ope = trim(fgets(STDIN));
        switch ($ope) {
            case 0:
                echo "\nVolviendo al menu anterior...\n";
                break;
            case 1:
                echo "\n nombre actual:" . $objEmpresa->getNombre();
                echo "\n Ingrese el nuevo nombre: ";
                $nomNuevo = trim(fgets(STDIN));
                if ($nomNuevo != "" && $nomNuevo != $objEmpresa->getNombre()) {
                    $objEmpresa->setNombre($nomNuevo);
                    $resp = $objEmpresa->modificar();
                    if ($resp) {
                        echo "\nEl nombre se cambio con EXITO.";
                    } else {
                        echo $objEmpresa->getMensajeError();
                    }
                } else {
                    echo "El nombre ingresado es el mismo o no ingreso ningun caracter";
                }
                break;
            case 2:
                echo "\n direccion actual:" . $objEmpresa->getDireccion();
                echo "\n Ingrese la nueva direccion: ";
                $direcNueva = trim(fgets(STDIN));
                if ($direcNueva != "" && $direcNueva != $objEmpresa->getDireccion()) {
                    $objEmpresa->setDireccion($direcNueva);
                    $resp = $objEmpresa->modificar();
                    if ($resp) {
                        echo "\nLa direccion se cambio con EXITO.";
                    } else {
                        echo $objEmpresa->getMensajeError();
                    }
                } else {
                    echo "la direccion ingresada es la misma o no ingreso ningun caracter";
                }
                break;
            case 3:
                echo "\n Se eliminara la empresa y con ella, los viajes, 
                el resposable de cada uno de ellos y la lista de pasajeros\n";
                $condicion = "idempresa=" . $objEmpresa->getidempresa();
                $objViaje = new Viaje();
                $colViajes = $objViaje->listar($condicion);
                if (!empty($colViajes)) {
                    foreach ($colViajes as $viaje) {
                        $colPasajeros = $viaje->getArrayObjPasajero();
                        if ($colPasajeros == []) {
                            $viaje->eliminar();
                        } else {
                            foreach ($colPasajeros as $pasajero) {
                                $pasajero->eliminar();
                            }
                        }
                        $viaje->eliminar();
                    }
                }
                if ($objEmpresa->eliminar()) {
                    echo "La empresa se elimino con exito";
                    $ope = 0;
                } else {
                    echo "hubo un error al eliminar la empresa";
                }


                break;
            default:
                echo "\n Ingrese un numero de la lista";
                break;
        }
    } while ($ope != 0);
}

// MENU DE VIAJES
function menuViajes()
{
    do {
        $objViaje = new Viaje();
        $objEmpresa = new empresa();
        $objResponsable = new responsable;
        echo "\n*+*+*+*+*+*+*+*+*+*+*+* \n";
        echo "\n menu de Viajes";
        echo "\n1.Ingresar un nuevo viaje";
        echo "\n2.Modificar un viaje";
        echo "\n3.Eliminar un viaje";
        echo "\n4.Ver lista de viajes";
        echo "\n0.volver atras";
        echo "\nElija una opcion: ";
        $ope = trim(fgets(STDIN));

        switch ($ope) {
            case 0:
                echo "\nVolviendo al menu anterior...\n";
                break;
            case 1:
                $cantResp = count($objResponsable->listar());
                $cantEmp = count($objEmpresa->listar());
                if ($cantResp > 0 && $cantEmp > 0) {
                    echo "Ingrese lugar de destino: ";
                    $destino = trim(fgets(STDIN));
                    echo "Ingrese cantidad maxima de pasajeros: ";
                    $cantMaxPasajeros = trim(fgets(STDIN));
                    //mostrando empresas
                    $colecEmpresas = $objEmpresa->listar();
                    $cadena = coleccion_a_cadena($colecEmpresas);
                    echo "\n////// LISTA DE EMPRESAS ///////\n";
                    echo $cadena;
                    do {
                        $sirve = false;
                        echo "Ingrese id de empresa: ";
                        $idEmpresa = trim(fgets(STDIN));
                        foreach ($colecEmpresas as $empresa) {
                            if ($empresa->getidempresa() == $idEmpresa) {
                                $sirve = true;
                            }
                        }
                        if ($sirve == false) {
                            echo "\nNo se encuentra una empresa con ese ID\n";
                        }
                    } while ($sirve == false);

                    //mostrando empleados     
                    $colecEmpleados = $objResponsable->listar();
                    do {
                        $sirve = false;
                        echo "\n////// LISTA DE RESPONSABLES///////\n";
                        foreach ($colecEmpleados as $empleado) {
                            echo $empleado . "\n";
                        }

                        echo "\nIngrese numero de empleado: ";
                        $numeroEmpleado = trim(fgets(STDIN));
                        foreach ($colecEmpleados as $empleado) {
                            if($empleado->getNumEmpleado() == $numeroEmpleado) {

                                $sirve = true;
                            }
                              
                        }
                        if(!$sirve){
                            echo "\nNo se encuentra numero de empleado\n";
                        }

                    } while ($sirve == false);
                    echo "\nIngrese importe del viaje: ";
                    $importeViaje = trim(fgets(STDIN));
                    if ($objEmpresa->buscar($idEmpresa)) {
                        if ($objResponsable->buscar($numeroEmpleado)) {
                            $objViaje->cargar(null, $destino, $cantMaxPasajeros, $objEmpresa, $objResponsable, $importeViaje);
                        }
                    } else {
                        echo "\nLa empresa o el responsable no existe ";
                    }
                    if ($objViaje->insertar()) {
                        echo "\nViaje agregado con éxito. Número de Viaje: " . $objViaje->getIdviaje() . "\n";
                    } else {
                        echo "\nError al insertar el Viaje: " . $objViaje->getMensajeError() . "\n";
                    }
                } else if ($cantResp < 1) {
                    echo "\n---No hay ningun responsable cargado en el sistema. Cargue uno y vuelva a intentar.---\n";
                } else {
                    echo "\n---No hay ninguna empresa cargada en el sistema. Cargue una y vuelva a intentar.---\n";
                }

                break;
            case 2:

                if (listarViajes()) {
                    opcionesDeUnViaje();
                }
                break;
            case 3:

                if (listarViajes()) {
                    echo "\ningrese el ID del viaje: ";
                    $idViaje = trim(fgets(STDIN));
                    $objViaje = new Viaje();
                    if ($objViaje->buscar($idViaje)) {
                        $objPasajero = new pasajero();
                        $condicion = "idviaje=" . $idViaje;
                        $list = $objPasajero->listar($condicion);
                        $cantPasajeros = count($list);
                        if ($cantPasajeros > 0) {
                            echo "Hay " . $cantPasajeros . " pasajeros en el viaje desea eliminarlo igualmente? (s/n): ";
                            $resp = trim(fgets(STDIN));
                            if ($resp == "s") {
                                if ($objViaje->eliminar()) {
                                    echo "\nel viaje y los pasajeros se eliminaron con exito";
                                } else {
                                    echo "\nhubo un error al eliminar el viaje";
                                }
                            }else{ echo "\n Su respuesta a sido NO o invalida por lo cual no se a eliminado el viaje ni sus pasajeros. ";}
                        }else{
                            if ($objViaje->eliminar()) {
                                echo "\nel viaje se a eliminado con exito (No habian pasajeros dentro del mismo)";
                            } else {
                                echo "\nhubo un error al eliminar el viaje";
                            }

                        }
                    } else {
                        echo "\nno se a encontrado un viaje con ese id";
                    }
                }
                break;
            case 4:
                listarViajes();
                break;
            default:
                echo "\ningrese un numero de la lista";
                break;
        }
    } while ($ope != 0);
}


////////////// OPCIONES PARA UN VIAJE ///////////////
function opcionesDeUnViaje()
{
    $objViaje = new Viaje();
    echo "Ingrese el id del viaje que desea modificar: ";
    $id = trim(fgets(STDIN));
    if ($objViaje->buscar($id)) {

        do {

            echo "\n*+*+*+*+*+*+*+*+*+*+*+* \n";
            echo "\n menu de Viaje ID: $id";
            echo "\n1.Cambiar destino";
            echo "\n2.Cambiar el limite de pasajeros";
            echo "\n3.Cambiar importe";
            echo "\n4.agregar Pasajero";
            echo "\n5.ver lista de pasajeros";
            echo "\n6.administrar un Pasajero";
            echo "\n7.Ver datos Responsable";
            echo "\n8.Cambiar de Responsable";
            echo "\n0.Volver atras";
            echo "\n*+*+*+*+*+*+*+*+*+*+*+* \n";
            echo "\nElija una opcion: ";
            $ope = trim(fgets(STDIN));

            switch ($ope) {
                case 0:
                    echo "\nVolviendo al menu anterior...\n";
                    break;
                case 1:
                    echo "El destino actual es: " . $objViaje->getVdestino();
                    echo "\nIngrese el nuevo Destino: ";
                    $destino = trim(fgets(STDIN));
                    $objViaje->setVdestino($destino);
                    if ($objViaje->modificar()) {
                        echo "\nSe actualizo el destino con exito";
                    } else {
                        echo $objViaje->getMensajeError();
                    }
                    break;
                case 2:
                    $cantPasa = count($objViaje->getArrayObjPasajero());
                    echo "\n Ingrese un limite mayor o igual a $cantPasa: ";
                    $limite = trim(fgets(STDIN));

                    if ($limite >= $cantPasa) {
                        $objViaje->setVcantmaxpasajeros($limite);
                        if ($objViaje->modificar()) {
                            echo "\nSe actualizo el destino con exito";
                        } else {
                            echo $objViaje->getMensajeError();
                        }
                    } else {
                        echo "\nEl limite ingresado es menor a la cantidad actual de pasajeros";
                    }
                    break;
                case 3:
                    echo "\nIngrese el nuevo Importe: ";
                    $imp = trim(fgets(STDIN));
                    $objViaje->setVimporte($imp);
                    if ($objViaje->modificar()) {
                        echo "\nSe actualizo el importe con exito";
                    } else {
                        echo $objViaje->getMensajeError();
                    }

                    break;
                case 4:

                    $objPasajero = new Pasajero();
                    $consulta = "idviaje=" . $objViaje->getIdviaje();
                    $list = $objPasajero->listar($consulta);
                    $cantPasa = count($list); // Obtener la cantidad de pasajeros actual y el límite de pasajeros del viaje

                    $limite = $objViaje->getVCantMaxPasajeros();

                    // Verificar si hay espacio en el viaje
                    if ($cantPasa < $limite) {
                        do {
                            echo "Ingrese número de documento del Pasajero: ";
                            $numDoc = trim(fgets(STDIN));
                            $objPersona = new persona();
                            $yaEsta = true;
                            if (!$objPersona->buscar($numDoc)) {
                                // Si no existe en el sistema, proceder con el registro
                                $yaEsta = false;
                                $objPasajero = new pasajero();

                                echo "Ingrese el nombre: ";
                                $nombre = trim(fgets(STDIN));
                                echo "Ingrese el apellido: ";
                                $apellido = trim(fgets(STDIN));
                                echo "Ingrese el teléfono: ";
                                $telefono = trim(fgets(STDIN));

                                $objPersona->cargar($numDoc, $nombre, $apellido, $telefono);

                                $objPasajero->cargar($numDoc, $nombre, $apellido, $telefono, $objViaje);
                                if ($objPasajero->insertar()) {
                                    echo "\nPasajero ingresado con éxito.\n";
                                } else {
                                    echo $objPasajero->getMensajeError();
                                }
                            } else {
                                echo "ya hay una persona con ese dni en el sistema ingrese otro dni\n";
                            }
                        } while ($yaEsta); // Salir del bucle cuando $yaEsta sea false

                    } else {
                        echo "No hay espacio disponible en el viaje.\n";
                    }
                    break;
                case 5:
                    $cadena = "\n>>>>>>>>>>>>>>>>>>>>>>>>>>Lista de pasajeros<<<<<<<<<<<<<<<<<<<<<<<<<\n";
                    $objPasajero = new pasajero();

                    $condicion = "idviaje=" . $objViaje->getIdviaje();
                    $list = $objPasajero->listar($condicion);
                    $cadena .= coleccion_a_cadena($list);
                    echo $cadena;
                    break;
                case 6:
                    echo "Ingrese número de documento del Pasajero: ";
                    $numDoc = trim(fgets(STDIN));
                    $esta = false;
                    $i = 0;
                    $objPasajero = new pasajero();
                    $condicion = "idviaje=" . $objViaje->getIdviaje();
                    $list = $objPasajero->listar($condicion);
                    $cantPasa = count($list);

                    while ($i < $cantPasa && $esta == false) {
                        $pasajero = $list[$i];
                        if ($pasajero->getPDocumento() == $numDoc) {
                            $esta = true;
                            menuPasajero($pasajero);
                        }
                        $i++;
                        if ($esta == false) {
                            echo "El pasajero no se encuentra en este viaje";
                        }
                    }

                    break;
                case 7:
                    echo "Los datos del responsable son los siguientes\n" . $objViaje->getObjResponsable();
                    break;
                case 8:
                    $objResponsable = $objViaje->getObjResponsable();
                    echo "los datos del Responsable son los siguientes: \n" .
                        $objResponsable .
                        "\n Desea cambiar de responsable? (s/n): ";
                    $resp = trim(fgets(STDIN));
                    if ($resp == "s") {
                        $objResponsable2 = new responsable();
                        $list = $objResponsable2->listar();
                        $lista = coleccion_a_cadena($list);
                        do {
                            echo "<<<<Lista de responsables>>>>\n" .
                                $lista .
                                "\n Ingrese el numero de responsable: ";
                            $num = trim(fgets(STDIN));
                            $cantResp = count($list);
                            $i = 0;
                            $esta = false;
                            while ($i < $cantResp && $esta == false) {
                                $responsable = $list[$i];
                                if ($responsable->getNumEmpleado() == $num) {
                                    $esta = true;
                                    $objViaje->setObjResponsable($responsable);
                                    if ($objViaje->modificar()) {
                                        echo "\nSe cambio con exito el responsable";
                                    } else {
                                        echo $objViaje->getMensajeError();
                                    }
                                }
                                $i++;
                            }
                            if ($esta == false) {
                                echo "No se encontro el Resposable ";
                            }
                        } while ($esta == false);
                    } else {
                        echo "\n el Responsable no a cambiado";
                    }
                    break;
            }
        } while ($ope != 0);
    } else {
        echo "El viaje con el id: $id no se a encontrado";
    }
}

function  menuResponsable()
{
    do {
        echo "\n*+*+*+*+*+*+*+*+*+*+*+* \n";
        echo "\n menu Responsables";
        echo "\n1.Crear Responsable";
        echo "\n2.Modificar Responsable";
        echo "\n3.Lista de Responsables";
        echo "\n4.Eliminar Responsable";
        echo "\n0.Volver atras";
        echo "\n*+*+*+*+*+*+*+*+*+*+*+* \n";
        echo "Elija una opcion: ";
        $ope = trim(fgets(STDIN));
        switch ($ope) {
            case 0:
                echo "\nVolviendo al menu anterior...\n";
                break;
            case 1:
                do {
                    $yaEsta = false;
                    echo "ingrese el num de documento:  ";
                    $numDoc = trim(fgets(STDIN));
                    $objPersona = new persona();
                    $list = $objPersona->listar();
                    $i = 0;
                    $cantPersona = count($list);
                    while ($i < $cantPersona && !$yaEsta) {
                        $persona = $list[$i];
                        if ($persona->getPDocumento() == $numDoc) {
                            $yaEsta = true;
                            echo "\nYa hay una persona con ese dni en el sistema\n";
                        }
                        $i++;
                    }
                } while ($yaEsta == true);
                $objResponsable = new responsable();

                echo "ingrese el nombre: ";
                $nombre = trim(fgets(STDIN));
                echo "ingrese el apellido: ";
                $apellido = trim(fgets(STDIN));
                echo "ingrese el telefono: ";
                $telefono = trim(fgets(STDIN));
                echo "ingrese numero de licencia: ";
                $numLicencia = trim(fgets(STDIN));
                $objPersona->cargar($numDoc, $nombre, $apellido, $telefono);
                $objResponsable->cargar($numDoc, $nombre, $apellido, $telefono, $numLicencia);
                if ($objResponsable->insertar()) {

                    echo "\nResponsable ingresado con exito\n";
                } else {
                    echo $objResponsable->getMensajeError();
                }

                break;
            case 2:

                if (listarResponsables()) {
                    echo "\ningrese el numero de empleado: ";
                    $num = trim(fgets(STDIN));
                    $objResponsable = new responsable();
                    if ($objResponsable->buscar($num)) {
                        do {
                            echo "Menu del responsable id: $num\n";
                            echo "1.cambiar Nombre\n";
                            echo "2.cambiar Apellido\n";
                            echo "3.cambiar Numero de licencia\n";
                            echo "4.cambiar Numero de Telefono\n";
                            echo "0. Volver\n";
                            echo "Elija una opcion: ";
                            $ope = trim(fgets(STDIN));
                            switch ($ope) {
                                case 0:
                                    echo "\nVolviendo al menu anterior...\n";
                                    break;
                                case 1:
                                    echo "Ingrese el nuevo nombre: ";
                                    $nom = trim(fgets(STDIN));
                                    $objResponsable->setPNombre($nom);
                                    if ($objResponsable->modificar()) {
                                        echo "\nSe modifico el nombre con exito\n";
                                    } else {
                                        echo $objResponsable->getMensajeError();
                                    }
                                    break;
                                case 2:
                                    echo "Ingrese el nuevo apellido: ";
                                    $ape = trim(fgets(STDIN));
                                    $objResponsable->setPApellido($ape);
                                    if ($objResponsable->modificar()) {
                                        echo "\nSe modifico el apellido con exito\n";
                                    } else {
                                        echo $objResponsable->getMensajeError();
                                    }
                                    break;
                                case 3:
                                    echo "Ingrese otro numero de licencia: ";
                                    $lic = trim(fgets(STDIN));
                                    $objResponsable->setNumLicencia($lic);
                                    if ($objResponsable->modificar()) {
                                        echo "\nSe modifico el numero de licencia con exito\n";
                                    } else {
                                        echo $objResponsable->getMensajeError();
                                    }
                                    break;
                                case 4:
                                    echo "Ingrese otro numero de telefono:  ";
                                    $tel = trim(fgets(STDIN));
                                    $objResponsable->setPTelefono($tel);
                                    if ($objResponsable->modificar()) {
                                        echo "\nSe modifico el numero de telefono con exito\n";
                                    } else {
                                        echo $objResponsable->getMensajeError();
                                    }
                                    break;
                            }
                        } while ($ope != 0);
                    } else {
                        echo "No se encontro ningun Responsable con ese numero";
                    }
                }
                break;
            case 3:
                listarResponsables();
                break;
            case 4:
                if (listarResponsables()) {
                    echo "\nIngrese el numero de empleado: ";
                    $num = trim(fgets(STDIN));
                    $objResponsable = new responsable();
                    if ($objResponsable->buscar($num)) {

                        $objViaje = new Viaje();
                        $condicion = "rnumeroempleado=" . $objResponsable->getNumEmpleado();
                        $colecViajes = $objViaje->listar($condicion);
                        if ($colecViajes != []) {
                            echo "El responsable esta ligado a Algun Viaje, para borrarlo debera borrar los viajes en los que se encuentre";
                        } else {
                            if ($objResponsable->eliminar()) {

                                echo "\n el responsable se borro con exito";
                            }
                        }
                    }
                }
                break;
        }
    } while ($ope != 0);
}


function  menuPasajero($pasajero)
{
    do {
        echo "\n*+*+*+*+*+*+*+*+*+*+*+* \n";
        echo "\n menu Pasajero";
        echo "\n1.Cambiar nombre";
        echo "\n2.Cambiar apellido";
        echo "\n3.Cambiar Telefono";
        echo "\n4.Eliminar Pasajero";
        echo "\n0.Volver atras";
        echo "\n*+*+*+*+*+*+*+*+*+*+*+* \n";
        echo "Elija una opcion: ";
        $ope = trim(fgets(STDIN));
        switch ($ope) {
            case 1:
                echo "ingrese el nuevo nombre: ";
                $nom = trim(fgets(STDIN));
                $pasajero->setPNombre($nom);
                if ($pasajero->modificar()) {
                    echo "el nombre se cambio con exito!!";
                } else {
                    echo "hubo un error al cambiar el nombre";
                }
                break;
            case 2:
                echo "ingrese el nuevo apellido: ";
                $ape = trim(fgets(STDIN));
                $pasajero->setPApellido($ape);
                if ($pasajero->modificar()) {
                    echo "el apellido se cambio con exito!!";
                } else {
                    echo "hubo un error al cambiar el apellido";
                }
                break;
            case 3:
                echo "ingrese el nuevo numero de telefono: ";
                $tel = trim(fgets(STDIN));
                $pasajero->setPTelefono($tel);
                if ($pasajero->modificar()) {
                    echo "el telefono se cambio con exito!!";
                } else {
                    echo "hubo un error al cambiar el numero de telefono";
                }
                break;
            case 4:

                if ($pasajero->eliminar()) {
                    echo "el pasajero se elimino con exito!!";
                    $ope = 0;
                } else {
                    echo "hubo un error al eliminar el pasajero";
                }
                break;
        }
    } while ($ope != 0 && $ope != 0);
}
function coleccion_a_cadena($coleccion)
{
    $cadena = '';
    foreach ($coleccion as $objeto) {
        $cadena .= $objeto;
    }
    return $cadena;
}
function listarEmpresas()
{
    $hay = true;
    $objEmpresa = new empresa();
    $colecEmpresas = $objEmpresa->listar();
    if (count($colecEmpresas) > 0) {
        $cadena = coleccion_a_cadena($colecEmpresas);
        echo "\n////// LISTA DE EMPRESAS ///////\n";
        echo $cadena;
    } else {
        echo " \n---No hay empresas cargadas en el sistema---\n";
        $hay = false;
    }
    return $hay;
}
function listarViajes()
{
    $hay = true;
    $objViaje = new Viaje();
    $lista = $objViaje->listar();
    if (count($lista) > 0) {
        $cadena = coleccion_a_cadena($lista);
        echo "\n////// LISTA DE VIAJES ///////";
        echo $cadena;
    } else {
        echo " \n---No hay viajes cargados en el sistema---\n";
        $hay = false;
    }
    return $hay;
}
function listarResponsables()
{
    $hay = true;
    $objResponsable = new responsable();
    $lista = $objResponsable->listar();
    if (count($lista) > 0) {
        $cadena = coleccion_a_cadena($lista);
        echo "\n////// LISTA DE RESPONSABLES ///////";
        echo $cadena;
    } else {
        echo " \n---No hay responsables cargados en el sistema---\n";
        $hay = false;
    }
    return $hay;
}

function listarPasajeros()
{
    $hay = true;
    $objPasajero = new pasajero();
    $lista = $objPasajero->listar();
    if (count($lista) > 0) {
        $cadena = coleccion_a_cadena($lista);
        echo "\n////// LISTA DE PASAJEROS ///////";
        echo $cadena;
    } else {
        echo " \n---No hay pasajeros cargados en el Viaje---\n";
        $hay = false;
    }
    return $hay;
}


//PROGRAMA PRINCIPAL
principal();
