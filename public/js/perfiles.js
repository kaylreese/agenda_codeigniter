$(document).ready(function () {
    lista();
});

function lista(){
    $("#titulo").text(" PERFILES");
    $("#acciones").show();
    $.get(url+"perfiles/lista", function(data) {
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
    $("#titulo").text(" REGISTRAR PERFIL ");
    $("#acciones").hide();
    $.get(url+"perfiles/nuevo", function(data) {
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
                data: $("#FormPerfil").serialize(),
                url: url+"perfiles/guardar",
                success: function(data){
                    console.log(data);
                    if (data==1) {
                        alerta("success", "Guardado", "El Perfil ha sido creado Correctamente.");
                    }else if (data==2) {
                        alerta("success", "Guardado", "El Perfil ha sido modificado Correctamente.");
                    } else{
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
        $("#titulo").text("MODIFICAR PERFIL");
        $("#acciones").hide();
        $.get(url+"perfiles/nuevo",function(data){
            $.get(url+"perfiles/modificar/"+idform,function(data1){
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
                $.get(url+"perfiles/eliminar/"+idform, function(data){
                    if (data==1) {
                        alerta("success", "Eliminado", "El Perfil  ha sido Eliminado.");
                    }else{
                        alerta("error", "Oops...", "Algo salió mal!");
                    }
                    lista();
                });                
            }
        })
    }     
}

var nombreperfil = "";
function seleccionPerfil(codperfil,perfil){
    idform = codperfil;
    nombreperfil = perfil;
}


function verpermisos(){
    if (idform==0) {
        notificacion("error","Debe Seleccionar un Registro para Ver sus Permisos.");
    }else{
        $("#titulo").text("GESTIÓN PERMISOS PARA "+nombreperfil.toUpperCase());
        $("#acciones").hide();
        $.get(url+"perfiles/verpermisos/"+idform, function(data) {
            $("#contenido").empty().html(data);
            $("#codperfil").val(idform); 
        });
    }
}


function guardarPermisos(){
    $(".btn-guardar").attr("disabled","true");
    Swal.fire({
        title: '¿Estás Seguro?',
        text: "Se asignarán nuevos permisos a este Perfil!.",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, Guardar!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                data: $("#FormPermisos").serialize(),
                url: url+"perfiles/guardarPermisos",
                success: function(data){
                    if (data==1) {
                        alerta("success", "Guardado", "Los Permisos han sido creados Correctamente.");
                    }else{
                        alerta("error", "Oops...", "Algo salió mal!");
                    }
                    lista(); return false;
                }
            }); return false;
        }
    });
}