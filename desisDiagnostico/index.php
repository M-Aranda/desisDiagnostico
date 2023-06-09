<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <body>
        <h2>Formulario de votacion</h2>

        Nombre y apellido <input id="nombreYApellido" type="text" required>  
        <br>
        Alias <input type="text"id="alias" min=5 required><br>

        Rut <input type="text" id="rut" required>
        <br>
        Email<input type="text" id="email" required>
        <br>
        Region<select id="region"></select>
        <br>
        Comuna<select id="comuna"></select>
        <br>

        Candidato<select id="candidato"></select>
        <br>

        Como se entero de nosotros<input id="chkWeb" type="checkbox"> Web<input id="chkTV" type="checkbox">TV<input id="chkRS" type="checkbox">Redes Sociales<input id="chkAmigo" type="checkbox"> Amigo

        <button id="btnVotar" onclick="votar()">Votar</button>

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
            var web = $("#chkWeb").val();
            var tv = $("#chkTV").val();
            var rs = $("#chkRS").val();
            var amigo = $("#chkAmigo").val();



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

            $.ajax({
                url: 'controller/manejarFlujo.php',
                type: 'POST',
                dataType: 'json',
                async: false,
                data: {
                    Accion: 'listarComunas',
                    id_region: id_region
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
            listarComunas(1);//de la primera reigon
            
        });








    </script>


</html>
