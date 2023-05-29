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

            
        });
    </script>
</head>
<body id="body_portada">
    <nav class="">
        <div id="logo_nav"><img src="imagenes/marcador-de-alfiler.png" alt=""><h1>Social Web</h1></div>
        <div id="buscador_nav"><input type="text" placeholder="Buscar personas..."></div>
        <div id="menu_nav">
            <button id="btnHamburguesa" class="hamburguesa-btn">
                <span class="hamburguesa-line"></span>
                <span class="hamburguesa-line"></span>
                <span class="hamburguesa-line"></span>
            </button>
            <img id="imagen_nav" src="<?php echo $foto ?>" alt="">
        </div>
    </nav>
    <button id="btnCerrar">Cerrar Sesión</button>
</body>
</html>