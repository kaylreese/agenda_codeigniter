$(document).ready(function () {
    lista();
});

function lista(){
    $("#titulo").text(" USUARIOS");
    $("#acciones").show();
    $.get(url+"usuarios/lista", function(data) {
        $("#contenido").empty().html(data);
        $('#datatable').DataTable({
            "fnInitComplete": function() {
                $(".dataTables_wrapper").find("select,input").addClass("form-control");
            }
        });
    });
    idform = 0;
}

function nuevo(){
    $("#titulo").text(" REGISTRAR USUARIO ");
    $("#acciones").hide();
    $.get(url+"usuarios/nuevo", function(data) {
        $("#contenido").empty().html(data);
    });
}

function cancelar(){
    lista();
}

function campo_requerido(id){
    $("#"+id).css("border-color","#ec7064");
    setTimeout(function(){
        $("#"+id).css("border-color","#ddd");
    }, 2000);
}

function guardar(){
    for (var i = 0; i < campos.length; i++) {
        if($("#"+campos[i]).val().trim()===""){
            $("#"+campos[i]).val(""); $("#"+campos[i]).focus(); 
            campo_requerido(campos[i]); 
            notificacion("error","Todos los campos son requeridos.");
            return false; break;
        }
    }

    Swal.fire({
        title: '¿Estás Seguro?',
        text: "Asegúrese de haber ingresado bien los datos!.",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, Guardar!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                data: $("#FormUsuario").serialize(),
                url: url+"usuarios/guardar",
                success: function(data){
                    if (data==1) {
                        alerta("success", "Guardado", "El Usuario ha sido creado Correctamente.");
                    }else if (data==2) {
                        alerta("success", "Guardado", "El usuarios ha sido modificado Correctamente.");
                    }else{
                        alerta("error", "Oops...", "Algo salió mal!");
                    }
                    lista(); return false;
                }
            }); return false;             
        }
    })     
}


function modificar(){
    if (idform==0) {
        notificacion("error","Debe Seleccionar un Registro para Modificar.");
    }else{
        $("#titulo").text("MODIFICAR USUARIO");
        $("#btn-nuevo").hide();
        $.get(url+"usuarios/nuevo",function(data){
            $.get(url+"usuarios/modificar/"+idform,function(data1){
                $("#contenido").empty().html(data);

                var info = eval(data1);
                for (var i = 0; i < datos.length; i++) {
                    $("#"+datos[i]).val(info[0][datos[i]]);
                }
            });
        });
    }
}


function eliminar(){
    if (idform==0) {
        notificacion("error","Debe Seleccionar un Registro para Eliminar.");
    }else{
        Swal.fire({
            title: '¿Estás Seguro?',
            text: "No podrás revertir esto!.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, Eliminar!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.get(url+"usuarios/eliminar/"+idform, function(data){
                    if (data==1) {
                        alerta("success", "Eliminado", "El Usuario  ha sido Eliminado.");
                    }else{
                        alerta("error", "Oops...", "Algo salió mal!");
                    }
                    lista();
                });                
            }
        }) 
    }    
}

function ver() {
    if (idform==0) {
        notificacion("error","Debe Seleccionar un Registro para Ver el Perfil.");
    }else{
        notificacion("warning","Funcionalidad no habilita por el desarrollador.");
        // $("#titulo").text(" PERFIL USUARIO ");
        // $("#acciones").hide();
        // $.get(url+"usuarios/perfil/"+idform, function(data) {
        //     $("#contenido").empty().html(data);
        // });
    }    
}


function imprimir () {
    notificacion("warning","Funcionalidad no habilita por el desarrollador.");
    // if (idform==0) {
    //     notificacion("error","Debe Seleccionar un Registro para Ver el Perfil.");
    // }else{
    //     notificacion("warning","Funcionalidad no habilita por el desarrollador.");
    // }    
}