<?php
    //Realizar la conexion a la base de datos
    include("conectar.php");

    $data = array(
        'nombre' => "",
        'apellido' => "",
        'foto' => "",
        'seguidores' => 0,
        'seguidos' => 0,
        'posts' => 0,
        'clave' => 0,
        'amigo' => 0
    );

    //Comprueba si hay una sesion por seguridad
    session_start();
    if($_SESSION['correo']==""){
        //No hay una sesion iniciada asi que devolvemos una respuesta vacia
        session_destroy();
        $data['clave'] = 1;
        echo json_encode($data);
    }else{
        //Hay una sesion asi que podemos enviar los datos

        $usuarioID = $_POST['usuarioID'];

        $consulta = "select * from usuarios where ID ='" . $usuarioID . "';";
        $resultado = $conn->query($consulta);
        while ($fila = mysqli_fetch_array($resultado)){
            $data['nombre'] = $fila['nombre'];
            $data['apellido'] = $fila['apellido'];
            $data['foto'] = $fila['foto'];
            $data['seguidores'] = $fila['seguidores'];
            $data['seguidos'] = $fila['seguidos'];
            $data['posts'] = $fila['posts'];
        }

        //Comprobar si ya está seguido
        $consulta = "select * from seguimientos where seguidor = '" . $_SESSION['ID'] . "';";
        $resultado = $conn->query($consulta);
        while ($fila = mysqli_fetch_array($resultado)){
            if($fila['seguido']==$usuarioID){
                $data['amigo'] = 1;
            }
        }

        $conn->close();
        echo json_encode($data);
    }
?>