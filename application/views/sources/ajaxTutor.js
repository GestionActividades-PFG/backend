/**
 * Created by 2DAW03 on 07/03/2017.
 */

$(document).ready(function(){

    $('#incidenciasAlumnos').on("click", function(e){
        e.preventDefault();
        $('#cuerpo').load("buscarIncidencia.php");
    });

    $("#ultimasIncidencias").on("click", function(e){
        e.preventDefault();
        $('#cuerpo').load("ultimasIncidenciasTutor.php");
    });

    $('#crearanotaciones').on("click",function(e){
        e.preventDefault();
        $("#cuerpo").load("crearanotaciones.php");
    });

    $('#anotaciones').on("click",function(e){
        e.preventDefault();
        $("#cuerpo").load("anotaciones.php");
    });

    $('#misanotaciones').on("click",function(e){
        e.preventDefault();
        $("#cuerpo").load("misanotaciones.php");
    });

    $('#sancion').on("click", function(e){
        e.preventDefault();
        $("#cuerpo").load("formNewSancion.php");
    });

    $('#visualizarSancion').on("click", function(){
        e.preventDefault();
        $("#cuerpo").load("listSancionesMod.php");
    });

    $('#botonBuscar').on("click",function(e){
        e.preventDefault();

        var nombre = $("#alumno").val();
        //make the ajax call
        $.ajax({
            type:"GET",
            url: '../consultas/incidenciasAlumno.php?nombre='+nombre,
            success: function(data) {
                e.preventDefault();
                $('#formularioBuscar').html(data);
            }
        });
    });

    //Para cuando el usuario hace click en modificar una sancion
    $('body').on('click', '.btn-ver-sancion', function(evt){
        var _idSancion = $(this).data('id-sancion');

        //No id? no hace nada
        if(!_idSancion) {
            console.error('No hay id de sanción como para que pueda hacer algo');
            return;
        }

        $.ajax({
            'type': 'GET',
            'url': '../paginas/formModSancion.php?idSancion=' + _idSancion
        }).done(function(response){
            $('#cuerpo').html(response);
        }).fail(function(error){
            console.error('Error -> %o', error);
        });
    });

    //Para cuando el usuario hace click en ver una sancion
    $('body').on('click', '.btn-mostrar-sancion', function(evt){
        var _idSancion = $(this).data('id-sancion');

        //No id? no hace nada
        if(!_idSancion) {
            console.error('No hay id de sanción como para que pueda hacer algo');
            return;
        }

        $.ajax({
            'type': 'GET',
            'url': '../consultas/visualizarDatosSancion.php?idSancion=' + _idSancion
        }).done(function(response){
            $('#cuerpo').html(response);
        }).fail(function(error){
            console.error('Error -> %o', error);
        });
    });

    //Para cuando el usuario hace click en borrar una sancion
    $('body').on('click', '.btn-delete-sancion', function(evt){
        var _idSancion = $(this).data('id-sancion');

        //No id? no hace nada
        if(!_idSancion) {
            console.error('No hay id de sanción como para que pueda hacer algo');
            return;
        }

        var _tr = $(this).parents('tr');
        _tr.addClass('hide');

        $.ajax({
            'type': 'GET',
            'url': '../consultas/deleteSancion.php?idSancion=' + _idSancion
        }).done(function(){
            _tr.remove();
        }).fail(function(error){
            _tr.removeClass('hide');
            console.error('Error -> %o', error);
        });
    });

    $('#create_sanction').on("click",function(e){
        e.preventDefault();
        $("#cuerpo").load("formNewSancion.php");
    });

    $('#view_sanction').on("click",function(e){
        e.preventDefault();
        $("#cuerpo").load("listSancionesMod.php");
    });

});

