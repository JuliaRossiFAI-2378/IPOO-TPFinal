<?php
class Viaje{
    private $idViaje;
    private $destino;
    private $cantMaxPasajeros;
    private $colPasajeros;
    private $numEmpleado;
    private $costoViaje;
    private $sumaCostos;
    private $mensajeError;
    
    public function __construct()
    {
        $this->idViaje = 0;
        $this->destino = "";
        $this->cantMaxPasajeros = 0;
        $this->colPasajeros = [];
        $this->numEmpleado = null;
        $this->costoViaje = 0;
        $this->sumaCostos = 0;
    }
    public function getIdViaje(){
        return $this->idViaje;
    }
    public function setIdViaje($newIdViaje){
        $this->idViaje = $newIdViaje;
    }
    public function getDestino(){
        return $this->destino;
    }
    public function setDestino($newDestino){
        $this->destino = $newDestino;
    }
    public function getCantMaxPasajeros(){
        return $this->cantMaxPasajeros;
    }
    public function setCantMaxPasajeros($newCantMaxPasajeros){
        $this->cantMaxPasajeros = $newCantMaxPasajeros;
    }
    public function getColPasajeros(){
        return $this->colPasajeros;
    }
    public function setColPasajeros($newColPasajeros){
        $this->colPasajeros = $newColPasajeros;
    }
    public function getNumEmpleado(){
        return $this->numEmpleado;
    }
    public function setNumEmpleado($newNumEmpleado){
        $this->numEmpleado = $newNumEmpleado;
    }
    public function getCostoViaje(){
        return $this->costoViaje;
    }
    public function setCostoViaje($newCostoViaje){
        $this->costoViaje = $newCostoViaje;
    }
    public function getSumaCostos(){
        return $this->sumaCostos;
    }
    public function setSumaCostos($newSumaCostos){
        $this->sumaCostos = $newSumaCostos;
    }
    public function getMensajeError(){
        return $this->mensajeError;
    }
    public function setMensajeError($newMensajeError){
        $this->mensajeError = $newMensajeError;
    }
    public function __toString()
    {
        $cad = "\nIdViaje: ".$this->getIdViaje()."\nDestino: ".$this->getDestino()."\nCantidad maxima de pasajeros: ".
        $this->getCantMaxPasajeros()."\nCantidad actual de pasajeros: ".count($this->getColPasajeros()).
        "\nCosto del viaje: $".$this->getCostoViaje()."\nRecaudacion total del viaje: $".$this->getSumaCostos().
        "\n\tInformacion de los pasajeros";
        $pasajeros = $this->getColPasajeros();
        for($i=0; $i<count($pasajeros); $i++){
            $cad .= "\n\tPasajero ".$i+1 .$pasajeros[$i];
        }   
        return $cad;
    }

    public function cargar($idViaje,$destino,$cantMaxPasajeros,$colPasajeros,$numEmpleado,$costoViaje,$sumaCostos){
        $this->idViaje = $idViaje;
        $this->destino = $destino;
        $this->cantMaxPasajeros = $cantMaxPasajeros;
        $this->colPasajeros = $colPasajeros;
        $this->numEmpleado = $numEmpleado;
        $this->costoViaje = $costoViaje;
        $this->sumaCostos = $sumaCostos;
    }
    public function modificarDatosPasajero($numPasajero,$datoPasajero,$nuevoDato){
        $pasajeros = $this->getColPasajeros();
        switch($datoPasajero){
            case "dni":
                $cantPasajeros = count($pasajeros);
                $seEncontro = false;
                $i = 0;
                while($i<$cantPasajeros && !$seEncontro){
                    $dniPasajero = $pasajeros[$i]->getNumDocumento();
                    if($dniPasajero == $nuevoDato){
                        $seEncontro = true;
                    }
                    $i++;
                }
                if(!$seEncontro){
                    $pasajeros[$numPasajero]->setNumDocumento($nuevoDato);
                }
                break;
            case "nombre":
                $pasajeros[$numPasajero]->setNombre($nuevoDato);
                break;
            case "apellido":
                $pasajeros[$numPasajero]->setApellido($nuevoDato);
                break;
            case "telefono":
                $pasajeros[$numPasajero]->setTelefono($nuevoDato);
                break;
        }
    }
    public function agregarPasajero($nuevoPasajero){
        $colPasajeros = $this->getColPasajeros();
        $cantPasajeros = count($colPasajeros);
        $seEncontro = false;
        $i = 0;
        while($i<$cantPasajeros && !$seEncontro){
            $dniPasajero = $colPasajeros[$i]->getNumDocumento();
            $nuevoDni = $nuevoPasajero->getNumDocumento();
            if($dniPasajero == $nuevoDni){
                $seEncontro = true;
            }
            $i++;
        }
        if(!$seEncontro){
            $colPasajeros[] = $nuevoPasajero;
            $this->setColPasajeros($colPasajeros);
        }
        return $seEncontro;    
    }
    public function eliminarPasajero($numPasajero){
        $colPasajeros = $this->getColPasajeros();
        unset($colPasajeros[$numPasajero]);
        $colPasajeros = array_values($colPasajeros);
        $this->setColPasajeros($colPasajeros);
    }
    public function hayPasajesDisponibles(){
        $disponible = false;
        if(count($this->getColPasajeros()) < $this->getCantMaxPasajeros()){
            $disponible = true;
        }
        return $disponible;
    }

