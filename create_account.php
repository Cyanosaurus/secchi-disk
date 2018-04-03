<?php
/* create_account.php
   Minimum Access Level: Admin (2)
   Date: 5/21/07
   Created by: Jasper Turcotte
   Modifiers:
		<name> on <date>
   Purpose: Collect and process new account info, and add

   Incoming variables:
	* Account Informatin

   Code Map:
	-1.0 Get and Process incoming info
	-2.0 Add form

   Main Outgoing Variables 
	-> self
		* Account Information

*/
	include_once("libraries.php");


if ( $_SESSION['level'] < 2 ) { header("Location: index.php"); exit; }
	
//Process incoming actions ----------------------------------

$action = get_post('action');
$alert_text = "";				//Message User, if Any
$reset_continue_FLAG = false;

//1.0 Get and Process incoming info
if ( get_post("action") == "Add Account" )
{
	//Get variables
	$fullname = get_post("fullname");
	$account_clean = super_cleantext(get_post("account"));
	$account = get_post("account");
	$pass1 = get_post("pass1");
	$pass2 = get_post("pass2");
	$pass_clean = super_cleantext($pass1);
	$email = get_post("email");
	$status = get_post("status");
	$priv = get_post("priv");

	$continue = true;	//Whether to finish processing in the end, or not
	
	//Validate input
	if ( $fullname == "" ) { $alert_text .= "You must supply a full name.<br />"; $continue = false;}
	if ( $account == "" ) { $alert_text .= "You must supply an AccountID.<br />"; $continue = false;}
	if ( $account != "" && strlen($account) < 3 || strlen($account) > 16 ) { $alert_text .= "The Account ID length must be between 3 and 16 characters.<br />"; $continue = false;}
	if ( $account != $account_clean ) { $alert_text .= "The Account ID contains invalid symbols.  You can only use letters, numbers, underscores or dashes.<br />"; $continue = false;}
	if ( $pass1 == "" ) { $alert_text .= "You must supply a password.<br />"; $continue = false;}
	if ( $pass2 == "" ) { $alert_text .= "You must verify the password.<br />"; $continue = false;}
	if ( $pass1 != $pass2 ) { $alert_text .= "The passwords do not match.<br />"; $continue = false;}
	if ( $pass1 != $pass_clean ) { $alert_text .= "The password contains invalid symbols.  You can only use letters, numbers and underscores.<br />"; $continue = false;}
	if ( $pass1 != "" && (strlen($pass1) < 6 || strlen($pass1) > 16 )) { $alert_text .= "The password length must be between 6 and 16 characters.<br />"; $continue = false;}
	if ( $email == "" ) { $alert_text .= "You must supply an email address.<br />"; $continue = false;}
	if ( !preg_match("/[.a-zA-Z0-9_-]+@[.a-zA-Z0-9_-]+\.[.a-zA-Z0-9_-]+/",$email,$matches) ) { $alert_text .= "Email address is invalid form.<br />"; $continue = false;}
	if ( $status != "i" && $status != "a" ) { $alert_text .= "You must supply a valid status type.<br />"; $continue = false;}
	if ( $priv != "a" && $priv != "u" ) { $alert_text .= "You must supply a valid user type (Privileges).<br />"; $continue = false;}
	
	//See if accountname is taken
	$query = "SELECT `ID` FROM `Account` WHERE `Username`='$account'";
	$result = mysql_get($query);
	$row = mysql_fetch_array($result);
	if ( $row != false ) { $alert_text .= "That Account already exists.<br />"; $continue = false; }

	//See if email is taken
	$query = "SELECT `ID` FROM `Account` WHERE `Email`='$email'";
	$result = mysql_get($query);
	$row = mysql_fetch_array($result);
	if ( $row != false ) { $alert_text .= "That Email address is already used.<br />"; $continue = false; }
	
	//Add it to the database
	if ( $continue )
	{

		$query = "INSERT INTO `Account` (`Name`,`Username`,`Password`,`Email`,`Status`,`Privileges`,`Email_user`) VALUES ('$fullname','$account','$pass1','$email','$status','$priv','1')";
		if (!mysql_get($query)) { Alert("Uh oh!",$query); }

		if ( preg_match("/(hotmail|yahoo)/i",$email,$matches)) { $alert_text .= "The email provider '".strtoupper($matches[1])."' has been detected. <br /><font color='red'>Make sure VLMP messages aren't redirected to your Junk or Spam folder.</font><br />"; }

		$alert_text .= "Account Added.<br />";
		//Send the email, if needed
		if ( get_post("notify") != "" ) { 
			$alert_text .= "Person has been notified of their new account.<br />";

			$subject = "Account Activation";
			$website = WEBSITE;			$body = "Dear $fullname,\n\nYour account with the VLMP has been created.\n\nYour login information:\nLogin name: $account\nPassword:  $pass1\n\nGo to $website and log in to take your test, when ready.\n\nThis is an automated message.  Do not reply.\n-Volunteer Lake Monitoring Program";
			$from = "From: VLMP_admin";
			if (!mail($email, $subject, $body,$from)) { $alert_text .= "Error sending email."; }
		 }	

		$fullname = "";
		$account = "";
		$accountID = "";
		$email = "";
	}
}


//-----------------------------------------------------------

	print_start_to_navbar("Create Account");
	print_navbar_to_content();
	
		delimiter_start();
		
		delimit();

			if ( $alert_text != "" ) { Alert("Notice",$alert_text); $reset_continue_FLAG = true; }

		delimit();

		//2.0 Add form

		echo "<form action='' method='POST'>\n";
			echo "<table class='data' width='100%'>\n";
				echo "<tr><td align='right'>Full Name (Last,First)</td><td align='left'><input type='text' size='40' maxlength='50' name='fullname' value=\"$fullname\"/></td></tr>\n";
				echo "<tr><td align='right'>Login Name</td><td align='left'><input type='text' size='16' maxlength='16' name='account' value=\"$account\" /></td></tr>\n";
				echo "<tr><td align='right'>Password</td><td align='left'><input type='password' size='16' maxlength='16' name='pass1' /></td></tr>\n";
				echo "<tr><td align='right'>Verify Passowrd</td><td align='left'><input type='password' size='16' maxlength='16' name='pass2' /></td></tr>\n";
				echo "<tr><td align='right'>&nbsp;</td><td></td></tr>\n";
				echo "<tr><td align='right'>Email</td><td align='left'><input type='text' size='50'  maxlength='50' name='email' value=\"$email\"/></td></tr>\n";
				echo "<tr><td align='right'>Status</td><td align='left'><select name='status'><option value='i'>Inactive</option><option value='a' selected='selected'>Active</option></select></td></tr>\n";
				echo "<tr><td align='right'>Privileges</td><td align='left'><select name='priv'><option value='a'>Administrator</option><option value='u' selected='selected'>User</option></select></td></tr>\n";
				echo "<tr><td align='right'>Notify person when added (Email).</td><td align='left'><input type='checkbox' name='notify'/></td></tr>\n";
				echo "<tr><td align='right'>&nbsp;</td><td></td></tr>\n";
				echo "<tr><td align='right'><input type='submit' value='Add Account' name='action' /></td><td></tr>\n";
			echo "</table>\n";
		echo "</form>\n";

		delimiter_end();
	
	print_content_to_end();
?>