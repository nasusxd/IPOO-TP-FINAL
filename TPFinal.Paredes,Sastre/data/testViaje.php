<?php


include_once "Persona.php";
include_once "Responsable.php";
include_once "Empresa.php";
include_once "Viaje.php";
include_once "BaseDatos.php";

//FUNCIONES

function opciones() {
    echo "\n=== MENU ===\n";
    echo "1. Insertar Empresa\n";
    echo "2. Insertar Viaje\n";
    echo "3. Insertar Responsable\n";
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

function principal() {
    do {
        opciones();
        echo "Seleccione una opción: ";
        $option = trim(fgets(STDIN));
        switch ($option) {
            case 1:
                
                break;
            case 2:
               
                break;
            case 3:
                
                break;
            case 4:
                
                break;
            case 5:
                
                break;
            case 6:
               
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
?>