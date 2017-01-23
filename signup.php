<?php
/**
 * Created by PhpStorm.
 * User: Junaid KHALID
 * Date: 1/02/2017
 * Time: 03:01 PM
 */

session_start();
require_once 'class.user.php';

$reg_user = new USER();

if($reg_user->is_logged_in()!="")
{
	$reg_user->redirect('home.php');
}


if(isset($_POST['btn-signup']))
{
	$fn = trim($_POST['txtfn']);
	$ln = trim($_POST['txtln']);
	$address = trim($_POST['txtadr']);
	$zipcode = trim($_POST['txtzc']);
	$town = trim($_POST['txttown']);
	$email = trim($_POST['txtemail']);
	$pass = trim($_POST['txtpass']);
	$conpass = trim($_POST['txtcpass']);
	$mob = trim($_POST['txtmob']);
	$num = trim($_POST['txtnum']);

	$code = md5(uniqid(rand()));

	if ($pass == $conpass)
	{

	$stmt = $reg_user->runQuery("SELECT * FROM person WHERE email=:email");
	$stmt->execute(array(":email"=>$email));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if($stmt->rowCount() > 0)
	{
		$msg = "
		      <div class='alert alert-error'>
				<button class='close' data-dismiss='alert'>&times;</button>
					<strong>Sorry !</strong>  Email allready exists , Please Try another one
			  </div>
			  ";
	}
	else
	{
		if($reg_user->register($fn,$ln,$address,$zipcode,$town,$email,$pass,$mob,$num,$code))
		{			
			$id = $reg_user->lasdID();		
			$key = base64_encode($id);
			$id = $key;
			
			$message = "					
						Hello $fn,
						<br /><br />
						Welcome to Training Center!<br/>
						To complete your registration  please , just click following link<br/>
						<br /><br />
						<a href='http://localhost/Training_Center/verify.php?id=$id&code=$code'>Click HERE to Activate :)</a>
						<br /><br />
						Thanks,";
						
			$subject = "Confirm Registration";
						
			$reg_user->send_mail($email,$message,$subject);	
			$msg = "
					<div class='alert alert-success'>
						<button class='close' data-dismiss='alert'>&times;</button>
						<strong>Success!</strong>  We've sent an email to $email.
                    Please click on the confirmation link in the email to create your account. 
			  		</div>
					";
		}
		else
		{
			echo "sorry , Query could no execute...";
		}
	}

	}
	else
	{
		$msg = "<div class='alert alert-block'>
						<button class='close' data-dismiss='alert'>&times;</button>
						<strong>Sorry!</strong>  Password Doesn't match. 
						</div>";
	}
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Signup | Training Center</title>
    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
    <link href="assets/styles.css" rel="stylesheet" media="screen">
     <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
  </head>
  <body id="login">
    <div class="container">
				<?php if(isset($msg)) echo $msg;  ?>
      <form class="form-signin" method="post">
        <h2 class="form-signin-heading">Sign Up</h2><hr />
          <input type="text" class="input-block-level" placeholder="First Name" name="txtfn" required />
          <input type="text" class="input-block-level" placeholder="Last Name" name="txtln" required />
          <input type="text" class="input-block-level" placeholder="Address" name="txtadr" required />
		  <input type="text" class="input-block-level" placeholder="Zip Code" name="txtzc" required />
		  <input type="text" class="input-block-level" placeholder="Town" name="txttown" required />
		  <input type="email" class="input-block-level" placeholder="Email" name="txtemail" required />
		  <input type="password" class="input-block-level" placeholder="Password" name="txtpass" required />
		  <input type="password" class="input-block-level" placeholder="Confirm Password" name="txtcpass" required />
		  <input type="text" class="input-block-level" placeholder="Mobile Number" name="txtmob" required />
		  <input type="text" class="input-block-level" placeholder="Other Number (Optional)" name="txtnum" />
     	<hr />
        <button class="btn btn-large btn-primary" type="submit" name="btn-signup">Sign Up</button>
        <a href="index.php" style="float:right;" class="btn btn-large">Sign In</a>
      </form>

    </div> <!-- /container -->
    <script src="bootstrap/js/jquery-1.9.1.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>