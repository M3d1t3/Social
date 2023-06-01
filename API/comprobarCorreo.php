<?php
    //Realizar la conexion a la base de datos
    include("conectar.php");

    //Datos recibidos de Ajax
    $correo = $_POST['correo'];

    $respuesta = 0;

    $consulta = "select * from usuarios where email='" . $correo . "';";
    $resultado = $conn->query($consulta);
    while ($fila = mysqli_fetch_array($resultado)){
        $respuesta = 1;
    }
    
    //Codificar el array en formato json
    $data = array(
        'codigo' => $respuesta
    );
    echo json_encode($data);
?>