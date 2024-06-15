<?php
class ResponsableV extends Persona{
    private $numEmpleado;
    private $numLicencia;

    public function __construct()
    {
        parent::__construct();
        $this->numEmpleado = "";
        $this->numLicencia = "";
    }
    public function getNumEmpleado(){
        return $this->numEmpleado;
    }
    public function setNumEmpleado($newNumEmpleado){
        $this->numEmpleado = $newNumEmpleado;
    }
    public function getNumLicencia(){
        return $this->numLicencia;
    }
    public function setNumLicencia($newNumLicencia){
        $this->numLicencia = $newNumLicencia;
    }
    public function __toString()
    {
        $cad = parent::__toString()."\nNumero de empleado: ".$this->getNumEmpleado()."\nNumero de licencia: ".$this->getNumLicencia()."\n";
        return $cad;
    }
    public function cargar($nombre,$apellido,$documento,$telefono,$numEmpleado=null,$numLicencia=null){
        parent::cargar($nombre,$apellido,$documento,$telefono);
        $this->numEmpleado = $numEmpleado;
        $this->numLicencia = $numLicencia;
    }

    public function buscar($dni){
        $baseDatos = new BDViajes();
        $resp = false;
        $query = "SELECT * FROM responsable WHERE rdocumento=".$dni;
        if($baseDatos->conectarBD()){
            if($baseDatos->consulta($query)){
                if(($result = $baseDatos->registro()) != null){
                    parent::buscar($dni);
                    $this->setNumEmpleado($result['numeroempleado']);
                    $this->setNumLicencia($result['numerolicencia']);
                    $resp = true;
                }else
                    $this->setMensajeError($baseDatos->getERROR());
            }else
                $this->setMensajeError($baseDatos->getERROR());
        }else
            $this->setMensajeError($baseDatos->getERROR());
        return $resp;
    }

    public function listar($where = ""){
        $resultadoResponsables = null;
        $baseDatos = new BDViajes();
        $sql = "SELECT * FROM responsable";
        if($where != ""){
            $sql .= " WHERE ".$where;
        }
        if($baseDatos->conectarBD()){
            if($baseDatos->consulta($sql)){
                $resultadoResponsables = [];
                while($tupla = $baseDatos->registro()){
                    $responsable = new ResponsableV();
                    $responsable->buscar($tupla['rdocumento']);
                    $resultadoResponsables[] = $responsable;
                }
            }else
                $this->setMensajeError($baseDatos->getERROR());
        }
        return $resultadoResponsables;
    }

    public function ingresar(){
        $baseDatos = new BDViajes();
        $resp = false;
        if(parent::ingresar()){
            $sql = "INSERT INTO responsable (rdocumento, numeroempleado, numerolicencia) VALUES (".parent::getDocumento().
                    ", ".$this->getNumEmpleado().", ".$this->getNumLicencia().")";
            if($baseDatos->conectarBD()){
                if($baseDatos->consulta($sql))
                    $resp = true;
                else
                    $this->setMensajeError($baseDatos->getERROR());
            }else
                $this->setMensajeError($baseDatos->getERROR());
        }
        return $resp;
    }

    public function modificar(){
        $baseDatos = new BDViajes();
        $resp = false;
        if(parent::modificar()){
            if($baseDatos->conectarBD()){
                $sql = "UPDATE responsable SET numeroempleado=".$this->getNumEmpleado().", numerolicencia=".
                        $this->getNumLicencia()." WHERE rdocumento=".parent::getDocumento();
                if($baseDatos->consulta($sql))
                    $resp = true;
                else
                    $this->setMensajeError($baseDatos->getERROR());
            }else
                $this->setMensajeError($baseDatos->getERROR());
        }else
            $this->setMensajeError($baseDatos->getERROR());
        return $resp;
    }

    public function eliminar(){
        $baseDatos = new BDViajes();
        $resp = false;
        if($baseDatos->conectarBD()){
            $sql = "DELETE FROM responsable WHERE rdocumento=".parent::getDocumento();
            if($baseDatos->consulta($sql)){
                if(parent::eliminar())
                    $resp = true;
            }
            else
                $this->setMensajeError($baseDatos->getERROR());
        }else
            $this->setMensajeError($baseDatos->getERROR());
        return $resp;
    }
}
?>