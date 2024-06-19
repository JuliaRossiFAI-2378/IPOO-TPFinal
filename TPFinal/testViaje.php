<?php
include_once 'Persona.php';
include_once 'Pasajero.php';
include_once 'ResponsableV.php';
include_once 'Viaje.php';
include_once 'Empresa.php';
include_once 'BDViajes.php';
$viaje = new Viaje();
$responsable = new ResponsableV();
$empresa = new Empresa();
$pasajero = new Pasajero();
$persona = new Persona();

/** recibe un intervalo y pide num valido por pantalla
 * @param int $minimo, $maximo
 * @return int
 */
function solicitarNumeroEntre($minimo,$maximo){
    $numero = trim(fgets(STDIN));
    while (!($numero>=$minimo && $numero<=$maximo) || !(is_numeric($numero))){
        echo "Debe ingresar un numero que este entre ".$minimo." y ".$maximo.": ";
        $numero = trim(fgets(STDIN));
    }
    return $numero;
}
/** muestra una lista de empresas por pantalla
 */
function verIDsEmpresas(){
    $empresa = new Empresa();
    $idEmpresas = $empresa->listar();
    echo "\nLista de empresas:\n";
    foreach($idEmpresas as $id){
        echo "\nEmpresa con ID ".$id->getIdEmpresa()." llamada '".$id->getNombre()."'";
    }
    echo "\n";
}
/** muestra una lista de viajes por pantalla
 */
function verIDsViajes(){
    $viaje = new Viaje();
    $idViaje = $viaje->listar();
    echo "\nLista de viajes:\n";
    foreach($idViaje as $id){
        echo "\nViaje con ID numero ".$id->getIdViaje()." y destino a '".$id->getDestino()."'";
    }
    echo "\n";
}
/** muestra una lista de responsables por pantalla
 */
function verIDsResponsables(){
    $responsable = new ResponsableV();
    $idResponsable = $responsable->listar();
    echo "\nLista de responsables:\n";
    foreach($idResponsable as $id){
        echo "\nResponsable con ID numero ".$id->getNumEmpleado()." llamado/a '".$id->getNombre()."'";
    }
    echo "\n";
}
/** recibe un dato y devuelve si es un numero entero
 * @param any $dato
 * @return boolean
 */
function esNum($dato){
    $numero = false;
    if(is_numeric($dato)){
        $dato += 0;//se convierte en int o float segun su valor
        if(is_int($dato) && $dato>0){
            $numero = true;
        }
    }else{
        echo "\nDebe ingresar un numero entero mayor a cero.\n";    
    }
    return $numero;
}
/** recibe un dato y devuelve si es un string no vacio
 * @param any $dato
 * @return boolean
 */
function esString($dato){//no creo usar esto nunca, no vale la pena
    $cadena = false;
    if(is_string($dato) && $dato != ""){
        $cadena = true;
    }else{
        echo "\nDebe ingresar una cadena de uno o mas caracteres.\n";
    }
    return $cadena;
}

function seleccionarOpcion(){
    echo "\n[1] Ingresar, editar o eliminar una empresa.\n";
    echo "[2] Visualizar datos empresa.\n";
    echo "[3] Ingresar, editar o eliminar un viaje.\n";
    echo "[4] Visualizar datos de un viaje o todos los viajes.\n";
    echo "[5] Ingresar, editar o eliminar el responsable de un viaje.\n";
    echo "[6] Visualizar datos del responsable de un viaje.\n";
    echo "[7] Ingresar, editar o eliminar pasajero\n";
    echo "[8] Visualizar datos de los pasajeros de un viaje\n";
    echo "[9] Salir.\n";
    echo "Ingrese la opcion del menu que desea elegir: ";
    //Verifica que el numero elegido vaya unicamente entre las opciones del menu
    $opcionMenu = solicitarNumeroEntre(1,9);
    return $opcionMenu;
}

