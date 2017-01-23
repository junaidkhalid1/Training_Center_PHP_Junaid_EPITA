<?php
/**
 * Created by PhpStorm.
 * User: Junaid KHALID
 * Date: 12/18/2016
 * Time: 09:48 PM
 */

require_once 'dbconfig.php';

class USER
{	

	private $conn;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
	
	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}
	
	public function lasdID()
	{
		$stmt = $this->conn->lastInsertId();
		return $stmt;
	}

	
	public function register($fn,$ln,$address,$zipcode,$town,$email,$pass,$mob,$num,$code)
	{
		try
		{
			$password = md5($pass);
			$stmt = $this->conn->prepare("INSERT INTO person(first_name,last_name,address,zip_code ,town,email,mobile_phone,
                                          phone,password,created_at,confirmation_token) 
			                                             VALUES(?, ?, ?, ? , ?, ?, ?, ?, ?, NOW(), ?)");

            $stmt->execute(array($fn,$ln,$address,$zipcode,$town,$email,$mob,$num,$password,$code));
			return $stmt;
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}
	
	public function login($email,$upass)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT * FROM person WHERE email=:email_id");
			$stmt->execute(array(":email_id"=>$email));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			
			if($stmt->rowCount() == 1)
			{
				if($userRow['confirmed_at']!="NULL")
				{
					if($userRow['password']==md5($upass))
					{
						$_SESSION['userSession'] = $userRow['person_id'];
						return true;
					}
					else
					{
						header("Location: index.php?error");
						exit;
					}
				}
				else
				{
					header("Location: index.php?inactive");
					exit;
				}	
			}
			else
			{
				header("Location: index.php?error");
				exit;
			}		
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}

    public function edit($id,$fn,$ln,$address,$zipcode,$town,$mob,$num)
    {
        try
        {
            $stmt=$this->conn->prepare("UPDATE person SET first_name=:fname,last_name=:lname,address=:adr,zip_code=:zip,town=:town,
													    mobile_phone=:mob, phone=:phone
													WHERE person_id=:id ");
            $stmt->bindparam(":fname",$fn);
            $stmt->bindparam(":lname",$ln);
            $stmt->bindparam(":adr",$address);
            $stmt->bindparam(":zip",$zipcode);
            $stmt->bindparam(":town",$town);
            $stmt->bindparam(":mob",$mob);
            $stmt->bindparam(":phone",$num);
            $stmt->bindparam(":id",$id);
            $stmt->execute();

            return true;
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
            return false;
        }
    }
	
	
	public function is_logged_in()
	{
		if(isset($_SESSION['userSession']))
		{
			return true;
		}
	}
	
	public function redirect($url)
	{
		header("Location: $url");
	}
	
	public function logout()
	{
		session_destroy();
		$_SESSION['userSession'] = false;
	}
	
	function send_mail($email,$message,$subject)
	{						
		require_once('mailer/class.phpmailer.php');
		$mail = new PHPMailer();
		$mail->IsSMTP(); 
		$mail->SMTPDebug  = 0;                     
		$mail->SMTPAuth   = true;                  
		$mail->SMTPSecure = "ssl";                 
		$mail->Host       = "smtp.gmail.com";      
		$mail->Port       = 465;             
		$mail->AddAddress($email);
		$mail->Username="email@gmail.com";
		$mail->Password="password";
		$mail->SetFrom('email@gmail.com','Company Name');
		$mail->AddReplyTo("email@gmail.com","Company Name");
		$mail->Subject    = $subject;
		$mail->MsgHTML($message);
		$mail->Send();
	}
}