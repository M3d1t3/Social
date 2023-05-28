<?php
    //Realizar la conexion a la base de datos
    include("conectar.php");

    //Datos recibidos de Ajax
    $correo = $_POST['correo'];
    $pass = $_POST['pass'];

    $code = 0;

    //Consultar a la BD si el correo y la pass son validas
    $consulta = "select * from usuarios where email ='" . $correo . "' and pass ='" . $pass . "';";
    $resultado = $conn->query($consulta);
    while ($fila = mysqli_fetch_array($resultado)){
        //Si es valida generamos un resultado y un token
        $code = 1;
    }

    //Crear la sesion
    session_start();
    $_SESSION['correo'] = $correo;
    $_SESSION['pass'] = $pass;


    //Codificar el array en formato json
    $data = array(
        'codigo' => $code
    );
    echo json_encode($data);
?>