<?php
class Viaje{
    private $idViaje;
    private $destino;
    private $cantMaxPasajeros;
    private $colPasajeros;
    private $numEmpleado;
    private $costoViaje;
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
        $pasajero = new Pasajero();
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
    public function getMensajeError(){
        return $this->mensajeError;
    }
    public function setMensajeError($newMensajeError){
        $this->mensajeError = $newMensajeError;
    }
    public function getALLPasajeros(){
        $colPasajeros = [];
        $pasajero = new Pasajero();
        if($temp = $pasajero->listar()){
            $colPasajeros = $temp;
        }
        return $colPasajeros;
    }
    public function __toString()
    {
        $cad = "\nId del viaje: ".$this->getIdViaje()."\nDestino: ".$this->getDestino()."\nCantidad maxima de pasajeros: ".
        $this->getCantMaxPasajeros()."\nCantidad actual de pasajeros: ".count($this->getColPasajeros()).
        "\nCosto del viaje: $".$this->getCostoViaje()."\n\n\t\tInformacion del responsable del viaje";
        $responsable = new ResponsableV();
        if($this->getNumEmpleado() != null){
            $responsable->buscar($this->getNumEmpleado());
            $cad .= $responsable;
        }else{
            $cad .= "\nEl viaje NO tiene un responsable, deberia asignarle uno.";
        }
        return $cad;
    }

    public function cargar($idViaje,$destino,$cantMaxPasajeros,$idEmpresa,$colPasajeros,$numEmpleado,$costoViaje){
        $this->idViaje = $idViaje;
        $this->destino = $destino;
        $this->cantMaxPasajeros = $cantMaxPasajeros;
        $this->idEmpresa = $idEmpresa;
        $this->colPasajeros = $colPasajeros;
        $this->numEmpleado = $numEmpleado;
        $this->costoViaje = $costoViaje;
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
                if($this->buscar($nuevoDato)){
                    $objPasajero->setIdViaje($nuevoDato);
                    $objPasajero->modificar();
                }else{
                    $seEncontro = true;
                }                
                break;
        }
        return $seEncontro;
    }
    public function agregarPasajero($nuevoPasajero){
        $colPasajeros = $this->getALLPasajeros();
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
        $sql = "SELECT * FROM viajes WHERE idviaje=".$id;
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
        $sql = "SELECT * FROM viajes";
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
                    $viaje->cargar($idViaje,$destino,$cantMaxPasajeros,$idEmpresa,[],$numeroEmpleado,$importe);
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