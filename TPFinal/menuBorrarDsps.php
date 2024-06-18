<?php
include_once 'Persona.php';
include_once 'Pasajeros.php';
include_once 'ResponsableV.php';
include_once 'Viaje.php';
include_once 'Empresa.php';
include_once 'BDViajes.php';
$viaje = new Viaje();
$responsable = new ResponsableV();
$empresa = new Empresa();
function solicitarNumeroEntre($minimo,$maximo){
    $numero = trim(fgets(STDIN));
    while (!($numero>=$minimo && $numero<=$maximo) || !(is_numeric($numero))){
        echo "Debe ingresar un numero que este entre ".$minimo." y ".$maximo.": ";
        $numero = trim(fgets(STDIN));
    }
    return $numero;
}
function verIDsEmpresas(){
    $empresa = new Empresa();
    $idEmpresas = $empresa->listar();
    foreach($idEmpresas as $id){
        echo "\nEmpresa con ID ".$id->getIdEmpresa()." llamada '".$id->getNombre()."'";
    }
    echo "\n";
}
function verIDsViajes(){
    $viaje = new Viaje();
    $idViaje = $viaje->listar();
    foreach($idViaje as $id){
        echo "\nViaje con ID numero ".$id->getIdViaje()." y destino a '".$id->getDestino()."'";
    }
    echo "\n";
}
function verIDsResponsables(){
    $responsable = new ResponsableV();
    $idResponsable = $responsable->listar();
    foreach($idResponsable as $id){
        echo "\nResponsable con ID numero ".$id->getNumEmpleado()." llamado/a '".$id->getNombre()."'";
    }
    echo "\n";
}
//asume que el id que recibe funciona
function pasajerosViaje($idViaje){
    $contienePasajeros = true;
    $pasajero = new Persona;
    if(($pasajeros = $pasajero->listar(" idviaje = " . $idViaje)) != null){
        echo "el viaje " . $idViaje . "contiene los siguientes pasajeros: " . "\n";
        echo "---------------------------------------------------------";
        foreach($pasajeros as $p){
            echo $p;
        }
        echo "---------------------------------------------------------";
    }else{
        $contienePasajeros = false;
    }
    return $contienePasajeros;
}
function seleccionarOpcion(){
    echo "\n[1] Ingresar, editar o eliminar una empresa.\n";//funcional, falta hacer a prueba de fallos
    echo "[2] Visualizar datos empresa.\n";//falta testear

    echo "[3] Ingresar, editar o eliminar un viaje.\n";//ARREGLAR ELIMINAR VIAJE 
    echo "[4] Visualizar datos de un viaje.\n";//deberia funcionar pero falta testear
    echo "[7] Editar datos un viaje.\n";//falta editar datos de un pasajero perdon :-)
    echo "[8] Eliminar un viaje.\n";//esta funcional, pero deberia agregarse un mensaje si hay pasajeros en el viaje
    //[8]mostrar mensaje si se elimina con exito
    echo "[9] Visualizar todos los viajes.\n";
    echo "[10] Visualizar datos del responsable de un viaje.\n";
    echo "[11] Ingresar, editar o eliminar el responsable de un viaje.\n";//TERMINAR EDITAR DATOS
    echo "[12] Eliminar al responsable de un viaje.\n";
    echo "[12] Cambiar [12] a ingresar,editar,eliminar pasajero\n";
    echo "[13] Salir.\n";
    echo "Ingrese la opcion del menu que desea elegir: ";
    //Verifica que el numero elegido vaya unicamente entre las opciones del menu
    $opcionMenu = solicitarNumeroEntre(1,13);
    return $opcionMenu;
}

