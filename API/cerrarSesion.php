<?php
    //Cerrar la sesion del usuario
    session_start();
    $_SESSION = array();
    session_destroy();

    //Lanzar a la pagina de inicio
    header('Location: ../index.php');
    exit;
?>