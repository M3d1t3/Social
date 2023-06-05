<?php
    //Realizar la conexion a la base de datos
    include("conectar.php");
    session_start();
    $usuario = $_SESSION['ID'];

    //Hacer un listado de los amigos del usuario
    $amigos = array();
    $i = 0;
    $consulta = "select * from seguimientos where seguidor='" . $usuario . "';";
    $resultado = $conn->query($consulta);
    while ($fila = mysqli_fetch_array($resultado)){
        //Listado de amigos del usuario
        $amigos[$i] = $fila['seguido'];
        $i++;
    }

    //Añadir el usuario como amigo para impedir que sea una sugerencia
    $amigos[$i] = $usuario;
    $i++;

    //Buscar todos los ultimos registros
    $candidatos = array();
    $i = 0;
    $consulta = "select * from registros;";
    $resultado = $conn->query($consulta);
    while ($fila = mysqli_fetch_array($resultado)){
        //Comprobar que el id es diferente del usuario que pide la consulta
        if($fila['usuario'] != $usuario){
            //Si es distinto, comprobamos que no sea un amigo ni el mismo usuario
            if(!in_array($fila['usuario'],$amigos)){
                //Es un buen candidato
                $candidatos[$i] = $fila['usuario'];
                $i++;
            }
        }
    }

    //Almacenar todos los datos de los candidatos
    $datosCandidatos = array();
    $i = 0;
    $consulta = "select * from usuarios;";
    $resultado = $conn->query($consulta);
    while ($fila = mysqli_fetch_array($resultado)){
        if(in_array($fila['ID'],$candidatos)){
            //cargamos los datos
            $datosCandidatos[$i] = array(
                'ID' => $fila['ID'],
                'nombre' => $fila['nombre'],
                'apellido' => $fila['apellido'],
                'foto' => $fila['foto']
            );
            $i++;
        }
    }

    //devolver los datos en formato json
    echo json_encode($datosCandidatos);
?>