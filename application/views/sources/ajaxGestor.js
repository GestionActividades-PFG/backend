/**
 * Created by alexe on 09/03/2017.
 */

$(document).ready(function(){
   $("#etapas").on("click", function(e){
       e.preventDefault();
       $("#cuerpo").load("etapas.php");
   });

   $("#horas").on("click",function(e){
       e.preventDefault();
       $("#cuerpo").load("gestionHoras.php");
   });

    $("#aniadirEtapa").on("click", function(e){
        e.preventDefault();
        $("#aniadir").load("addEtapa.php");
    });

    $("#secciongestor").on("click", function(e){
        e.preventDefault();
        $("#cuerpo").load("seccionesgestor.php");
    });

    $("#aniadirSeccion").on("click", function(e){
        e.preventDefault();
        $("#aniadirsec").load("../consultas/aniadirSeccion.php");
    });

    $("#nuevoAlumno").on("click", function(e){
       e.preventDefault();
       $("#cuerpo").load("aniadirNuevoAlumno.php");
    });


    $("#modificar").on("click", function(e){
        e.preventDefault();
        var codEtapa = $("#modificar").val();
        $.ajax({
            type:"GET",
            url: '../paginas/alterEtapa.php?codEtapa='+codEtapa,
            success: function(data) {
                e.preventDefault();
                $('#aniadir').append(data);
                console.log(data);
            }
        });

    });

    $("#borrar").on("click", function(e){
        e.preventDefault();

        var codEtapa = $("#borrar").val();
        $.ajax({
            type:"GET",
            url: '../consultas/deleteEtapa.php?codEtapa='+codEtapa,
            success: function(data) {
                e.preventDefault();
                $('#aniadir').html(data);

            }
        });

    });

});