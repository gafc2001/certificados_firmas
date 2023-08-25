<?php
include_once('../controllers/Session.php');
include_once('../db/DatabaseConn.php');
if ($_SESSION["role"] != "ADMIN") {
    header("index.php");
}

$db = new Connection();
$user_id = $_SESSION["user_id"];
$sql = "SELECT * FROM users_certificados WHERE role = 'PROFESOR' AND estado = 1";

$results = $db->query($sql);
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
        <main class="my-5">
            <div class="container content">
                <h2>Profesores</h2>
                <div class="mb-3">
                    <button type="button" class="btn btn-primary" id="btnAgregar" data-bs-toggle="modal" data-bs-target="#modalProfesor">
                        Agregar profesor
                    </button>
                </div>
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th>Senati Id</th>
                            <th>Profesor</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($result = $results->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $result["senati_id"] ?></td>
                                <td><?= $result["nombres"] ?></td>
                                <td style='display: none'><?= $result["id"] ?></td>
                                <td>
                                    <button class="btn btn-success btnEditar">Editar</button>
                                    <button class="btn btn-danger btnEliminar">Eliminar</button>
                                </td>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalProfesor" tabindex="-1" role="dialog" aria-labelledby="modalProfesor" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalProfesor">Modal title</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-profesor">
                        <input type="hidden" name="idProfesor" id="id-profesor">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">Senati ID</span>
                                    <input type="text" name="senatiId" id="senati-id" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <span class="input-group-text">Nombres</span>
                                    <input type="text" name="nombres" id="nombres" class="form-control">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="btnCancelar">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="guardar">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/0dadf959e1.js" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script language="JavaScript" src="./../assets/js/formProfesor.js" type="text/javascript" defer></script>
</body>

</html>