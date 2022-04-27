/**
 * Created by juan1 on 11/06/2017.
 */
$(document).ready(function(){

    $("#radio_grupo").click(function(){
        $("#alumnos_o_seccion").load("cargarDatoAlumnos");

    })
    $("#radio_individual").click(function(){
        $("#alumnos_o_seccion").html("");

    })

    if($("#radio_grupo").is(":checked")){
        $("#alumnos_o_seccion").load("cargarDatoAlumnos");
    }

    $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });

    $("#boton_enviar").click(function(){

        var correcto = 1;

        if($("#campo_nombre").val() == ""){
            alert("El campo del nombre de la actividad está vacío.");
            correcto = 0;
        }

        if(!$(".campo_concurso").is(":checked")) {
            alert('Debes de marcar si la actividad es un concurso o no.');
            correcto = 0;
        }

        if($("#fecha_inicio").val() == "" && $("#fecha_fin").val() == ""){
            alert("Debes de marcar cual es la fecha de inicio y de fin de las inscripciones a esta actividad.");
            correcto = 0;
        }else{
            var fecha = new RegExp("^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$");
            if(!fecha.test($("#fecha_inicio").val()) && !fecha.test($("#fecha_fin").val())){
                alert("El formato de las fechas es incorrecto.");
                correcto = 0;
            }
            else{
                var fechaInicio = $('#fecha_inicio').val().replace('-','/');
                var fechaFin = $('#fecha_fin').val().replace('-','/');
                if(fechaInicio > fechaFin){
                    alert("Las fecha de inicio es mayor que la fecha maxima.");
                    correcto = 0;
                }
            }
        }

        if($("#max_clase").val() == ""){
            alert("Debes de indicar el número máximo de participantes por clase en esta actividad.");
            correcto = 0;
        }else{
            if(!$.isNumeric($("#max_clase").val())){
                alert("El número maximo de participantes por clase debe de ser un número.");
                correcto = 0;
            }
        }


        if(!$(".grupo_tipo_act").is(":checked")) {

            alert('Debes de marcar si la actividad es individual o de grupo.');
            correcto = 0;
        }


        if(correcto == 1){
            $("form").submit();
        }
    });
});

