window.addEventListener('DOMContentLoaded', () => {
    const barCsv = document.querySelector('#bar-csv');
    const boxCsv = document.querySelector('#box-csv');
    const barData = document.querySelector('#bar-data');
    const boxData = document.querySelector('#box-data');
    const message = document.querySelector('#message');
    const fileFeed = document.querySelector('#file-feedback');
    const labelInput = document.querySelector('#label-input');
    const fileInput = document.querySelector('#fileinput');
    const form = document.getElementById('form');
    
    let file = document.getElementById("fileinput");
    let data = "";
    let fileName = "";
    let isOk = true;

    //URLs
    const getUrl = (path) => window.location.protocol + "//" + window.location.host + path;

    const uploadUrl = getUrl('/controllers/UploadController.php');
    const uploadData =  getUrl('/controllers/UploadController.php');
    const deleteCsvUrl = getUrl('/controllers/UploadController.php');
    boxCsv.style.display = "none";
    fileFeed.style.display = "none";
    boxData.style.display = "none";

    
    fileInput.addEventListener("change", function () {
        fileInput.classList.add("is-valid");
    })


    form.addEventListener("submit", (event) => {
        event.preventDefault();
        if (fileExists()) {
            uploadCsv();
        }
    })

    async function uploadCsv() {
        let formData = new FormData();
        formData.append('file', file.files[0]);
        formData.append('action', 'upload');
        boxCsv.style.display = "block";
        // console.log(uploadUrl);
        await axios.post(uploadUrl, formData,
            {
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                },
                onUploadProgress: (progressEvent) => {
                    let progress = Math.round(progressEvent.loaded * 100 / progressEvent.total);
                    barCsv.style.width = progress + "%";
                    barCsv.innerText = progress + "%";
                }
            })
            .then(resp => {
                url = resp.data.url;
                fileName = resp.data.file_name;
                
            })
            .catch(err => {
                console.log(err)
                isOk = false;
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "Error al subir CSV "+err
                })
            });
        getCsv(url);
    }




    async function getCsv(url) {
        const csv = await fetch(url)
            .then(r => r.text())
            .then(v => Papa.parse(v, { header: true }))
            .then(csv => {
                csv.errors.forEach(err => {
                    if(err.code == "TooFewFields"){
                        csv.data.splice(err.row,1);
                    }
                })
                data = csv.data;
                return csv.data;
            })
            .catch(err => {
                console.log(err);
                isOk = false;
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "Error al obtener el archivo "+err
                })
            });
        let maxSize = data.length;
        let arraysOfData = []
        let parallelUploads = 5;
        for (let i = 0; i < maxSize; i += parallelUploads) {
            arraysOfData.push(data.splice(0, parallelUploads));
        }
        uploadBlockOfData(arraysOfData);
    }

    async function uploadBlockOfData(array) {
        let size = array.length;
        let value = 0;
        boxData.style.display = "block";
        for (let row of array) {
            await fetch(uploadData, {
                'method': 'POST',
                'body': JSON.stringify({"action" : "save","data":row}),
                headers: {
                    "Ceontent-Type": "application/json",
                    "Accept": "application/json",
                },
            })
                .then(resp => {
                    if(!resp.ok){
                        isOk = false;        
                    }
                    return resp.json();
                })
                .then(json => {
                    console.log(json);
                    if(!isOk){
                        if(json[0].type == "fields"){
                            showRequiredFields(json);
                        }
                        if(json[0].type == "values"){
                            showWarning(json);
                        }

                    }
                })
                .catch(err => {
                    isOk = false;
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: "Error al guardar la informacion\n"+err
                    })
                });
            // if(!isOk){
            //     // break;
            // }
            value += 1;
            let progress = Math.round(value * 100 / size);
            barData.style.width = progress + "%";
            barData.innerText = progress + "%";

        }
        await deleteFile(fileName);
    }

    function fileExists() {
        if (file.files.length == 0) {
            fileFeed.style.display = "block";
            return false;
        }
        return true;
    }
    async function deleteFile(file) {
        await fetch(deleteCsvUrl, {
            method: "DELETE",
            body: JSON.stringify({ 'file': file ,'action':'delete'}),
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
        })
            
        if(isOk){
            Swal.fire(
                "Correcto",
                data.message,
                'success'
            );
        }
    }
    const showWarning = (resp) => {
        if(resp){
            const toastConstainer = document.getElementById("toast-container");
            resp.forEach(error => {
                let toastElm = toast(error);
                toastConstainer.appendChild(toastElm);
                const bToast =  new bootstrap.Toast(toastElm);
                bToast.show();
            });
        }
    }
    const toast = (resp) => {
        const div = document.createElement("div");
        div.classList.add("toast");
        div.setAttribute("data-animation",true);
        div.setAttribute("data-autohide",false);
        div.setAttribute("data-delay",30000);
        const html = 
        `<div class="toast-header bg-white">
            <span class="rounded me-2 bg-warning" style="width: 15px; height:15px;"></span>
            <strong>Registro N° ${resp.registro}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body bg-white" style="border-radius: 0 0 4px 4px ;">
            ${resp.message}
        </div>`;
        div.innerHTML = html.trim();
        return div;
    }

    const showRequiredFields = (data) => {
        const msg = `Faltan las siguientes cabeceras ${data.map(e => e.field)}`;
        throw new Error(msg);
    }
});