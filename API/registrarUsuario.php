<?php
    //Realizar la conexion a la base de datos
    include("conectar.php");

    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['email'];
    $pass = $_POST['pass'];

    //Volver a comprobar que el correo no existe
    $respuesta = 0;

    $consulta = "select * from usuarios where email='" . $correo . "';";
    $resultado = $conn->query($consulta);
    while ($fila = mysqli_fetch_array($resultado)){
        $respuesta = 1;
    }

    if($respuesta==0){
        //El correo no existe, lo registramos
        $consulta = "insert into usuarios (nombre, apellido, pass, email, seguidores, seguidos, posts) values ('" . $nombre . "','" . $apellido . "','" . $pass . "','" . $correo . "',0,0,0);";
        $conn->query($consulta);
        //Iniciamos la sesion
        session_start();
        $_SESSION['correo'] = $correo;
        //Conseguir el ID;
        $consulta = "select * from usuarios where email = '" . $correo . "';";
        $resultado = $conn->query($consulta);
        while ($fila = mysqli_fetch_array($resultado)){
            $_SESSION['ID'] = $fila['ID'];
        }
        //Incluir el ID en la lista de registros para salir como sugerencia
        $consulta = "insert into registros (usuario) values ('" . $_SESSION['ID'] . "');";
        $conn->query($consulta);
        //Cerrar la conexion con la base de datos y redirigir
        $conn->close();
        header('Location: ../portada.php');
    }
    else{
        $conn->close();
        header('location: ../index.php');
    }

?>