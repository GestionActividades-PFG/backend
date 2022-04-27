/**
 * Created by Rafa on 05/03/2017.
 */
$(document).ready(function(){
    $('#tipoInc').on("click",function(e){
        e.preventDefault();
        $("#cuerpo").load("addTipoInc.php");
    });

    $('#tipoAnotaciones').on("click",function(e){
        e.preventDefault();
        $("#cuerpo").load("addTipoAnotacion.php");
    });

    $('#tipoSancion').on("click",function(e){
        e.preventDefault();
        $("#cuerpo").load("addTipoSancion.php");
    });
    $("#ultimasIncidencias").on("click", function(e){
        e.preventDefault();
        $('#cuerpo').load("ultimasIncidenciasTutor.php");
    });
    $('#tipoSancionInc').on("click",function(e){
        e.preventDefault();
        $("#cuerpo").load("addTipoSancionIncidencia.php");
    })

    $('#otras').on("click",function(e){
        e.preventDefault();
        $("#cuerpo").load("otrasIncidenciasCoor.php");
    });

    $('#sanc_ult_hora').on("click",function(e){
        e.preventDefault();
        $("#cuerpo").load("listSanccionesUltimaHora.php");
    });

    $('#sanc_aula_convivencia').on("click",function(e){
        e.preventDefault();
        $("#cuerpo").load("listSanccionesAulaConv.php");
    });

    $('#create_sanction').on("click",function(e){
        e.preventDefault();
        $("#cuerpo").load("formNewSancionCoor.php");
    });

    $('#view_sanction').on("click",function(e){
        e.preventDefault();
        $("#cuerpo").load("listSancionesMod.php");
    });

    $('#acum_partes_edu').on("click",function(e){
        e.preventDefault();
        $("#cuerpo").load("partesEducativosCoord.php");
    });
    $('#botonBuscar').on("click",function(e){
        e.preventDefault();

        var nombre = $('#alumnos').find(":selected").text();
        //make the ajax call
        $.ajax({
            type:"GET",
            url: '../consultas/incidenciasAlumno.php?nombre='+nombre,
            success: function(data) {
                e.preventDefault();
                $('#cuerpo').append('<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>');
                $('#cuerpo').append('<script type="text/javascript" src="../sources/ajaxCoordinador.js"></script>');
                $('#cuerpo').html(data);

                $('#cuerpo').append('<button id="volverBuscarAlumnos">Volver</button>');

            }
        });

    });
    $('#secciones').on("change",function(e){
        e.preventDefault();
        var seccion = $('#secciones').find(":selected").text();
        $.ajax({
            type:"GET",
            url: '../consultas/buscarAlumnoCoord2.php?idSeccion='+seccion,
            success: function(data) {
                e.preventDefault();
                $('#divAlumnos').empty();
                $('#divAlumnos').append(data);
                $('#botonBuscar').attr('type', 'submit');

            }
        });

    });

    $('#volverBuscarAlumnos').on("click",function(e){
        e.preventDefault();
        $("#cuerpo").load("../paginas/buscarIncidenciasAlumno.php");
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

    $('#extractSection').on("change",function(e){
        e.preventDefault();
        var seccion = $('#extractSection').find(":selected").val();
        console.log(seccion);
        $.ajax({
            type:"POST",
            url: '../consultas/extraerAlumnos.php',
            data: {"idSeccion":seccion},
            success: function(data) {
                console.log('entra');
                e.preventDefault();
                $('#extractAlumnosSecc').empty();
                $('#extractAlumnosSecc').append(data);
                //$('#botonBuscar').attr('type', 'submit');

            }
        });

    });

});















