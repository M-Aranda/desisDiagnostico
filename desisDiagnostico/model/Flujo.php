<?php

session_start();
require("conexion.php");

class Flujo {

    public $conn;

    function __construct() {
        try {
            $this->conn = new Conexion();
        } catch (Exception $e) {
            echo "Error" . $e->getMessage();
        }
    }

    function __destruct() {
        $this->conn = null;
    }

    public function listarRegiones() {
        $sql = "SELECT * FROM regiones;";

        $ej = $this->conn->Consultar($sql);
        $rowcount = mysqli_num_rows($ej);
        $retorno = array();
        if ($rowcount > 0) {
            while ($r = mysqli_fetch_assoc($ej)) {
                array_push($retorno, $r);
            }

            return json_encode($retorno);
        } else {

            return json_encode(array());
        }
    }

    public function listarComunasDeRegion($id_region) {
        $sql = "SELECT A.id, A.comuna FROM comunas A JOIN provincias B ON A.provincia_id=B.id 
               JOIN regiones C ON B.region_id=C.id
               WHERE C.id=$id_region";
        $ej = $this->conn->Consultar($sql);
        $rowcount = mysqli_num_rows($ej);
        $retorno = array();
        if ($rowcount > 0) {
            while ($r = mysqli_fetch_assoc($ej)) {
                array_push($retorno, $r);
            }
            return json_encode($retorno);
        } else {
            return json_encode(array());
        }
    }

    public function registrar($nombreYApellido, $alias, $rut, $email, $region, $comuna, $candidato, $chkWeb, $chkTV, $chkRS, $chkAmigo) {
        try {

            $sql = "INSERT INTO voto VALUES (NULL,'$nombreYApellido','$alias','$rut','$email', 1, 1,0,0);";
            $ej = $this->conn->Consultar($sql);

            return json_encode(array("mensaje" => "OK"));
        } catch (Exception $e) {
            echo "Error" . $e->getMessage();
            return json_encode(array("mensaje" => "ERROR"));
        }
    }

    public function listarCandidatos() {
        $sql = "SELECT * FROM candidato;";

        $ej = $this->conn->Consultar($sql);
        $rowcount = mysqli_num_rows($ej);
        $retorno = array();
        if ($rowcount > 0) {
            while ($r = mysqli_fetch_assoc($ej)) {
                array_push($retorno, $r);
            }

            return json_encode($retorno);
        } else {

            return json_encode(array());
        }
    }

}


//para pruebas
//
//$f = new Flujo();
//
//echo $f->listarRegiones();
//echo $f->listarComunasDeRegion(1);
//echo $f->listarCandidatos();


?>
