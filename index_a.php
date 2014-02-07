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
	
		$tid = $_POST['teacherid'];		//Takes the hidden information from $_POST[newmax] and stores it in $tid.
		$tid = strip_tags(stripslashes($tid));
		$tid = mysql_real_escape_string($tid);
		
		$newmax = $_POST['newmax'];		//Takes the hidden information from $_POST[newmax] and stores it in $tid.
		$newmax = strip_tags(stripslashes($newmax));
		$newmax = mysql_real_escape_string($newmax);
		
		$query = "UPDATE teachers SET max_slots='$newmax' WHERE teacher_id = '$tid'";		//Updates the teachers table by setting the selected teacher's max_slots to the inputted value.
		$query_result = mysql_query($query);		//Holds the result of the query (whether the query was successful or not.
		
		if($query_result){		//If the query successfully updated the table, the following statement is echoed.
			echo "The teacher was successfully updated.";
		}
		
		if(!$query_result){		//If the query failed to update the table, the following statement is echoed.
			echo "The teacher could not be updated.";
		}
	}

?>


<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Admin Control</title>
    <style type="text/css">
		body{
			font-family: Trebuchet MS;
	
		}
		h1, h3, h4{
			color: #980B1A;
	</style>
</head>

<body>
    <?php include("mysqlkey.php"); include("mysqlconnect.php");
		
		echo "<h1>Senior Options - <i><u>Admin Edition</u></i></h1>";
		echo "<h4>Welcome, ".$fname." ".$lname."!";
		echo "<h3>Manage the teachers:</h3>";
		echo "<ul>";		//The unordered list of teachers in the database.
		
		$result = mysql_query("SELECT * FROM teachers ORDER BY lname,fname");	   //Selects the information of all the teachers ordered by last name, primarily and first name secondarily.

		while($row = mysql_fetch_array($result, MYSQL_BOTH)){	//Displays each teacher, the number of current maximum slots that each teacher has, and a text box that allows for the			
																	//input of a new value of the number of maximum slots for each teacher.
			$teacherid = $row['teacher_id'];
			$teacherid = mysql_real_escape_string($teacherid);
			$count = mysql_num_rows(mysql_query("SELECT * FROM students where mentor=$teacherid"));
			$count = mysql_real_escape_string($count);

			if($uname != $row['uname'])		//If the current jlamela does not equal the current $uname, continue.
				echo "<li>".$row['fname']." ".$row['lname']." - Current Max Slots: ".$row['max_slots'].". Current Number of Mentees: ".$count.".<br/><form method='POST' action='index_a.php'>New value: <input type='text' name='newmax'/><input type='hidden' name='teacherid' value='$row[0]' /><input type='submit'/></form></li><br/>";			//The teacher's number of maximum slots is stored in $_POST and is used to 	
																												//update the teachers table (see top of page).
		}

		echo "</ul>";
	
		echo "<a href='logout.php'>Log Out</a>";		//Log out.
			
	?>
</body>
</html>