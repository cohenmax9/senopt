<?php session_start();
	if(!isset($_SESSION["user"]))		//If the session is not set, redirect to the login page.
		header("location:login.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php include("mysqlkey.php"); include("mysqlconnect.php");

	$uname = $_SESSION["user"];
	$fname = $_SESSION["first"];
	$lname = $_SESSION["last"];
	$slots = $_SESSION["slots"];
	$id = $_SESSION["id"];

	$uname = strip_tags(stripslashes($uname));
	$fname = strip_tags(stripslashes($fname));
	$lname = strip_tags(stripslashes($lname));
	$slots = strip_tags(stripslashes($slots));
	$id = strip_tags(stripslashes($id));
	
	$uname = mysql_real_escape_string($uname);
	$fname = mysql_real_escape_string($fname);
	$lname = mysql_real_escape_string($lname);
	$slots = mysql_real_escape_string($slots);
	$id = mysql_real_escape_string($id);

	if($_POST){		//If $_POST is set, continue.
	
		if(isset($_POST['remove'])){		//If $_POST[remove] is set, continue.
			$sid = $_POST['remove'];		//Takes the hidden information from $_POST[remove] and stores it in $sid.
			$sid = strip_tags(stripslashes($sid));
			$sid = mysql_real_escape_string($sid);
			
			$query = "UPDATE students SET mentor=null WHERE student_id = $sid";		//Updates the students table by setting the removed student's mentor equal to null, thereby removing the 																						
																					//student from the logged in teacher's list
			$query_result = mysql_query($query);		//Holds the result of the query (whether the query was successful or not).
			
			if($query_result){		//If the query successfully updated the table, the following statement is echoed.
				echo "The student was successfully removed.";
			}
			
			if(!$query_result){		//If the query failed to update the table, the following statement is echoed.
				echo "The student could not be removed.";
			}
		}
		
		if(isset($_POST['add'])){		//If $_POST[add] is set, continue.
			$sid = $_POST['add'];
			$sid = strip_tags(stripslashes($sid));
			$sid = mysql_real_escape_string($sid);
			
			$query = "UPDATE students SET mentor=$id WHERE student_id = $sid";		//Updates the students table by setting the added student's mentor equal to the logged in teacher's 	
																					//teacher_id, thereby adding the student to the logged in teacher's list
			
			$query_result = mysql_query($query);
			
			if($query_result){		//If the query successfully updated the table, the following statement is echoed.
				echo "The student was successfully added.";
			}
			
			if(!$query_result){		//If the query failed to update the table, the following statement is echoed.
				echo "The student could not be added.";
			}
		}

	}

?>


<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>TeacherView</title>
    <style type="text/css">
		body{
			font-family: Trebuchet MS;
	
		}
		h1, h3, h4{
			color: #980B1A;	
		}
	</style>
</head>

<body>
	<h1>Senior Options - TeacherView</h1>
	
    <?php include("mysqlkey.php"); include("mysqlconnect.php");
		
		$count = mysql_num_rows(mysql_query("SELECT * FROM students where mentor=$id"));		//Keeps track of the number of slots that are currently occupied.
		$open = $slots - $count;		//Keeps track of the number of slots that are available.
		
		$openstudentcount = mysql_num_rows(mysql_query("SELECT * FROM students WHERE mentor IS NULL ORDER BY lname,fname ASC"));		//Keeps track of the number of students without a mentor.
		echo "<h4>Welcome, ".$fname." ".$lname."!";
		if($open>=0)
			echo "<h5>You have $open slots remaining.</h5>";
		else if($open == -1)
			echo "<h5>You have 1 student more than your limit! Please remove a student below.";
		else{
			$change = $open * -1;
			echo "<h5>You have $change students more than your limit! Please remove $change students below.";
		}
		
		if($count>0){
			echo "<h3>Manage your students:</h3>";
			echo "<ul>";		//The unordered list of students of this teacher.
			
			$result = mysql_query("SELECT * FROM students WHERE mentor = '$id' ORDER BY lname,fname ASC");		//Selects the information of all the students that have the same mentor.
	
			while($row = mysql_fetch_array($result, MYSQL_BOTH)){		//Outputs each student of the teacher that is logged in.
				echo "<li>".$row['fname']." ".$row['lname']." <form method='POST' action='index_t.php'><input type='hidden' name='remove' value='$row[0]' /><input type='submit' value='Remove'/></form></li>";		//Displays the first name and the last name of the students of the teacher that is logged in. A submit button also appears called 'Remove' that allows the `				
									//teacher that is logged in to remove a student from that teacher's list. The student's information is stored in $_POST and is used to update the 
									//students table (see top of page).
			}
				
				
			echo "</ul>";
		}
				
		if($open>0 && $openstudentcount>0){
			echo "<h3>Add a student:</h3>";
			$addresult = mysql_query("SELECT * FROM students WHERE mentor IS NULL ORDER BY lname,fname ASC");		//Selects all of the students from the students table that do not have an 	
																													//assigned mentor.
			echo "<form method='POST' action='index_t.php'><select name='add'>";		
			while($row = mysql_fetch_array($addresult, MYSQL_BOTH))		//Creates a dropdown list of all the students that do not have a mentor.
				echo "<option value='".$row['student_id']."'>".$row['fname']." ".$row['lname']."</option>";	
			echo "</select><input type='submit' value='Add'/></form>";
		}
		if($openstudentcount<=0)
			echo "<h3>There are no students available</h3>";
	?>
    <br/>
    <a href='logout.php'>Log Out</a>		<!--Log out.-->
</body>
</html>