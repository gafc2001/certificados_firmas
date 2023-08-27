<?php
session_start();
require_once('./Session.php');
include_once('../db/DatabaseConn.php');
include_once('./../Shuchkin/SimpleXLSXGen.php');
class ProfesoresController{
    private Connection $db;
    private $request;
    public function __construct(){
        $this->db = new Connection();
        $this->request = $_REQUEST;
    }
    public function guardarProfesor(){
        $senatiId = $_REQUEST["senatiId"];
        $nombres = $_REQUEST["nombres"];
        $role = "PROFESOR";
        $sql = "INSERT INTO users_certificados(senati_id,nombres,passwd,role) " 
        ." VALUES(?,?,?,?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssss',
            $senatiId,
            $nombres,
            $senatiId,
            $role);

        if(!$stmt->execute()){
            echo json_encode(array(
                "message" => "Error al agregar al profesor",
                "status" => false,
            ));
            return;
        }
        echo json_encode(array(
            "message" => "Se agrego al profesor",
            "status" => true,
        ));
    }
    public function editarProfesor(){
        $nombres = $_REQUEST["nombres"];
        $idProfesor = $_REQUEST["idProfesor"];
        $sql = "UPDATE users_certificados 
                SET nombres = ?
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ss',
            $nombres,
            $idProfesor,
        );

        if(!$stmt->execute()){
            echo json_encode(array(
                "message" => "Error al editar al profesor",
                "status" => false,
            ));
            return;
        }
        echo json_encode(array(
            "message" => "Se edito al profesor",
            "status" => true,
        ));
    }
    public function eliminarProfesor(){
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);
        $idProfesor = $_REQUEST["idProfesor"];
        $sql = "UPDATE users_certificados 
                SET estado = 0
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s',
            $idProfesor,
        );

        if(!$stmt->execute()){
            echo json_encode(array(
                "message" => "Error al desactivar al profesor",
                "status" => false,
            ));
            return;
        }
        echo json_encode(array(
            "message" => "Se desactivo al profesor",
            "status" => true,
        ));
    }
    public function actualizarFirma(){
        $idProfesor = $_SESSION['user_id'];
        $username = $_SESSION['username'];
        $dir = "./../assets/firmas/";
        $filename = $username. "." . pathinfo(basename( $_FILES["firma_file"]["name"]),PATHINFO_EXTENSION );
        $target_dir = $dir . $filename;

        if (!move_uploaded_file($_FILES["firma_file"]["tmp_name"], $target_dir)) {
            echo json_encode(array(
                "message" => "Error subir la firma",
                "status" => false,
            ));
            return;
        }

        $query = "UPDATE users_certificados 
                    SET firma_profesor = ?
                    WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ss',
            $filename,
            $idProfesor,
        );

        if(!$stmt->execute()){
            echo json_encode(array(
                "message" => "Error al actualizar la firma",
                "error" => $this->db->error,
                "error2" => json_encode(error_get_last()),
                "status" => false,
                "params" => array(
                    "filename" => $filename,
                    "id_profesor" => $idProfesor,
                )
            ));
            return;
        }
        echo json_encode(array(
            "message" => "Se actualizo la firma",
            "status" => true,
            "params" => array(
                "filename" => $filename,
                "id_profesor" => $idProfesor,
            )
        ));

    }
    function execute(){
        $method = $_SERVER['REQUEST_METHOD'];
        if(isset($_REQUEST['action'])){
            $action = $_REQUEST["action"];
            $this->$action();
        }
    }
}

$code = new ProfesoresController();
$code->execute();
