<?php
class BDViajes{
    private $HOSTNAME;
    private $USERNAME;
    private $PASSWORD;
    private $NOMBREBASE;
    private $LINK;
    private $RESULT;
    private $ERROR;

    public function __construct(){
        $this->HOSTNAME = "127.0.0.1";
        $this->USERNAME = "root";
        $this->PASSWORD = "";
        $this->NOMBREBASE = "bdviajes";
    }
    
    public function getERROR(){
        return $this->ERROR;
    }

    public function conectarBD(){
        $resp = false;
        $link = mysqli_connect($this->HOSTNAME,$this->USERNAME,$this->PASSWORD);
        if($link){
            mysqli_select_db($link,$this->NOMBREBASE);
            $this->LINK = $link;
            $resp = true;
        }else{
            $this->ERROR = "\nError de conexion: ".mysqli_connect_error()."\n";
        }
        return $resp;
    }

    public function consulta($query){
        $resp = false;
        $link = $this->LINK;
        if($this->RESULT = mysqli_query($link,$query)){
            $resp = true;
        }else{
            $this->ERROR = "\nError ". mysqli_error($link)."\n";
        }
        return $resp;
    }

    public function registro(){
        $resp = null;
        if($this->RESULT){
            if($temp = mysqli_fetch_assoc($this->RESULT)){
                $resp = $temp;
            }else{
                mysqli_free_result($this->RESULT);
            }
        }else{
            $this->ERROR = mysqli_error($this->LINK);
        }
        return $resp;
    }

    public function numAutoIncrement($query){
        $resp = null;
        if($this->RESULT = mysqli_query($this->LINK, $query)){
            $resp = mysqli_insert_id($this->LINK);
        }else{
            $this->ERROR = mysqli_error($this->LINK);
        }
        return $resp;
    }
}
?>