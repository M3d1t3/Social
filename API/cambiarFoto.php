<?php
// Realizar la conexión a la base de datos e iniciar sesión
include("conectar.php");
session_start();

// Verificar si se ha enviado una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Verificar si se ha recibido la imagen recortada
  if (isset($_POST['imagen'])) {
    // Obtener la imagen recortada en base64
    $imagenRecortadaBase64 = $_POST['imagen'];

    // Decodificar la imagen base64 y guardarla en el servidor
    $directorio = '../imagenes/';
    $nombreArchivo = $_SESSION['ID'] . date("YmdHis") . '.jpg'; // Puedes ajustar el nombre del archivo según tus necesidades
    $rutaArchivo = $directorio . $nombreArchivo;

    // Elimina el encabezado de datos de la imagen codificada en base64
    $imagenRecortadaBase64 = str_replace('data:image/jpeg;base64,', '', $imagenRecortadaBase64);
    $imagenRecortadaBase64 = str_replace(' ', '+', $imagenRecortadaBase64);

    // Decodifica y guarda la imagen en el servidor
    file_put_contents($rutaArchivo, base64_decode($imagenRecortadaBase64));

    // Aquí puedes realizar otras operaciones con la imagen guardada

    // Devolver una respuesta al cliente (opcional)
    $response = array('success' => true, 'message' => 'Imagen recortada guardada con éxito');
    echo json_encode($response);
  } else {
    // No se ha recibido la imagen recortada
    $response = array('success' => false, 'message' => 'No se ha recibido la imagen recortada');
    echo json_encode($response);
  }
} else {
  // No se ha enviado una solicitud POST
  $response = array('success' => false, 'message' => 'No se ha recibido una solicitud válida');
  echo json_encode($response);
}
?>


