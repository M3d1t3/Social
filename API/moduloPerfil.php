<?php
    //Conexion a la base de datos
    include("conectar.php");

    session_start();

    $email = $_SESSION['correo'];
    $data = array(
        'nombre' => "",
        'apellido' => "",
        'foto' => "",
        'seguidores' => 0,
        'seguidos' => 0,
        'posts' => 0
    );

    //Recopilar datos de la base de datos
    if($email != ""){
        $consulta = "select * from usuarios where email ='" . $email . "';";
        $resultado = $conn->query($consulta);
        while ($fila = mysqli_fetch_array($resultado)){
            $data['nombre'] = $fila['nombre'];
            $data['apellido'] = $fila['apellido'];
            $data['foto'] = $fila['foto'];
            $data['seguidores'] = $fila['seguidores'];
            $data['seguidos'] = $fila['seguidos'];
            $data['posts'] = $fila['posts'];
        }
    }
    
    $conn->close();
    echo json_encode($data);
?>