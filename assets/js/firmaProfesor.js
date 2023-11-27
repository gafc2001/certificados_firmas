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
            location.reload()
        }
    })
})

const btnPrevisualizar = document.querySelector("#previsualizar");

if(!!btnPrevisualizar){
    btnPrevisualizar.addEventListener("click",function(){
        const certificado = document.querySelector("#certificado");
        if(!certificado.value){
            Swal.fire({
                icon: "error",
                title: 'Error',
                text: "Seleccione el certificado de prueba",
            })
        }
        const firmaRuta = document.getElementById("firma_ruta");
        window.open(getUrl(`/admin/previsualizar_firma.php?certificado=${certificado.value}&firma_ruta=${firmaRuta.value}`));
    })
}