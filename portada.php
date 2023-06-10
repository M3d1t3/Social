<?php

    session_start();
    //Comprobar que la sesion es correcta

    // Verificar si hay una sesión iniciada
    if (!isset($_SESSION['correo'])) {
        // Sesión no iniciada, redirigir al usuario a otra página
        header('Location: index.php');
        exit();
    }else{
        //Sesion iniciada, cargamos los datos
        include("API/conectar.php");
        $consulta = "select * from usuarios where email = '" . $_SESSION['correo'] . "';";
        $resultado = $conn->query($consulta);
        while ($fila = mysqli_fetch_array($resultado)){
            $nombre = $fila['nombre'];
            $apellido = $fila['apellido'];
            $foto = $fila['foto'];
            if($foto == NULL){
                $foto = "imagenes/user.png";
            }
        }
    }
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="imagenes/marcador-de-alfiler.png" type="image/png">
    <link href="dist/output.css" rel="stylesheet">
    <link rel="stylesheet" href="css/portada.css<?php echo '?' . date("YmdHis") ?>">
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script src="js/portada.js"></script>
    <link rel="stylesheet" href="node_modules/cropperjs/dist/cropper.css">
    <script src="node_modules/cropperjs/dist/cropper.min.js"></script>
    <title>Red Social - Diego Sánchez</title>
    <script>
        $(document).ready(function(){
            //Procesar al iniciar la pagina-------------------------------------------------
            $("#bloque_spinner").hide();
            $("#contenedorRecorte").hide();
            $("#pantalla_perfil").hide();

            //Boton de cerrar sesion
            $("#btnCerrar").click(function(){
                window.location.href = 'API/cerrarSesion.php';
            });

            //Boton hamburguesa de despliegue de menu
            $("#menu_nav").click(function(){
                $("#menu_desplegable").toggle(300);
            });

            //Boton del envio de nuevo post
            $("#btnForm").click(function(){
                event.preventDefault();
            });

            //Abrir el cargador de fotos
            $("#fotoPerfil").click(function() {
                document.getElementById("inputFoto").click(); // Activar el input de tipo file
            });

            //Cancela la carga de la foto de perfil
            $("#btnCancelarRecorte").click(function(){
                $("#contenedorRecorte").hide();
                $("#imgRecorte").attr("src","");
            });

            // Carga la imagen seleccionada en el elemento de imagen
            $("#inputFoto").change(function() {
                var file = this.files[0];
                var reader = new FileReader();
                $("#contenedorRecorte").show();
                reader.onload = function(e) {
                    $imgRecorte.attr("src", e.target.result);
                    // Espera a que la imagen se cargue antes de inicializar Cropper.js
                    $imgRecorte.on("load", function() {
                        initCropper();
                    });
                };

                reader.readAsDataURL(file);
            });

            // Obtén una referencia al elemento de imagen y al contenedor del recorte
            var $imgRecorte = $("#imgRecorte");
            var $contenedorRecorte = $("#contenedorRecorte");

            // Configuración de Cropper.js
            var cropper;

            // Función para inicializar Cropper.js en la imagen cargada
            function initCropper() {
                // Destruye la instancia de Cropper.js si ya existe
                if (cropper) {
                cropper.destroy();
                $imgRecorte.data("cropper", null);
                }

                // Inicializa Cropper.js en la imagen
                cropper = new Cropper($imgRecorte[0], {
                    aspectRatio: 1, // Establece un aspecto cuadrado para la selección
                    viewMode: 3,
                    autoCrop: true,
                    responsive: true
                });
            }

            //Enviar la imagen y los datos de recorte a cambiarFoto.php
            $("#btnAceptarRecorte").click(function() {
                // Obtén la imagen recortada como un objeto de lienzo
                var canvas = cropper.getCroppedCanvas();

                // Convierte el lienzo en una imagen codificada en base64
                var imageData = canvas.toDataURL('image/jpeg');

                // Crea un objeto FormData y agrega la imagen codificada
                var formData = new FormData();
                formData.append('imagen', imageData);

                // Realiza la solicitud AJAX al archivo PHP
                $.ajax({
                    url: 'API/cambiarFoto.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                    // Maneja la respuesta del servidor
                    console.log(response);
                    },
                    error: function(xhr, status, error) {
                    // Maneja los errores de la solicitud AJAX
                    console.error(error);
                    }
                });

                //Cierra la ventana de seleccion de imagenes
                $("#contenedorRecorte").hide();
                $("#imgRecorte").attr("src","");

                //Actualiza las imagenes de la portada
                $("#imagen_nav").attr("src", imageData);
                $("#fotoPerfil").attr("src", imageData);
            });


            //Carga de modulos de la portada
            moduloPerfil();
            cargarSugerencias();

            

            //Control de los botones del menu desplegable
            $("#logo_nav").click(function(){
                $("#pantalla_perfil").hide(600);
                $("#pantalla_notificaciones").hide(600);
                $("#pantalla_tendencias").hide(600);
                $("#pantalla_configuracion").hide(600);
                $("#pantalla_mensajes").hide(600);
                $("#pantalla_principal").show(600);
                $("#menu_desplegable").hide(300);
            });
            $("#btnPerfil").click(function(){
                $("#pantalla_principal").hide(600);
                $("#pantalla_notificaciones").hide(600);
                $("#pantalla_tendencias").hide(600);
                $("#pantalla_configuracion").hide(600);
                $("#pantalla_mensajes").hide(600);
                $("#pantalla_perfil").show(600);
                $("#menu_desplegable").hide(300);
            });
            $("#btnConfiguracion").click(function(){
                $("#pantalla_principal").hide(600);
                $("#pantalla_notificaciones").hide(600);
                $("#pantalla_tendencias").hide(600);
                $("#pantalla_perfil").hide(600);
                $("#pantalla_mensajes").hide(600);
                $("#pantalla_configuracion").show(600);
                $("#menu_desplegable").hide(300);
            });
            
        });
    </script>
