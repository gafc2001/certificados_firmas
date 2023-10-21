<?php
require_once('./Session.php');
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
        $id = $_SESSION['user_id'];
        $sql = "INSERT INTO codes(user_id,certify_id,sign_code,is_used,created_at) " 
        ." VALUES(?,?,?,?,?)";
        $qty = $this->request['qty'];
        $certificate = $this->request['certificate'];
        for($i = 0; $i < $qty;$i++){
            $stmt = $this->db->prepare($sql);
            $code = $this->generateCode();
            $date = date("Y-m-d");
            $is_used = 0;
            $stmt->bind_param('sisis',$id,$certificate,$code,$is_used,$date);
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
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);
        $user_id = $_SESSION["user_id"];
        
        
        $sql = "SELECT u.senati_id,ce.name,c.sign_code FROM codes c
                INNER JOIN certificates ce ON ce.id = c.certify_id
                LEFT JOIN users_certificados u ON c.user_id = u.id
                WHERE c.is_used = 0 AND profesor_id = '{$user_id}'";

        $result = $this->db->query($sql);
        $html = $this->htmlExcel($result);
        $date = date("Y_m_d_H_i_s");
        header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=codigos_{$date}_.xls");  //File name extension was wrong
        header("Cache-Control: max-age=0");
        echo $html;
        exit;
    }
    function htmlExcel($result){
        $tbody = "";
        while($row = $result->fetch_assoc()){
            if(is_null($row['senati_id'])){
                $row['senati_id'] = "alumno no presente";
            }
            $senati_id = $row["senati_id"];
            $name = $row["name"];
            $codigo = $row["sign_code"];
            $tbody.= "
            <tr>
                <td>{$senati_id}</td>
                <td>{$name}</td>
                <td>{$codigo}</td>
            </tr>
            ";
        }
        $html = "
            <table>
                <thead>
                    <tr>
                        <td>Codigo Alumno</td>
                        <td>Curso</td>
                        <td>Codigo</td>
                    </tr>
                </thead>
                <tbody>{$tbody}</tbody>
            </table>
        ";
        return $html;
    }
    function truncateCodes(){
        $profesor_id = $_SESSION["user_id"];
        $result = $this->db->query("DELETE FROM codes WHERE profesor_id = {$profesor_id}");
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
