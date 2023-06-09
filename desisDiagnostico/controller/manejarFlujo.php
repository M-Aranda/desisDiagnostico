<?php


$_SESSION['timestamp'] = time();
//include '..model/Conexion.php';
include '../model/Flujo.php';


if (isset($_POST)) {
    $accion = filter_input(INPUT_POST, "Accion");
    if ($accion == "registrar") {
        $nombreYApellido = filter_input(INPUT_POST, "nombreYApellido");
        $alias = filter_input(INPUT_POST, "alias");
        $rut = filter_input(INPUT_POST, "rut");
        $email = filter_input(INPUT_POST, "email");
        $region = filter_input(INPUT_POST, "region");
        $comuna = filter_input(INPUT_POST, "comuna");
        $candidato = filter_input(INPUT_POST, "candidato");
        $chkWeb = filter_input(INPUT_POST, "chkWeb");
        $chkTV = filter_input(INPUT_POST, "chkTV");
        $chkRS = filter_input(INPUT_POST, "chkRS");
        $chkAmigo = filter_input(INPUT_POST, "chkAmigo");
        registrar($nombreYApellido, $alias, $rut, $email, $region, $comuna, $candidato, $chkWeb, $chkTV, $chkRS, $chkAmigo);
    }

    if ($accion == "listarRegiones") {
        listarRegiones();
    }

    if ($accion == "listarCandidatos") {
        listarCandidatos();
    }

    if ($accion == "listarComunas") {
        $idRegion = $region = filter_input(INPUT_POST, "id_region");
        listarComunas($idRegion);
    }
}

function registrar($nombreYApellido, $alias, $rut, $email, $region, $comuna, $candidato, $chkWeb, $chkTV, $chkRS, $chkAmigo) {
    $f = new Flujo();
    $json = $f->registrar($nombreYApellido, $alias, $rut, $email, $region, $comuna, $candidato, $chkWeb, $chkTV, $chkRS, $chkAmigo);

    echo $json;
    $f = null;
}

function listarRegiones() {
    $f = new Flujo();
    $json = $f->listarRegiones();

    echo $json;
    $f = null;
    
}

function listarCandidatos() {
    $f = new Flujo();
    $json = $f->listarCandidatos();

    echo $json;
    $f = null;
}

function listarComunas($idRegion) {
    $f = new Flujo();
    $json = $f->listarComunasDeRegion($idRegion);

    echo $json;
    $f = null;
}



?>