<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <style>
        .form-box {
            width: 33%;
            margin: auto;
            border: 1px solid #ccc;
            padding: 20px;
            box-sizing: border-box;
        }

        .form-box h2 {
            text-align: center;
        }

        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .form-group label {
            width: 150px;
        }

        .form-group input,
        .form-group select {
            flex: 1;
            width: 100%;
            box-sizing: border-box;
        }

        .form-group .checkbox-label {
            margin-right: 10px;
        }

        .form-group button {
            margin-top: 10px;
        }
    </style>

    <body>
        <div class="form-box">
            <h2>Formulario de votacion</h2>

            <div class="form-group">
                <label for="nombreYApellido">Nombre y apellido:</label>
                <input id="nombreYApellido" type="text" required>
            </div>

            <div class="form-group">
                <label for="alias">Alias:</label>
                <input id="alias" type="text" required>
            </div>

            <div class="form-group">
                <label for="rut">Rut:</label>
                <input id="rut" type="text" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input id="email" type="text" required>
            </div>

            <div class="form-group">
                <label for="region">Region:</label>
                <select id="region" onchange="listarComunas()"></select>
            </div>

            <div class="form-group">
                <label for="comuna">Comuna:</label>
                <select id="comuna"></select>
            </div>

            <div class="form-group">
                <label for="candidato">Candidato:</label>
                <select id="candidato"></select>
            </div>

            <div class="form-group">
                <label>Como se entero de nostoros:</label>
                <label class="checkbox-label" for="chkWeb">Web:</label>
                <input id="chkWeb" type="checkbox">
                <label class="checkbox-label" for="chkTV">Tv:</label>
                <input id="chkTV" type="checkbox">
                <label class="checkbox-label" for="chkRS">Redes Sociales:</label>
                <input id="chkRS" type="checkbox">
                <label class="checkbox-label" for="chkAmigo">Amigo:</label>
                <input id="chkAmigo" type="checkbox">
            </div>

            <button id="btnVotar" onclick="votar()">Votar</button>
        </div>
    </body>



    <script>



        function votar() {


            var nombreYApellido = $("#nombreYApellido").val();
            var alias = $("#alias").val();
            var rut = $("#rut").val();
            var email = $("#email").val();
            var region = $('#region').val();
            var comuna = $('#comuna').val();
            var candidato = $('#candidato').val();
            var web = 0;
            var tv = 0;
            var rs = 0;
            var amigo = 0;

            var validezNA = true;
            var validezAlias = true;
            var validezRut = true;
            var validezEmail = true;
            var validezCSEN = true;
            var validezRutVotoDuplicado = true;

            var webChequeado = $('#chkWeb').is(':checked');
            var tvChequeado = $('#chkTV').is(':checked');
            var rsChequeado = $('#chkRS').is(':checked');
            var amigoChequeado = $('#chkAmigo').is(':checked');


            var comoSeEnteroContador = 0;

            if (webChequeado) {
                comoSeEnteroContador++;
                web = 1;
            }
            if (tvChequeado) {
                comoSeEnteroContador++;
                tv = 1;
            }
            if (rsChequeado) {
                comoSeEnteroContador++;
                rs = 1;
            }
            if (amigoChequeado) {
                comoSeEnteroContador++;
                amigo = 1;
            }



            if (validarNombreYApellido(nombreYApellido)) {
                validezNA = false;
                console.log('nombre y apellido invalido');
            } else {
                console.log("nombre y apellidos ok");
            }



            if (!validarAlias(alias)) {
                validezAlias = false;
                console.log('alias invalido');
            } else {
                console.log("alias ok");
            }


            if (!validarRut(rut)) {
                validezRut = false;
                console.log('rut invalido');
            } else {
                console.log("rut ok");
            }

            if (!validarEmail(email)) {
                var validezEmail = false;
                console.log('email invalido');

            } else {
                console.log("email ok");
            }

            if (comoSeEnteroContador < 2) {
                validezCSEN = false;
                console.log('debe seleccionar al menos 2 opciones');
            } else {
                console.log("Cantidad de selecciones ok");
            }


            if (verificarDuplicados(rut)) {
                console.log("Rut ya voto");
                validezRutVotoDuplicado = false;

            } else {
                console.log('Rut vota por primera vez');
            }

            if (!validezNA || !validezAlias || !validezEmail || !validezCSEN || !validezRutVotoDuplicado) {

                if (!validezRutVotoDuplicado) {
                    alert("este rut ya voto");
                } else {
                    alert("Asegurese de completar el formulario adecuadamente");
                }


            } else {
                //verificacion exitosa

                $.ajax({
                    url: 'controller/manejarFlujo.php',
                    type: 'POST',
                    dataType: 'json',
                    async: true,
                    data: {
                        Accion: 'registrar',
                        nombreYApellido: nombreYApellido,
                        alias: alias,
                        rut: rut,
                        email: email,
                        region: region,
                        comuna: comuna,
                        candidato: candidato,
                        chkWeb: web,
                        chkTV: tv,
                        chkRS: rs,
                        chkAmigo: amigo

                    },
                    beforeSend: function () {
                    },
                    success: function (data) {
                        alert("registro exitoso");
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log('error: ' + textStatus);
                    }
                });

            }





        }

        function listarRegiones() {

            $.ajax({
                url: 'controller/manejarFlujo.php',
                type: 'POST',
                dataType: 'json',
                async: false,
                data: {
                    Accion: 'listarRegiones'
                },
                beforeSend: function () {
                },
                success: function (data) {

                    if (data.length > 0) {
                        $("#region").html('');
                        $.each(data, function (id, dato) {

                            $("#region").append('<option value="' + dato.id + '">' + dato.region + '</option>');
                        });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('error: ' + textStatus);
                }
            });
        }




        function listarCandidatos() {

            $.ajax({
                url: 'controller/manejarFlujo.php',
                type: 'POST',
                dataType: 'json',
                async: false,
                data: {
                    Accion: 'listarCandidatos'
                },
                beforeSend: function () {
                },
                success: function (data) {

                    if (data.length > 0) {
                        $("#candidato").html('');
                        $.each(data, function (id, dato) {

                            $("#candidato").append('<option value="' + dato.id + '">' + dato.nombre + '</option>');
                        });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('error: ' + textStatus);
                }
            });
        }

        function listarComunas(id_region) {

            var e = document.getElementById("region");
            var region = e.value;

            $.ajax({
                url: 'controller/manejarFlujo.php',
                type: 'POST',
                dataType: 'json',
                async: false,
                data: {
                    Accion: 'listarComunas',
                    id_region: region
                },
                beforeSend: function () {
                },
                success: function (data) {

                    if (data.length > 0) {
                        $("#comuna").html('');
                        $.each(data, function (id, dato) {

                            $("#comuna").append('<option value="' + dato.id + '">' + dato.comuna + '</option>');
                        });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('error: ' + textStatus);
                }
            });
        }



        $(document).ready(function () {
            listarRegiones();
            listarCandidatos();
            listarComunas(1);

        });


        function validarRut(rut) {

            rut = rut.replace(/[^\dKk]/g, '').toUpperCase();

            if (!/^\d{1,2}\.\d{3}\.\d{3}[-][0-9Kk]{1}$/.test(rut)) {
                return false;
            }

            const digits = rut.slice(0, -2).replace(/\D/g, '');
            const verifier = rut.slice(-1);

            let sum = 0;
            let multiplier = 2;
            for (let i = digits.length - 1; i >= 0; i--) {
                sum += parseInt(digits[i], 10) * multiplier;
                multiplier = (multiplier + 1) % 8 || 2;
            }
            const expectedVerifier = String(11 - (sum % 11));

            if (expectedVerifier === '10' && verifier === 'K') {
                return true;
            } else if (expectedVerifier === '11' && verifier === '0') {
                return true;
            } else if (expectedVerifier === verifier) {
                return true;
            } else {
                return false;
            }


        }


        function validarNombreYApellido(na) {
            return na.trim().length === 0;
        }


        function validarAlias(alias) {

            if (alias.length > 5 && /[a-zA-Z]/.test(alias) && /\d/.test(alias)) {
                return true;
            }
            return false;

        }


        function validarEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const domainRegex = /^[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*$/;
            const tldRegex = /^[a-zA-Z]{2,}$/;

            if (!emailRegex.test(email)) {
                return false;
            }

            const [localPart, domainPart] = email.split("@");

            if (localPart.length > 64 || domainPart.length > 255) {
                return false;
            }

            if (!domainRegex.test(domainPart)) {
                return false;
            }

            const tld = domainPart.split(".").pop();

            if (!tldRegex.test(tld)) {
                return false;
            }

            return true;
        }

        function verificarDuplicados(rut) {

            var rut = $("#rut").val();

            var rutNoVoto = true;
            $.ajax({
                url: 'controller/manejarFlujo.php',
                type: 'POST',
                dataType: 'json',
                async: true,
                data: {
                    Accion: 'verificarDuplicados',
                    rut: rut

                },
                beforeSend: function () {
                },
                success: function (data) {
                    var cant = Number(data[0].cantidad);

                    if (cant === 0) {
                        rutNoVoto = false;
                        console.log('Primera vez votando');
                    } else {
                        console.log('Ya voto');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('error: ' + textStatus);
                }
            });

            return rutNoVoto;
        }




    </script>

</html>
