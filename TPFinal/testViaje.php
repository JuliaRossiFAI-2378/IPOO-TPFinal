<?php
include_once 'Persona.php';
include_once 'Pasajeros.php';
include_once 'ResponsableV.php';
include_once 'Viaje.php';
include_once 'BDViajes.php';
/**Implementar un script testViaje.php que cree una instancia de la clase Viaje y 
 * presente un menú que permita cargar la información del viaje, modificar y ver sus datos. */

$responsable = new ResponsableV();
$responsable->cargar("Millie","Parfait",1231231,66642069,5,10);
/**if($responsable->ingresar()){
    echo "\n:))))))))))))))))))YIPPEEEEEEEEEEEE\n\n\n";
} */
//$pas1 = new PasajerosEstandares("Juan","Perez",123123,321321,20,65464);
//$pas2 = new PasajerosEspeciales("Macarena","Lopez",879798,546465,60,12332,true,false,true);

$viaje = new Viaje();
//$viaje->cargar(13,"Neuquen",5,[],$responsable->getNumEmpleado(),20000,0);

function solicitarNumeroEntre($minimo,$maximo){
    $numero = trim(fgets(STDIN));
    while (!($numero>=$minimo && $numero<=$maximo) || !(is_numeric($numero))){
        echo "Debe ingresar un numero que este entre ".$minimo." y ".$maximo.": ";
        $numero = trim(fgets(STDIN));
    }
    return $numero;
}
/** Muestra el menu y pide seleccionar una opcion
 * @return INT
 */
