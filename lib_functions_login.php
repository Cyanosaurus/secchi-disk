<?php /* COMMON FUNCTIONS */

/* File Index

--Generic
(string) cleantext_login($val)

--Secchi Specific
(void) check_level()
(bool) log_in($username, $password)

*/

session_start();
include_once("lib_functions_mysql.php");

//Keeps the incoming input from user 'clean' from messing up internal function.
//Login-version for extra-cleaning
function cleantext_login($val)
{
	$temp = "";
	for ($i = 0; $i < strlen($val); $i++)
	{
		$ch = substr($val,$i,1);
		if ( ($ch >= 0 && $ch <= 9) || (strtoupper($ch) >= 'A' && strtoupper($ch) <= 'Z') || $ch == "_" ) { $temp .= $ch; }
	}

	return $temp;
}

//Use to return the Account's valid access level
function check_level()
{
	//Get user information
	$query = "SELECT * FROM `Account` WHERE `Username`='" . $_SESSION['username'] . "' AND `Login_key`='" . $_SESSION['sekey'] . "' AND `Expire_time`>'".time()."'";
	$results = mysql_get($query);

	if ( $row = mysql_fetch_array($results) )
	{	
		//Admins expire in 10 minutes, users in an hour
		if ( $row['Privileges'] == 'a' ) { $_SESSION['level'] = 2; $expire = time() + 600; } else { $_SESSION['level'] = 1; $expire = time() + 3600; }

		//Update timeout
		  $query = "UPDATE `Account` SET `Expire_time`='$expire' WHERE `Username`='".$_SESSION['username']."' AND `Login_key`='".$_SESSION['sekey']."'";
	      mysql_get($query);

	} else {
		$_SESSION['level'] = 0;
		$_SESSION['sekey'] = "";
		$_SESSION['username'] = "";
	 	 $_SESSION['AccountID'] = "";
	}
	
}

//Logs into account.  Returns true on success.
function log_in($in_user,$in_password)
{

   //Look up user ID/PASS
   $in_user = cleantext_login($in_user);
   $in_password = cleantext_login($in_password);
   
   $query = "SELECT * FROM `Account` WHERE `Username`='$in_user' AND `Password`='$in_password'";
   $results = mysql_get($query);
   if ( ($row = mysql_fetch_array($results)) != false )
   {
   
   	  //Security Key, and update
   	  $mykey = randstring(100,time());

    	  $_SESSION['sekey'] = $mykey;
    	  $_SESSION["username"] = "$in_user";
	  $_SESSION['AccountID'] = $row['ID'];
	  //Admins expire in 10 minutes, users in an hour
	  if ( $row['Privileges'] == 'a' ) { $_SESSION['level'] = 2; $expire = time() + 600; } else { $_SESSION['level'] = 1; $expire = time() + 3600;}
      
	  $query = "UPDATE `Account` SET `Login_key`='$mykey',`Expire_time`='$expire' WHERE `Username`='$in_user'";
      mysql_get($query);
      
      return true;
   }

   $_SESSION["level"] = 0;

   return false;
}



?>