do{
    $opcion = seleccionarOpcion();
switch($opcion){
    case 1://ingresar/editar/eliminar una empresa
        do{
            echo "\n[1] Ingresar una empresa.\n";
            echo "[2] Editar datos empresa.\n";
            echo "[3] Eliminar empresa.\n";
            echo "[4] Volver al menu anterior.\n";
            echo "Ingrese la opcion del menu que desea elegir: ";
            $opcionMenuEmpresa = solicitarNumeroEntre(1,4);
            switch($opcionMenuEmpresa){
                case 1://ingresar empresa
                    echo "Ingrese el nombre de la empresa: ";
                    $nombreEmpresa = trim(fgets(STDIN));
                    if(esString($nombreEmpresa)){
                        echo "Ingrese la direccion de la empresa: ";
                        $direccionEmpresa = trim(fgets(STDIN));
                        if(esString($direccionEmpresa)){
                            $empresa = new Empresa();
                            $empresa->cargar(null,$nombreEmpresa,$direccionEmpresa);
                            $empresa->ingresar();
                            echo "\nEmpresa ingresada con exito.\n";
                        }
                    }
                    break;
                case 2://editar datos empresa
                    if($empresa->listar() != null){
                        verIDsEmpresas();
                        echo "\nIngrese el ID de la empresa que desea editar: ";
                        $idEmpresa = trim(fgets(STDIN));
                        if(esNum($idEmpresa)){
                            if($empresa->buscar($idEmpresa)){           
                                do{
                                    echo "\n[1]Modificar el nombre de la empresa.\n";
                                    echo "[2]Modificar la direccion de la empresa.\n";
                                    echo "[3]Volver al menu anterior.\n";
                                    echo "Ingrese la opcion del menu que desea elegir: ";
                                    $opcionMenuDatosEmpresa = solicitarNumeroEntre(1,3);
                                    switch($opcionMenuDatosEmpresa){
                                        case 1://editar nombre empresa
                                            echo "Ingrese el nuevo nombre de empresa: ";
                                            $nuevoNombre = trim(fgets(STDIN));
                                            if(esString($nuevoNombre)){
                                                $empresa->setNombre($nuevoNombre);
                                                $empresa->modificar();
                                                echo "\nCambio realizado con exito.\n";
                                            }
                                            break;
                                        case 2://editar direccion empresa
                                            echo "Ingrese la nueva direccion de empresa: ";
                                            $nuevoDireccion = trim(fgets(STDIN));
                                            if(esString($nuevoDireccion)){
                                                $empresa->setDireccion($nuevoDireccion);
                                                $empresa->modificar();
                                                echo "\nCambio realizado con exito.\n";
                                            }
                                            break;
                                        case 3://volver atras
                                            break;
                                    }
                                }while($opcionMenuDatosEmpresa!=3);
                            }else{
                                echo "\nNo hay una empresa con ese ID en la base de datos.\n";     
                            }
                        }                      
                    }else{
                        echo "\nNo hay ninguna empresa registrada, sera regresado al menu anterior.\n";
                    }
                    break;
                case 3://eliminar empresa 
                    if($empresa->listar() != null){
                        verIDsEmpresas();
                        echo "\nIngrese el ID de la empresa que desea eliminar: ";
                        $idEmpresa = trim(fgets(STDIN));
                        if(esNum($idEmpresa)){
                            if($empresa->buscar($idEmpresa)){
                                if(($viajes = $viaje->listar("idempresa=" . $empresa->getIdEmpresa())) != null){
                                    echo "La empresa actualmente esta asociada a ".count($viajes)." viaje/s";
                                    echo "\nSeguro que desea eliminar la empresa '" . $empresa->getNombre() . "' de id: " . $idEmpresa . "?";
                                    echo "\n[1]Eliminar empresa. (esta accion no se puede deshacer)";
                                    echo "\n[2]Cancelar operacion.";
                                    echo "\nSeleccione que accion desea realizar: ";
                                    $eleccion = solicitarNumeroEntre(1,2);
                                    if($eleccion == 1){
                                        $empresa->eliminar();
                                        echo "\nEmpresa eliminada con exito.\n";
                                    }else{
                                        echo "\nOperacion cancelada.\n";
                                    }
                                }else{
                                    $empresa->eliminar();
                                    echo "\nEmpresa eliminada con exito.\n";
                                }
                            }else{
                                echo "\nNo hay una empresa con ese ID en la base de datos.\n";
                            }
                        }
                    }else{
                        echo "\nNo hay ninguna empresa registrada, sera regresado al menu anterior.\n";
                    }
                    break;
                case 4:
                    break;
            }
        }while($opcionMenuEmpresa != 4);
        break;
    case 2://visualizar datos empresa 
        if($empresa->listar() != null){
            verIDsEmpresas();
            echo "\nIngrese el ID de la empresa que desea ver: ";
            $idEmpresa = trim(fgets(STDIN));
            if(esNum($idEmpresa)){
                $cantViajesEmpresa = $viaje->listar("idempresa=$idEmpresa");
                if($empresa->buscar($idEmpresa)){
                    echo $empresa."\nCantidad de viajes asociados a la empresa: ".count($cantViajesEmpresa)."\n";
                }else{
                    echo "\nNo hay una empresa con ese ID en la base de datos.\n";
                }
            }
        }else{
            echo "\nNo hay ninguna empresa registrada, sera regresado al menu anterior.\n";
        }
        break;
    case 3://ingresar/editar/eliminar viaje  
        do{
            echo "\n[1] Ingresar un viaje.\n";
            echo "[2] Editar datos de un viaje.\n";
            echo "[3] Eliminar un viaje.\n";
            echo "[4] Volver al menu anterior.\n";
            echo "Ingrese la opcion del menu que desea elegir: ";
            $opcionMenuViaje = solicitarNumeroEntre(1,4);
            switch($opcionMenuViaje){
                case 1://ingresar viaje
                    if($empresa->listar() != null){
                        if($responsable->listar() != null){
                            echo "Ingrese el destino del viaje: ";
                            $destViaje = trim(fgets(STDIN));
                            if(esString($destViaje)){
                                echo "Ingrese la cantidad maxima de pasajeros: ";
                                $cantidadMaximaPasajeros = trim(fgets(STDIN));
                                if(esNum($cantidadMaximaPasajeros)){
                                    verIDsEmpresas();
                                    echo "Ingrese el ID de la empresa que realiza el viaje: ";
                                    $idEmpresa = trim(fgets(STDIN));
                                    if(esNum($idEmpresa)){
                                        if($empresa->buscar($idEmpresa)){
                                            echo "Ingrese el costo del viaje: ";
                                            $costoViaje = trim(fgets(STDIN));
                                            if(esNum($costoViaje)){
                                                verIDsResponsables();
                                                echo "Ingrese el numero de empleado del responsable: ";
                                                $numeroEmpleado = trim(fgets(STDIN));
                                                if(esNum($numeroEmpleado)){
                                                    if($responsable->buscar($numeroEmpleado)){
                                                        $viaje->cargar(null,$destViaje,$cantidadMaximaPasajeros,$idEmpresa,[],$numeroEmpleado, $costoViaje);
                                                        if($viaje->ingresar()){
                                                            echo "\nViaje ingresado con exito.";
                                                        }
                                                    }else{
                                                        echo "\nNo existe responsable con ese ID.\n";
                                                    }                                                    
                                                }
                                            }
                                        }else{
                                            echo "\nNo existe empresa con ese ID.\n";
                                        }
                                        
                                    }
                                }
                            }
                        }else{
                            echo "\nNo hay ningun responsable registrado, por ende no se puede registrar el viaje, se regresara al menu anterior.\n";
                        }
                    }else{
                        echo "\nNo hay ninguna empresa registrada, por ende no se puede registrar el viaje, se regresara al menu anterior.\n";
                    }
                    break;
                case 2://editar datos viaje 
                    if($viaje->listar() != null){
                        verIDsViajes();
                        echo "\nIngrese el ID del viaje que desea editar: ";
                        $idViaje = trim(fgets(STDIN));
                        if(esNum($idViaje)){
                            if($viaje->buscar($idViaje)){
                                do{
                                    echo "\n[1] Modificar el destino del viaje.\n";
                                    echo "[2] Modificar la cantidad maxima de pasajeros en el viaje.\n";
                                    echo "[3] Cambiar responsable.\n";
                                    echo "[4] Modificar el costo del viaje.\n";
                                    echo "[5] Volver al menu anterior.\n";
                                    echo "Ingrese la opcion del menu que desea elegir: ";
                                    $opcionMenuDatosViajes = solicitarNumeroEntre(1,5);
                                    switch($opcionMenuDatosViajes){
                                        case 1://editar destino
                                            echo "Ingrese el nuevo destino: ";
                                            $nuevoDestino = trim(fgets(STDIN));
                                            if(esString($nuevoDestino)){
                                                $viaje->setDestino($nuevoDestino);
                                                $viaje->modificar();
                                                echo "\nModificacion realizada con exito.\n";
                                            }
                                            break;
                                        case 2://editar cantidad maxima de pasajeros
                                            echo "Ingrese nueva cantidad maxima de pasajeros: ";
                                            $nuevoCantMaxPasajeros = trim(fgets(STDIN));
                                            if(esNum($nuevoCantMaxPasajeros)){
                                                $pasajeros = count($viaje->getColPasajeros());
                                                if($pasajeros <= $nuevoCantMaxPasajeros){
                                                    $viaje->setCantMaxPasajeros($nuevoCantMaxPasajeros);
                                                    $viaje->modificar();
                                                    echo "\nModificacion realizada con exito.\n";
                                                }else{
                                                    echo "\nNo se puede poner el limite de pasajeros debajo de la cantidad actual de:".$pasajeros." pasajeros.\n";
                                                }
                                            }
                                            break;
                                        case 3://cambiar empleado responsable
                                            verIDsResponsables();
                                            echo "Ingrese numero de empleado nuevo: ";
                                            $idEmpleado = trim(fgets(STDIN));
                                            if(esNum($idEmpleado)){
                                                if($responsable->buscar($idEmpleado)){
                                                    $viaje->setNumEmpleado($idEmpleado);
                                                    echo "\nModificacion realizada con exito.\n";
                                                }else{
                                                    echo "\nNo existe empleado con ese ID.\n";
                                                }
                                            }
                                            break;
                                        case 4://editar importe
                                            echo "Ingrese nuevo importe: ";
                                            $nuevoCostoViaje = trim(fgets(STDIN));
                                            if(esNum($nuevoCostoViaje)){
                                                $viaje->setCostoViaje($nuevoCostoViaje);
                                                $viaje->modificar();
                                                echo "\nModificacion realizada con exito.\n";
                                            }
                                            break;
                                        case 5://volver atras
                                            break;
                                    }
                                }while($opcionMenuDatosViajes != 5);
                            }else{
                                echo "\nNo hay una viaje con ese ID en la base de datos.\n";
                            }
                        }                      
                    }else{
                        echo "\nNo hay ningun viaje registrado, sera regresado al menu anterior.\n";
                    }
                    break;    
                case 3://eliminar viaje
                    if($viaje->listar() != null){
                        verIDsviajes();
                        echo "\nIngrese el ID del viaje que desea eliminar: ";
                        $idViaje = trim(fgets(STDIN));
                        if(esNum($idViaje)){
                            if($viaje->buscar($idViaje)){
                                if(($pasajeros = $viaje->getColPasajeros()) != null){
                                    echo "El viaje tiene ".count($pasajeros)." pasajeros registrados.";
                                    echo "\nSeguro que desea eliminar el viaje con destino a '" . $viaje->getDestino()."' de id: ".$idViaje."?";
                                    echo "\n[1]Eliminar viaje. (esta accion no se puede deshacer)";
                                    echo "\n[2]Cancelar operacion.";
                                    echo "\nSeleccione que accion desea realizar: ";
                                    $eleccion = solicitarNumeroEntre(1,2);
                                    if($eleccion == 1){
                                        $viaje->eliminar();
                                        echo "\nViaje eliminado con exito.\n";
                                    }else{
                                        echo "Operacion cancelada.\n";
                                    }
                                }else{
                                    $viaje->eliminar();
                                    echo "\nViaje eliminado con exito.\n";
                                }
                            }else{
                                echo "\nNo hay un viaje con ese ID en la base de datos.\n";
                            }            
                        }                    
                    }else{
                        echo "\nNo hay ningun viaje registrado, sera regresado al menu anterior.\n";
                    }
                    break;
                case 4:
                    break;
            }
        }while($opcionMenuViaje !=4);
        break;
    case 4://visualizar datos viaje/s
        if($viaje->listar() != null){
            do{
                echo "\n[1] Visualizar un viaje en especifico.\n";
                echo "[2] Visualizar todos los viajes.\n";
                echo "[3] Volver al menu anterior.\n";
                echo "Ingrese la opcion del menu que desea elegir: ";
                $opcionVisualizarViajes = solicitarNumeroEntre(1,3);
                switch($opcionVisualizarViajes){
                    case 1://mostrar un viaje en especifico
                        verIDsViajes();
                        echo "\nIngrese el ID del viaje que desea ver: ";
                        $idViaje = trim(fgets(STDIN));
                        if(esNum($idViaje)){
                            if($viaje->buscar($idViaje)){
                                echo $viaje;
                            }else{
                                echo "\nNo hay un viaje con ese ID en la base de datos.\n";
                            }
                        }
                        break;
                    case 2://mostrar todos los viajes
                        $colViajes = $viaje->listar();
                        foreach($colViajes as $unViaje){
                            echo "-------------------------------------------------------------------------";
                            echo $unViaje;
                            echo "-------------------------------------------------------------------------";
                        }
                        break;
                    case 3:
                        break;
                }
            }while($opcionVisualizarViajes != 3);
        }else{
            echo "\nNo hay ningun viaje registrado, sera regresado al menu anterior.\n";
        }
        break;
    case 5://ingresar/editar/eliminar responsable   
        do{
            echo "\n[1] Ingresar un responsable.\n";
            echo "[2] Editar datos de un responsable.\n";
            echo "[3] Eliminar un responsable.\n";
            echo "[4] Volver al menu anterior.\n";
            echo "Ingrese la opcion del menu que desea elegir: ";
            $opcionMenuResponsable = solicitarNumeroEntre(1,4);
            switch($opcionMenuResponsable){
                case 1://ingresar responsable
                    echo "Ingrese el numero de documento del responsable: ";
                    $documentoResponsable = trim(fgets(STDIN));
                    if(esNum($documentoResponsable)){
                        if($responsable->listar("rdocumento= $documentoResponsable") == null){//si la consulta devuelve vacio
                            if(!($persona->buscar($documentoResponsable))){
                                echo "Ingrese el nombre del responsable: ";
                                $nombreResponsable = trim(fgets(STDIN));
                                if(esString($nombreResponsable)){
                                    echo "Ingrese el apellido del responsable: ";
                                    $apellidoResponsable = trim(fgets(STDIN));
                                    if(esString($apellidoResponsable)){
                                        echo "Ingrese el numero de telefono del responsable: ";
                                        $telefonoResponsable = trim(fgets(STDIN));
                                        if(esNum($telefonoResponsable)){
                                            echo "Ingrese el numero de licencia del responsable: ";
                                            $numLicencia = trim(fgets(STDIN));
                                            if(esNum($numLicencia)){
                                                $nuevoResponsable = new ResponsableV();
                                                $nuevoResponsable->cargar($nombreResponsable,$apellidoResponsable,$documentoResponsable,$telefonoResponsable,null,$numLicencia);
                                                if($nuevoResponsable->ingresar()){
                                                    echo "\nResposable agregado con exito.\n";
                                                }
                                            }
                                        }
                                    }
                                }
                            }else{
                                echo "\nYa existe una persona en la base de datos con el dni: ".$documentoResponsable.".\n";
                            }
                        }else{
                            echo "\nYa existe un empleado responsable con el dni: ".$documentoResponsable.".\n";
                        }
                    }
                    break;
                case 2://editar responsable  
                    if($responsable->listar() != null){
                        verIDsResponsables();
                        echo "Ingrese el ID del responsable que desea editar :";
                        $numeroEmpleado = trim(fgets(STDIN));
                        if(esNum($numeroEmpleado)){
                            if($responsable->buscar($numeroEmpleado)){
                                do{
                                    echo "\n[1] Editar el nombre del responsable.\n";
                                    echo "[2] Editar el apellido del responsable.\n";
                                    echo "[3] Editar el telefono del responsable.\n";
                                    echo "[4] Editar el numero de licencia del responsable.\n";
                                    echo "[5] Volver al menu anterior.\n";
                                    echo "Ingrese la opcion del menu que desea elegir: ";
                                    $opcionMenuDatosResponsable = solicitarNumeroEntre(1,5);
                                    switch($opcionMenuDatosResponsable){
                                        case 1://cambiar nombre
                                            echo "Ingrese el nuevo nombre: ";
                                            $nombre = trim(fgets(STDIN));
                                            if(esString($nombre)){
                                                $responsable->setNombre($nombre);
                                                $responsable->modificar();
                                                echo "\nNombre modificado con exito.\n";
                                            }
                                            break;
                                        case 2://cambiar apellido
                                            echo "Ingrese el nuevo apellido: ";
                                            $apellido = trim(fgets(STDIN));
                                            if(esString($apellido)){
                                                $responsable->setApellido($apellido);
                                                $responsable->modificar();
                                                echo "\nApellido modificado con exito.\n";
                                            }
                                            break;
                                        case 3://cambiar telefono
                                            echo "Ingrese el nuevo telefono: ";
                                            $telefono = trim(fgets(STDIN));
                                            if(esNum($telefono)){
                                                $responsable->setTelefono($telefono);
                                                $responsable->modificar();
                                                echo "\nTelefono modificado con exito.\n";
                                            }
                                            break;
                                        case 4://cambiar numero licencia
                                            echo "Ingrese el nuevo numero de licencia: ";
                                            $numLicencia = trim(fgets(STDIN));
                                            if(esNum($numLicencia)){
                                                $responsable->setNumLicencia($numLicencia);
                                                $responsable->modificar();
                                                echo "\nNumero de licencia modificado con exito.\n";
                                            }
                                            break;
                                        case 5://volver atras
                                            break;
                                    }
                                }while($opcionMenuDatosResponsable != 6);
                            }else{
                                echo "\nNo hay un responsable con ese ID en la base de datos.\n";
                            }
                        }                     
                    }else{
                        echo "\nNo hay ningun responsable registrado, sera regresado al menu anterior.\n";
                    }
                    break;
                case 3://eliminar responsable   
                    if($responsable->listar() != null){
                        verIDsResponsables();
                        echo "Ingrese el ID del responsable que desea eliminar :";
                        $numeroEmpleado = trim(fgets(STDIN));
                        if(esNum($numeroEmpleado)){
                            if($responsable->buscar($numeroEmpleado)){
                                if(($cantViajes = $viaje->listar("rnumeroempleado=$numeroEmpleado")) != null){
                                    echo "El empleado se encuentra a cargo de ".count($cantViajes)." viaje/s";
                                    echo "\nSeguro que desea eliminar al responsable llamado ".$responsable->getNombre().
                                        " con ID numero ".$responsable->getNumEmpleado()."?";
                                    echo "\n[1] Eliminar responsable. (esta accion no se puede deshacer)";
                                    echo "\n[2] Cancelar operacion.";
                                    echo "\nSeleccione que accion desea realizar: ";
                                    $eleccion = solicitarNumeroEntre(1,2);
                                    if($eleccion == 1){
                                        echo "\nResponsable eliminado con exito.\n";
                                        $responsable->eliminar();
                                    }else{
                                        echo "Operacion cancelada.\n";
                                    }
                                }else{
                                    $responsable->eliminar();
                                    echo "\nResponsable eliminado con exito.\n";
                                }
                            }else{
                                echo "\nNo hay un responsable con ese ID en la base de datos.\n";
                            }
                        }
                    }else{
                        echo "\nNo hay ningun responsable registrado, sera regresado al menu anterior.\n";
                    }
                    break;
                case 4://volver
                    break;                    
            }
        }while($opcionMenuResponsable != 4);
        break;    
    case 6://Visualizar responsable !=null listo
        if($responsable->listar() != null){
            verIDsResponsables();
            echo "Ingrese el ID del responsable que desea ver: ";
            $numeroEmpleado = trim(fgets(STDIN));
            if(esNum($numeroEmpleado)){
                if($responsable->buscar($numeroEmpleado)){
                    echo $responsable;
                    if(($cantViajes = $viaje->listar("rnumeroempleado=$numeroEmpleado")) != null){
                        echo "El empleado se encuentra a cargo de ".count($cantViajes)." viaje/s";
                    }
                }else{
                    echo "\nNo hay un responsable con ese ID en la base de datos.\n";
                }
            }           
        }else{
            echo "\nNo hay ningun responsable registrado, sera regresado al menu anterior.\n";
        }
        break;
    case 7://Ingresar,editar,eliminar pasajero !=null listo 
        do{
            echo "\n[1] Ingresar un pasajero.\n";
            echo "[2] Editar datos de un pasajero.\n";
            echo "[3] Eliminar un pasajero.\n";
            echo "[4] Volver al menu anterior.\n";
            echo "Ingrese la opcion del menu que desea elegir: ";
            $opcionMenuPasajeros = solicitarNumeroEntre(1,4);
            switch($opcionMenuPasajeros){
                case 1://Ingresar pasajero
                    if($viaje->listar() != null){
                        verIDsViajes();
                        echo "Ingrese el ID del viaje donde ingresara al pasajero: ";
                        $idViaje = trim(fgets(STDIN));
                        if(esNum($idViaje)){
                            if($viaje->buscar($idViaje)){
                                if($viaje->hayPasajesDisponibles()){
                                    echo "Ingrese el nombre del pasajero: ";
                                    $nombrePasajero = trim(fgets(STDIN));
                                    echo "Ingrese el apellido del pasajero: ";
                                    $apellidoPasajero = trim(fgets(STDIN));
                                    echo "Ingrese el numero de documento del pasajero: ";
                                    $documentoPasajero = trim(fgets(STDIN));
                                    if(esNum($documentoPasajero)){
                                        echo "Ingrese el numero de telefono del pasajero: ";
                                        $telefonoPasajero = trim(fgets(STDIN));
                                        if(esNum($telefonoPasajero)){
                                            $viaje->buscar($idViaje);
                                            $nuevoPasajero = new Pasajero();
                                            if(! ($persona->buscar($documentoPasajero))){
                                                $nuevoPasajero->cargar($nombrePasajero,$apellidoPasajero,$documentoPasajero,$telefonoPasajero,$idViaje);
                                                if(!$viaje->agregarPasajero($nuevoPasajero)){
                                                    echo "\nEl pasajero fue agregado exitosamente.\n";
                                                }else{
                                                    echo "\nYa existe un pasajero con ese dni en este u otro viaje.\n";
                                                }
                                            }else{
                                                echo "\nYa existe una persona en la base de datos con ese dni.\n";
                                            }
                                        }
                                    }
                                }else{
                                    echo "\nNo se pueden agregar mas pasajeros, el viaje ya alcanzo su capacidad maxima.\n";
                                }
                            }else{
                                echo "\nEse ID no corresponde a ningun viaje, sera regresado al menu anterior.\n";  
                            } 
                        }
                    }else{
                        echo "\nNo hay ningun viaje registrado, sera regresado al menu anterior.\n";
                    }
                    break;
                case 2://Editar Pasajero   
                    if($viaje->listar() != null){
                        verIDsViajes();
                        echo "Ingrese el ID del viaje donde se encuentra el pasajero que desea editar: ";
                        $idViaje = trim(fgets(STDIN));
                        if(esNum($idViaje)){
                            if($viaje->buscar($idViaje)){
                                $pasajeros = $viaje->getColPasajeros();
                                if(count($pasajeros) == 0){
                                    echo "\nNo hay pasajeros registrados en ese viaje, sera regresado al menu anterior.\n";
                                }else{
                                    for($i=0; $i<count($pasajeros); $i++){
                                        echo "\nPasajero numero: ".$i+1;
                                        echo $pasajeros[$i];
                                    }
                                    echo "\nIngrese el numero del pasajero desea modificar: ";
                                    $numeroDePasajero = solicitarNumeroEntre(1,count($pasajeros));
                                    $pasajero->buscar($pasajeros[$numeroDePasajero-1]->getDocumento());
                                    do{
                                        echo "\n[1] Editar el nombre del pasajero.\n";
                                        echo "[2] Editar el apellido del pasajero.\n";
                                        echo "[3] Editar el telefono del pasajero.\n";
                                        echo "[4] Editar el ID del viaje del pasajero.\n";
                                        echo "[5] Volver al menu anterior.\n";
                                        echo "Ingrese la opcion del menu que desea elegir: ";
                                        $opcionMenuDatosPasajero = solicitarNumeroEntre(1,5);
                                        switch($opcionMenuDatosPasajero){
                                            case 4:
                                                verIDsViajes();
                                                echo "Elija el nuevo ID de viaje para el pasajero: ";
                                                $nuevoDato = trim(fgets(STDIN));
                                                if(esNum($nuevoDato)){
                                                    if(!$viaje->modificarDatosPasajero($pasajero, $opcionMenuDatosPasajero,$nuevoDato)){
                                                        echo "\nDato modificado con exito.\n";
                                                    }else{
                                                        echo "\nEse ID no corresponde a ningun viaje.\n";
                                                    }
                                                }
                                                break;
                                            case 5:
                                                break;
                                            case 1:
                                            case 2:
                                                echo "Ingrese el nuevo dato: ";
                                                $nuevoDato = trim(fgets(STDIN));
                                                if(!$viaje->modificarDatosPasajero($pasajero, $opcionMenuDatosPasajero,$nuevoDato)){
                                                    echo "\nDato modificado con exito.\n";
                                                }else{
                                                    echo "\nYa existe un pasajero con ese dni en este u otro viaje.\n";
                                                }
                                                break;
                                            case 3:
                                                echo "Ingrese el nuevo dato: ";
                                                $nuevoDato = trim(fgets(STDIN));
                                                    if(!$viaje->modificarDatosPasajero($pasajero, $opcionMenuDatosPasajero,$nuevoDato)){
                                                        echo "\nDato modificado con exito.\n";
                                                    }else{
                                                        echo "\nYa existe un pasajero con ese dni en este u otro viaje.\n";
                                                    }
                                                break;
                                        }                                            
                                    }while($opcionMenuDatosPasajero != 6);
                                }
                            }else{
                                echo "\nEse ID no corresponde a ningun viaje.\n";
                            }
                        }                      
                    }else{
                        echo "\nNo hay ningun viaje registrado, por ende no hay pasajeros, se regresara al menu anterior.\n";
                    }
                    break;
                case 3://Eliminar pasajero
                    if($viaje->listar() != null){
                        echo "Ingrese el ID del viaje en el que desea eliminar un pasajero: ";
                        $idViaje = trim(fgets(STDIN));
                        if(esNum($idViaje)){
                            if($viaje->buscar($idViaje)){
                                $pasajeros = $viaje->getColPasajeros();
                                $cantPasajeros = count($pasajeros);
                                if($cantPasajeros == 0){
                                    echo "\nNo hay pasajeros registrados en ese viaje, sera regresado al menu anterior.\n";
                                }else{
                                    //Muestra los pasajeros asi el usuario sabe que numero de pasajero elegir
                                    for($i=0; $i<$cantPasajeros; $i++){
                                        echo "\nPasajero numero: ".$i+1;
                                        echo $pasajeros[$i];
                                    }
                                    echo "\nIngrese el numero del pasajero desea eliminar: ";
                                    //Solicita un numero que no sobrepase la cantidad de pasajeros
                                    $numeroDePasajero = solicitarNumeroEntre(1,$cantPasajeros);
                                    $viaje->eliminarPasajero($numeroDePasajero-1);
                                }
                            }else{
                                echo "\nEse ID no corresponde a ningun viaje\n";
                            }
                        }                                               
                    }else{
                        echo "\nNo hay ningun viaje registrado, por ende no hay pasajeros, se regresara al menu anterior.\n";
                    }
                    break;
                case 4://Volver atras
                    break;    
            }
        }while($opcionMenuPasajeros != 4);
        break;
    case 8://Visualizar datos pasajero/s !=null listo 
        if($viaje->listar() != null){
            verIDsViajes();
            echo "Ingrese el ID del viaje donde se encuentra el/los pasajero/s que desea visualizar: ";
            $idViaje = trim(fgets(STDIN));
            if(esNum($idViaje)){
                if($viaje->buscar($idViaje)){
                    $pasajeros = $viaje->getColPasajeros();
                    if(count($pasajeros) != 0){
                        foreach($pasajeros as $pasajero){
                            echo "---------------------------------------------";
                            echo $pasajero;
                            echo "---------------------------------------------";
                        }
                    }else{
                        echo "\nEl viaje seleccionado no tiene pasajeros.\n";
                    }
                }else{
                    echo "\nEse ID no corresponde a ningun viaje, se regresara al menu anterior.\n";
                }
            }else{
                echo "\nNo ingreso ningun ID, se regresara al menu anterior.\n";
            }
        }else{
            echo "\nNo hay ningun viaje registrado, por ende no hay pasajeros, se regresara al menu anterior.\n";
        }
        break;
    case 9://salir
        break;
}
}while($opcion != 9);

?>