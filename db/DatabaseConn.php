<?php 
class Connection extends mysqli{
    private $host = 'fdb21.awardspace.net';
    private $username = '2738101_asistencia';
    private $password = 'root@123negreiros';
    private $schema = '2738101_asistencia';
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