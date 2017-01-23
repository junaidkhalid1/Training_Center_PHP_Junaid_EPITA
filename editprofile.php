<?php
/**
 * Created by PhpStorm.
 * User: Junaid KHALID
 * Date: 1/16/2017
 * Time: 11:48 PM
 */

session_start();
require_once 'class.user.php';
$reg_user = new USER();

if(isset($_POST['btn-signup']))
{
    $id = trim($_POST['txtid']);
    $fn = trim($_POST['txtfn']);
    $ln = trim($_POST['txtln']);
    $address = trim($_POST['txtadr']);
    $zipcode = trim($_POST['txtzc']);
    $town = trim($_POST['txttown']);
    $mob = trim($_POST['txtmob']);
    $num = trim($_POST['txtnum']);

    if ($fn != null && $ln != null && $address != null&& $zipcode != null&& $town != null&& $mob != null)
    {
            if($reg_user->edit($id,$fn,$ln,$address,$zipcode,$town,$mob,$num))
            {
                $msg = "<div class='alert alert-block'>
						<button class='close' data-dismiss='alert'></button>
						<strong>Updated</strong>  Your profile is successfully updated.
						<a href=\"index.php\" style=\"float:right;\" class=\"btn btn-small\">Okay</a>
						</div>";
            }
            else
            {
                echo "sorry , Query could no execute...";
            }

    }
    else
    {
        $msg = "<div class='alert alert-block'>
						<button class='close' data-dismiss='alert'>&times;</button>
						<strong>Sorry!</strong>  One or more field is empty. 
						</div>";
    }
}

//To fill the form
$stmt = $reg_user->runQuery("SELECT * FROM person WHERE person_id=:uid");
$stmt->execute(array(":uid"=>$_SESSION['userSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile | Training Center</title>
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
        <h2 class="form-signin-heading">Edit Profile</h2><hr />
        <input type="hidden" class="input-block-level" placeholder="Person ID" name="txtid" value="<?php echo $row ['person_id']; ?> " required />
        <input type="text" class="input-block-level" placeholder="First Name" name="txtfn" value="<?php echo $row ['first_name']; ?> " required />
        <input type="text" class="input-block-level" placeholder="Last Name" name="txtln" value="<?php echo $row ['last_name']; ?> " required />
        <input type="text" class="input-block-level" placeholder="Address" name="txtadr" value="<?php echo $row ['address']; ?> " required />
        <input type="text" class="input-block-level" placeholder="Zip Code" name="txtzc" value="<?php echo $row ['zip_code']; ?> " required />
        <input type="text" class="input-block-level" placeholder="Town" name="txttown" value="<?php echo $row ['town']; ?> " required />
        <!--<input type="email" class="input-block-level" placeholder="Email" name="txtemail" readonly="readonly" value="<?php echo $row ['email']; ?> " required /> -->
        <input type="text" class="input-block-level" placeholder="Mobile Number" name="txtmob" value="<?php echo $row ['mobile_phone']; ?> " required />
        <input type="text" class="input-block-level" placeholder="Other Number (Optional)" value="<?php echo $row ['phone']; ?> " name="txtnum" />
        <hr />
        <button class="btn btn-large btn-primary" type="submit" name="btn-signup">Update</button>
        <a href="index.php" style="float:right;" class="btn btn-large">Cancel</a>
    </form>

</div> <!-- /container -->
<script src="bootstrap/js/jquery-1.9.1.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>