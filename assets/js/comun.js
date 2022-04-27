/**
 * Created by 2daw01 on 03/03/2017.
 */


$(document).ready(function(){
    
    $('#initial-date').on("change",function(){

        var initialDt = this.value;

        $('#end-date').val(initialDt);

    });

    $('#end-date').on("change",function(){

        var initialDt = document.getElementById("initial-date").value;
        var endDt = this.value;

        if((new Date(initialDt).getTime() > new Date(endDt).getTime()) || this.value == ''){

            this.value = initialDt;

        }

    });
/*
    $('#formAddSancion').on("submit",function(){

        var initialDt = document.forms['formAddSancion']['initial-date'].value;
        if(initialDt == '')
            return false;
        
        var description = document.forms['formAddSancion']['observations'].value;
        if(description == '')
            return false;
        
    });
*/
});