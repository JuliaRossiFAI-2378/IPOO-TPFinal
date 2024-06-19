<?php
class Pasajero extends Persona{
    private $idViaje;

    public function __construct()
    {
        parent::__construct();
        $this->idViaje = "";
    }
    public function getIdViaje(){
        return $this->idViaje;
    }
    public function setIdViaje($newIdViaje){
        $this->idViaje = $newIdViaje;
    }
    public function __toString()
    {
        $cad = parent::__toString()."\nID viaje: ".$this->getIdViaje()."\n";
        return $cad;
    }

    public function cargar($nombre,$apellido,$documento,$telefono,$idViaje=null){
        parent::cargar($nombre,$apellido,$documento,$telefono);
        $this->idViaje = $idViaje;
    }

    public function buscar($dni){
        $baseDatos = new BDViajes();
        $resp = false;
        $query = "SELECT * FROM pasajeros WHERE pdocumento=".$dni;
        if($baseDatos->conectarBD()){
            if($baseDatos->consulta($query)){
                if(($result = $baseDatos->registro()) != null){
                    parent::buscar($dni);
                    $this->setIdViaje($result['idviaje']);
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

    public function listar($where = ""){
        $resultadoPasajeros = null;
        $baseDatos = new BDViajes();
        $sql = "SELECT * FROM pasajeros";
        if($where != ""){
            $sql .= " WHERE ".$where;
        }
        if($baseDatos->conectarBD()){
            if($baseDatos->consulta($sql)){
                $resultadoPasajeros = [];
                while($tupla = $baseDatos->registro()){
                    $pasajero = new Pasajero();
                    $pasajero->buscar($tupla['pdocumento']);
                    $resultadoPasajeros[] = $pasajero;
                }
            }else{
                $this->setMensajeError($baseDatos->getERROR());
            }
        }
        return $resultadoPasajeros;
    }

    public function ingresar(){
        $baseDatos = new BDViajes();
        $resp = false;
        if(parent::ingresar()){
            $sql = "INSERT INTO pasajeros (pdocumento, idviaje) VALUES (".parent::getDocumento().", ".$this->getIdViaje().")";
            if($baseDatos->conectarBD()){
                if($baseDatos->consulta($sql)){
                    $resp = true;
                }else{
                    $this->setMensajeError($baseDatos->getERROR());
                }
            }else{
                $this->setMensajeError($baseDatos->getERROR());
            }
        }
        return $resp;
    }

    public function modificar(){
        $baseDatos = new BDViajes();
        $resp = false;
        if(parent::modificar()){
            if($baseDatos->conectarBD()){
                $sql = "UPDATE pasajeros SET idviaje=".$this->getIdViaje()." WHERE pdocumento=".parent::getDocumento();
                if($baseDatos->consulta($sql)){
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

    public function eliminar(){
        $baseDatos = new BDViajes();
        $resp = false;
        if($baseDatos->conectarBD()){
            $sql = "DELETE FROM pasajeros WHERE pdocumento=".parent::getDocumento();
            if($baseDatos->consulta($sql)){
                if(parent::eliminar()){
                    $resp = true;
                }
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