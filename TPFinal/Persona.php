<?php
class Persona{
    private $documento;
    private $nombre;
    private $apellido;
    private $telefono;
    private $mensajeError;

    public function __construct()
    {
        $this->documento = "";
        $this->nombre = "";
        $this->apellido = "";
        $this->telefono = "";
    }
    public function getDocumento(){
        return $this->documento;
    }
    public function setDocumento($newDocumento){
        $this->documento = $newDocumento;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function setNombre($newNombre){
        $this->nombre = $newNombre;
    }
    public function getApellido(){
        return $this->apellido;
    }
    public function setApellido($newApellido){
        $this->apellido = $newApellido;
    }
    public function getTelefono(){
        return $this->telefono;
    }
    public function setTelefono($newTelefono){
        $this->telefono = $newTelefono;
    }
    public function getMensajeError(){
        return $this->mensajeError;
    }
    public function setMensajeError($newMensajeError){
        $this->mensajeError = $newMensajeError;
    }
    public function __toString()
    {
        $cad = "\nNombre: ".$this->getNombre()."\nApellido: ".$this->getApellido().
        "\nDNI: ".$this->getDocumento()."\nTelefono: ".$this->getTelefono();
        return $cad;
    }

    public function cargar($nombre,$apellido,$documento,$telefono){
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->documento = $documento;
        $this->telefono = $telefono;
    }
    
    public function buscar($documento)
    {
        $baseDatos = new BDViajes();
        $resp = false;
        $query = "SELECT * FROM persona WHERE documento=".$documento;
        if($baseDatos->conectarBD()){
            if($baseDatos->consulta($query)){
                if(($result = $baseDatos->registro()) != null){
                    $this->setNombre($result['nombre']);
                    $this->setDocumento($documento);
                    $this->setApellido($result['apellido']);
                    $this->setTelefono($result['telefono']);
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
        $sql = "INSERT INTO persona(documento, apellido, nombre, telefono) VALUES (".$this->getDocumento().",'".$this->getApellido().
        "','".$this->getNombre()."','".$this->getTelefono()."')";
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
        $sql = "SELECT * FROM persona";
        if($condicion != ""){
            $sql .= " WHERE ".$condicion;
        }
        if($baseDatos->conectarBD()){
            if($baseDatos->consulta($sql)){
                $resultados = [];
                while($tupla = $baseDatos->registro()){
                    $documento = $tupla ['documento'];
                    $nombre = $tupla['nombre'];
                    $apellido = $tupla['apellido'];
                    $telefono = $tupla['telefono'];
                    $persona = new Persona();
                    $persona->cargar($nombre,$apellido,$documento,$telefono);
                    $resultados[] = $persona;
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
        $sql = "DELETE FROM persona WHERE documento=".$this->getDocumento();
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
        $sql = "UPDATE persona SET nombre='".$this->getNombre()."', apellido='".$this->getApellido().
            "', telefono=".$this->getTelefono()." WHERE documento=".$this->getDocumento();
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

    public function modificarClave($documentoAnterior){
        $resp = false;
        $baseDatos = new BDViajes();
        $sql = "UPDATE persona SET documento=".$this->getDocumento()." WHERE documento=".$documentoAnterior;
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