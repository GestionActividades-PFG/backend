/**
 * Created by juan1 on 12/06/2017.
 */
$(document).ready(function(){
    $("#boton-crear").click(function(){

        var correcto = 1;

        if($("#label_letra").val() == ""){
            correcto = 0;
            alert("Tienes que meter una letra de categoria");
        }
        else{
            if($("#label_letra").val().length > 1){
                correcto = 0;
                alert("Solo puedes meter una letra");
            }
        }

        if($("#label_nombre").val() == ""){
            correcto = 0;
            alert("Tienes que escribir el nombre de la categoria");
        }


        if(correcto == 1){
            $("form").submit();
        }
    });

})