function seleccionarOpcion(){
    echo "\n[1] Cargar informacion del viaje.\n";
    echo "[2] Modificar id del viaje.\n"; //es auto increment :), ya se pero se pueden editar igual
    echo "[3] Modificar destino del viaje.\n";
    echo "[4] Modificar cantidad maxima de pasajeros.\n";
    echo "[5] Modificar el costo del viaje\n";
    echo "[6] Agregar un pasajero.\n";//funcionando por el momento
    echo "[7] Eliminar un pasajero.\n";//funcionando por el momento
    echo "[8] Modificar el dato de un pasajero.\n";//funcionando por el momento
    echo "[9] Modificar los datos del responsable.\n";
    echo "[10] Ver la informacion de un viaje.\n";//funcional, por ahora
    echo "[11] Ver los datos de los pasajeros.\n";
    echo "[12] Ver los datos del responsable.\n";
    echo "[13] Salir.\n";
    echo "Ingrese la opcion del menu que desea elegir: ";
    //Verifica que el numero elegido vaya unicamente entre las opciones del menu
    $opcionMenu = solicitarNumeroEntre(1,13);
    return $opcionMenu;
}
do{
    //Muestra el menu y devuelve la opcion elegida para utilizar en el switch
    $opcion = seleccionarOpcion();
    switch($opcion){
        case 1:
            echo "Ingrese el destino del viaje: ";
            $destViaje = trim(fgets(STDIN));
            echo "Ingrese la cantidad maxima de pasajeros: ";
            $cantidadMaximaPasajeros = trim(fgets(STDIN));
            echo "Ingrese el costo del viaje: ";
            $costoViaje = trim(fgets(STDIN));
            echo "Ingrese el numero de empleado del responsable: ";
            $numeroEmpleado = trim(fgets(STDIN));
            $viaje->cargar(null,$destViaje,$cantidadMaximaPasajeros,[],$numeroEmpleado, $costoViaje, 0);
            $viaje->ingresar();
            break;
        case 2:
            echo "Ingrese el nuevo id del viaje: ";
            $nuevoIdViaje = trim(fgets(STDIN));
            $viaje->setIdViaje($nuevoIdViaje);
            $viaje->modificar();
            break;
        case 3:
            echo "Ingrese el nuevo destino de viaje: ";
            $nuevoDestinoViaje = trim(fgets(STDIN));
            $viaje->setDestino($nuevoDestinoViaje);
            $viaje->modificar();
            break;
        case 4:
            echo "Ingrese la nueva cantidad maxima de pasajeros: ";
            $nuevaCantMaxPasajeros = trim(fgets(STDIN));
            $viaje->setCantMaxPasajeros($nuevaCantMaxPasajeros);
            $viaje->modificar();
            break;
        case 5:
            echo "Ingrese el nuevo costo del viaje: ";
            $costoViaje = trim(fgets(STDIN));
            while(!(is_numeric($costoViaje)) || $costoViaje < 0){
                echo "El costo debe ser un numero positivo. Ingrese el costo del viaje: ";
                $costoViaje = trim(fgets(STDIN));
            }
            $viaje->setCostoViaje($costoViaje);
            $viaje->modificar();
            break;
        case 6: 
            echo "Ingrese el id del viaje: ";
            $idViaje = trim(fgets(STDIN));
            if($viaje->buscar($idViaje)){
                if($viaje->hayPasajesDisponibles()){
                    echo "Ingrese el nombre del pasajero: ";
                    $nombrePasajero = trim(fgets(STDIN));
                    echo "Ingrese el apellido del pasajero: ";
                    $apellidoPasajero = trim(fgets(STDIN));
                    echo "Ingrese el numero de documento del pasajero: ";
                    $documentoPasajero = trim(fgets(STDIN));
                    echo "Ingrese el numero de telefono del pasajero: ";
                    $telefonoPasajero = trim(fgets(STDIN));
                    $viaje->buscar($idViaje);
                    $nuevoPasajero = new Pasajeros();
                    $nuevoPasajero->cargar($nombrePasajero,$apellidoPasajero,$documentoPasajero,$telefonoPasajero,$idViaje);
                    if(!$viaje->agregarPasajero($nuevoPasajero)){
                        //$viaje->agregarPasajero($nuevoPasajero); 
                        echo "\nEl pasajero fue agregado exitosamente.\n";
                    }else{
                        echo "\nYa existe un pasajero con ese dni.\n";
                    }
                }else{
                    echo "\nNo se pueden agregar mas pasajeros, el viaje ya alcanzo su capacidad maxima.\n";
                }
            }else
                echo "Ese id no corresponde a ningun viaje";           
            break;
        case 7:
            echo "Ingrese el id del viaje en el que desea eliminar un pasajero: ";
            $idViaje = trim(fgets(STDIN));
            if($viaje->buscar($idViaje)){
                $pasajeros = $viaje->getColPasajeros();
                $cantPasajeros = count($pasajeros);
                if ($cantPasajeros == 0){
                    echo "\nNo hay pasajeros para eliminar.\n";
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
            }else
                echo "Ese id no corresponde a ningun viaje";             
            break;
        case 8:
            echo "Ingrese el id del viaje en el que se encuentra ese pasajero: ";
            $idViaje = trim(fgets(STDIN));
            if($viaje->buscar($idViaje)){
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
                    $datoPasajero = trim(fgets(STDIN));
                    /*Si el dato a modificar ingresado no es el nombre, el apellido o el dni, 
                    seguira pidiendo dato a modificar hasta que se ingrese uno valido*/
                    while (strcasecmp($datoPasajero,"nombre")!=0 && strcasecmp($datoPasajero,"apellido")!=0 && 
                        strcasecmp($datoPasajero,"dni")!=0 && strcasecmp($datoPasajero,"telefono")!=0){
                        echo "El dato ingresado no es valido, debe ser nombre, apellido, telefono o dni.\n".
                        "Ingrese el dato a modificar: ";
                        $datoPasajero = trim(fgets(STDIN));
                    }
                    echo "Ingrese el nuevo dato: ";
                    $nuevoDato = trim(fgets(STDIN));
                    if(!$viaje->modificarDatosPasajero($numeroDePasajero-1,$datoPasajero,$nuevoDato))
                        echo "\nDato modificado con exito.\n";
                    else
                        echo "\nYa existe un pasajero con ese dni en este u otro viaje.\n";
                }
            }else
                echo "Ese id no corresponde a ningun viaje";
            break;
        case 10:
            echo "Ingrese el id del viaje que desea ver: ";
            $idViaje = trim(fgets(STDIN));
            if($viaje->buscar($idViaje))
                echo $viaje;
            else
                echo "No hay un viaje con ese id en la base de datos.\n";
            break;
        case 11: 
            $pasajeros = $viaje->getColPasajeros();
            $cantPasajeros = count($pasajeros);
            if($cantPasajeros > 0){
                for($i=0; $i<$cantPasajeros; $i++){
                    echo "\n\tPasajero ".$i+1 .$pasajeros[$i];
                }
            }else{
                echo "No hay pasajeros asignados a este viaje.";
            }
            break;
        case 12:
            echo $responsable;
            break;
        case 9:
            echo "Ingrese el nombre del responsable: ";
            $nombreResponsable = trim(fgets(STDIN));
            $responsable->setNombre($nombreResponsable);
            echo "Ingrese el apellido del responsable: ";
            $apellidoResponsable = trim(fgets(STDIN));
            $responsable->setApellido($apellidoResponsable);
            echo "Ingrese el numero de empleado del responsable: ";
            $numeroEmpleado = trim(fgets(STDIN));
            $responsable->setNumEmpleado($numeroEmpleado);
            echo "Ingrese el numero de licencia del responsable: ";
            $numeroLicencia = trim(fgets(STDIN));
            $responsable->setNumLicencia($numeroLicencia);
            echo "Los datos fueron cambiados exitosamente.";
            break;
  }
}while ($opcion!=13);
?>