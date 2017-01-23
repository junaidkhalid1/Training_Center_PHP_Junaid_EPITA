<?php
/**
 * Created by PhpStorm.
 * User: Junaid KHALID
 * Date: 1/17/2017
 * Time: 7:29 PM
 */

session_start();
require_once 'class.crud.php';
$crud = new crud();

require_once 'class.user.php';
$project = new USER();

if(isset($_POST['btn-save']))
{
    $proj = $_POST['all_projects'];
    $summ = $_POST['summary'];
    $id = $_POST['txtid'];

    if($getpid = $crud->getprojIDByTitle($proj))
    {

    if($crud->createteam($getpid,$summ,$id))
    {
        if($gettid = $crud->getteamIDBySummary($summ))
        {
        if ($crud->add_member_to_team($gettid,$id))
        {
            header("Location: create_team.php?inserted");
        }
        else
        {
            header("Location: create_team.php?failure");
        }

        }
        else
        {
            echo "sorry , Query could no execute...";
        }
    }
    else
    {
        echo "sorry , Query could no execute...";
    }

    }
else
    {
        echo "sorry , Query could no execute...";
    }
}
?>
<?php include_once 'header.php'; ?>
    <div class="clearfix"></div>

<?php
if(isset($_GET['inserted']))
{
    ?>
    <div class="container">
        <div class="alert alert-info">
            <strong>WOW!</strong> Team was inserted successfully <a href="http://localhost/Training_Center/home.php">HOME</a>!
        </div>
    </div>
    <?php
}
else if(isset($_GET['failure']))
{
    ?>
    <div class="container">
        <div class="alert alert-warning">
            <strong>SORRY!</strong> ERROR while Creating Team !
        </div>
    </div>
    <?php
}

//For getting user ID
$stmt = $project->runQuery("SELECT * FROM person WHERE person_id=:uid");
$stmt->execute(array(":uid"=>$_SESSION['userSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

//For Project populating
$stmt = $project->runQuery("SELECT title FROM project");
$stmt->execute();
$data = $stmt->fetchAll();

?>

    <div class="clearfix"></div><br />

    <div class="container">


        <form method='post'>
            <h2 class="form-signin-heading">Create Team</h2><hr />

            <input type="hidden" class="input-block-level" placeholder="Person ID" name="txtid" value="<?php echo $row ['person_id']; ?> " required />


            <tr>
            <table class='table table-bordered'>

                <td>Project</td>
                <td>
                <select name="all_projects" id="all_projects">

                    <?php foreach ($data as $row): ?>
                    <option><?=$row["title"]?></option>
                    <?php endforeach ?>

                </select>
                </td>
                </tr>

                <tr>
                    <td>Summary</td>
                    <td><input type='text' name='summary' class='form-control' required></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <button type="submit" class="btn btn-primary" name="btn-save">
                            <span class="glyphicon glyphicon-plus"></span> Create Team
                        </button>
                        <a href="http://localhost/Training_Center/home.php" class="btn btn-primary btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; Back</a>
                    </td>
                </tr>

            </table>
        </form>


    </div>

<?php include_once 'footer.php'; ?>