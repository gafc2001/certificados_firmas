<?php
include_once('../controllers/Session.php');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <div class="wrapper">
        <?php include_once('./templates/header.php') ?>
        <div class="toast-container" id="toast-container"  style="position: fixed; min-height: 200px; top:70px;right:10px;">
    
        </div>

        <div class="container col-5 mt-2">
            <h4>Cabeceras modelo</h4>
            <div class="alert alert-info fs-6">
                <i class="fa fa-info"></i>
                Los Cursos disponibles son 
                <ul>
                    <li>IOT</i>
                    <li>CIBERSEGURIDAD</i>
                    <li>NETWORKING</i>
                    <li>GETCONNECTED</i>
                    <li>PYTHON</i>
                    <li>EMPRENDIMIENTO</i>
                </ul>
            </div>
            <table class="table table-border">
                <thead class="bg-primary text-light">
                    <tr>
                        <th>REGISTRO</th>
                        <th>CURSO</th>
                        <th>ALUMNO</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>IOT</td>
                        <td>1111111</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <main class="my-5 pb-5">
            <div class="container content">
                <h1>Subir alumnos</h1>
                <form class="form needs-validation" id="form" method="post" action="./../controllers/UploadController.php" enctype="multipart/form">
                    <div class="form-group mt-3">
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Subir archivo</label>
                            <input class="form-control" type="file" id="fileinput" name="file" required accept=".csv">
                        </div>
                    </div>
                    <div class="text-danger" id="file-feedback">
                        Seleccione un archivo
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary mt-1" id="btn-upload">Subir archivos</button>
                    </div>
                </form>
                <div id="box-csv">

                    <h3>Subiendo archivo</h3>
                    <div class="progress m-3" style="height: 30px">
                        <div id="bar-csv" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0">0%</div>
                    </div>
                </div>
                <div id="box-data">
                    <h3>Subiendo datos</h3>
                    <div class="progress m-3" style="height: 30px">
                        <div id="bar-data" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="0">0%</div>
                    </div>
                    <div id="message"></div>
                </div>
            </div>
        </main>
    </div>
    <script src="https://kit.fontawesome.com/0dadf959e1.js" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.1/papaparse.min.js" integrity="sha512-EbdJQSugx0nVWrtyK3JdQQ/03mS3Q1UiAhRtErbwl1YL/+e2hZdlIcSURxxh7WXHTzn83sjlh2rysACoJGfb6g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="./../assets/js/upload.js"></script>
</body>

</html>