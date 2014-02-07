<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
   
<?php		//This page is intended to create a user account that is the first two letters of the user's first name plus their last name, and only meant to be used if the default style (first initial + last name) has been taken by another user.

include("mysqlkey.php"); include("mysqlconnect.php");		//See comments on respective files.

	$error = false;
	$errormsg = "Uh oh! The following things went wrong: ";		//Initializes error message at a 40-character string.
	
if($_POST /*&& isset($_POST["first"]) && isset($_POST["last"]) && isset($_POST["user"]) && isset($_POST["pass"]) && isset($'_POST["confirm"])*/){
	
	$fname = $_POST["first"];		//Takes in the data submitted.
	$lname = $_POST["last"];
	$pword = $_POST["pass"];
	$pconf = $_POST["confirm"];

	$fname = strip_tags(stripslashes($fname));		//Strips HTML tags from the submitted string to avoid any HTML injection.
	$lname = strip_tags(stripslashes($lname));
	$pword = strip_tags(stripslashes($pword));
	$pconf = strip_tags(stripslashes($pconf));
	
	$fname = mysql_real_escape_string($fname);		//Escapes the variables from MySQL queries, so we can use the data stored within them for queries.
	$lname = mysql_real_escape_string($lname);
	$pword = mysql_real_escape_string($pword);
	$pconf = mysql_real_escape_string($pconf);
	
	$uname = strtolower($fname[0] . $fname[1] . $lname);		//Sets $uname equal to the first two letters of the teacher's first name plus the teacher's last name that were entered by the teacher.
	$uname = strip_tags(stripslashes($uname));
	$uname = mysql_real_escape_string($uname);
	
	
	if(strlen($fname) == 0 || strlen($lname) == 0 || strlen($pword) == 0)		//If either $fname, $lname, or $pword are left blank, the error is added to $errormsg.
		$errormsg .= "A required field was left blank. ";
	if($pword != $pconf)		//If $pword does not equal $pconf, the error is added to $errormsg.
		$errormsg .= "The password fields do not match. ";		
	if(strlen($fname) > 40)		//If the entered first name is more than 40 characters, the error is added to $errormsg.
		$errormsg .= "The first name you entered was too long. ";		
	if(strlen($lname) > 40)		//If the entered last name is more than 40 characters, the error is added to $errormsg.
		$errormsg .= "The last name you entered was too long. ";		
	if(strlen($pword) > 40)		//If the entered password is more than 40 characters, the error is added to $errormsg
		$errormsg .= "The password you entered was too long. ";
	if(strlen($errormsg)>40)		//If $errormsg is over 40 characters, then we know that one of the previous errors occurred and $errormsg needs to be echoed.
		$error = true;
	else{		//If there are no errors, then we know that the entered information satisfies the requirements and we can proceed with creating the account by entering the information input by 				//the teacher into the database.
		
		$user_query = "SELECT * FROM teachers WHERE uname='$uname'";		//Gets all the information about the teacher with the username in $uname.
		$user_query_result = mysql_query($user_query);		//Executes the query and stores the result. If the account exists then
															//$query_result is a resource with the row data, otherwise is false.					
		
		$userdata = mysql_fetch_assoc($user_query_result);		//Creates an array with all the data from the row. If $query_result was false, then is a null array.
			if($userdata['uname'] == null){		//uname is a required field in the teachers table, so if $userdata['uname']
												//is null then you know that $query_result was false and therefore the account is not in the database.

				$query = "INSERT INTO teachers (fname, lname, uname, pword) VALUES ('$fname', '$lname', '$uname', '$pword')";		//Inserts the information inputted into the teachers table.
				$query_result = mysql_query($query);		//Executes the query. Returns true is successful, otherwise false.				
				if($query_result){		//If $query_result is true, the account was successfully created
					echo "Account successfully created! \n The username that you will use to login each time is $uname";
				}
				if(!$query_result)		//If $query_result is false, then the account was not successfully created.
					echo "There was a problem. Please try again.";
		
			}
			else
				echo "The user ".$uname." has already been created. Do you want to try <a href='login.php'>logging in?</a> If someone else has this username, please <a href='signup2.php'>create an account here</a>.";
	}
}
?>
   
   
   
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
	<title>Signup</title>
	<meta http-equiv="Content-type" content="text/html; charset=iso-8859-1" />
	<meta http-equiv="Content-Language" content="en-us" />
    <style type="text/css">
		body{
			font-family: Trebuchet MS;
	
		}
		h1, h3, h4{
			color: #980B1A;	
		}
	</style>
	</head>

<!--aes_encrypt($pass,$key)-->

	<body>
    <?php 	if($error)			//If one of the above errors occurred, meaning that $errormsg>40, then $error is set to true. If $error is true, then $errormsg, which contains the errors made			 								//by the teacher is echoed.
		echo $errormsg; ?>
		<h1>Sign up here!</h1>
		<form method="POST" action="signup2.php">
			First name: <input type="text" name="first" /><br />
			Last name: <input type="text" name="last" /><br />
			Password: <input type="password" name="pass" /><br />
			Confirm Password: <input type="password" name="confirm" />
			</br>
			<input type="submit" />
		</form>

	</body>
</html>