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
        include("api/conectar.php");
        $consulta = "select * from usuarios where email = '" . $_SESSION['correo'] . "';";
        $resultado = $conn->query($consulta);
        while ($fila = mysqli_fetch_array($resultado)){
            $nombre = $fila['nombre'];
            $apellido = $fila['apellido'];
            $foto = $fila['foto'];
        }
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
            $("#btnCerrar").click(function(){
                window.location.href = 'api/cerrarSesion.php';
            });
            $("#menu_nav").click(function(){
                $("#menu_desplegable").toggle(300);
            });
            
        });
    </script>
</head>
<body id="body_portada">
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
                    <li><a href="#">Opción 1</a></li>
                    <li><a href="#">Opción 2</a></li>
                    <li><a href="#">Opción 3</a></li>
                    <li id="btnCerrar"><a href="#">Cerrar sesión</a></li>
                </ul>
        </div>
    </nav>

    <div id="bloque_principal">
        <div id="pantalla_principal"><!--Pantalla principal de la red social-------------------------------------------------------------->
            <div class="modulo">
                <div class="contenedor">
                    <!--Modulo del perfil de usuario-->
                </div>
            </div>
            <div class="modulo">
                <div class="contenedor">
                    <!--Modulo de creacion de un nuevo post-->
                </div>
            </div>
            <div class="modulo">
                <div class="contenedor">
                    <!--Modulo con los posibles amigos, usa todo el ancho-->
                </div>
            </div>
            <div class="modulo">
                <div class="contenedor">
                    <!--Modulo con las nuevas actualizaciones de los amigos-->
                </div>
            </div>
        </div>
        <div id="pantalla_perfil"><!--Pantalla para configurar el perfil del usuario------------------------------------------------------------>

        </div>
    </div>

    <div id="footer">

    </div>
</body>
</html>