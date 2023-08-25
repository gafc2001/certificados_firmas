<?php 
class Connection extends mysqli{
    private $host = 'localhost';
    private $username = 'root';
    private $password = '$pth6fuX%dG1M$#MJ*mr';
    private $schema = 'certificados';
    private $port = 3306;

    public function __construct($file = 'my_settings.ini'){
        parent::__construct(
            $host = $this->host,
            $username = $this->username,
            $passwd = $this->password,
            $dbname = $this->schema,
            $port = $this->port
        );
        if (mysqli_connect_error()) {
            die('Error de Conexión (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
        }
    }
}