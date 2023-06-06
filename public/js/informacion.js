function guardar(){
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
                data: $("#FormInformacion").serialize(),
                url: url+"home/guardar",
                success: function(data){
                    if (data==1) {
                        alerta("success", "Modificado", "Datos actualizados correctamente.");
                    }else{
                        alerta("error", "Oops...", "Algo salió mal!");
                    }
                    return false;
                }
            }); return false;             
        }
    })           
}