do{
    $opcion = seleccionarOpcion();
//en los editar, poner funcion que garantize recibir el tipo de dato correcto, se puede poner modificar al salir del menu
switch($opcion){
    case 1://ingresar/editar/eliminar una empresa
        do{
            echo "\n[1] Ingresar una empresa.\n";//funcional, falta hacer a prueba de fallos
            echo "[2] Editar datos empresa.\n";//falta testear
            echo "[3] Eliminar empresa.\n";//falta testear
            echo "[4] Volver al menu anterior.\n";
            echo "Ingrese la opcion del menu que desea elegir: ";
            $opcionMenuEmpresa = solicitarNumeroEntre(1,4);
            switch($opcionMenuEmpresa){
                case 1://ingresar empresa
                    echo "Ingrese el nombre de la empresa: ";
                    $nombreEmpresa = trim(fgets(STDIN));
                    echo "Ingrese la direccion de la empresa: ";
                    $direccionEmpresa = trim(fgets(STDIN));
                    $empresa = new Empresa();
                    $empresa->cargar(null,$nombreEmpresa,$direccionEmpresa);
                    //agregar que si uno es null no cargue ni ingrese la empresa
                    $empresa->ingresar();
                    break;
                case 2://editar datos empresa
                    verIDsEmpresas();
                    echo "\nIngrese el id de la empresa que desea editar: ";
                    $idEmpresa = trim(fgets(STDIN));
                    if($idEmpresa != null){
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
                                        $empresa->setNombre($nuevoNombre);
                                        $empresa->modificar();
                                        echo "\nCambio realizado con exito.";
                                        break;
                                    case 2://editar direccion empresa
                                        echo "Ingrese la nueva direccion de empresa: ";
                                        $nuevoDireccion = trim(fgets(STDIN));
                                        $empresa->setDireccion($nuevoDireccion);
                                        $empresa->modificar();
                                        echo "\nCambio realizado con exito.";
                                        break;
                                    case 3://volver atras
                                        break;
                                }
                            }while($opcionMenuDatosEmpresa!=3);
                        }else{
                            echo "\nNo hay una empresa con ese id en la base de datos.\n";     
                        }
                    }else{
                        echo "\nNo ingreso ningun ID, se regresara al menu anterior.";
                    }
                    break;
                case 3://eliminar empresa
                    verIDsEmpresas();
                    echo "\nIngrese el id de la empresa que desea eliminar: ";
                    $idEmpresa = trim(fgets(STDIN));
                    if($idEmpresa != null){
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
                                    echo "\nEmpresa borrada con exito.";
                                }else{
                                    echo "\nOperacion cancelada.";
                                }
                            }else{
                                $empresa->eliminar();
                                echo "\nEmpresa borrada con exito.";
                            }
                        }else{
                            echo "\nNo hay una empresa con ese id en la base de datos.\n";
                        }
                    }else{
                        echo "\nNo ingreso ningun ID, se regresara al menu anterior.";
                    }
                    break;
                case 4:
                    break;
            }
        }while($opcionMenuEmpresa != 4);
        break;
    case 2://visualizar datos empresa
        verIDsEmpresas();
        echo "\nIngrese el id de la empresa que desea ver: ";
        $idEmpresa = trim(fgets(STDIN));
        if($idEmpresa != null){
            $cantViajesEmpresa = $viaje->listar("idempresa=$idEmpresa");
            if($empresa->buscar($idEmpresa)){
                echo $empresa."\nCantidad de viajes asociados a la empresa: ".count($cantViajesEmpresa)."\n";
            }else{
                echo "\nNo hay una empresa con ese id en la base de datos.\n";
            }
        }else{
            echo "\nNo ingreso ningun ID, se regresara al menu anterior.";
        }
        break;
    /**case 3://editar datos empresa
        verIDsEmpresas();
        echo "\nIngrese el id de la empresa que desea editar: ";
        $idEmpresa = trim(fgets(STDIN));
        if($idEmpresa != null){
            if($empresa->buscar($idEmpresa)){           
                do{
                    echo "\n[1]Modificar el nombre de la empresa.\n";
                    echo "[2]Modificar la direccion de la empresa.\n";
                    echo "[3]Volver al menu anterior.\n";
                    echo "Ingrese la opcion del menu que desea elegir: ";
                    $opcionMenuEmpresa = solicitarNumeroEntre(1,3);
                    switch($opcionMenuEmpresa){
                        case 1://editar nombre empresa
                            echo "Ingrese el nuevo nombre de empresa: ";
                            $nuevoNombre = trim(fgets(STDIN));
                            $empresa->setNombre($nuevoNombre);
                            $empresa->modificar();
                            echo "\nCambio realizado con exito.";
                            break;
                        case 2://editar direccion empresa
                            echo "Ingrese la nueva direccion de empresa: ";
                            $nuevoDireccion = trim(fgets(STDIN));
                            $empresa->setDireccion($nuevoDireccion);
                            $empresa->modificar();
                            echo "\nCambio realizado con exito.";
                            break;
                        case 3://volver atras
                            break;
                    }
                }while($opcionMenuEmpresa!=3);
            }else{
                echo "\nNo hay una empresa con ese id en la base de datos.\n";     
            }
        }else{
            echo "\nNo ingreso ningun ID, se regresara al menu anterior.";
        }
        break;
    case 4://eliminar empresa */
        verIDsEmpresas();
        echo "\nIngrese el id de la empresa que desea eliminar: ";
        $idEmpresa = trim(fgets(STDIN));
        if($idEmpresa != null){
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
                        echo "\nEmpresa borrada con exito.";
                    }else{
                        echo "\nOperacion cancelada.";
                    }
                }else{
                    $empresa->eliminar();
                    echo "\nEmpresa borrada con exito.";
                }
            }else{
                echo "\nNo hay una empresa con ese id en la base de datos.\n";
            }
        }else{
            echo "\nNo ingreso ningun ID, se regresara al menu anterior.";
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
                    echo "Ingrese el destino del viaje: ";
                    $destViaje = trim(fgets(STDIN));
                    echo "Ingrese la cantidad maxima de pasajeros: ";
                    $cantidadMaximaPasajeros = trim(fgets(STDIN));
                    verIDsEmpresas();
                    echo "Ingrese el id de la empresa que realiza el viaje: ";
                    $idEmpresa = trim(fgets(STDIN));
                    echo "Ingrese el costo del viaje: ";
                    $costoViaje = trim(fgets(STDIN));
                    verIDsResponsables();
                    echo "Ingrese el numero de empleado del responsable: ";
                    $numeroEmpleado = trim(fgets(STDIN));
                    $viaje->cargar(null,$destViaje,$cantidadMaximaPasajeros,$idEmpresa,[],$numeroEmpleado, $costoViaje, 0);
                    if($viaje->ingresar()){
                        echo "\nViaje ingresado con exito.";
                    }else{
                        //tenemos que manejar mejor los errores para que no explote en caso de fallar
                    }
                    break;
                case 2://editar datos viaje
                    verIDsViajes();
                    echo "\nIngrese el id del viaje que desea editar: ";
                    $idViaje = trim(fgets(STDIN));
                    if($idViaje != null){
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
                                        $viaje->setDestino($nuevoDestino);
                                        $viaje->modificar();
                                        echo "\nModificacion realizada con exito.";
                                        break;
                                    case 2://editar cantidad maxima de pasajeros
                                        echo "Ingrese nueva cantidad maxima de pasajeros: ";
                                        $nuevoCantMaxPasajeros = trim(fgets(STDIN));
                                        $viaje->setCantMaxPasajeros($nuevoCantMaxPasajeros);
                                        $viaje->modificar();
                                        echo "\nModificacion realizada con exito.";
                                        break;
                                    case 3://cambiar empleado responsable
                                        verIDsResponsables();
                                        echo "ingrese numero de empleado nuevo";
                                        $idEmpleado = trim(fgets(STDIN));
                                        if($responsable->buscar($idEmpleado)){
                                            $viaje->setNumEmpleado($idEmpleado);
                                        }else{
                                            echo "no existe empleado con ese id";
                                        }
                                        break;
                                    case 4://editar importe
                                        echo "Ingrese nuevo importe: ";
                                        $nuevoCostoViaje = trim(fgets(STDIN));
                                        $viaje->setCostoViaje($nuevoCostoViaje);
                                        $viaje->modificar();
                                        break;
                                    case 5://volver atras
                                        break;
                                }
                            }while($opcionMenuDatosViajes != 5);
                        }else{
                            echo "\nNo hay una viaje con ese id en la base de datos.\n";
                        }
                    }else{
                        echo "\nNo ingreso ningun ID, se regresara al menu anterior.";
                    }
                    break;    
                case 3://eliminar viaje
                    verIDsviajes();
                    echo "\nIngrese el id del viaje que desea eliminar: ";
                    $idViaje = trim(fgets(STDIN));
                    if($idViaje != null){
                        if($viaje->buscar($idViaje)){
                            if (pasajerosViaje($idViaje)){
                                echo "seguro que desea eliminar el viaje " . $idViaje . "? (S/N)";
                                $eleccion = strtoupper(trim(fgets(STDIN)));
                                if($eleccion == "S"){
                                    $viaje->eliminar();
                                    echo "todo piola, borrado.";
                                }else{
                                    echo "operacion cancelada";
                                }
                            }else{
                                $viaje->eliminar();
                                echo "todo piola, borrado.";
                            }
                        }else{
                            echo "\nNo hay un viaje con ese id en la base de datos.\n";
                        }            
                    }else{
                        echo "\nNo ingreso ningun ID, se regresara al menu anterior.";
                    }
                    break;
                case 4:
                    break;
            }
        }while($opcionMenuViaje !=4);
        break;
    case 4://visualizar datos viaje
        verIDsViajes();
        echo "\nIngrese el id del viaje que desea ver: ";
        $idViaje = trim(fgets(STDIN));
        if($idViaje != null){
            if($viaje->buscar($idViaje)){
                echo $viaje;
            }else{
                echo "No hay un viaje con ese id en la base de datos.\n";
            }
        }else{
            echo "\nNo ingreso ningun ID, se regresara al menu anterior.";
        }
        break;
    /**case 7://editar datos viaje__>
        verIDsViajes();
        echo "\nIngrese el id del viaje que desea editar: ";
        $idViaje = trim(fgets(STDIN));
        if($idViaje != null){
            if($viaje->buscar($idViaje)){
                do{
                    echo "\n[1]Modificar el id del viaje.\n";
                    echo "[2]Modificar el destino del viaje.\n";
                    echo "[3]Modificar la cantidad maxima de pasajeros en el viaje.\n";
                    echo "[4]Editar los datos de un pasajero.\n";
                    echo "[5]Cambiar responsable.\n";
                    echo "[6]Modificar el costo del viaje.\n";
                    echo "[7]Volver al menu anterior.\n";
                    echo "Ingrese la opcion del menu que desea elegir: ";
                    $opcionMenuViajes = solicitarNumeroEntre(1,7);
                    switch($opcionMenuViajes){
                        case 1://editar id viaje
                            echo "Ingrese el nuevo ID de viaje: ";
                            $nuevoIdViaje = trim(fgets(STDIN));
                            $viaje->setIdViaje($nuevoIdViaje);
                            if (($viaje->listar("idviaje=" . $viaje->getIdEmpresa()))){
                                $viaje->modificar();
                            }else{
                                echo "Ya existe un viaje con ese ID";
                            }
                            break;
                        case 2://editar destino
                            echo "Ingrese el nuevo destino: ";
                            $nuevoDestino = trim(fgets(STDIN));
                            $viaje->setDestino($nuevoDestino);
                            $viaje->modificar();
                            echo "\nModificacion realizada con exito.";
                            break;
                        case 3://editar cantidad maxima de pasajeros
                            echo "Ingrese nueva cantidad maxima de pasajeros: ";
                            $nuevoCantMaxPasajeros = trim(fgets(STDIN));
                            $viaje->setCantMaxPasajeros($nuevoCantMaxPasajeros);
                            $viaje->modificar();
                            echo "\nModificacion realizada con exito.";
                            break;
                        case 4://editar datos de un pasajero
                            $pasajeros = $viaje->getColPasajeros();
                            //Verifica que haya pasajeros para modificar
                            if (count($pasajeros) != 0){
                                //Imprime los pasajeros para que el usuario sepa que numero seleccionar
                                for($i=0; $i<$cantPasajeros; $i++){
                                    echo "\nPasajero numero: ".$i+1;
                                    echo $pasajeros[$i];
                                }
                                echo "\nIngrese el numero del pasajero desea modificar: ";
                                //Solicita un numero que no sobrepase la cantidad de pasajeros
                                $numeroDePasajero = solicitarNumeroEntre(1,$cantPasajeros);
                                
                                do{//no sabemos si vamso a tener la coleccion o no, asique depende la implementacion
                                    echo "[1]Modificar el nombre del pasajero.\n";
                                    echo "[2]Modificar el apellido del pasajero.\n";
                                    echo "[3]Modificar el telefono del pasajero.\n";
                                    echo "[4]Modificar el documento del pasajero.\n";
                                    echo "[5]Volver al menu anterior.\n";
                                    $opcionMenuPasajeros = solicitarNumeroEntre(1,5);
                                    switch($opcionMenuPasajeros){
                                        case 1://modificar nombre pasajero
                                            break;
                                        case 2://modificar apellido pasajero
                                            break;
                                        case 3://modificaar telefono pasajero
                                            break;
                                        case 4://modificar dni pasajero
                                            break;
                                        case 5://volver atras
                                            break;
                                    }
                                }while($opcionMenuPasajeros!=5);
                                
                            }else{
                                echo "No hay pasajeros registrados.\n";
                            }
                            break;
                        case 5://cambiar empleado responsable
                            verIDsResponsables();
                            echo "ingrese numero de empleado nuevo";
                            $idEmpleado = trim(fgets(STDIN));
                            if($responsable->buscar($idEmpleado)){
                                $viaje->setNumEmpleado($idEmpleado);
                            }else{
                                echo "no existe empleado con ese id";
                            }
                            break;
                        case 6://editar importe
                            echo "Ingrese nuevo importe: ";
                            $nuevoCostoViaje = trim(fgets(STDIN));
                            $viaje->setCostoViaje($nuevoCostoViaje);
                            $viaje->modificar();
                            break;
                        case 7://volver atras
                            break;
                    }
                }while($opcionMenuViajes != 7);
            }else{
                echo "\nNo hay una viaje con ese id en la base de datos.\n";
            }
        }else{
            echo "\nNo ingreso ningun ID, se regresara al menu anterior.";
        }
        break;
    case 8://eliminar un viaje
        verIDsviajes();
        echo "\nIngrese el id del viaje que desea eliminar: ";
        $idViaje = trim(fgets(STDIN));
        if($idViaje != null){
            $pasajero = new Persona;
            if($viaje->buscar($idViaje)){
                if (pasajerosViaje($idViaje)){
                    echo "seguro que desea eliminar el viaje " . $idViaje . "? (S/N)";
                    $eleccion = strtoupper(trim(fgets(STDIN)));
                    if($eleccion == "S"){
                        $viaje->eliminar();
                        echo "todo piola, borrado.";
                    }else{
                        echo "operacion cancelada";
                    }
                }else{
                    $viaje->eliminar();
                    echo "todo piola, borrado.";
                }
            }else{
                echo "\nNo hay un viaje con ese id en la base de datos.\n";
            }            
        }
        break;*/
    case 9://mostrar todos los viajes
        $colViajes = $viaje->listar();
        foreach($colViajes as $unViaje){
            echo $unViaje;
        }
        break;
    case 10://mostrar responsable
        verIDsResponsables();
        echo "Ingrese el ID del responsable que desea ver:";
        $numeroEmpleado = trim(fgets(STDIN));
        if($numeroEmpleado != null){
            if($responsable->buscar($numeroEmpleado)){
                echo $responsable;
            }else{
                echo "No hay un responsable con ese ID en la base de datos.\n";
            }
        }else{
            echo "\nNo ingreso ningun ID, se regresara al menu anterior.";
        }
        break;
    case 11://ingresar/editar/eliminar responsable
        do{
            echo "\n[1] Ingresar un responsable.\n";
            echo "[2] Editar datos de un responsable.\n";//terminar!!!!
            echo "[3] Eliminar un responsable.\n";
            echo "[4] Volver al menu anterior.\n";
            echo "Ingrese la opcion del menu que desea elegir: ";
            $opcionMenuResponsable = solicitarNumeroEntre(1,4);
            switch($opcionMenuResponsable){
                case 1://ingresar responsable
                    echo "Ingrese el nombre del responsable: ";
                    $nombreResponsable = trim(fgets(STDIN));
                    echo "Ingrese el apellido del responsable: ";
                    $apellidoResponsable = trim(fgets(STDIN));
                    echo "Ingrese el numero de documento del responsable: ";
                    $documentoResponsable = trim(fgets(STDIN));
                    echo "Ingrese el numero de telefono del responsable: ";
                    $telefonoResponsable = trim(fgets(STDIN));
                    echo "Ingrese el numero de licencia del responsable: ";
                    $numLicencia = trim(fgets(STDIN));
                    $nuevoResponsable = new ResponsableV();
                    $nuevoResponsable->cargar($nombreResponsable,$apellidoResponsable,$documentoResponsable,$telefonoResponsable,null,$numLicencia);
                    if($nuevoResponsable->ingresar()){
                        echo "\nResposable agregado con exito.\n";
                    }
                    break;
                case 2://editar responsable
                    verIDsResponsables();
                    echo "Ingrese el ID del responsable que desea editar :";
                    $numeroEmpleado = trim(fgets(STDIN));
                    if($numeroEmpleado != null){
                        if($responsable->buscar($numeroEmpleado)){
                            switch(0){
                                case 1://cambir nombre
                                    echo "igrese nombre nuevo";
                                    $nombre = trim(fgets(STDIN));
                                    $responsable->setNombre($nombre);
                                    $responsable->modificar();
                                    break;
                                case 2://cambiar apellido
                                    echo "igrese apellido nuevo";
                                    $apellido = trim(fgets(STDIN));
                                    $responsable->setApellido($apellido);
                                    $responsable->modificar();
                                    break;
                                case 3://cambiar telefono
                                    echo "igrese telefono nuevo";
                                    $telefono = trim(fgets(STDIN));
                                    $responsable->setTelefono($telefono);
                                    $responsable->modificar();
                                    break;
                                case 4://cambiar documento
                                    //tengo mis dudas con este, puiede que rompa persona???
                                    echo "igrese documento nuevo";
                                    $documento = trim(fgets(STDIN));
                                    $responsable->setDocumento($documento);
                                    $responsable->modificar();
                                    break;
                                case 5://cambiar numero licencia
                                    echo "igrese telefono nuevo";
                                    $numLicencia = trim(fgets(STDIN));
                                    $responsable->setNumLicencia($numLicencia);
                                    $responsable->modificar();
                                    break;
                                case 6://volver atras
                                    break;
                            }
                        }else{
                            echo "No hay un responsable con ese ID en la base de datos.\n";
                        }
                    }else{
                        echo "\nNo ingreso ningun ID, se regresara al menu anterior.";
                    }
                    break;
                case 3://eliminar responsable
                    verIDsResponsables();
                    echo "Ingrese el ID del responsable que desea eliminar :";
                    $numeroEmpleado = trim(fgets(STDIN));
                    if($numeroEmpleado != null){
                        if($responsable->buscar($numeroEmpleado)){
                            if(($cantViajes = $viaje->listar("rnumeroempleado=$numeroEmpleado")) != null){
                                echo "El empleado se encuentra a cargo de ".count($cantViajes)." viaje/s";
                                echo "\nSeguro que desea eliminar al responsable llamado ".$responsable->getNombre().
                                    " con id numero ".$responsable->getNumEmpleado()."?";
                                echo "\n[1] Eliminar responsable. (esta accion no se puede deshacer)";
                                echo "\n[2] Cancelar operacion.";
                                echo "\nSeleccione que accion desea realizar: ";
                                $eleccion = solicitarNumeroEntre(1,2);
                                if($eleccion == 1){
                                    echo "\nResponsable eliminado con exito.\n";
                                    $responsable->eliminar();
                                }else{
                                    echo "\nOperacion cancelada.\n";
                                }
                            }else{
                                $responsable->eliminar();
                                echo "\nResponsable eliminado con exito.\n";
                            }
                        }else{
                            echo "\nNo hay un responsable con ese ID en la base de datos.\n";
                        }
                    }else{
                        echo "\nNo ingreso ningun ID, se regresara al menu anterior.\n";
                    }
                    break;
                case 4://volver
                    break;                    
            }
        }while($opcionMenuResponsable != 4);
        break;
    case 13://salir
        echo "bai bai";
        break;
}
}while($opcion != 13);
?>