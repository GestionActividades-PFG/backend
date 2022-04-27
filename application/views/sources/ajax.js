/**
 * Created by Rafa on 05/03/2017.
 */
$(document).ready(function() {


    $('#aulaconvivencia').on("click", function (e) {
        e.preventDefault();
        $("#cuerpo").load("aulaConvivenciaCoord.php");
    });
    $('#parteDisciplinario').on("click", function (e) {
        e.preventDefault();
        $("#cuerpo").load("parteDisciplinario.php");
    });
    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').focus()
    })
    $('#parteseducativos').on("click", function (e) {
        e.preventDefault();
        $("#cuerpo").load("partesEducativosCoord.php");
    });

    $('#crearanotaciones').on("click", function (e) {
        e.preventDefault();
        $("#cuerpo").load("crearanotaciones.php");
    });

    $('#anotaciones').on("click", function (e) {
        e.preventDefault();
        $("#cuerpo").load("anotaciones.php");
    });

    $('#anotacionesmostrar').on("click", function (e) {
        e.preventDefault();
        $("#cuerpo").load("anotacionesmostrar.php");
    });
    $('#consultaralumno').on("click", function (e) {
        e.preventDefault();
        $("#cuerpo").load("../paginas/buscarIncidenciasAlumno.php");
    });

    $('#misanotaciones').on("click", function (e) {
        e.preventDefault();
        $("#cuerpo").load("misanotaciones.php");
    });

    $('#misanotacionesmostrar').on("click", function (e) {
        e.preventDefault();
        $("#cuerpo").load("misanotacionesmostrar.php");
    });

    $('#eliminaranotaciones').on("click", function (e) {
        e.preventDefault();
        $("#cuerpo").load("eliminaranotaciones.php");
    });

    $('#modificaranotaciones').on("click", function (e) {
        e.preventDefault();
        $("#cuerpo").load("modificaranotaciones.php");
    });

    $('#formulario').on("click", function (e) {
        e.preventDefault();
        $("#cuerpo").load("crearanotaciones.php");
    });

    $('#desplegable1').change(function(e){
        e.preventDefault();
        var seccion = $('#desplegable1').find(":selected").text();
        $.ajax({
            type:"GET",
            url: '../consultas/recogealumno.php?seccion='+seccion,
            success: function(data) {
                e.preventDefault();
                $('#alumnos').empty();
                $('#alumnos').append(data);
                console.log(data);

            }
        });
    });

    $('#crearAnotacion').on("click", function (e) {
        e.preventDefault();
        //make the ajax call
        var profesores = $("#profesores").prop("checked");
        var params = {
             tipo : $('select[name=desplegabletipo]').val(),
             nia : $('select[name=desplegable2]').val(),
             observaciones : $('#observations').val(),
             profesores: profesores,

        };

        $.ajax({
            type: "GET",
            url: '../consultas/consultascrear.php',
            data: params,
            success: function (data) {
                e.preventDefault();
                console.log("Entra correctamente");
                $('#alerta').hide();
                $('#cuerpo').append(data);

            }
        });

    });

    $('#crearAnotacion2').on("click", function (e) {
        e.preventDefault();
        //make the ajax call
        var profesores = $("#profesores").prop("checked");
        var params = {
            tipo : $('select[name=desplegabletipo]').val(),
            nia : $('select[name=nia]').val(),
            observaciones : $('#observations').val(),
            profesores: profesores,

        };

        $.ajax({
            type: "GET",
            url: '../consultas/consultascrear.php',
            data: params,
            success: function (data) {
                e.preventDefault();
                console.log("Entra correctamente");
                $('#alerta').hide();
                $('#cuerpo').append(data);

            }
        });

    });

});

