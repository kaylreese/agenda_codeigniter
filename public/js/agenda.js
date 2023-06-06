function validarRealizado() {
    if($('#realizado').prop('checked')) {
        $("#campodocumento").show();
        $("#estado").val(1);
    } else {
        $("#campodocumento").hide();
        $("#estado").val("");
    }
}

function tipoagenda(codtipoagenda){
    if(codtipoagenda==1){
        $("#divusuario").show();
        campos = ["codusuario","codtipoagenda","title","start","end"];
    } else {
        $("#divusuario").hide();
        campos = ["codtipoagenda","title","start","end"];
    }
}

function agendaCliente(codcliente) {
    $("#codcliente").val(codcliente);

    var calendarEl = document.getElementById('calendar');
    let formulario = document.getElementById('FormAgenda');
    let myModal = new bootstrap.Modal(document.getElementById('myModal'));

    $.post(url+'agenda/agendaCliente/'+codcliente, function(data) {
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },
            initialDate: new Date(),
            navLinks: true, // can click day/week names to navigate views
            selectable: true,
            // businessHours: true,
            // selectMirror: true,
            editable: true,
            dayMaxEvents: true, // allow "more" link when too many events
            droppable: true,
            events: $.parseJSON(data),
            select: function(info) {
                formulario.reset();
                $('#titulo').text('Registrar Evento');
                $('#codtipoagenda').removeAttr('selected');
                $("#divrealizado").show();
                $("#campodocumento").hide();
                $("#descargardocumento").hide();
                $("#btnguardar").show();
                $("#btneliminar").hide();
                $("#start").val(info.startStr+" 00:00:00");
                $("#end").val(info.endStr+" 00:00:00");
                myModal.show();
            },
            eventClick: function(info) {
                $('#titulo').text('Actualizar Evento');
                $("#campodocumento").hide();
                $("#descargardocumento").hide();
                $("#divrealizado").show();
                $("#btnguardar").show();
                $("#btneliminar").show();
                
                $.get(url+"agenda/modificar/"+info.event.id,function(data){
                    let evento = eval(data);
                    if(evento[0]["estado"]==2) {
                        $('#titulo').text('Detalle Evento');
                        $("#divrealizado").hide();
                        $("#descargardocumento").show();
                        $('#documento').attr('href', url+'public/documentos/'+evento[0]["urldocumento"]);
                        $("#btnguardar").hide();
                        $("#btneliminar").hide();
                    }
                    if(evento[0]["codtipoagenda"]==2) {
                        $("#divrealizado").hide();
                    }
                    $("#codagenda").val(evento[0]["codagenda"]);
                    $("#codcliente").val(evento[0]["codcliente"]);
                    $("#codusuario option").eq(evento[0]["codusuario"]).prop("selected",true);
                    $("#codtipoagenda option").eq(evento[0]["codtipoagenda"]).prop("selected",true);
                    $("#title").val(evento[0]["titulo"]);
                    $("#start").val(evento[0]["fechainicio"]);
                    
                    if (evento[0]["fechafin"] == "1969-12-31 19:00:00") {
                        $("#end").val("");
                    } else {
                        $("#end").val(evento[0]["fechafin"]);
                    }
                    
                    $("#color").val(evento[0]["color"]);
                    myModal.show();
                }); 
            },
            eventDrop: function(info) {
                if(info.event.extendedProps.estado == 2) {
                    Swal.fire({
                        title: 'Evento Finalizado!!',
                        text: "Este evento ya no puede ser modificado.",
                        icon: 'warning',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    })
                } else {
                    let id = info.event.id;
                    let title = info.event.title;

                    let start = info.event.startStr;
                    let end = info.event.endStr;

                    Swal.fire({
                        title: '¿Estás Seguro?',
                        text: "Asegúrese de haber seleccionado bien las fechas!.",
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, Guardar!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: url+"agenda/update",
                                type:"POST",
                                data: {'codagenda': id, 'fechainicio': start, 'fechafin': end},
                                success:function(data){
                                    if (data==1) {
                                        alerta("success", "Modificado", "El Evento ha sido Modificado.");
                                    } else {
                                        alerta("error", "Oops...", "Algo salió mal!");
                                    }
                                    return false;
                                }
                            }); return false;  
                        } else {
                            location.reload();
                        }
                    })
                }
            }
        });

        calendar.render();
    });
}


function campo_requerido(id){
    $("#"+id).css("border-color","#ec7064");
    setTimeout(function(){
        $("#"+id).css("border-color","#ddd");
    }, 4000);
}

function guardar(){
    for (var i = 0; i < campos.length; i++) {
        if($("#"+campos[i]).val().trim()===""){
            $("#"+campos[i]).val(""); $("#"+campos[i]).focus(); 
            campo_requerido(campos[i]);
            if(i==0) {
                notificacion("error","Seleccionar un Tipo de Evento: Tarea o Feriado.");
            }
            if(i==1) {
                notificacion("error","Ingresar un Titulo para el Evento.");
            }
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
                cache: false,
                processData: false,
                contentType: false,
                mimeType:"multipart/form-data",
                data: new FormData($("#FormAgenda")[0]),
                url: url+"agenda/guardar",
                success: function(data){
                    console.log(data);
                    if (data==1) {
                        alerta("success", "Guardado", "El Evento ha sido Guardado y notificado Correctamente.");
                    }else if(data==2){
                        alerta("success", "Modificado", "El Evento ha sido Modificado y notificado Correctamente.");
                    }else{
                        alerta("error", "Oops...", "Algo salió mal!");
                    }
                    location.reload();
                    return false;
                }
            }); return false;             
        }
    })          
}

function eliminar(){
    let codevento = $("#codagenda").val()
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
            $.get(url+"agenda/eliminar/"+codevento, function(data){
                if (data==1) {
                    alerta("success", "Eliminado", "El Evento ha sido Eliminado.");
                }else{
                    alerta("error", "Oops...", "Algo salió mal!");
                }
                location.reload();
            });                
        }
    })     
}

function guardar_documento(){
    if($('#realizado').prop('checked')) {
        if($("#urldocumento").val().trim()===""){
            $("#urldocumento").val(""); $("#urldocumento").focus(); 
            campo_requerido("urldocumento");
            notificacion("error","Adjuntar un documento de referencia.");  
            return false;
        }

        Swal.fire({
            title: '¿Estás Seguro?',
            text: "Asegúrese de haber ingresado bien el archivo!.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, Guardar!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    cache: false,
                    processData: false,
                    contentType: false,
                    mimeType:"multipart/form-data",
                    data: new FormData($("#FormAgendaCliente")[0]),
                    url: url+"cliente/agenda/guardar_documento",
                    success: function(data){
                        console.log(data);
                        if (data==1) {
                            alerta("success", "Guardado", "El Evento ha sido Guardado y notificado Correctamente.");
                        }else{
                            alerta("error", "Oops...", "Algo salió mal!");
                        }
                        location.reload();
                        return false;
                    }
                }); return false;             
            }
        })
    }else {
        notificacion("error","Marcar Check como realizado para adjuntar documento.");     
    }      
}

