<?php
/**
 * Created by PhpStorm.
 * User: Junaid KHALID
 * Date: 1/19/2017
 * Time: 12:53 AM
 */

require_once 'class.crud.php';
$crud = new crud();

require_once 'class.user.php';
$user = new USER();

if(isset($_POST['btn-add']))
{
    $team_id = $_GET['team_id'];
    $stud_id = $_GET['add_id'];
    if($crud->add_member_to_team($team_id,$stud_id))
    {
    header("Location: add_in_team.php?added");
    }
    else
    {
        echo "sorry , Query could no execute...";
    }
}

?>

<?php include_once 'header.php'; ?>

    <div class="clearfix"></div>

    <div class="container">

        <?php
        if(isset($_GET['added']))
        {
            ?>
            <div class="alert alert-success">
                <strong>Success!</strong> Student was added...
            </div>
            <?php
        }
        else
        {
            ?>
            <div class="alert alert-danger">
                <strong>Sure !</strong> to add the following student ?
            </div>
            <?php
        }
        ?>
    </div>

    <div class="clearfix"></div>

    <div class="container">

        <?php
        if(isset($_GET['add_id']) && isset($_GET['team_id']))
        {
            echo $_GET['add_id'];
            echo $_GET['team_id'];
            ?>
            <table class='table table-bordered'>
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                </tr>
                <?php

                $stmt = $user->runQuery("SELECT * FROM person WHERE person_id=:id");
                $stmt->execute(array(":id"=>$_GET['add_id']));
                while($row=$stmt->fetch(PDO::FETCH_BOTH))
                {
                    ?>
                    <tr>
                        <td><?php print($row['person_id']); ?></td>
                        <td><?php print($row['first_name']); ?></td>
                        <td><?php print($row['last_name']); ?></td>
                        <td><?php print($row['email']); ?></td>
                        <td><?php print($row['mobile_phone']); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <?php
        }
        ?>
    </div>

    <div class="container">
        <p>
            <?php
            if(isset($_GET['add_id']))
            {
            ?>
        <form method="post">
            <input type="hidden" name="id" value="<?php echo $_GET['team_id'];; ?>" />
            <input type="hidden" name="id" value="<?php echo $row['person_id']; ?>" />
            <button class="btn btn-large btn-primary" type="submit" name="btn-add"><i class="glyphicon glyphicon-trash"></i> &nbsp; YES</button>
            <a href="http://localhost/Training_Center/home.php" class="btn btn-large btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; NO</a>
        </form>
        <?php
        }
        else
        {
            ?>
            <a href="http://localhost/Training_Center/home.php" class="btn btn-large btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; Back</a>
            <?php
        }
        ?>
        </p>
    </div>
<?php include_once 'footer.php'; ?>