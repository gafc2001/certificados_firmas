<?php
require_once("./../db/DatabaseConn.php");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: *");
header("Accept: application/json");
session_start();
class UploadController{
    private Connection $db;
    private $path;
    private $request;
    private array $errors;
    public function __construct(){
        $this->request = empty($_REQUEST)?(array)json_decode(file_get_contents('php://input'),true):$_REQUEST;
        $this->db = new Connection();
        $this->path = "./../assets/csv/";
    }
    public function uploadCsv(){
        if(!file_exists($this->path)){
            mkdir($this->path, 0777, true);
        }
        $file = $_FILES['file']['tmp_name'];
        $target = $this->targetFile($file);
        if(move_uploaded_file($file,$target[0])){
            $resp = array(
                "url" => $this->getFileUrl($target[1]),
                "file_name" => $target[1],
            );
            die(json_encode($resp));
        }else{
         $resp = array(
                "error" => "Error al subir el CSV"
            );
            die(json_encode($resp));
        }
        
    }
    public function targetFile($name){
        $file_name = hash('sha256',$name);
        return [$this->path.$file_name,$file_name];
    }
    public function getFileUrl($file){
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';
        $server = $_SERVER['SERVER_NAME'];
        return $protocol.$server."/assets/csv/".$file;
    }
    public function saveCodes(){
        if(!empty($this->validateHeaders())){
            http_response_code(400);
            die(json_encode($this->validateHeaders()));
            exit;
        }
        if(!empty($this->validateCertificates())){
            http_response_code(400);
            die(json_encode($this->validateCertificates()));
            exit;
        }
        for($i=0;$i<count($this->request['data']);$i++){
            $data[$i] = (array)$this->request['data'][$i];
            $data[$i]['CURSO'] = $this->getCertificateId(strtoupper($data[$i]['CURSO']));
            $data[$i]['ALUMNO'] = $this->getOrInsertUser($data[$i]['ALUMNO']);
        }
        
        foreach($data as $row){
            $stmt = $this->db->prepare("INSERT INTO codes(profesor_id,user_id,certify_id,sign_code,is_used,created_at) VALUES(?,?,?,?,?,?)");
            $code = $this->generateCode();
            $user_id = $row['ALUMNO'];
            $certify_id = $row['CURSO'];
            $is_used = 0;
            $created_at = date('Y-m-d');
            $profesor_id = $_SESSION["user_id"];
            $stmt->bind_param("iiisis",$profesor_id,$user_id,$certify_id,$code,$is_used,$created_at);
            $stmt->execute();
        }
        die(json_encode(["message" => "ok"]));
        exit;
    }
    function generateCode(){
        $date_format = date('Y@m!d%H#i&sv');
        $rand_num = random_int(1,99999);
        $text = $date_format.$rand_num;
        $opciones = [
            'cost' => 11
        ];
        $hash = strval(password_hash($text,PASSWORD_BCRYPT,$opciones));
        return str_split($hash,23)[1];
    }
    public function getCertificateId($curso){
        $sql = "SELECT id FROM certificates WHERE name = '$curso'";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch_assoc();
        if(!is_null($result)){
            return $result['id'];
        }else{
            array_push($this->errors,$curso);
            return false;
        }
    }

    public function userExist($senati_id){
        $result = $this->db->query("SELECT * FROM users_certificados WHERE senati_id = '$senati_id'");
        $rows = $result->num_rows == 1;
        $fetch = $result->fetch_assoc();
        return [$rows,$fetch];
    }
    public function getOrInsertUser($senati_id){
        $user = $this->userExist($senati_id);
        if(!$user[0]){
            $stmt_user = $this->db->prepare("INSERT INTO users_certificados(senati_id) VALUES(?)");
            $stmt_user->bind_param('s',$senati_id);
            $stmt_user->execute();
            return $stmt_user->insert_id;
        }else{
            return $user[1]["id"];
        }
        
    }
    public function validateCertificates(){
        $certificates = [
            "IOT",
            "CIBERSEGURIDAD",
            "NETWORKING",
            "GETCONNECTED",
            "PYTHON",
            "EMPRENDIMIENTO",
        ];
        $errors = [];
        foreach($this->request["data"] as $row){
            if(!in_array($row["CURSO"],$certificates)){
                $error = [
                    "registro" => $row["REGISTRO"],
                    "message" => "No existe el certificado",
                    "type" => "values"
                ];
                array_push($errors,$error);
            }
        }
        return $errors;
        
    }
    public function validateHeaders(){
        $headers = [
            "REGISTRO",
            "ALUMNO",
            "CURSO"
        ];
        $errors = [];
        $data = $this->request["data"][0];
        foreach ($headers as $header){
            if(!empty($this->validateField($data,$header))){
                array_push($errors,$this->validateField($data,$header));
            }
        }
        return $errors;
        
    }
    public function validateField($data,$field){
        if(!isset($data[$field])){
            $error = [
                "field" => $field,
                "message" => "Cabecera incorrecta",
                "type" => "fields"
            ];
            return $error;
        }
    }

    public function deleteCsv(){
        $file = $this->request['file'];
        unlink("./assets/csv/$file");
    }
    public function execute(){
        $action = $this->request['action'];
        
        if($action == 'upload'){
            $this->uploadCsv();
        }
        $this->request = json_decode(file_get_contents('php://input'),true);
        $action = $this->request['action'];
        if($action == 'save'){            
            $this->saveCodes();
        }
        if($action == 'delete'){
            $this->deleteCsv();
        }
    }
}
$upload = new UploadController;
$upload->execute();

