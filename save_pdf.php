<?php //Verifying is dir exist
include_once(__DIR__.'//db//DatabaseConn.php');
$db = new Connection();

$dir = "assets/pdf/";
if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
}

$code = trim($_POST['code']);
$certificate = $_POST['certificate'];
$profesor = $_POST['profesor'];

$result = $db->query("SELECT * FROM codes where sign_code = '$code' AND user_id = '{$profesor}'");
if($result->num_rows == 0){
    header("Location: index.php?error=not_exist");
    die();
}
$fetch = $result->fetch_assoc();

if($fetch['is_used'] == "1"){
    header("Location: index.php?error=used");
    die();
}
if($fetch['certify_id'] != $certificate){
    header("Location: index.php?error=code_incorrect");
    die();
}

//Getting pdf file
$file_name  = $_FILES["file-input"]["name"];

//Filename
$date = date("YmdHisv");
$formated_file_name  = $date.$file_name;


//Saving file
$file = $dir . basename($formated_file_name); 
if (move_uploaded_file($_FILES["file-input"] ["tmp_name"], $file)) {
    $date = date("Y-m-d");
    $db->query("UPDATE codes SET is_used = 1, code_used = '$date' WHERE sign_code = '$code'");
	header("Location: certificado.php?code=".$code."&file=".basename($formated_file_name));
    exit;
} else {
    echo "error en la subida del archivo";
}

 ?>