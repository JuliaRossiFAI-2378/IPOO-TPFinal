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
    private $idEmpresa;

    public function __construct()
    {
        $this->idViaje = 0;
        $this->destino = "";
        $this->cantMaxPasajeros = 0;
        $this->idEmpresa = 0;
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
    public function getIdEmpresa(){
        return $this->idEmpresa;
    }
    public function setIdEmpresa($newIdEmpresa){
        $this->idEmpresa = $newIdEmpresa;
    }
    public function getColPasajeros(){
        $this->colPasajeros = [];
        $pasajero = new Pasajeros();
        $sql = "idviaje=".$this->getIdViaje();
        if($temp = $pasajero->listar($sql)){
            $this->colPasajeros = $temp;
        }
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
    public function getALLPasajeros(){
        $colPasajeros = [];
        $pasajero = new Pasajeros();
        if($temp = $pasajero->listar()){
            $colPasajeros = $temp;
        }
        return $colPasajeros;
    }
    public function __toString()
    {
        $cad = "\nId del viaje: ".$this->getIdViaje()."\nDestino: ".$this->getDestino()."\nCantidad maxima de pasajeros: ".
        $this->getCantMaxPasajeros()."\nCantidad actual de pasajeros: ".count($this->getColPasajeros()).
        "\nCosto del viaje: $".$this->getCostoViaje()."\nRecaudacion total del viaje: $".$this->getSumaCostos().
        "\n\n\t\tInformacion del responsable del viaje";
        $responsable = new ResponsableV();
        if($this->getNumEmpleado() != null){
            $responsable->buscar($this->getNumEmpleado());
            $cad .= $responsable;
        }else{
            $cad .= "\nEl viaje NO tiene un responsable, deberia asignarle uno.";
        }
        $cad .= "\n\t\tInformacion de los pasajeros";
        $pasajeros = $this->getColPasajeros();
        if(count($pasajeros) == 0){
            $cad .= "\nEl viaje no tiene pasajeros por el momento.";
        }
        for($i=0; $i<count($pasajeros); $i++){
            $cad .= "\n\tPasajero ".$i+1 .$pasajeros[$i];
        }
        return $cad;
    }

    public function cargar($idViaje,$destino,$cantMaxPasajeros,$idEmpresa,$colPasajeros,$numEmpleado,$costoViaje,$sumaCostos){
        $this->idViaje = $idViaje;
        $this->destino = $destino;
        $this->cantMaxPasajeros = $cantMaxPasajeros;
        $this->idEmpresa = $idEmpresa;
        $this->colPasajeros = $colPasajeros;
        $this->numEmpleado = $numEmpleado;
        $this->costoViaje = $costoViaje;
        $this->sumaCostos = $sumaCostos;
    }
    public function modificarDatosPasajero($objPasajero,$datoPasajero,$nuevoDato){
        $pasajeros = $this->getALLPasajeros();
        $seEncontro = false;
        switch($datoPasajero){
            case 1:
                $objPasajero->setNombre($nuevoDato);
                $objPasajero->modificar();
                break;
            case 2:
                $objPasajero->setApellido($nuevoDato);
                $objPasajero->modificar();
                break;
            case 3:
                $objPasajero->setTelefono($nuevoDato);
                $objPasajero->modificar();
                break;
            case 4:
                $cantPasajeros = count($pasajeros);
                $i = 0;
                while($i<$cantPasajeros && !$seEncontro){
                    $dniPasajero = $pasajeros[$i]->getDocumento();
                    if($dniPasajero == $nuevoDato)
                        $seEncontro = true;
                    $i++;
                }
                if(!$seEncontro){
                    $objPasajero->modificarDocumento($nuevoDato);
                }
                break;
            case 5:
                $objPasajero->setIdViaje($nuevoDato);
                $objPasajero->modificar();
                break;
            case 6:
                break;
        }
        return $seEncontro;
    }
    public function agregarPasajero($nuevoPasajero){
        $colPasajeros = $this->getColPasajeros();
        $cantPasajeros = count($colPasajeros);
        $seEncontro = false;
        $i = 0;
        while($i<$cantPasajeros && !$seEncontro){
            $dniPasajero = $colPasajeros[$i]->getDocumento();
            $nuevoDni = $nuevoPasajero->getDocumento();
            if($dniPasajero == $nuevoDni){
                $seEncontro = true;
            }
            $i++;
        }
        if(!$seEncontro){
            $nombre = $nuevoPasajero->getNombre();
            $apellido = $nuevoPasajero->getApellido();
            $documento = $nuevoPasajero->getDocumento();
            $telefono = $nuevoPasajero->getTelefono();
            $nuevoPasajero->cargar($nombre,$apellido,$documento,$telefono,$this->idViaje);
            $nuevoPasajero->ingresar();
        }
        return $seEncontro;    
    }
    public function eliminarPasajero($numPasajero){
        $colPasajeros = $this->getColPasajeros();
        $colPasajeros[$numPasajero]->eliminar();
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
        $sql = "INSERT INTO viaje(idviaje, destino, cantmaxpasajeros, idempresa, rnumeroempleado, importe) VALUES (NULL,'".$this->getDestino().
        "', ".$this->getCantMaxPasajeros().", ".$this->getIdEmpresa().", ".$this->getNumEmpleado().", ".$this->getCostoViaje().")";
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
                    $this->setIdEmpresa($result['idempresa']);
                    $this->setNumEmpleado($result['rnumeroempleado']);
                    $this->setCostoViaje($result['importe']);
                    $resp = true;
                }else{
                    $this->setMensajeError($baseDatos->getERROR());
                }
            }else{
                $this->setMensajeError($baseDatos->getERROR());
            }
        }else{
            $this->setMensajeError($baseDatos->getERROR());
        }
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
                    $idEmpresa = $tupla['idempresa'];
                    $numeroEmpleado = $tupla['rnumeroempleado'];
                    $importe = $tupla['importe'];
                    $viaje = new Viaje();
                    $viaje->cargar($idViaje,$destino,$cantMaxPasajeros,$idEmpresa,[],$numeroEmpleado,$importe,0);
                    //falta ver como manejar el tema de la colPasajeros y la sumaCostos 
                    $resultados[] = $viaje;
                }
            }else{
                $this->setMensajeError($baseDatos->getERROR());
            }
        }else{
            $this->setMensajeError($baseDatos->getERROR());
        }
        return $resultados;
    }

    public function eliminar(){
        $baseDatos = new BDViajes();
        $resp = false;
        $sql = "DELETE FROM viaje WHERE idviaje=".$this->getIdViaje();
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

    public function modificar(){
        $baseDatos = new BDViajes();
        $resp = false;
        $sql = "UPDATE viaje SET destino='".$this->getDestino()."', cantmaxpasajeros=".
            $this->getCantMaxPasajeros().", idempresa=".$this->getIdEmpresa().", rnumeroempleado=".$this->getNumEmpleado().
            ", importe=".$this->getCostoViaje()." WHERE idviaje=".$this->getIdViaje();
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
}

?>