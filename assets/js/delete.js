window.addEventListener("DOMContentLoaded",function(){
    const deleteCodes = document.getElementById("delete-db");
    deleteCodes.addEventListener("click",function(){
        Swal.fire({
            title: 'Desea eliminar todos los codigos?',
            text: "Se eliminaran los codigos",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!',
            cancelButtonText: 'Cancelar!'
          }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "./../controllers/CodesController.php?action=truncate";
            }
          })
    });
});