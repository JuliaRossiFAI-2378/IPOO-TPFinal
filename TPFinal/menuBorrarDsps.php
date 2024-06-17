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
        echo "\nEmpresa llamada '".$id->getNombre()."' con ID numero ".$id->getIdEmpresa();
    }
    echo "\n";
}
function verIDsViajes(){
    $viaje = new Viaje();
    $idViaje = $viaje->listar();
    foreach($idViaje as $id){
        echo "\nId del viaje: ".$id->getIdViaje();
    }
    echo "\n";
}
function verIDsResponsables(){
    $responsable = new ResponsableV();
    $idResponsable = $responsable->listar();
    foreach($idResponsable as $id){
        echo "\nResponsable llamado/a '".$id->getNombre()."' con ID numero ".$id->getNumEmpleado();
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
    echo "\n[1] Ingresar un empresa.\n";//funcional, falta hacer a prueba de fallos
    echo "[2] Visualizar datos empresa.\n";//falta testear
    echo "[3] Editar datos empresa.\n";//falta testear
    //[3]verificar que no se cambie al id de otra empresa 
    echo "[4] Eliminar empresa.\n";//FALTA HACER//creo que ya esta solo falta el checkeo?
    //[4]mostrar mensaje cuando la empresa es eliminada con exito (ademas de los checkeos que faltan) 
    echo "[5] Ingresar un viaje.\n";//deberia funcionar pero falta testear
    echo "[6] Visualizar datos de un viaje.\n";//deberia funcionar pero falta testear
    echo "[7] Editar datos un viaje.\n";//falta mostrar menu// creo que ya esta?
    echo "[8] Eliminar un viaje.\n";//esta funcional, pero deberia agregarse un mensaje si hay pasajeros en el viaje
    //[8]mostrar mensaje si se elimina con exito
    echo "[9] Visualizar todos los viajes.\n";//no esta hecho pero deberia funcionar el de testViaje.php
    echo "[10] Salir.\n";
    echo "Ingrese la opcion del menu que desea elegir: ";
    //Verifica que el numero elegido vaya unicamente entre las opciones del menu
    $opcionMenu = solicitarNumeroEntre(1,10);
    return $opcionMenu;
}

do{
    $opcion = seleccionarOpcion();
//en los editar, poner funcion que garantize recibir el tipo de dato correcto, se puede poner modificar al salir del menu
switch($opcion){
    case 1://ingresar una empresa
        //pedimos datos
        echo "Ingrese el nombre de la empresa: ";
        $nombreEmpresa = trim(fgets(STDIN));
        echo "Ingrese la direccion de la empresa: ";
        $direccionEmpresa = trim(fgets(STDIN));
        $empresa = new Empresa();
        //empresa.cargar
        $empresa->cargar(null,$nombreEmpresa,$direccionEmpresa);
        //empresa.ingresar
        $empresa->ingresar();
        break;
    case 2://visualizar datos empresa
        verIDsEmpresas();
        echo "\nIngrese el id de la empresa que desea ver: ";
        $idEmpresa = trim(fgets(STDIN));
        $cantViajesEmpresa = $viaje->listar("idempresa=$idEmpresa");
        if($empresa->buscar($idEmpresa)){
            echo $empresa."\nCantidad de viajes asociados a la empresa: ".count($cantViajesEmpresa)."\n";
        }else{
            echo "\nNo hay una empresa con ese id en la base de datos.\n";
        }
        break;
    case 3://editar datos empresa__>
        verIDsEmpresas();
        echo "\nIngrese el id de la empresa que desea editar: ";
        $idEmpresa = trim(fgets(STDIN));
        if($empresa->buscar($idEmpresa)){           
            do{
                echo "\n[1]Modificar el id de la empresa.\n";
                echo "[2]Modificar el nombre de la empresa.\n";
                echo "[3]Modificar la direccion de la empresa.\n";
                echo "[4]Volver al menu anterior.\n";
                echo "Ingrese la opcion del menu que desea elegir: ";
                $opcionMenuEmpresa = solicitarNumeroEntre(1,4);
                switch($opcionMenuEmpresa){
                    case 1://editar id empresa
                        echo "Ingrese el nuevo ID de empresa: ";
                        $nuevoIdEmpresa = trim(fgets(STDIN));
                        $empresa->setIdEmpresa($nuevoIdEmpresa);
                        if (($empresa->listar(" idEmpresa = " . $empresa->getIdEmpresa())) != null){
                            $empresa->modificar();
                            echo "\nCambio realizado con exito.";
                        }else{
                            echo "Ya existe una empresa con ese ID";
                        }
                        break;
                    case 2://editar nombre empresa
                        echo "Ingrese el nuevo nombre de empresa: ";
                        $nuevoNombre = trim(fgets(STDIN));
                        $empresa->setNombre($nuevoNombre);
                        $empresa->modificar();
                        echo "\nCambio realizado con exito.";
                        break;
                    case 3://editar direccion empresa
                        echo "Ingrese la nueva direccion de empresa: ";
                        $nuevoDireccion = trim(fgets(STDIN));
                        $empresa->setDireccion($nuevoDireccion);
                        $empresa->modificar();
                        echo "\nCambio realizado con exito.";
                        break;
                    case 4://volver atras
                        break;
                }
            }while($opcionMenuEmpresa!=4);
        }else{
            echo "\nNo hay una empresa con ese id en la base de datos.\n";     
        }
        break;
    case 4://eliminar empresa
        verIDsEmpresas();
        echo "\nIngrese el id de la empresa que desea eliminar: ";
        $idEmpresa = trim(fgets(STDIN));
        if($empresa->buscar($idEmpresa)){
            if (($viajes = $viaje->listar(" idEmpresa = " . $empresa->getIdEmpresa())) != null){
                echo "Existen los siguientes viajes de la empresa: " . $idEmpresa . "\n";
                echo "---------------------------------------------------------";
                foreach($viajes as $v){
                    echo $v;
                    if(pasajerosViaje($v->getIdViaje())){
                    }
                }
                echo "---------------------------------------------------------";
                echo "seguro que desea eliminar la empresa " . $empresa->getNombre() . " de id: " . $idEmpresa . "? (S/N)";
                $eleccion = strtoupper(trim(fgets(STDIN)));
                if($eleccion == "S"){
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
        break;
    case 5://ingresar viaje
        echo "Ingrese el destino del viaje: ";
        $destViaje = trim(fgets(STDIN));
        echo "Ingrese la cantidad maxima de pasajeros: ";
        $cantidadMaximaPasajeros = trim(fgets(STDIN));
        verIDsEmpresas();
        echo "Ingrese el id de la empresa: ";
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
    case 6://visualizar datos viaje
        verIDsViajes();
        echo "\nIngrese el id del viaje que desea ver: ";
            $idViaje = trim(fgets(STDIN));
            if($viaje->buscar($idViaje)){
                echo $viaje;
            }else{
                echo "No hay un viaje con ese id en la base de datos.\n";
            }
        break;
    case 7://editar datos viaje__>
        verIDsViajes();
        echo "\nIngrese el id del viaje que desea editar: ";
        $idViaje = trim(fgets(STDIN));
        if($viaje->buscar($idViaje)){
            echo "\n[1]Modificar el id del viaje.\n";
            echo "[2]Modificar el destino del viaje.\n";
            echo "[3]Modificar la cantidad maxima de pasajeros en el viaje.\n";
            echo "[4]Editar los datos de un pasajero.\n";
            echo "[5]Editar los datos de un empleado responsable por el viaje.\n";
            echo "[6]Modificar el costo del viaje.\n";
            echo "[7]Volver al menu anterior.\n";
            $opcion = solicitarNumeroEntre(1,7);
            do{
                switch($opcion){
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
                        break;
                    case 3://editar cantidad maxima de pasajeros
                        echo "Ingrese nueva cantidad maxima de pasajeros: ";
                        $nuevoCantMaxPasajeros = trim(fgets(STDIN));
                        $viaje->setCantMaxPasajeros($nuevoCantMaxPasajeros);
                        $viaje->modificar();
                        break;
                    case 4://editar datos de un pasajero
                        $cantPasajeros = count($viaje->getColPasajeros());
                        //Verifica que haya pasajeros para modificar
                        if ($cantPasajeros == 0){
                            echo "No hay pasajeros registrados.\n";
                        }else{
                            $pasajeros = $viaje->getColPasajeros();
                            //Imprime los pasajeros para que el usuario sepa que numero seleccionar
                            //Muestra los pasajeros asi el usuario sabe que numero de pasajero elegir
                            for($i=0; $i<$cantPasajeros; $i++){
                                echo "\nPasajero numero: ".$i+1;
                                echo $pasajeros[$i];
                            }
                            echo "\nIngrese el numero del pasajero desea modificar: ";
                            //Solicita un numero que no sobrepase la cantidad de pasajeros
                            $numeroDePasajero = solicitarNumeroEntre(1,$cantPasajeros);
                            echo "[1]Modificar el nombre del pasajero.\n";
                            echo "[2]Modificar el apellido del pasajero.\n";
                            echo "[3]Modificar el telefono del pasajero.\n";
                            echo "[4]Modificar el documento de el.\n";
                            echo "[5]Volver al menu anterior.\n";
                            $opcion = solicitarNumeroEntre(1,5);
                            do{//no sabemos si vamso a tener la coleccion o no, asique depende la implementacion
                                $opcion = solicitarNumeroEntre(1,5);
                                switch($opcion){
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
                            }while($opcion!=5);
                        }
                        break;
                    case 5://editar datos de empleado responsable
                        //aca tenemos que buscar empleado segun el id de empleado
                        //la cosa es, EN DONDE? o tenemos una coleccion,o hacemos un query
                        echo "ingrese id de empleado a editar";
                        $idEmpleado = trim(fgets(STDIN));
                        if(0){// !buscarEmpleado($idEmpleado)
                            echo "no existe empleado con ese id";
                        }else{
                            echo "[1]Modificar el numero empleado del responsable del viaje.\n";
                            echo "[2]Modificar el nombre del empleado responsable del viaje.\n";
                            echo "[3]Modificar el apellido del empleado responsable del viaje.\n";
                            echo "[4]Modificar el telefono del empleado responsable del viaje.\n";
                            echo "[5]Modificar el documento del empleado responsable del viaje.\n";
                            echo "[6]Modificar el numero de licencia del empleado responsable del viaje.\n";
                            echo "[7]Volver al menu anterior.\n";
                            $opcion = solicitarNumeroEntre(1,7);
                            do{
                                switch($opcion){//misma situacion que editar empleado
                                    case 1://modificar numero empleado
                                        break;
                                    case 2://modificar nombre empleado
                                        break;
                                    case 3://modificar apellido empleado
                                        break;
                                    case 4://modificar telefono empleado
                                        break;
                                    case 5://modificar documento empleado
                                        break;
                                    case 6://modificar numero licencia empleado
                                        break;
                                    case 7://volver atras
                                        break;
                                }
                            }while($opcion!=7);
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
            }while($opcion != 7);
        }else{
            echo "\nNo hay una viaje con ese id en la base de datos.\n";
        }
        break;
    case 8://eliminar un viaje
        verIDsviajes();
        echo "\nIngrese el id del viaje que desea eliminar: ";
        $idviaje = trim(fgets(STDIN));
        $pasajero = new Persona;
        if($viaje->buscar($idviaje)){
            if (pasajerosViaje($idviaje)){
                echo "seguro que desea eliminar el viaje " . $idviaje . "? (S/N)";
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
        break;
    case 9://mostrar todos los viajes
        $colViajes = $viaje->listar();
        foreach($colViajes as $unViaje){
            echo $unViaje;
        }
        break;
    case 10://salir
        echo "bai bai";
        break;
}
}while($opcion != 10);
?>