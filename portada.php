<?php

    session_start();
    //Comprobar que la sesion es correcta

    // Verificar si hay una sesión iniciada
    if (!isset($_SESSION['correo'])) {
        // Sesión iniciada, redirigir al usuario a otra página
        header('Location: index.php');
        exit();
    }

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
<body>
    
    <button id="btnCerrar">Cerrar Sesión</button>
</body>
</html>