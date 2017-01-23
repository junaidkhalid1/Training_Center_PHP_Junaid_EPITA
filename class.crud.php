<?php

/**
 * Created by PhpStorm.
 * User: Junaid KHALID
 * Date: 1/19/2017
 * Time: 3:31 AM
 */

require_once 'dbconfig.php';
class crud
{
	private $db;

	public function __construct()
	{
		$database = new Database();
		$dbs = $database->dbConnection();
		$this->db = $dbs;
	}

	public function getprojIDByTitle($proj)
	{
		$stmt = $this->db->prepare("SELECT project_id FROM project WHERE title=:proj");
		$stmt->execute(array(":proj"=>$proj));
		$editRow=$stmt->fetch(PDO::FETCH_COLUMN);
		return $editRow;
	}

	public function createteam($getpid,$summ,$id)
	{
		try
		{
			$stmt = $this->db->prepare("INSERT INTO team(project_id,owner_id,created_at,summary) VALUES(:projid, :ownid, NOW(), :summ)");
			$stmt->bindparam(":projid",$getpid);
			$stmt->bindparam(":ownid",$id);
			$stmt->bindparam(":summ",$summ);
			$stmt->execute();
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			return false;
		}

	}

	public function getteamIDBySummary($summ)
	{
		$stmt = $this->db->prepare("SELECT team_id FROM team WHERE summary=:summ");
		$stmt->execute(array(":summ"=>$summ));
		$editRow=$stmt->fetch(PDO::FETCH_COLUMN);
		return $editRow;
	}

	public function add_member_to_team($gettid,$id)
	{
		try
		{
			$stmt = $this->db->prepare("INSERT INTO team_member(team_id,student_id) VALUES(:teamid, :studid)");
			$stmt->bindparam(":teamid",$gettid);
			$stmt->bindparam(":studid",$id);
			$stmt->execute();
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			return false;
		}

	}

	public function del_member_from_team($gettid,$id)
	{
		try
		{
			$stmt = $this->db->prepare("DELETE FROM team_member WHERE team_id=:teamid AND student_id=:studid");

			$stmt->bindparam(":teamid",$gettid);
			$stmt->bindparam(":studid",$id);
			$stmt->execute();
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			return false;
		}

	}

	public function getID($id)
	{
		$stmt = $this->db->prepare("SELECT * FROM person WHERE person_id=:id");
		$stmt->execute(array(":id"=>$id));
		$editRow=$stmt->fetch(PDO::FETCH_ASSOC);
		return $editRow;
	}

	/* paging */

	public function dataviewProjects($query)
	{
		$stmt = $this->db->prepare($query);
		$stmt->execute();

		if($stmt->rowCount()>0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{?>
				<tr>
					<td><?php print($row['project_id']); ?></td>
					<td><?php print($row['title']); ?></td>
					<td><?php print($row['subject']); ?></td>
					<td><?php print($row['deadline']); ?></td>
				</tr>
				<?php
			}
		}
		else
		{
			?>
			<tr>
				<td>Nothing here...</td>
			</tr>
			<?php
		}

	}

	public function dataviewPersons($query)
	{
		$stmt = $this->db->prepare($query);
		$stmt->execute();

		if($stmt->rowCount()>0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{?>
				<tr>
					<td><?php print($row['first_name']); ?></td>
					<td><?php print($row['last_name']); ?></td>
					<td align="center">
						<a href="delete_from_team.php?delete_id=<?php print($row['person_id']);?>&team_id=<?php print($row['team_id']);?>"><i class="btn btn-navbar">Delete</i></a>
					</td>
				</tr>
				<?php
			}
		}
		else
		{
			?>
			<tr>
				<td>Nothing here...</td>
			</tr>
			<?php
		}

	}

	public function dataviewPersonsNotInTeam($query)
	{
		$stmt = $this->db->prepare($query);
		$stmt->execute();

		if($stmt->rowCount()>0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{?>
				<tr>
					<td><?php print($row['first_name']); ?></td>
					<td><?php print($row['last_name']); ?></td>
					<td align="center">
						<a href="add_in_team.php?add_id=<?php print($row['person_id']);?>&team_id=<?php print($row['team_id']);?>"><i class="btn btn-navbar">Add</i></a>
					</td>
				</tr>
				<?php
			}
		}
		else
		{
			?>
			<tr>
				<td>Nothing here...</td>
			</tr>
			<?php
		}

	}

	public function dataview($query)
	{
		$stmt = $this->db->prepare($query);
		$stmt->execute();

		if($stmt->rowCount()>0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				?>
				<tr>
					<td><?php print($row['team_id']); ?></td>
					<td><?php print($row['summary']); ?></td>
					<!--<td><?php /*print($row['last_name']); */?></td>
                <td><?php /*print($row['email_id']); */?></td>
                <td><?php /*print($row['contact_no']); */?></td>-->
					<td align="center">
						<a href="not_in_team.php?team_id=<?php print($row['team_id']); ?>"><i class="btn btn-navbar">Students not in team</i></a>
					</td>
					<td align="center">
						<a href="in_team.php?team_id=<?php print($row['team_id']); ?>"><i class="btn btn-navbar">Students in team</i></a>
					</td>
				</tr>
				<?php
			}
		}
		else
		{
			?>
			<tr>
				<td>Nothing here...</td>
			</tr>
			<?php
		}

	}

	public function paging($query,$records_per_page)
	{
		$starting_position=0;
		if(isset($_GET["page_no"]))
		{
			$starting_position=($_GET["page_no"]-1)*$records_per_page;
		}
		$query2=$query." limit $starting_position,$records_per_page";
		return $query2;
	}

	public function paginglink($query,$records_per_page)
	{

		$self = $_SERVER['PHP_SELF'];

		$stmt = $this->db->prepare($query);
		$stmt->execute();

		$total_no_of_records = $stmt->rowCount();

		if($total_no_of_records > 0)
		{
			?><ul class="pagination"><?php
			$total_no_of_pages=ceil($total_no_of_records/$records_per_page);
			$current_page=1;
			if(isset($_GET["page_no"]))
			{
				$current_page=$_GET["page_no"];
			}
			if($current_page!=1)
			{
				$previous =$current_page-1;
				echo "<li><a href='".$self."?page_no=1'>First</a></li>";
				echo "<li><a href='".$self."?page_no=".$previous."'>Previous</a></li>";
			}
			for($i=1;$i<=$total_no_of_pages;$i++)
			{
				if($i==$current_page)
				{
					echo "<div class=\"col-md-4 text-center\">
							<li class=\"btn btn-navbar\"><a href='".$self."?page_no=".$i."' style='color:red;'>".$i."</a></li>
							</div>";
				}
				else
				{
					echo "<li><a href='".$self."?page_no=".$i."'>".$i."</a></li>";
				}
			}
			if($current_page!=$total_no_of_pages)
			{
				$next=$current_page+1;
				echo "<li class=\"btn btn-navbar\"><a href='".$self."?page_no=".$next."'>Next</a></li>";
				echo "<li class=\"btn btn-navbar\"><a href='".$self."?page_no=".$total_no_of_pages."'>Last</a></li>";
			}
			?></ul><?php
		}
	}

	/* paging */

}