    public function venderPasaje($objPasajero){
        $sumaCostos = $this->getSumaCostos();
        $costoFinal = -1;
        if($this->hayPasajesDisponibles()){
            $this->agregarPasajero($objPasajero);
            $sumaCostos += $this->getCostoViaje();
            $costoFinal = $this->getCostoViaje();
            $porcentajeIncremento = $objPasajero->darPorcentajeIncremento();
            $porcentajeIncremento = $costoFinal * ($porcentajeIncremento / 100);
            $sumaCostos += $porcentajeIncremento;
            $costoFinal += $porcentajeIncremento;
            $this->setSumaCostos($sumaCostos);
        }
        return $costoFinal;
    }
    
    /**************************************************************************
     ************************************************************************** 
     ************************FUNCIONES BASE DE DATOS*************************** 
     ************************************************************************** 
     ************************************************************************** 
    */

    public function ingresar(){
        $baseDatos = new BDViajes();
        $resp = false;
        $sql = "INSERT INTO viaje(idviaje, destino, cantmaxpasajeros, rnumeroempleado, importe) VALUES (NULL,'".$this->getDestino().
        "', ".$this->getCantMaxPasajeros().", ".$this->getNumEmpleado().", ".$this->getCostoViaje().")";
        if($baseDatos->conectarBD()){
            if($baseDatos->consulta($sql)){
                $resp = true;
            }else{
                $this->setMensajeError($baseDatos->getERROR());
            }
        }else{
            $this->setMensajeError($baseDatos->getERROR());
        }
        return $resp;
    }

    public function buscar($id){
        $baseDatos = new BDViajes();
        $resp = false;
        $sql = "SELECT * FROM viaje WHERE idviaje=".$id;
        if($baseDatos->conectarBD()){
            if($baseDatos->consulta($sql)){
                if(($result = $baseDatos->registro()) != null){
                    $this->setIdViaje($id);
                    $this->setDestino($result['destino']);
                    $this->setCantMaxPasajeros($result['cantmaxpasajeros']);
                    $this->setNumEmpleado(['rnumeroempleado']);
                    $this->setCostoViaje($result['importe']);
                    $resp = true;
                }else  
                    $this->setMensajeError($baseDatos->getERROR());
            }else
                $this->setMensajeError($baseDatos->getERROR());
        }else
            $this->setMensajeError($baseDatos->getERROR());
        return $resp;
    }

    public function listar($condicion=""){
        $baseDatos = new BDViajes();
        $resultados = null;
        $sql = "SELECT * FROM viaje";
        if($condicion != ""){
            $sql .= " WHERE " .$condicion;
        }
        if($baseDatos->conectarBD()){
            if($baseDatos->consulta($sql)){
                $resultados = [];
                while($tupla = $baseDatos->registro()){
                    $idViaje = $tupla['idviaje'];
                    $destino = $tupla['destino'];
                    $cantMaxPasajeros = $tupla['cantmaxpasajeros'];
                    $numeroEmpleado = $tupla['rnumeroempleado'];
                    $importe = $tupla['importe'];
                    $viaje = new Viaje();
                    $viaje->cargar($idViaje,$destino,$cantMaxPasajeros,[],$numeroEmpleado,$importe,0);
                    //falta ver como manejar el tema de la colPasajeros y la sumaCostos 
                    $resultados[] = $viaje;
                }
            }else
                $this->setMensajeError($baseDatos->getERROR());
        }else
            $this->setMensajeError($baseDatos->getERROR());
        return $resultados;
    }
}

?>