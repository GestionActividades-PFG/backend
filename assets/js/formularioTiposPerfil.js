/**
 * Created by juan1 on 12/06/2017.
 */
$(document).ready(function(){
    $("#boton_enviar").click(function(){

        var correcto = 1;

        if($("#nombre_abreviado").val() == ""){
            correcto = 0;
            alert("Debes de rellenar el nombre abreviado");
        }
        else{
            if($("#nombre_abreviado").val().length > 4){
                correcto = 0;
                alert("El nombre abreviado debe de ser de 4 caracteres o menos");
            }
        }
        if($("#descripcion").val() == ""){
            correcto = 0;
            alert("Debes de rellenar la descripción del perfil");
        }

        if(correcto == 1){
            $("form").submit();
        }
    })

});