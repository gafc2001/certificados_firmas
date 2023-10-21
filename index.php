<?php
  include_once(__DIR__ . '/db/DatabaseConn.php');
  $db = new Connection();
  $result = $db->query("SELECT * FROM certificates");
  $profesores = $db->query("SELECT * FROM users_certificados
                            WHERE estado = 1 AND role IN ('ADMIN','PROFESOR') 
                            AND firma_profesor != ''");
  $error = "";
  if(isset($_GET['error'])){
    $errors = $_GET['error'];
    switch($errors){
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
    <form method="POST" action="save_pdf.php" class="login-form mt-3" enctype="multipart/form-data"
      style="height: 380px !important;">
      <div>
        <a href="./codigos.php" class="btn btn-info">
          <i class="fa fa-search"></i>
          Codigos
        </a>
      </div>
      <img class="logo" src="./assets/icon.png" alt="certificate" width="10">
      <!-- File input -->
      <div class="file-input text-center">
        <input type="file" name="file-input" id="file-input" class="file-input__input" accept=".pdf" required/>
        <label class="file-input__label" for="file-input">
          <i class="fas fa-upload mr-2"></i>
          <span>Subir certificado</span>
        </label>
      </div>
      <!-- End file input -->
    
      <!-- Code input -->
      <div class="input-group mb-3">
        <i class="fa fa-key input-group-text"></i>
        <input type="text" name="code" id="code" placeholder="Codigo unico" class="form-control <?php echo empty($error)?'':'is-invalid' ?>" required>
        <?php if($error):?>
        <div class="invalid-feedback">
          <?php echo $error?>
        </div>
        <?php endif ?>
      </div>
    
    
      <!-- Certificate input -->
      <div class="input-group mb-3">
        <i class="fa fa-list input-group-text"></i>
        <select name="certificate" id="" required class="custom-select">
          <?php while($certificate = $result->fetch_assoc()):?>
          <option value="<?php echo $certificate['id']?>"><?php echo $certificate['name']?></option>
          <?php endwhile ?>
        </select>
      </div>
      
      <div class="input-group">
        <i class="fa fa-user input-group-text"></i>
        <select name="profesor" id="" required class="custom-select">
          
          <?php while($profesor = $profesores->fetch_assoc()):?>
          <option value="<?php echo $profesor['id']?>"><?php echo $profesor['nombres']?></option>
          <?php endwhile ?>
        </select>
      </div>
    
      <button class="btn btn-primary mt-2" value="Entrar">Subir Archivo</button>
    </form>
  </section>


  <script src="https://kit.fontawesome.com/0dadf959e1.js" crossorigin="anonymous"></script>
</body>
</html>