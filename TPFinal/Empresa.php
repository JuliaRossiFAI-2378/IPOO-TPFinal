<?php
class Empresa{
    private $idEmpresa;
    private $nombre;
    private $direccion;
    private $mensajeError;

    public function __construct(){
        $this->idEmpresa = 0;
        $this->nombre = "";
        $this->direccion = "";
    }
    public function getIdEmpresa(){
        return $this->idEmpresa;
    }
    public function setIdEmpresa($newIdEmpresa){
        $this->idEmpresa = $newIdEmpresa;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function setNombre($newNombre){
        $this->nombre = $newNombre;
    }
    public function getDireccion(){
        return $this->direccion;
    }
    public function setDireccion($newDireccion){
        $this->direccion = $newDireccion;
    }
    public function getMensajeError(){
        return $this->mensajeError;
    }
    public function setMensajeError($newMensajeError){
        $this->mensajeError = $newMensajeError;
    }
    public function __toString()
    {
        $cad = "\nNombre: ".$this->getNombre()."\nDireccion: ".$this->getDireccion().
        "\nId: ".$this->getIdEmpresa();
        return $cad;
    }

    public function cargar($id,$nombre,$direccion){
        $this->idEmpresa = $id;
        $this->nombre = $nombre;
        $this->direccion = $direccion;
    }

    public function buscar($id){
        $baseDatos = new BDViajes();
        $resp = false;
        $query = "SELECT * FROM empresa WHERE idempresa=".$id;
        if($baseDatos->conectarBD()){
            if($baseDatos->consulta($query)){
                if(($tupla = $baseDatos->registro()) != null){
                    $this->setNombre($tupla['enombre']);
                    $this->setIdEmpresa($id);
                    $this->setDireccion($tupla['edireccion']);
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

    public function ingresar(){
        $baseDatos = new BDViajes();
        $resp = false;
        $sql = "INSERT INTO empresa(idempresa, enombre, edireccion) VALUES (NULL, '".$this->getNombre()."', '".
            $this->getDireccion()."')";
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

    public function listar($condicion = ""){
        $resultados = null;
        $baseDatos = new BDViajes();
        $sql = "SELECT * FROM empresa";
        if($condicion != ""){
            $sql .= " WHERE ".$condicion;
        }
        if($baseDatos->conectarBD()){
            if($baseDatos->consulta($sql)){
                $resultados = [];
                while($tupla = $baseDatos->registro()){
                    $idEmpresa = $tupla ['idempresa'];
                    $nombre = $tupla['enombre'];
                    $direccion = $tupla['edireccion'];
                    $empresa = new Empresa();
                    $empresa->cargar($idEmpresa,$nombre,$direccion);
                    $resultados[] = $empresa;
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
        $resp = false;
        $baseDatos = new BDViajes();
        $sql = "DELETE FROM empresa WHERE idempresa=".$this->getIdEmpresa();
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
        $resp = false;
        $baseDatos = new BDViajes();
        $sql = "UPDATE empresa SET enombre='".$this->getNombre()."', edireccion='".$this->getDireccion().
            " WHERE idempresa=".$this->getIdEmpresa();
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