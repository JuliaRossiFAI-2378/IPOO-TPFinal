<?php

function seleccionarOpcion(){
    echo "\n[1] Ingresar un empresa.\n";
    echo "[2] Visualizar datos empresa.\n";
    echo "[3] Editar datos empresa.\n";
    echo "[4] Eliminar empresa.\n";
    echo "[5] Ingresar un viaje.\n";
    echo "[6] Visualizar datos viaje.\n";
    echo "[7] Editar datos un viaje.\n";
    echo "[8] Eliminar un viaje.\n";
    echo "[9] Visualizar todos los viajes.\n";
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
        //empresa.cargar
        //empresa.ingresar
        break;
    case 2://visualizar datos empresa
        echo "Ingrese el id de la empresa que desea ver: ";
        $idEmpresa = trim(fgets(STDIN));
        if($empresa->buscar($idEmpresa)){
            echo $empresa;
        }else{
            echo "No hay una empresa con ese id en la base de datos.\n";
        }
        break;
    case 3://editar datos empresa__>
        echo "Ingrese el id de la empresa que desea editar: ";
        $idEmpresa = trim(fgets(STDIN));
        if($empresa->buscar($idEmpresa)){
            echo "que datos queres editar";
            $opcion = solicitarNumeroEntre(1,4);
            do{
                switch($opcion){
                    case 1://editar id empresa
                        echo "Ingrese el nuevo id de empresa: ";
                        $nuevoIdEmpresa = trim(fgets(STDIN));
                        $empresa->setIdEmpresa($nuevoIdEmpresa);
                        $empresa->modificar();
                        break;
                    case 2://editar nombre empresa
                        echo "Ingrese el nuevo nombre de empresa: ";
                        $nuevoNombre = trim(fgets(STDIN));
                        $empresa->setNombre($nuevoNombre);
                        $empresa->modificar();
                        break;
                    case 3://editar direccion empresa
                        echo "Ingrese la nueva direccion de empresa: ";
                        $nuevoDireccion = trim(fgets(STDIN));
                        $empresa->setDireccion($nuevoDireccion);
                        $empresa->modificar();
                        break;
                    case 4://volver atras
                        break;
                }
            }while($opcion!=4);
        }else{
            echo "No hay una empresa con ese id en la base de datos.\n";     
        }
        break;
    case 4://eliminar empresa
        echo "Ingrese el id de la empresa que desea eliminar: ";
        $idEmpresa = trim(fgets(STDIN));
        if($empresa->buscar($idEmpresa)){
            $empresa->eliminar();
            //aca se puede hacer un checkeo de dependencias
            //tipo, "esto romperia un viaje, desea continuar?"
        }else{
            echo "No hay una empresa con ese id en la base de datos.\n";
        }
        break;
    case 5://ingresar viaje
        echo "Ingrese el destino del viaje: ";
        $destViaje = trim(fgets(STDIN));
        echo "Ingrese la cantidad maxima de pasajeros: ";
        $cantidadMaximaPasajeros = trim(fgets(STDIN));
        echo "Ingrese el id de la empresa: ";
        $idEmpresa = trim(fgets(STDIN));
        echo "Ingrese el costo del viaje: ";
        $costoViaje = trim(fgets(STDIN));
        echo "Ingrese el numero de empleado del responsable: ";
        $numeroEmpleado = trim(fgets(STDIN));
        $viaje->cargar(null,$destViaje,$cantidadMaximaPasajeros,$idEmpresa,[],$numeroEmpleado, $costoViaje, 0);
        $viaje->ingresar();
        break;
    case 6://visualizar datos viaje
        echo "Ingrese el id del viaje que desea ver: ";
            $idViaje = trim(fgets(STDIN));
            if($viaje->buscar($idViaje)){
                echo $viaje;
            }else{
                echo "No hay un viaje con ese id en la base de datos.\n";
            }
        break;
    case 7://editar datos viaje__>
        echo "Ingrese el id del viaje que desea editar: ";
        $idViaje = trim(fgets(STDIN));
        if($viaje->buscar($idviaje)){
            echo "que datos queres editar";
            $opcion = solicitarNumeroEntre(1,7);
            do{
                switch($opcion){
                    case 1://editar id viaje
                        echo "Ingrese el nuevo id de viaje: ";
                        $nuevoIdViaje = trim(fgets(STDIN));
                        $viaje->setIdViaje($nuevoIdViaje);
                        $viaje->modificar();
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
                            echo "Ingrese que dato desea modificar (nombre, apellido, telefono o dni): ";
                            $opcion = trim(fgets(STDIN));
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
                            echo "que dato queres cambiar";
                            $opcion = solicitarNumeroEntre(1,1);
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
                    case 7://editar coleccion pasajeros //****no sabemos si este atributo se queda
                        //si ponemos esto, seria agregar pasajeros uno por uno con un for/while
                        $nuevoColPasajeros = [];
                        $viaje->setColPasajeros($nuevoColPasajeros);
                        $viaje->modificar();
                        break;
                    case 8://volver atras
                        break;
                }
            }while($opcion != 8);
        }else{
            echo "No hay una viaje con ese id en la base de datos.\n";
        }
        break;
    case 8://eliminar un viaje
        echo "Ingrese el id del viaje que desea eliminar: ";
        $idViaje = trim(fgets(STDIN));
        if($viaje->buscar($idViaje)){
            $viaje->eliminar();
            //aca se puede hacer un checkeo de dependencias
            //tipo, "esto romperia un viaje, desea continuar?"
        }else{
            echo "No hay un viaje con ese id en la base de datos.\n";
        }
        break;
    case 9://mostrar todos los viajes
        //aca podemos hacer query, o mostrar una posible coleccion
        //depende de como manejamos el guardado de vuelta
        break;
    case 10://salir
        echo "bai bai";
        break;
}
}while($opcion != 10)

?>