<?php
class ResponsableV extends Persona{
    private $numEmpleado;
    private $numLicencia;

    public function __construct()
    {
        parent::__construct();
        $this->numEmpleado = 0;
        $this->numLicencia = 0;
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

    public function buscar($numEmpleado){
        $baseDatos = new BDViajes();
        $resp = false;
        $query = "SELECT * FROM responsables WHERE numeroempleado=".$numEmpleado;
        if($baseDatos->conectarBD()){
            if($baseDatos->consulta($query)){
                if(($result = $baseDatos->registro()) != null){
                    parent::buscar($result['rdocumento']);
                    $this->setNumEmpleado($numEmpleado);
                    $this->setNumLicencia($result['numerolicencia']);
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

    public function listar($condicion = ""){
        $resultadoResponsables = null;
        $baseDatos = new BDViajes();
        $sql = "SELECT * FROM responsables";
        if($condicion != ""){
            $sql .= " WHERE ".$condicion;
        }
        if($baseDatos->conectarBD()){
            if($baseDatos->consulta($sql)){
                $resultadoResponsables = [];
                while($tupla = $baseDatos->registro()){
                    $responsable = new ResponsableV();
                    $responsable->buscar($tupla['numeroempleado']);
                    $resultadoResponsables[] = $responsable;
                }
            }else{
                $this->setMensajeError($baseDatos->getERROR());
            }
        }
        return $resultadoResponsables;
    }

    public function ingresar(){
        $baseDatos = new BDViajes();
        $resp = false;
        if(parent::ingresar()){
            $sql = "INSERT INTO responsables (numeroempleado, numerolicencia, rdocumento) VALUES (NULL, ".
                $this->getNumLicencia().", ".parent::getDocumento().")";
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
                $sql = "UPDATE responsables SET numeroempleado=".$this->getNumEmpleado().", numerolicencia=".
                        $this->getNumLicencia()." WHERE rdocumento=".parent::getDocumento();
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
            $sql = "DELETE FROM responsables WHERE rdocumento=".parent::getDocumento();
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