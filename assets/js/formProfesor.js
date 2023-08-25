const getUrl = (path) => window.location.protocol + "//" +window.location.host +  path;



const modalForm  = new bootstrap.Modal(document.getElementById('modalProfesor'))

let operacion = "AGREGAR";

const btnAgregar = document.querySelector('#btnAgregar');
btnAgregar.addEventListener("click",function(){
    document.querySelector("#senati-id").disabled = false;
    operacion = "AGREGAR";
    modalForm.show();
})

const buttonsEditar = document.querySelectorAll('.btnEditar');

buttonsEditar.forEach( element => {
    element.addEventListener("click",function(event){
        operacion = "EDITAR";
        const [senatiId, profesor,id] = event.target.parentElement.parentElement.children;
        document.querySelector("#nombres").value = profesor.textContent
        document.querySelector("#senati-id").value = senatiId.textContent
        document.querySelector("#id-profesor").value = id.textContent;
        document.querySelector("#senati-id").disabled = true;

        modalForm.show();
    })
})


function peticionProfesor(formData,metodo){
    const url = getUrl('/controllers/ProfesoresController.php');
    formData.append("action",metodo)
    fetch(url,{
        method : "POST",
        body : formData,
    }).then(resp => resp.json())
    .then(json => {
        Swal.fire({
            icon: json.status?"success":"error",
            title: 'Informacion',
            text: json.message,
        })
        if(json.status){
            setTimeout(() => {
                location.reload();
            },3000)
        }
    })
}

const btnGuardar = document.querySelector('#guardar');
btnGuardar.addEventListener("click",function(){
    const form = document.getElementById("form-profesor");
    const {nombres,senatiId} = form;
    if(!senatiId.value){
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: "Favor de ingresar Senati ID",
        })
        return;
    }

    if(!nombres.value){
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: "Favor de ingresar nombres",
        })
        return;
    }
    
    const formData = new FormData(form);
    if(operacion === "AGREGAR"){
        return peticionProfesor(formData,"guardarProfesor")
    }
    peticionProfesor(formData,"editarProfesor");
})


const btnCancelar = document.querySelector('#btnCancelar');
btnCancelar.addEventListener("click",function(){
    const form = document.getElementById("form-profesor");
    form.reset();
    modalForm.hide();
})



const buttonsEliminar = document.querySelectorAll('.btnEliminar');

buttonsEliminar.forEach( element => {
    element.addEventListener("click",function(event){
        const id = event.target.parentElement.parentElement.children[2].textContent;
        const url = getUrl(`/controllers/ProfesoresController.php?idProfesor=${id}&action=eliminarProfesor`);
        fetch(url)
        .then(resp => resp.json())
        .then(json => {
            Swal.fire({
                icon: json.status?"success":"error",
                title: 'Informacion',
                text: json.message,
            })
            if(json.status){
                setTimeout(() => {
                    location.reload();
                },3000)
            }
        })
    })
})