<?php
    // Realizar la conexión a la base de datos e iniciar sesión
    include("conectar.php");
    session_start();

    $id = $_POST['id'];
    $usuario = $_SESSION['ID'];

    $consulta = "insert into seguimientos (seguidor,seguido) values ('" . $usuario . "','" . $id . "');";
    $conn->query($consulta);
?>