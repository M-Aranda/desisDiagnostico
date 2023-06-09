<?php

class Conexion {

    private $Servidor;
    private $UsuarioDeMysql;
    private $ClaveDeMysql;
    private $BaseDeDatos;
    private $Conectar;

    function __construct() {


//Desarrollo Local
        if ($_SERVER['HTTP_HOST'] == "localhost") {
            $this->UsuarioDeMysql = "root";
            $this->ClaveDeMysql = "root";
            $this->BaseDeDatos = "diagnosticoDesis";
        }
        $this->ConectarAMysql();
    }

    private function ConectarAMysql() {
        $this->Conectar = mysqli_connect($this->Servidor, $this->UsuarioDeMysql, $this->ClaveDeMysql, $this->BaseDeDatos);
        //echo mysqli_connect_error();
        mysqli_set_charset($this->Conectar, "utf8");
        $sql = "SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED ;";
        $this->Consultar($sql);
    }

    public function Consultar($sql) {
        
        $resultado = mysqli_query($this->Conectar, $sql);
        
        return $resultado;
    }

    public function NFilas($sql) {
        return mysqli_num_rows($sql);
    }

    public function NColumnas($sql) {
        return mysqli_num_fields($sql);
    }

    public function NomCampo($sql, $i) {
        return mysqli_fetch_field_direct($sql, $i);
    }
    public function idInsertado(){
        return mysqli_insert_id($this->Conectar);
    }
    public function escapaCaracteres($archivo){
        return mysqli_real_escape_string($this->Conectar, $archivo);
    }
    function __destruct() {
        $sql = "COMMIT ;";
        $this->Consultar($sql);
        mysqli_close($this->Conectar);
    } 

    public function iniciaTransaccion(){
        mysqli_autocommit($this->Conectar, FALSE);
        mysqli_begin_transaction($this->Conectar);
    }
    public function commit(){
        mysqli_commit($this->Conectar);
        mysqli_autocommit($this->Conectar, TRUE);
    }
}

?>