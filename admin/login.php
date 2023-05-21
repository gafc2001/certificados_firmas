<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="./../css/login.css" rel="stylesheet">
</head>
<body class="bg-dark">
    <div class="login-content">
        <form action="./../controllers/LoginController.php" method="post" class="form box-shadow bg-light">
            <div class="input-group mb-3">
                <i class="fas fa-user-circle input-group-text"></i>
                <input type="text" name="username" id="username" class="form-control <?php echo isset($_GET['error'])?'is-invalid':'' ?>" placeholder="Usuario">
            </div>
            <div class="input-group mb-3">
                <i class="fas fa-lock input-group-text"></i>
                <input type="password" name="password" id="password" class="form-control <?php echo isset($_GET['error'])?'is-invalid':'' ?>" placeholder="Clave">
                <?php if(isset($_GET['error'])): ?>
                    <div class="invalid-feedback">
                        Credenciales incorrectas
                    </div>
                <?php endif?>
            </div>
            <div class="d-grid">
                <button class="btn btn-primary btn-block " type="submit">Iniciar sesion</button>
            </div>
        </form>
    </div>
    <script src="https://kit.fontawesome.com/0dadf959e1.js" crossorigin="anonymous"></script>
</body>
</html>