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
                $objEmpresa->cargar($nombre,$direccion);
                if ($objEmpresa->insertar()) {
                    echo "Empresa insertada con éxito.\n";
                } else {
                    echo "Error al insertar la empresa: " . $objEmpresa->getMensajeError() . "\n";
                }
                break;

            case 2:
                echo "ingrese el ID de la empresa: ";
                $objEmpresa = new empresa();

                $id = trim(fgets(STDIN));
                if ($objEmpresa->buscar($id)) {
                    menuEmpresa($objEmpresa);
                } else {
                    echo "No se a encontrado una empresa con ese id";
                }

                break;

            case 3:
                $objEmpresa = new empresa();
                $colecEmpresas = $objEmpresa->listar();
                $cadena = coleccion_a_cadena($colecEmpresas);
                echo "\n////// LISTA DE EMPRESAS ///////";
                echo $cadena;
                break;

            case 0:
                break;
            default:
                echo "\ningrese un numero de la lista";
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
                echo "\nSe eliminara la empresa y con ella, los viajes, el resposable de cada uno de ellos y la lista de pasajeros";
                $condicion = "idempresa=" . $objEmpresa->getidempresa();
                $objViaje = new Viaje();
                $colViajes = $objViaje->listar($condicion);
                if ($colViajes <> []) {
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
                $objEmpresa->eliminar();
                echo "La empresa se elimino con exito";
                break;
            default:
                echo "\ningrese un numero de la lista";
                break;
        }
    } while ($ope != 0);
}

