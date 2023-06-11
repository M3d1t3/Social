/*Funciones de carga de los modulos de la portada*/

function moduloPerfil(){
    /*Funcion para cargar el modulo del perfil de la portada*/
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
    let total = sugerencias.length;
    if(total > 18){
        total = 18;
    }
    for (let i = 0; i < total; i++) {//Ponemos un maximo de 18 elementos
        var sugerencia = sugerencias[i];
        
        // Crear el elemento de la sugerencia
        var sugerenciaDiv = $("<div>").addClass("sugerencia");

        // Crear la imagen de la foto
        if(sugerencia.foto == null){
            var fotoImg = $("<img>").attr("src", "imagenes/user.png").attr("alt", "Foto de perfil");
        }else{
            var fotoImg = $("<img>").attr("src", sugerencia.foto).attr("alt", "Foto de perfil");
        }
        sugerenciaDiv.append(fotoImg);

        // Crear el nombre y apellido
        var nombreApellido = $("<p>").text(sugerencia.nombre + " " + sugerencia.apellido);
        sugerenciaDiv.append(nombreApellido);

        // Crear el botón de seguir
        var seguirBtn = $("<button>").text("Seguir").attr("data-id", sugerencia.ID);
        sugerenciaDiv.append(seguirBtn);

        // Agregar el evento de clic al botón de seguir
        seguirBtn.on("click", function() {
            event.stopPropagation();//Evita que se ejecuta el clic del div padre
            var usuarioId = $(this).attr("data-id");
            seguirUsuario(usuarioId);
            $(this).closest(".sugerencia").hide(600);
        });

        //Agregar el evento de clic al div
        sugerenciaDiv.on("click", function() {
            // Obtener el ID de la sugerencia
            var sugerenciaId = $(this).find("button").attr("data-id");
            // Llamar a la función para mostrar el perfil
            verPerfil(sugerenciaId);
        });

        // Agregar la sugerencia al contenedor
        contenedorSugerencias.append(sugerenciaDiv);
    }

    //Una vez puestas las sugerencias, se pone el boton de ver más sugeencias
    sugerenciaDiv = $("<div>").addClass("sugerencia");
    seguirBtn = $("<button>").text("Ver más").attr("data-id", -1);
    sugerenciaDiv.append(seguirBtn);
    seguirBtn.on("click", function() {
        var usuarioId = $(this).attr("data-id");
        seguirUsuario(usuarioId);
    });
    contenedorSugerencias.append(sugerenciaDiv);
}

function seguirUsuario(usuarioId) {
    /*Hacer consulta de Ajax para seguir al usuario*/
    $.ajax({
        url: "API/seguirUsuario.php",
        type: "POST",
        data: { id: usuarioId },
        dataType: "json",
        success: function(response) {
          console.log(response);
          moduloPerfil();
        },
        error: function(xhr, status, error) {
          // Manejar los errores de la solicitud AJAX aquí
          console.log(xhr);
        }
    });
}

function verPerfil(usuarioID){
    /*Funcion que carga un perfil de usuario dentro del modulo
    pantalla_visor_usuarios y lo carga*/
    
    $.ajax({
        type: 'POST',
        url: './API/moduloPerfilAmigo.php',
        dataType: 'json',
        data:{usuarioID:usuarioID},
        success: function(data){
            if(data.clave==0){
                //La carga de datos ha sido buena
                if(data.foto == null){
                    $("#fotoPerfilAmigo").attr('src', 'imagenes/user.png');
                }else{
                    $("#fotoPerfilAmigo").attr('src', data.foto);
                }
                $("#nombrePerfilAmigo").text(data.nombre + " " + data.apellido);
                $("#seguidoresAmigo").text(data.seguidores);
                $("#seguidosAmigo").text(data.seguidos);
                $("#postsAmigo").text(data.posts);
                if(data.amigo == 0){
                    $("#btnSeguirPerfil").text("Seguir").attr("alt", "Foto de perfil");;
                }else{
                    $("#btnSeguirPerfil").text("Dejar de seguir");
                }
            }else{
                //La carga de datos ha sido mala
                alert("Carga de datos incorrecta, clave: " + data.clave);
            }
        },
        error: function(xhr, status, errorThrown){
            console.log("Estado: " + status);
            console.log("Error: " + errorThrown);
        }
    });
    


    $("#pantalla_principal").hide(600);
    $("#pantalla_notificaciones").hide(600);
    $("#pantalla_tendencias").hide(600);
    $("#pantalla_perfil").hide(600);
    $("#pantalla_mensajes").hide(600);
    $("#pantalla_configuracion").hide(600);
    $("#pantalla_visor_usuario").show(600);
}