</head>
<body id="body_portada">
    <div id="bloque_spinner">
        <div id="spinner">

        </div>
    </div>

    <nav class="">
        <div id="logo_nav"><img src="imagenes/marcador-de-alfiler.png" alt=""><h1>Social Web</h1></div>
        <div id="buscador_nav"><input type="text" placeholder="Buscar personas..."></div>
        <div id="derecha_nav">
            <div id="mensaje_nav"><img src="imagenes/charla.png" alt=""></div>
            <div id="menu_nav">
                <button id="btnHamburguesa" class="hamburguesa-btn">
                    <span class="hamburguesa-line"></span>
                    <span class="hamburguesa-line"></span>
                    <span class="hamburguesa-line"></span>
                </button>
                <img id="imagen_nav" src="<?php echo $foto ?>" alt="">
            </div>
                <ul id="menu_desplegable" class="menu-desplegable hidden1">
                    <li class="btnMenu" id="btnPerfil"><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"><style>svg{fill:#A6ACAF}</style><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/></svg><a style="color:#A6ACAF" href="#">Perfil</a></li>
                    <li class="btnMenu"><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"><style>svg{fill:#A6ACAF}</style><path d="M224 0c-17.7 0-32 14.3-32 32V51.2C119 66 64 130.6 64 208v18.8c0 47-17.3 92.4-48.5 127.6l-7.4 8.3c-8.4 9.4-10.4 22.9-5.3 34.4S19.4 416 32 416H416c12.6 0 24-7.4 29.2-18.9s3.1-25-5.3-34.4l-7.4-8.3C401.3 319.2 384 273.9 384 226.8V208c0-77.4-55-142-128-156.8V32c0-17.7-14.3-32-32-32zm45.3 493.3c12-12 18.7-28.3 18.7-45.3H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7z"/></svg><a style="color:#A6ACAF" href="#">Notificaciones</a></li>
                    <li class="btnMenu"><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><style>svg{fill:#A6ACAF}</style><path d="M352 256c0 22.2-1.2 43.6-3.3 64H163.3c-2.2-20.4-3.3-41.8-3.3-64s1.2-43.6 3.3-64H348.7c2.2 20.4 3.3 41.8 3.3 64zm28.8-64H503.9c5.3 20.5 8.1 41.9 8.1 64s-2.8 43.5-8.1 64H380.8c2.1-20.6 3.2-42 3.2-64s-1.1-43.4-3.2-64zm112.6-32H376.7c-10-63.9-29.8-117.4-55.3-151.6c78.3 20.7 142 77.5 171.9 151.6zm-149.1 0H167.7c6.1-36.4 15.5-68.6 27-94.7c10.5-23.6 22.2-40.7 33.5-51.5C239.4 3.2 248.7 0 256 0s16.6 3.2 27.8 13.8c11.3 10.8 23 27.9 33.5 51.5c11.6 26 20.9 58.2 27 94.7zm-209 0H18.6C48.6 85.9 112.2 29.1 190.6 8.4C165.1 42.6 145.3 96.1 135.3 160zM8.1 192H131.2c-2.1 20.6-3.2 42-3.2 64s1.1 43.4 3.2 64H8.1C2.8 299.5 0 278.1 0 256s2.8-43.5 8.1-64zM194.7 446.6c-11.6-26-20.9-58.2-27-94.6H344.3c-6.1 36.4-15.5 68.6-27 94.6c-10.5 23.6-22.2 40.7-33.5 51.5C272.6 508.8 263.3 512 256 512s-16.6-3.2-27.8-13.8c-11.3-10.8-23-27.9-33.5-51.5zM135.3 352c10 63.9 29.8 117.4 55.3 151.6C112.2 482.9 48.6 426.1 18.6 352H135.3zm358.1 0c-30 74.1-93.6 130.9-171.9 151.6c25.5-34.2 45.2-87.7 55.3-151.6H493.4z"/></svg><a style="color:#A6ACAF" href="">Tendencias</a></li>
                    <li class="btnMenu" id="btnConfiguracion"><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><style>svg{fill:#A6ACAF}</style><path d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s.6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336a80 80 0 1 0 0-160 80 80 0 1 0 0 160z"/></svg><a style="color:#A6ACAF" href="#">Configuración</a></li>
                    <li id="btnCerrar" class="btnMenu"><svg xmlns="http://www.w3.org/2000/svg" height="0.875em" viewBox="0 0 512 512"><style>svg{fill:#A6ACAF}</style><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"/></svg><a style="color:#A6ACAF" href="#">Cerrar sesión</a></li>
                </ul>
        </div>
    </nav>

    <div id="bloque_principal">
        <div id="contenedorRecorte">
            <img id="imgRecorte" src="" alt="">
            <div id="btnRecorte">
                <button id="btnCancelarRecorte">Cancelar</button>
                <button id="btnAceptarRecorte">Aceptar</button>
            </div>
        </div>
        <div id="pantalla_principal"><!--Pantalla principal de la red social-------------------------------------------------------------->
            <div class="modulo">
                <div class="contenedor">
                    <!--Modulo del perfil de usuario-->
                    <div id="bloqueFoto">
                        <img id="fotoPerfil" style="max-width:25%;border-radius:50%;border:5px solid lightgrey;" src="" alt="">
                        <input type="file" id="inputFoto" style="display: none;" accept="image/*" /><!--Bloque oculto para seleccion de imagen nueva-->
                    </div>
                    <h1 id="nombrePerfil"></h1>
                    <div id="bloqueSegPerfil">
                        <div class="seguimientos" id="bloqueSeguidores"><h1 id="seguidores"></h1><h1 style="color: #A6ACAF;">Seguidores</h1></div>
                        <div class="seguimientos" id="bloqueSeguidos"><h1 id="seguidos"></h1><h1 style="color: #A6ACAF;">Seguidos</h1></div>
                        <div class="seguimientos" id="bloquePosts"><h1 id="posts"></h1><h1 style="color: #A6ACAF;">Posts</h1></div>
                    </div>
                </div>
            </div>
            <div class="modulo">
                <div class="contenedor">
                    <!--Modulo de creacion de un nuevo post-->
                    <form id="areaForm" action="">
                        <textarea name="" id="" cols="30" rows="10" placeholder="Comparte tus ideas..."></textarea>
                        <button id="btnForm">Postear</button>
                    </form>
                </div>
            </div>
            <div class="modulo2">
                <div class="contenedor2" id="bloqueSugerencias">
                    <!--Modulo con los posibles amigos, usa todo el ancho. El contenido se carga dinamicamente-->
                </div>
            </div>
            <div class="modulo">
                <div class="contenedor">
                    <!--Modulo con las nuevas actualizaciones de los amigos-->
                </div>
            </div>
        </div>
        <div id="pantalla_perfil"><!--Pantalla para ver el perfil del usuario------------------------------------------------------------>
            <h1>Esta es la pantalla del perfil del usuario</h1>
        </div>
        <div id="pantalla_visor_usuario"><!--Pantalla para ver el perfil del usuario------------------------------------------------------------>
            <h1>Esta es la pantalla que muestra los perfiles de otros usuarios</h1>
        </div>
        <div id="pantalla_notificaciones"><!--Pantalla para ver el las notificaciones del usuario------------------------------------------------------------>

        </div>
        <div id="pantalla_tendencias"><!--Pantalla para ver el las tendencias en la red social------------------------------------------------------------>

        </div>
        <div id="pantalla_configuracion"><!--Pantalla para configuracion de la cuenta------------------------------------------------------------>
            <h1>Esta es la pantalla de configuracion de la cuenta</h1>
        </div>
        <div id="pantalla_mensajes"><!--Pantalla para ver los mensajes privados------------------------------------------------------------>

        </div>
    </div>

    <div id="footer">

    </div>
</body>
</html>