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
    echo "\n4.Administrar Viajes";
    echo "\n5.Administrar Responsables";
    echo "\n";
    echo "\n0.Salir";
    echo "\n*+*+*+*+*+*+*+*+*+*+*+* \n";
    echo "Elija una opcion: ";
}
function opciones()
{
    echo "\n=== MENU ===\n";
    echo "1. Insertar Empresa\n";
    echo "2. Insertar Responsable\n";
    echo "3. Insertar Viaje\n";
    echo "4. Insertar Pasajero\n";
    echo "5. Modificar Empresa\n";
    echo "6. Modificar Viaje\n";
    echo "7. Modificar Responsable\n";
    echo "8. Modificar Pasajero\n";
    echo "9. Eliminar Empresa\n";
    echo "10.Eliminar Viaje\n";
    echo "11.Eliminar Responsable\n";
    echo "12.Eliminar Pasajero\n";
    echo "13. Buscar Empresa\n";
    echo "14. Buscar Responsable\n";
    echo "15. Buscar Viaje\n";
    echo "16. Buscar Pasajero\n";
    echo "17. Listar Empresas\n";
    echo "18. Listar Viajes\n";
    echo "19. Listar Responsables\n";
    echo "20. Listar Pasajeros\n";
    echo "0. Salir\n";
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
        echo "\n1.Ver lista de viajes";
        echo "\n2.Modificar un viaje";
        echo "\n3.Eliminar un viaje";
        echo "\n4.Ingresar un nuevo viaje";
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
                $objViaje = new Viaje();
                $cadena = coleccion_a_cadena($objViaje->listar());
                echo "\n////// LISTA DE VIAJES ///////";
                echo $cadena;

                break;
            case 2:
                opcionesDeUnViaje();
                break;
            case 3:
                break;
            case 4:
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
                    $cantPasa = count($objViaje->getArrayObjPasajero());
                    $limite = $objViaje->getVcantmaxpasajeros();
                    if($cantPasa < $limite){
                     echo "Ingrese número de documento del Pasajero: ";
                     $numDoc = trim(fgets(STDIN));
                     $esta=false;
                     $i=0;
                     $colecPasajeros=$objViaje->getArrayObjPasajero();
                     while($i<$cantPasa && $esta==false){
                        $pasajero=$colecPasajeros[$i];
                        if($pasajero->getPDocumento()==$numDoc){
                            $esta=true;
                        }$i++;
                     }
                      if($esta==false){
                        $objPasajero = new pasajero();
                        $objPersona = new Persona;
                        if ($objPersona->buscar($numDoc)) {
                           $nombre = $objPersona->getPNombre();
                            $apellido = $objPersona->getPApellido();
                            $telefono = $objPersona->getPTelefono();
                            $objPasajero->setPDocumento($numDoc);
                            $objPasajero->setPNombre($nombre);
                            $objPasajero->setPApellido($apellido);
                            $objPasajero->setPTelefono($telefono);
                            $objPasajero->setObjViaje($objViaje);
                           
                            if( $objPasajero->modificar()){}
                            //INCOMPLETO
                            $colecPasajeros =array_push($colecPasajeros,)
                            $objViaje->setArrayObjPasajero()
                         }else{
                            echo "\nno hay una persona con ese documento cargado en el sistema.";}
                    }else{
                        echo "\nEl pasajero ya esta en viaje";}
                }else{
                    echo "\nLa cantidad de pasajeros del viaje ya llego a su limite";}
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
                    break;
                case 8:
                    break;
                case 9:
                    break;
            }
        } while ($ope != 0);
    }
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
