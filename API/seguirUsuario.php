<?php
    // Realizar la conexión a la base de datos e iniciar sesión
    include("conectar.php");
    session_start();

    $id = $_POST['id'];
    $usuario = $_SESSION['ID'];

    //Añadir el seguimiento a la tabla seguimientos
    $consulta = "insert into seguimientos (seguidor,seguido) values ('" . $usuario . "','" . $id . "');";
    $conn->query($consulta);

    //Sumar un seguidor al usuario seguido 'id'
    $consulta = "select * from usuarios where ID = '" . $id . "';";
    $resultado = $conn->query($consulta);
    while ($fila = mysqli_fetch_array($resultado)){
        $total = $fila['seguidores'];
        $total++;
    }
    $consulta = "update usuarios set seguidores = '" . $total . "' where ID = '" . $id . "';";
    $conn->query($consulta);

    //Sumar un seguido al usuario seguidor 'usuario'
    $consulta = "select * from usuarios where ID = '" . $usuario . "';";
    $resultado = $conn->query($consulta);
    while ($fila = mysqli_fetch_array($resultado)){
        $total = $fila['seguidos'];
        $total++;
    }
    $consulta = "update usuarios set seguidos = '" . $total . "' where ID = '" . $usuario . "';";
    $conn->query($consulta);
?>