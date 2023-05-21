<?php
include_once('../controllers/Session.php');
include_once('../db/DatabaseConn.php');
$db = new Connection();
$is_available = isset($_GET['code'])?"WHERE is_used =".$_GET['code']:"";
$sql = "SELECT co.*,c.name,u.senati_id FROM codes co".
       " INNER JOIN certificates c ON co.certify_id = c.id ".
       " LEFT JOIN users_certificados u on u.id = co.user_id "
       .$is_available;
$results = $db->query($sql);
$certificates = $db->query("SELECT * FROM certificates");
?>
<!DOCTYPE html>
<html lang="en">
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
        <?php include_once('./templates/header.php')?>
        <main class="my-5">
            <div class="container content">
                <h1>Generar codigos</h1>
                <form action="../controllers/CodesController.php" method="post">
                    <div class="row mb-4">
                        <div class="col-2">
                            <input type="number" name="qty" id="qty" class="form-control" placeholder="Cantidad" min="1" value="1">
                        </div>
                        <div class="col-4">
                            <select name="certificate" id="certificate" class="form-select">
                                <?php while($certificate = $certificates->fetch_assoc()):?>
                                <option value="<?php echo $certificate['id']?>"><?php echo $certificate['name']?></option>
                                <?php endwhile ?>
                            </select>
                        </div>
                        <div class="col">
                            <button class="btn btn-primary">Generar codigos</button>
                        </div>
                    </div>
                </form>
                <div class="d-flex">
                    <div class="ms-2">
                        Filtrar por:
                    </div>
                    <div class="ms-2">
                        <a class="btn btn-success" href="?code=0">Disponible</a>
                    </div>
                    <div class="ms-2">
                        <a class="btn btn-danger" href="?code=1">No Disponible</a>
                    </div>
                    <div class="ms-2">
                        <a class="btn btn-primary" href="./">Todos</a>
                    </div>
                    <div class="ms-auto">
                        <button class="btn btn-danger" id="delete-db"><i class="fa fa-database"></i> Borrar codigos</button>
                    </div>
                </div>
                
                <table class="table">
                    <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>Certificado</th>
                            <th>Estudiante</th>
                            <th>Disponible</th>
                            <th>Fecha de Uso</th>
                            <th>Fecha de Creacion</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($result = $results->fetch_assoc()):?>
                        <tr>
                            
                            <td><?php echo $result['sign_code'] ?></td>
                            <td><?php echo $result['name'] ?></td>
                            <td><?php echo is_null($result['senati_id'])?'No alumno':$result['senati_id'] ?></td>
                            <td class="text-light">
                                <?php if($result['is_used'] == 0):?>
                                <span class="text-center bg-success d-block rounded p-1">Disponible</span>
                                <?php else:?>
                                <span class="text-center bg-danger d-block rounded p-1">Disponible</span>
                                <?php endif?>
                            </td>
                            <td><?php echo !is_null($result['code_used'])?$result['code_used']:'Todavia no se usa' ?></td>
                            <td><?php echo $result['created_at'] ?></td>
                            <td>
                                <a href="./../controllers/CodesController.php?delete=<?php echo $result['id']?>" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>

    </div>
    <script src="https://kit.fontawesome.com/0dadf959e1.js" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./../assets/js/delete.js"></script>
</body>
</html>