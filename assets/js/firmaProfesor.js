const getUrl = (path) => window.location.protocol + "//" +window.location.host +  path;

const btnFirma = document.querySelector("#btnFirma");

btnFirma.addEventListener("click",function(){
    const firmaImg = document.querySelector("#firma-img").files[0]
    if(!firmaImg){
        Swal.fire({
            icon: "error",
            title: 'Informacion',
            text: "Favor de ingresar la imagen de la firma",
        })
        return;
    }
    const formData = new FormData();
    formData.append("firma_file",firmaImg);
    const url = getUrl('/controllers/ProfesoresController.php');
    formData.append("action","actualizarFirma")
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
            setTimeout(() => location.reload(),3000)      
        }
    })
})