<?php
/**
 * Created by PhpStorm.
 * User: Junaid KHALID
 * Date: 12/16/2016
 * Time: 12:55 PM
 */
class Database
{
     
    private $host = "localhost";
    private $db_name = "training_center";
    private $username = "root";
    private $password = "";
    public $conn;
     
    public function dbConnection()
	{
     
	    $this->conn = null;    
        try
		{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
        }
		catch(PDOException $exception)
		{
            echo "Connection error: " . $exception->getMessage();
        }
         
        return $this->conn;
    }
}
?>