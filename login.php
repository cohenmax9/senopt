<?php session_start(); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Login</title>
    <style type="text/css">
		body{
			font-family: Trebuchet MS;
	
		}
		h1, h3, h4{
			color: #980B1A;	
		}
	</style>
</head>
<?php include("mysqlkey.php"); include("mysqlconnect.php");		//See comments on respective files.
if($_POST){
	$uname = $_POST["user"];		//Takes in the data submitted.
	$pword = $_POST["pass"];
	
	$uname = strip_tags(stripslashes($uname));		//Strips HTML tags from the submitted string to avoid any HTML injection.
	$pword = strip_tags(stripslashes($pword));
	
	$uname = mysql_real_escape_string($uname);		//Escapes the variables from MySQL queries, so we can use the data stored within them for queries.
	$pword = mysql_real_escape_string($pword);
	
	$query = "SELECT * FROM teachers WHERE uname='$uname'";		//Writes a query to access all the information stored
																//under that specific teacher in the teachers table of the senopt database.
	$query_result = mysql_query($query);		//Executes the query and stores the result. If the account exists then
												//$query_result is a resource with the row data, otherwise is false.
	
	$userdata = mysql_fetch_assoc($query_result);		//Creates an array with all the data from the row. If $query_result was false, then is a null array.
		if($userdata['uname'] == null)		//uname is a required field in the teachers table, so if $userdata['uname']
											//is null then you know that $query_result was false and therefore the account is not in the database.
			echo "The username was not found";
		else{		//If $userdata['uname'] is not null, then it must be the same as $uname because $query specifically asks for it, therefore you do not need to retest.
			if($userdata['pword'] == $pword){		//Tests if the password is the same as the one stored in the database.
				$_SESSION["user"] = $uname;		//If the password is correct, stores the userdata in the $_SESSION array so the site can display only the information relevant to the user.
				$_SESSION["first"] = $userdata['fname'];
				$_SESSION["last"] = $userdata['lname'];
				$_SESSION["slots"] = $userdata['max_slots'];
				$_SESSION["id"] = $userdata['teacher_id'];
				
				if($uname == "jlamela" || $uname == "gleong")		//If $uname is that of the admin (the person running senior options,
											//who is Jose Lamela when this was created, and Gregory Leong) then the user is taken to the admin page.
					header("location:index_a.php");
				else		//If the user is not the admin, then they must be a teacher because they have a correct login and are therefore taken to the teacher page.
					header("location:index_t.php");
			}
			else		//If login info is incorrect, the user remains on the login page and are notified of the problem.
				echo "Incorrect password!";
			}
		}
?>
<body>
	<h1>Log in here</h1>
	<form method="POST" action="login.php">
		Username: <input type="text" name="user" /><br />
    	Password: <input type="password" name="pass" /><br />
    
    	<input type="submit" />
   	</form>
</body>
</html>

