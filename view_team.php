<?php
/**
 * Created by PhpStorm.
 * User: Junaid KHALID
 * Date: 1/18/2017
 * Time: 3:18 PM
 */

session_start();

require_once 'class.crud.php';
$crud = new crud();
?>
<?php include_once 'header.php';?>

<?php
$id = $_SESSION['userSession'];
?>

    <div class="clearfix"></div>

    <div class="container">
        <h2 class="form-signin-heading">View Teams</h2><hr />
    </div>

    <div class="clearfix"></div><br />

    <div class="container">
        <table class='table table-bordered table-responsive'>
            <tr>
                <th>#</th>
                <th>Teams</th>
                <th colspan="2" align="center">Actions</th>
            </tr>
            <?php
            $query = "SELECT team_id,summary FROM team WHERE owner_id=$id";
            $records_per_page=3;
            $newquery = $crud->paging($query,$records_per_page);
            $crud->dataview($newquery);
            ?>
            <tr>
                <td colspan="7" align="center">
                    <div class="pagination-wrap">
                        <?php $crud->paginglink($query,$records_per_page); ?>
                    </div>
                </td>
            </tr>

        </table>
        <tr>
            <td colspan="1">
                <a href="http://localhost/Training_Center/home.php" class="btn btn-primary btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; Back</a>
            </td>
        </tr>


    </div>
<?php include_once 'footer.php'; ?>