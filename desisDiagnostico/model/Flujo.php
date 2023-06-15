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

    public function verificarDuplicados($rut) {
        $sql = "SELECT COUNT(id) AS 'cantidad' FROM voto WHERE rut='$rut';";
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

    public function phpRule_ValidarRut($rut) {

        // Verifica que no esté vacio y que el string sea de tamaño mayor a 3 carácteres(1-9)        
        if ((empty($rut)) || strlen($rut) < 3) {
            return json_encode(array('error' => true, 'msj' => 'RUT vacío o con menos de 3 caracteres.'));
        }

        // Quitar los últimos 2 valores (el guión y el dígito verificador) y luego verificar que sólo sea
        // numérico
        $parteNumerica = str_replace(substr($rut, -2, 2), '', $rut);

        if (!preg_match("/^[0-9]*$/", $parteNumerica)) {
            return json_encode(array('error' => true, 'msj' => 'La parte numérica del RUT sólo debe contener números.'));
        }

        $guionYVerificador = substr($rut, -2, 2);
        // Verifica que el guion y dígito verificador tengan un largo de 2.
        if (strlen($guionYVerificador) != 2) {
            return json_encode(array('error' => true, 'msj' => 'Error en el largo del dígito verificador.'));
        }

        // obliga a que el dígito verificador tenga la forma -[0-9] o -[kK]
        if (!preg_match('/(^[-]{1}+[0-9kK]).{0}$/', $guionYVerificador)) {
            return json_encode(array('error' => true, 'msj' => 'El dígito verificador no cuenta con el patrón requerido'));
        }

        // Valida que sólo sean números, excepto el último dígito que pueda ser k
        if (!preg_match("/^[0-9.]+[-]?+[0-9kK]{1}/", $rut)) {
            return json_encode(array('error' => true, 'msj' => 'Error al digitar el RUT'));
        }

        $rutV = preg_replace('/[\.\-]/i', '', $rut);
        $dv = substr($rutV, -1);
        $numero = substr($rutV, 0, strlen($rutV) - 1);
        $i = 2;
        $suma = 0;
        foreach (array_reverse(str_split($numero)) as $v) {
            if ($i == 8) {
                $i = 2;
            }
            $suma += $v * $i;
            ++$i;
        }
        $dvr = 11 - ($suma % 11);
        if ($dvr == 11) {
            $dvr = 0;
        }
        if ($dvr == 10) {
            $dvr = 'K';
        }
        if ($dvr == strtoupper($dv)) {
            return json_encode(array('error' => false, 'msj' => 'RUT ingresado correctamente.'));
        } else {
            return json_encode(array('error' => true, 'msj' => 'El RUT ingresado no es válido.'));
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