// MENU DE VIAJES
function menuViajes()
{
    do {
        echo "\n*+*+*+*+*+*+*+*+*+*+*+* \n";
        echo "\n menu de Viajes";
        echo "\n1.Ingresar un nuevo viaje";
        echo "\n2.Modificar un viaje";
        echo "\n3.Eliminar un viaje";
        echo "\n4.Ver lista de viajes";
        echo "\n5.Ver Datos de un viaje";
        echo "\n0.volver atras";
        echo "\n*+*+*+*+*+*+*+*+*+*+*+* \n";
        echo "Elija una opcion: ";
        $ope = trim(fgets(STDIN));
        switch ($ope) {
            case 0:
                echo "\nVolviendo al menu anterior...\n";
                break;
            case 1:
               

                break;
            case 2:
                opcionesDeUnViaje();
                break;
            case 3:
                break;
            case 4: $objViaje = new Viaje();
                $cadena = coleccion_a_cadena($objViaje->listar());
                echo "\n////// LISTA DE VIAJES ///////";
                echo $cadena;
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
    echo "Ingrese el id del viaje que desea modificar";
    $objViaje = new Viaje();
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
            echo "\nACLARACION no se puede cambiar la empresa y tampoco se puede eliminar el responsable";
            echo "\n*+*+*+*+*+*+*+*+*+*+*+* \n";
            echo "\nElija una opcion: ";
            $ope = trim(fgets(STDIN));

            switch ($ope) {
                case 0:
                    echo "\nVolviendo al menu anterior...\n";
                    break;
                case 1:
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
                    }else{echo "\nEl limite ingresado es menor a la cantidad actual de pasajeros";}
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
                    // Obtener la cantidad de pasajeros actual y el límite de pasajeros del viaje
    $cantPasa = count($objViaje->getArrayObjPasajero());
    $limite = $objViaje->getVCantMaxPasajeros();

    // Verificar si hay espacio en el viaje
    if($cantPasa < $limite) {
        echo "Ingrese número de documento del Pasajero: ";
        $numDoc = trim(fgets(STDIN));
        $esta = false;
        $i = 0;
        $colecPasajeros = $objViaje->getArrayObjPasajero();

        // Verificar si el pasajero ya está en el viaje
        while($i < $cantPasa && !$esta) {
            $pasajero = $colecPasajeros[$i];
            if($pasajero->getPDocumento() == $numDoc) {
                $esta = true;
            }
            $i++;
        }

        // Si el pasajero no está en el viaje
        if(!$esta) {
            $objPasajero = new Pasajero();
            $objPersona = new Persona();

            
            if ($objPersona->buscar($numDoc)) {
               
                $nombre = $objPersona->getPNombre();
                $apellido = $objPersona->getPApellido();
                $telefono = $objPersona->getPTelefono();

               $objPasajero->cargar($numDoc,$nombre, $apellido, $telefono, $objViaje);
              
                if($objPasajero->insertar()) {
                   
                    array_push($colecPasajeros, $objPasajero);

                   
                    $objViaje->setArrayObjPasajero($colecPasajeros);
                    if($objViaje->modificar()){
                        echo "Pasajero agregado exitosamente al viaje.\n";
                    }else{echo "error al querer guardar la lista de pasajeros";}
                    
                } else {
                    echo "Error al insertar el pasajero.\n";
                }
            } else {
                echo "No se encontró la persona con el documento proporcionado.\n";
            }
        } else {
            echo "El pasajero ya está registrado en este viaje.\n";
        }
    } else {
        echo "No hay espacio disponible en el viaje.\n";
    }
                    break;
                case 5:
                    $cadena = "\n>>>>>>>>>>>>>>>>>>>>>>>>>>Lista de pasajeros<<<<<<<<<<<<<<<<<<<<<<<<<\n";
                   $cadena.= coleccion_a_cadena($objViaje->getArrayObjPasajero());
                   echo $cadena;
                    break;
                case 6:
                    echo "Ingrese número de documento del Pasajero: ";
                    $numDoc = trim(fgets(STDIN));
                    $esta=false;
                    $i=0;
                    $cantPasa = count($objViaje->getArrayObjPasajero());
                    $colecPasajeros=$objViaje->getArrayObjPasajero();
                    while($i<$cantPasa && $esta==false){
                       $pasajero=$colecPasajeros[$i];
                       if($pasajero->getPDocumento()==$numDoc){
                           $esta=true;
                           echo $pasajero;
                       }$i++;
                    if($esta==false){
                        echo "El pasajero no se encuentra en este viaje";
                    }

                    break;
                case 7: 
                    $objResponsable= $objViaje->getObjResponsable();
                       echo "Los datos del responsable son los siguientes\n". $objResponsable;
                    break;
                case 8:
                    $objResponsable= $objViaje->getObjResponsable();
                    echo "los datos del Responsable son los siguientes: \n".
                    $objResponsable.
                    "\n Desea cambiar de responsable? (s/n)";
                    $resp = trim(fgets(STDIN));
                    if($resp == "s"){
                        $objResponsable2=new Responsable();
                        $list = $objResponsable2->listar();
                       $lista = coleccion_a_cadena($list);
                     do{echo "<<<<Lista de responsables>>>>\n". 
                        $lista.
                        "\n Ingrese el numero de responsable: ";
                        $num=trim(fgets(STDIN));
                        $cantResp=count($list);
                        $i=0;
                        $esta=false;
                        while($i<$cantResp && $esta == false){
                            $responsable = $list[$i];
                            if($resposable->getNumEmpleado()==$num){
                                $esta=true;
                                $objViaje->setObjResponsable($responsable);
                                if($objViaje->modificar()){
                                    echo "\nSe cambio con exito el responsable";
                                }else{echo $objViaje->getMensajeError();}
                            }$i++;

                        }if($esta==false){
                            echo "No se encontro el Resposable ";
                        }}while($esta==false);
                       
                    }else{echo "\n el Responsable no a cambiado";}
                    break;
                
            }
        } while ($ope != 0);
    }
  }else{echo "El viaje con el id: $id no se a encontrado";}
}


function coleccion_a_cadena($coleccion)
{
    $cadena = '';
    foreach ($coleccion as $objeto) {
        $cadena .= $objeto;
    }
    return $cadena;
}


//PROGRAMA PRINCIPAL
principal();
