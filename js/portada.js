/*Funciones de carga de los modulos de la portada*/

function moduloPerfil(){
    /*Funcion para cargar el modulo del perfil de la portada*/
    var datos;
    $.ajax({
        type: 'POST',
        url: './API/moduloPerfil.php',
        dataType: 'json',
        success: function(data){
            if(data.nombre!=""){
                //La carga de datos ha sido buena
                if(data.foto == null){
                    $("#fotoPerfil").attr('src', 'imagenes/user.png');
                }else{
                    $("#fotoPerfil").attr('src', data.foto);
                }
                $("#nombrePerfil").text(data.nombre + " " + data.apellido);
                $("#seguidores").text(data.seguidores);
                $("#seguidos").text(data.seguidos);
                $("#posts").text(data.posts);
                
            }else{
                //La carga de datos ha sido mala
                alert("Carga de datos incorrecta");
            }
        },
        error: function(xhr, status, errorThrown){
            console.log("Estado: " + status);
            console.log("Error: " + errorThrown);
        }
    });

    
}


//Cargar dentro de la matriz sugerencias todas las sugerencias posibles en base a los ultimos registros
let sugerencias;

function cargarSugerencias(){
    $.ajax({
        url: './API/sugerencias.php',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            sugerencias = response;
        },
        error: function(xhr, status, error) {
          // Manejar los errores de la solicitud AJAX aquí
          console.error(error);
        }
    });

      
}