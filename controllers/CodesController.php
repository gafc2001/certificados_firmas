<?php
include_once('../db/DatabaseConn.php');
include_once('./../Shuchkin/SimpleXLSXGen.php');
class CodeController{
    private Connection $db;
    private $request;
    public function __construct(){
        $this->db = new Connection();
        $this->request = $_REQUEST;
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

    function saveCodes(){
        $sql = "INSERT INTO codes(certify_id,sign_code,is_used,created_at) " 
        ." VALUES(?,?,?,?)";
        $qty = $this->request['qty'];
        $certificate = $this->request['certificate'];
        for($i = 0; $i < $qty;$i++){
            $stmt = $this->db->prepare($sql);
            $code = $this->generateCode();
            $date = date("Y-m-d");
            $is_used = 0;
            $stmt->bind_param('isis',$certificate,$code,$is_used,$date);
            if(!$stmt->execute()){
                echo "Upps";
                break;
            }

        }
        header("Location: ../admin/");
    }
    function deleteCode(){
        $id = $this->request['delete'];
        $sql = "DELETE FROM codes WHERE id = ?";
        $stmt = $this->db->prepare("$sql");
        $stmt->bind_param('i',$id);
        $stmt->execute();
        header("Location: ../admin/");
    }
    
    function download(){
        $sql = "SELECT u.senati_id,ce.name,c.sign_code FROM codes c".
               " INNER JOIN certificates ce ON ce.id = c.certify_id".
               " LEFT JOIN users_certificados u ON c.user_id = u.id".
               " WHERE c.is_used = 0";
        $result = $this->db->query($sql);
        $data = $this->populateData($result);
        $xlsx = Shuchkin\SimpleXLSXGen::fromArray( $data );
        $xlsx->downloadAs('books.xlsx');
    }
    function populateData($result){
        $columns = ["Codigo Alumno","Curso","Codigo"];
        $data = [$columns];

        while($row = $result->fetch_assoc()){
            if(is_null($row['senati_id'])){
                $row['senati_id'] = "alumno no presente";
            }
            array_push($data,$row);
        }
        return $data;
    }
    function truncateCodes(){
        $result = $this->db->query("TRUNCATE TABLE codes");
        header("Location: ../admin/");
    }
    function execute(){
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == 'POST'){
            $this->saveCodes();
        }
        if($method == 'GET'){
            if(isset($_GET['action']) && $_GET['action'] == "download"){
                $this->download();
            }
            if(isset($_GET['action']) && $_GET['action'] == "truncate"){
                $this->truncateCodes();
            }else{
                $this->deleteCode();
            }
        }
    }
}

$code = new CodeController();
$code->execute();
