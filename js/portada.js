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
            console.log(sugerencias);
            moduloSugerencias();
        },
        error: function(xhr, status, error) {
          // Manejar los errores de la solicitud AJAX aquí
          console.error(error);
        }
    });
}



/*Cargar el modulo sugerencias a partir de la matriz sugerencias, coge los ultimos 15 registros y crea un div para cada uno de ellos. 
Al final crea un boton para ir a más sugerencias*/
function moduloSugerencias(){
    let contenedorSugerencias = $("#bloqueSugerencias");

    for (let i = 0; i < sugerencias.length; i++) {
        var sugerencia = sugerencias[i];
        
        // Crear el elemento de la sugerencia
        var sugerenciaDiv = $("<div>").addClass("sugerencia");

        // Crear la imagen de la foto
        var fotoImg = $("<img>").attr("src", sugerencia.foto).attr("alt", "Foto de perfil");
        sugerenciaDiv.append(fotoImg);

        // Crear el nombre y apellido
        var nombreApellido = $("<p>").text(sugerencia.nombre + " " + sugerencia.apellido);
        sugerenciaDiv.append(nombreApellido);

        // Crear el botón de seguir
        var seguirBtn = $("<button>").text("Seguir").attr("data-id", sugerencia.ID);
        sugerenciaDiv.append(seguirBtn);

        // Agregar el evento de clic al botón de seguir
        seguirBtn.on("click", function() {
            var usuarioId = $(this).attr("data-id");
            seguirUsuario(usuarioId);
        });

        // Agregar la sugerencia al contenedor
        contenedorSugerencias.append(sugerenciaDiv);
    }
}

function seguirUsuario(usuarioId) {
    // Aquí puedes implementar la lógica para seguir al usuario con el ID proporcionado
    // por ejemplo, puedes realizar una solicitud AJAX al servidor para enviar la solicitud de seguimiento
    console.log("Siguiendo al usuario con ID:", usuarioId);
}
