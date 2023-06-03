<?php
    //Control de sesion
    session_start();

    // Verificar si hay una sesión iniciada
    if (isset($_SESSION['correo'])) {
        // Sesión iniciada, redirigir al usuario a otra página
        header('Location: portada.php');
        exit();
    }
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="dist/output.css" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css">
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <title>Red Social - Diego Sánchez</title>

    <script>
        $(document).ready(function(){
            $("#error").hide();

            //Borrar mensaje de error al pulsar en el input
            $("#email").keydown(function(){
                $("#error").hide(500);
            });
            $("#nombre").keydown(function(){
                $("#error").hide(500);
            });
            $("#pass").keydown(function(){
                $("#error").hide(500);
            });
            $("#repitePass").keydown(function(){
                $("#error").hide(500);
            });
            $("#apellido").keydown(function(){
                $("#error").hide(500);
            });
            

            $("#btnRegistrar").click(function(){
                event.preventDefault();//Evita el envio del formulario
                let correo = $("#email").val();
                let pass = $("#pass").val();
                let repitePass = $("#repitePass").val();
                let nombre = $("#nombre").val();
                let apellido = $("#apellido").val();

                //Comprobar que ningun campo está vacio
                if((correo == '') || (pass == '') || (repitePass == '') || (nombre == '') || (apellido == '')){
                    $("#mensajeError").text("Ningún campo puede estar vacio");
                    $("#error").show(500);
                }else{
                    //Comprobar que las dos contraseñas son iguales
                    if(pass != repitePass){
                        $("#mensajeError").text("Las contraseñas no coinciden");
                        $("#error").show(500);
                    }else{
                        //Todo está correcto asi que mandamos la comprobacion del correo
                        $.ajax({
                            type: 'POST',
                            url: 'api/comprobarCorreo.php',
                            data: {correo:correo},
                            dataType: 'json',
                            success: function(data){
                                if(data.codigo==0){
                                    //La conexion ha sido buena, vamos a la pagina principal, donde ya existe una sesion
                                    $("#formRegistro").submit();
                                }else{
                                    //El usuario y/o la contraseña son erroneas
                                    $("#mensajeError").text("El correo ya existe");
                                    $("#error").show(500);
                                }
                            },
                            error: function(){
                                alert("Error");
                            }
                        });
                    }
                }
            });


            
        });
    </script>
</head>
<body>
<div class="flex h-screen w-full items-center justify-center bg-gray-900 bg-cover bg-no-repeat" style="background-image:url('imagenes/fondo2.jpg')">
    <div class="rounded-xl bg-gray-800 bg-opacity-50 px-16 py-10 shadow-lg backdrop-blur-md max-sm:px-8">
        <div class="text-white">
            <div class="mb-8 flex flex-col items-center">
                <img src="imagenes/marcador-de-alfiler.png" width="150" alt="" srcset="" />
                <h1 class="mb-2 text-2xl">Social Web</h1>
                <span class="text-gray-300">Diego Sánchez</span>
            </div>
            <form id="formRegistro" action="api/registrarUsuario.php" method="POST">
                <div class="mb-4 text-lg">
                    <input id="nombre" name="nombre" class="rounded-3xl border-none bg-yellow-400 bg-opacity-50 px-6 py-2 text-center text-inherit placeholder-slate-200 shadow-lg outline-none backdrop-blur-md" type="text" name="nombre" placeholder="Nombre" />
                </div>
                <div class="mb-4 text-lg">
                    <input id="apellido" name="apellido" class="rounded-3xl border-none bg-yellow-400 bg-opacity-50 px-6 py-2 text-center text-inherit placeholder-slate-200 shadow-lg outline-none backdrop-blur-md" type="text" name="apellido" placeholder="Apellido" />
                </div>
                <div class="mb-4 text-lg">
                    <input id="email" name="email" class="rounded-3xl border-none bg-yellow-400 bg-opacity-50 px-6 py-2 text-center text-inherit placeholder-slate-200 shadow-lg outline-none backdrop-blur-md" type="text" name="email" placeholder="Email" />
                </div>
                <div class="mb-4 text-lg">
                    <input id="pass" name="pass" class="rounded-3xl border-none bg-yellow-400 bg-opacity-50 px-6 py-2 text-center text-inherit placeholder-slate-200 shadow-lg outline-none backdrop-blur-md" type="password" name="contrasena" placeholder="Contraseña" />
                </div>
                <div class="mb-4 text-lg">
                    <input id="repitePass" name="repitePass" class="rounded-3xl border-none bg-yellow-400 bg-opacity-50 px-6 py-2 text-center text-inherit placeholder-slate-200 shadow-lg outline-none backdrop-blur-md" type="password" name="repetirContrasena" placeholder="Repetir Contraseña" />
                </div>
                <div class="mb-8 flex flex-col items-center">
                    <h1 class="mb-2 text-sm" style="cursor: pointer"><a href="index.php">Ya tengo una cuenta</a></h1>
                </div>
                <div id="error" class="error">
                    <h1 id="mensajeError">Los campos no pueden estar vacíos</h1>
                </div>
                <div class="mt-8 flex justify-center text-lg text-black">
                    <button type="submit" id="btnRegistrar" class="rounded-3xl bg-yellow-400 bg-opacity-50 px-10 py-2 text-white shadow-xl backdrop-blur-md transition-colors duration-300 hover:bg-blue-600">Registrarse</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>