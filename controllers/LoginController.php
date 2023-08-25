<?php
session_start();
include_once('../db/DatabaseConn.php');

class LoginController{
    private Connection $db;
    private $request;

    public function __construct(){
        $this->db = new Connection();    
        $this->request = $_REQUEST;
    }

    public function login(){
        $username = $this->request['username'];
        $password = $this->request['password'];
        $sql = "SELECT * FROM users_certificados 
                WHERE senati_id = '$username' and passwd = '$password'
                AND role IN ('ADMIN','PROFESOR') AND estado = 1";
        $result = $this->db->query($sql);
        if($result->num_rows != 1){
            header("Location: ../admin/login.php?error");
        }
        $fetch = $result->fetch_assoc();
        $_SESSION['username'] = $fetch['senati_id'];
        $_SESSION['role'] = $fetch['role'];
        $_SESSION['user_id'] = $fetch['id'];
        header("Location: ../admin/");
    }
    public function logout(){
        session_destroy();
        header("Location: ../index.php");
    }
    public function execute(){
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == 'POST'){
            $this->login();
        }
        if($method == 'GET'){
            $this->logout();
        }
    }
}
$login = new LoginController();
$login->execute();