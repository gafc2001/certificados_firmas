<?php
include_once(__DIR__ . '/db/DatabaseConn.php');
$db = new Connection();

$error = "";
if (isset($_GET['error'])) {
    $errors = $_GET['error'];
    switch ($errors) {
        case 'not_exist':
            $error = 'El codigo no existe';
            break;
        case 'used':
            $error = 'El codigo ya fue usado';
            break;
        case 'code_incorrect':
            $error = 'El codigo no es del curso';
            break;
    }
}
$usuario = $_REQUEST["usuario"];
$sql = "SELECT co.*,c.name,u.senati_id,p.nombres profesor FROM codes co
        INNER JOIN certificates c ON co.certify_id = c.id
        INNER JOIN users_certificados u on u.id = co.user_id
        INNER JOIN users_certificados p ON p.id = co.profesor_id
        WHERE 1 {$is_available}
        AND u.senati_id = '{$usuario}'";

$results = $db->query($sql);
$certificates = $db->query("SELECT * FROM certificates");

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar PDF con FPDI</title>
    <link type="text/css" rel="shortcut icon" href="imgs/logo-mywebsite-urian-viera.svg" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" />
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
</head>

<body class="pt-5">
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <span class="navbar-brand">
            <img src="./assets/white-icon.png" alt="Web Developer Urian Viera" width="60">
            Firma de de Certificado
        </span>
    </nav>
    <section class="mt-5">
        <div class="row">
            <div class="col-8 mx-auto bg-white rounded-lg shadow">
                <form class=" mt-3" enctype="multipart/form-data">

                    <!-- Code input -->
                    <div class="input-group mb-3">
                        <i class="fa fa-user input-group-text"></i>
                        <input type="text" name="usuario" id="usuario" placeholder="Usuario" class="form-control <?php echo empty($error) ? '' : 'is-invalid' ?>" required>
                        <?php if ($error) : ?>
                            <div class="invalid-feedback">
                                <?php echo $error ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <button class="btn btn-primary my-3" value="Entrar">
                        <i class="fa fa-search"></i>
                        Buscar
                    </button>
                    <a href="index.php" class="btn btn-success">Ingresar codigo</a>
                </form>
                <table class="table table-bordered">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th>Codigo</th>
                            <th>Certificado</th>
                            <th>Estudiante</th>
                            <th>Profesor</th>
                            <th>Disponible</th>
                            <th>Fecha de Uso</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($result = $results->fetch_assoc()) : ?>
                            <tr>

                                <td><?php echo $result['sign_code'] ?></td>
                                <td><?php echo $result['name'] ?></td>
                                <td><?php echo is_null($result['senati_id']) ? 'No alumno' : $result['senati_id'] ?></td>
                                <td><?php echo $result['profesor'] ?></td>
                                <td class="text-light">
                                    <?php if ($result['is_used'] == 0) : ?>
                                        <span class="text-center bg-success d-block rounded p-1">Disponible</span>
                                    <?php else : ?>
                                        <span class="text-center bg-danger d-block rounded p-1">Disponible</span>
                                    <?php endif ?>
                                </td>
                                <td><?php echo !is_null($result['code_used']) ? $result['code_used'] : 'Todavia no se usa' ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <script src="https://kit.fontawesome.com/0dadf959e1.js" crossorigin="anonymous"></script>
</body>

</html>