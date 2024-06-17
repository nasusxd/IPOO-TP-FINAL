<?php


include_once "persona.php";
include_once "pasajero.php";
include_once "responsable.php";
include_once "empresa.php";
include_once "viaje.php";
include_once "BaseDatos.php";

//FUNCIONES

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


// (modificar, eliminar, buscar, listar)  Persona, Responsable, Empresa, Viaje.

function principal()
{
    do {
        $objPersona = new persona();
        $objEmpresa = new empresa();
        $objResponsable = new responsable();
        $objPasajero = new pasajero();
        $objViaje = new viaje();


        opciones();
        echo "Seleccione una opción: ";
        $option = trim(fgets(STDIN));
        switch ($option) {
            case 1:
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
                echo "Ingrese número de documento de la persona a asignar como Responsable: ";
                $numDoc = trim(fgets(STDIN));

                // Buscar la persona por número de documento
                if ($objPersona->buscar($numDoc)) {
                    // La persona existe, obtener sus datos
                    $nombre = $objPersona->getPNombre();
                    $apellido = $objPersona->getPApellido();
                    $telefono = $objPersona->getPTelefono();

                    // Solicitar número de licencia     
                    echo "Ingrese número de licencia del Responsable: ";
                    $numLicencia = trim(fgets(STDIN));

                    // Crear instancia de Responsable con los datos de la persona
                    $objResponsable->setPDocumento($numDoc);
                    $objResponsable->setPNombre($nombre);
                    $objResponsable->setPApellido($apellido);
                    $objResponsable->setPTelefono($telefono);
                    $objResponsable->setNumLicencia($numLicencia);

                    // Insertar el responsable en la base de datos
                    if ($objResponsable->insertar()) {
                        echo "Responsable insertado con éxito. Número de Empleado: " . $objResponsable->getNumEmpleado() . "\n";
                    } else {
                        echo "Error al insertar el responsable: " . $objResponsable->getMensajeError() . "\n";
                    }
                } else {
                    echo "No se encontró una persona con el documento proporcionado.\n";
                }
                break;
            case 3:
                echo "Ingrese lugar de destino: ";
                $destino = trim(fgets(STDIN));
                echo "Ingrese cantidad maxima de pasajeros: ";
                $cantMaxPasajeros = trim(fgets(STDIN));
                echo "Ingrese id de impresa: ";
                $idEmpresa = trim(fgets(STDIN));
                echo "Ingrese numero de empleado: ";
                $numeroEmpleado = trim(fgets(STDIN));
                echo "Ingrese importe del viaje: ";
                $importeViaje = trim(fgets(STDIN));
                $objViaje->setVdestino($destino);
                $objViaje->setVcantmaxpasajeros($cantMaxPasajeros);
                if($objEmpresa->buscar($idEmpresa)){
                    $objViaje->setObjEmpresa($objEmpresa);
                }
                if($objResponsable->buscar($numeroEmpleado)){
                    $objViaje->setObjResponsable($objResponsable);
                }
                $objViaje->setVimporte($importeViaje);
                if ($objViaje->insertar()) {
                    echo "Viaje agregado con éxito. Número de Viaje: " . $objViaje->getIdviaje() . "\n";
                } else {
                    echo "Error al insertar el Viaje: " . $objViaje->getMensajeError() . "\n";
                }
            

                break;
            case 4:
                echo "Ingrese número de documento del Pasajero: ";
                $numDoc = trim(fgets(STDIN));

                // Buscar la persona por número de documento
                if ($objPersona->buscar($numDoc)) {
                    // La persona existe, obtener sus datos
                    $nombre = $objPersona->getPNombre();
                    $apellido = $objPersona->getPApellido();
                    $telefono = $objPersona->getPTelefono();

                    // Solicitar número de licencia     
                    echo "Ingrese el ID del Viaje: ";
                    $idViaje = trim(fgets(STDIN));

                    // Crear instancia de Pasajero con los datos de la persona
                    $objPasajero->setPDocumento($numDoc);
                    $objPasajero->setPNombre($nombre);
                    $objPasajero->setPApellido($apellido);
                    $objPasajero->setPTelefono($telefono);
                    if($objViaje->buscar($idViaje)){
                        $objPasajero->setObjViaje($objViaje);
                    }
                    

                    // Insertar el responsable en la base de datos
                    if ($objPasajero->insertar()) {
                        echo "Pasajero insertado con éxito. " . $objPasajero . "\n";
                    } else {
                        echo "Error al insertar el responsable: " . $objPasajero->getMensajeError() . "\n";
                    }
                } else {
                    echo "No se encontró una persona con el documento proporcionado.\n";
                }


                break;
            case 5:

                break;
            case 6: echo "ingrese el id del viaje";
                    $id = trim(fgets(STDIN));
                    $viaje = new Viaje();
                    $encontrado = $viaje->Buscar($id); 
                
                    if($encontrado == true){
                
                    
                       
                        do{
                            echo "\n------Ingrese dato que desea MODIFICAR del viaje------\n"
                                ."1) Destino.\n"
                                ."2) Cantidad Maxima de pasajeros.\n"
                                ."3) Responsable.\n"
                                ."4) Importe.\n"
                                ."5) Tipo de asiento.\n"
                                ."6) Ida y vuelta del viaje.\n"
                                ."7) Pasajeros.\n"
                                ."8) Eliminar un pasajero.\n"
                                ."9) Agregar un pasajero.\n"
                                ."10) Ver pasajeros del viaje.\n"
                                ."11) Ver responsable del viaje.\n"
                                ."12) Volver.\n";
                                
                            echo "Ingrese su eleccion: ";
                            $eleccion = trim(fgets(STDIN));
                            
                            //llama al metodo escogido por el usuario 
                            switch($eleccion){
                                case 1:echo "Ingrese destino nuevo del viaje: ";
                                $destino = trim(fgets(STDIN));
                                            if(verificarDestinoViaje($destino,$empresa)){
                                                $viaje->setVdestino($destino);
                                            }else{
                                                echo "Este destino ya ha sido ingresado, intente con otro";
                                            }
                                            break;
                                case 2:echo "Ingrese cantidad maxima de pasajeros nueva del viaje: ";
                                        $cantNueva = trim(fgets(STDIN));
                                        if($cantNueva>count($viaje->getColPasajeros())){
                                        $viaje->setVcantmaxpasajeros($cantNueva);break;
                                        }else{
                                        echo "La cantidad nueva es menor a la cantidad de pasajeros ya ingresados.\n"; 
                                        }
                                        break;
                                case 3: modificarResponsable($viaje);break;
                                case 4:echo "Ingrese nuevo importe del viaje: ";
                                            $importe = trim(fgets(STDIN));
                                            $viaje->setVimporte($importe);
                                            modificarBd($viaje);
                                            break;
                                case 5:echo "Ingrese nuevo tipo de asiento del viaje: ";
                                            $tipoAsiento = trim(fgets(STDIN));
                                            $viaje->setTipoAsiento($tipoAsiento);
                                            modificarBd($viaje);
                                            break;
                                case 6:echo "Ingrese si es de ida y vuelta o no: ";
                                            $idavuelta = trim(fgets(STDIN));
                                            $viaje->setIdayvuelta($idavuelta);
                                            modificarBd($viaje);
                                            break;
                                case 7: modificarDatosPasajero($viaje);break;
                                case 8: eliminarPasajero($viaje);break;
                                case 9: ingresarPasajeros($viaje);break;
                                case 10: echo $viaje->stringColeccion($viaje->getColPasajeros());break;
                                case 11: echo $viaje->getRefResponsable();break;
                                case 12: echo "Volviendo al menú principal...\n";break;
                                default:echo "Elección inexistente, ingrese otra\n";break;
                            }
                        }while($eleccion!=12);
                        
                    }else{    
                        echo "\n¡Viaje no encontrado!\n";
                    }    
                } else { echo "\n no se encontro una empresa con ese ID ";}
                break;
            case 7:

                break;
            case 8:

                break;
            case 9:

                break;
            case 10:

                break;
            case 11:

                break;
            case 12:

                break;
            case 13:

                break;
            case 14:

                break;
            case 15:

                break;
            case 16:

                break;
            case 17:

                break;
            case 18:

                break;
            case 19:

                break;
            case 20:

                break;
            case 0:
                echo "Saliendo...\n";
                break;
            default:
                echo "Opción no válida, intente de nuevo\n";
                break;
        }
    } while ($option != 0);
}

principal();
