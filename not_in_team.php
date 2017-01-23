<?php
/**
 * Created by PhpStorm.
 * User: Junaid KHALID
 * Date: 1/19/2017
 * Time: 3:31 AM
 */

session_start();

require_once 'class.crud.php';
$crud = new crud();

$id = $_SESSION['userSession'];
?>
<?php include_once 'header.php'; ?>

    <div class="clearfix"></div>

    <div class="container">
        <h2 class="form-signin-heading">Students not in Team</h2><hr />    </div>

    <div class="clearfix"></div><br />

    <div class="container">
        <table class='table table-bordered table-responsive'>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th colspan="2" align="center">Actions</th>
            </tr>
            <?php
// $query = "SELECT * FROM   B WHERE  NOT EXISTS (SELECT 1 FROM A WHERE A.ID = B.ID)";

            $query = "SELECT * FROM person a INNER JOIN team b ON a.person_id = b.owner_id
                      INNER JOIN team_member c ON a.person_id = c.student_id WHERE a.person_id != $id";

            //$query = "SELECT * FROM person, team_member WHERE  NOT EXISTS (SELECT 1 FROM team_member WHERE team_member.student_id = person.person_id)";


            //$query = "SELECT * FROM person INNER JOIN team_member ON person.person_id != team_member.student_id";
            $records_per_page=3;
            $newquery = $crud->paging($query,$records_per_page);
            $crud->dataviewPersonsNotInTeam($newquery);
            ?>
            <tr>
                <td colspan="7" align="center">
                    <div class="pagination-wrap">
                        <?php $crud->paginglink($query,$records_per_page); ?>
                    </div>
                </td>
            </tr>

            <tr>
                <td colspan="1">
                    <a href="http://localhost/Training_Center/view_team.php" class="btn btn-primary btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; Back</a>
                </td>
            </tr>

        </table>


    </div>

<?php include_once 'footer.php'; ?>