<?php session_start(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php include("mysqlkey.php"); include("mysqlconnect.php");	
?>


<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>StudentView</title>
    <style type="text/css">
		body{
			font-family: Trebuchet MS;
	
		}
		h1, h3, h4{
			color: #980B1A;	
		}
		div #tlogin{
			font-size: 22px;
		}
	</style>
</head>

<body>
	<h1>Senior Options - StudentView</h1>
    <h3>Available teachers:</h3>
    <ul>		<!--The unordered list of teachers in the database.-->

    <?php include("mysqlkey.php"); include("mysqlconnect.php");

			$result = mysql_query("SELECT * FROM teachers ORDER BY lname,fname");	   //Selects the information of all the teachers ordered by last name, primarily and first name secondarily.
	
			while($row = mysql_fetch_array($result, MYSQL_BOTH)){		//Displays each teacher, the number of current maximum slots that each teacher has, and a text box that allows for the			
																		//input of a new value of the number of maximum slots for each teacher.
				$id = $row['teacher_id'];
				$id = mysql_real_escape_string($id);										
				$count = mysql_num_rows(mysql_query("SELECT * FROM students where mentor=$id"));
				$open = $row['max_slots'] - $count;
				if($open>0)
					echo "<li>".$row['fname']." ".$row['lname']." - Current Open Slots: ".$open.".</li><br/>";			//The teacher's number of maximum slots is stored in $_POST and is used 
																														//to update the teachers table (see top of page).
			}
				
	?>	
	</ul>
    <h3>Unavailable teachers:</h3>
    <ul>		<!--The unordered list of teachers in the database.-->

    <?php include("mysqlkey.php"); include("mysqlconnect.php");

			$result = mysql_query("SELECT * FROM teachers ORDER BY lname,fname");	   //Selects the information of all the teachers ordered by last name, primarily and first name secondarily.
	
			while($row = mysql_fetch_array($result, MYSQL_BOTH)){		//Displays each teacher, the number of current maximum slots that each teacher has, and a text box that allows for the			
																		//input of a new value of the number of maximum slots for each teacher.
				$id = $row['teacher_id'];
				$id = mysql_real_escape_string($id);										
				$count = mysql_num_rows(mysql_query("SELECT * FROM students where mentor=$id"));
				$open = $row['max_slots'] - $count;
				if($open==0)
					echo "<li>".$row['fname']." ".$row['lname']."</li><br/>";			//The teacher's number of maximum slots is stored in $_POST and is used 
																														//to update the teachers table (see top of page).
			}
				
	?>	
	</ul>
	<div id='tlogin'><a href='login.php'>Teacher Log In</a></div>	<!--Log in.-->

</body>
</html>