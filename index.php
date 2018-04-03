<?php
/* index.php
   Minimum Access Level: Public (0)
   Date: 5/21/07
   Created by: Jasper Turcotte
   Modifiers:
		<name> on <date>
   Purpose: Front log-in page.  

   Incoming variables:
	* Account username and password

   Code Map:
	-1.0 Validate input & login
		-1.1 REgular User
		-1.2 Admin
	-2.0 Main Content
		-2.1 Login and retrieve password
		-2.2 Go to secchi disk

   Main Outgoing Variables 
	-> self
		* Account username and password
	-> show_tests.php
		* Test ID and Test-taken ID
*/


	include_once("libraries.php");
	
//Process incoming actions ----------------------------------

$alert_text = "";				//Message User, if Any

$user = super_cleantext(get_post("user"));
$pass = super_cleantext(get_post("pass"));

//TextBlob Editing
$TB_action = get_post("tbaction"); //Edit1.2, Edit2.2


//Clean TB_content from any 'bad' tags
$TB_content = preg_replace('/\<\/?(script|embed|textarea).*?\>/i',"",$TB_content);
$TB_content = str_replace("\"","&quot;",$TB_content);
$TB_content = str_replace("<","&lt;",$TB_content);
$TB_content = str_replace(">","&gt;",$TB_content);
$TB_content = str_replace("'","&apos;",$TB_content);


$TB_admin = $_SESSION['level'] == 2;
if ( $TB_action == "Update Text 1" && $TB_admin )
{
    $TB_content = $_POST["tbcontent"];
	$fh = fopen("text/blob1.txt","w");
	fwrite($fh,$TB_content);
	fclose($fh);
	$alert_text .= "Text field 1 updated.";
} 
else if ( $TB_action == "Update Text 2" && $TB_admin )
{
    $TB_content = $_POST["tbcontent"];
	$fh = fopen("text/blob2.txt","w");
	fwrite($fh,$TB_content);
	fclose($fh);
	$alert_text .= "Text field 2 updated.";
}
	$textblob1 = stripslashes(file_get_contents("text/blob1.txt"));
	$textblob2 = stripslashes(file_get_contents("text/blob2.txt"));


//Look up Test ID
$TestID = sqlTest_getCurrentTest();
$action = get_post('action');

//1.0 Validate input & login
if ( isset($_POST['trig']) && $action == "Login")
{
	if ( $user == "" ) { $alert_text .= "You must supply a username.<br />"; }
	else if ( $pass == "" ) { $alert_text .= "You must supply a password.<br />"; }
	else {
		//Check to see if they are valid
		if ( !log_in($user,$pass) ) { $alert_text .= "Invalid username or password."; } 
		else {
			//1.1 REgular User
			if ( $_SESSION['level'] == 1  && $TestID != "") { 
				//Start Exam
				header("Location:show_tests.php?TestTID=$TestTID&TestID=$TestID"); exit;
			}

			//1.2 Admin
			if ( $_SESSION['level'] == 2 ) {
				//Clean out any incomplete tests before logging in

				$query = "DELETE FROM `Account_answer` WHERE `TestTID` IN (SELECT `ID` FROM `Test_Taken` WHERE `Completed`='0' AND `AccountID` IN (SELECT `ID` FROM `Account` WHERE `Expire`<'".time()."'));";
				$query .= "DELETE FROM `Test_taken` WHERE `AccountID` IN (SELECT `ID` FROM `Account` WHERE `Expire`<'".time()."') AND `Completed`='0'";
				mysql_get($query);
				

				header("Location:view_volunteers.php"); exit;
			}
		}
	}
}


//-----------------------------------------------------------

	//2.0 Main Content
	print_start_to_navbar("Secchi Recertification");

	print_navbar_to_content();
	
		delimiter_start();

				//Give a warning if no test is currently available
				if ( $TestID == "" ) 
				{
					Alert("Notice","No tests are currently available to take.");
				}
				if ( $alert_text != "" ) { Alert("Notice",$alert_text); }

			delimit(); //2.1 Login and retrieve password
		
				if ( $action != "Password/Email Help" )
				{
					//Send password email
					if ( $action == "Request Password" )
					{
						$email = get_post('email');
						$result = mysql_get("SELECT `Username`,`Password`,`Name` FROM `Account` WHERE `Email`='$email'");
						$row = mysql_fetch_array($result);
						if ( $row )
						{

							$subject = "Account Password";
							$website = WEBSITE;
							$body = "Dear ".$row['Name'].",\n\nYour login information:\nLogin name: ".$row['Username']."\nPassword:  ".$row['Password']."\n\nThis is an automated message.  Do not reply.\n-Volunteer Lake Monitoring Program";
				
							$from = "From: VLMP_admin";
							if (!mail($email, $subject, $body,$from)) { $alert_text .= "Error sending email."; }
							else { Alert("Notice","Password sent to your email address.  Please allow some time for it to arrive."); }

						} else { Alert("Notice","That email address does not exist in the user lists."); }

					}

					//Update password
					if ( $action == "Change Password" )
					{
						$email = get_post('email');
						$pass0 = get_post('pass0');
						$pass1 = get_post('pass1');
						$pass2 = get_post('pass2');
						$pass_clean = super_cleantext($pass1);
						
						//Verify input
						$continue = true;
						if ( $pass0 == "" ) { $alert_text .= "You must supply your old password.<br />"; $continue = false;}
						if ( $pass1 == "" ) { $alert_text .= "You must supply a new password.<br />"; $continue = false;}
						if ( $pass2 == "" ) { $alert_text .= "You must verify the password.<br />"; $continue = false;}
						if ( $pass1 != $pass2 ) { $alert_text .= "The passwords do not match.<br />"; $continue = false;}
						if ( $pass1 != $pass_clean ) { $alert_text .= "The new password contains invalid symbols.  You can only use letters, numbers and underscores.<br />"; $continue = false;}
						if ( $pass1 != "" && (strlen($pass1) < 6 || strlen($pass1) > 16 )) { $alert_text .= "The new password length must be between 6 and 16 characters.<br />"; $continue = false;}
						if ( $email == "" ) { $alert_text .= "You must supply an email address.<br />"; $continue = false;}

						if ( $continue )
						{
							$query = "UPDATE `Account` SET `Password`='$pass1' WHERE `Email`='$email' AND `Password`='$pass0'";
							mysql_get($query);

							$query = "SELECT * FROM `Account` WHERE `Email`='$email' AND `Password`='$pass1'";
							$result2 = mysql_get($query);
							if ( $result2 == false ) { Alert("Uh oh!",$query); }
							
							if ( $row = mysql_fetch_array($result2) )
							{
	
								$subject = "Account Update";
								$website = WEBSITE;
								$body = "Dear ".$row['Name'].",\n\nYour new login information:\nLogin name: ".$row['Username']."\nPassword:  ".$row['Password']."\n\nThis is an automated message.  Do not reply.\n-Volunteer Lake Monitoring Program";
					
								$from = "From: VLMP_admin";
								if (!mail($email, $subject, $body,$from)) { $alert_text .= "Error sending email."; }
								else { Alert("Notice","Email sent.  Please allow some time for it to arrive."); }


	
							} else { Alert("Notice","That email address does not exist in the user lists, or the password is wrong."); }
						} else { Alert("Notice",$alert_text); }
					}

					//Update email
					if ( $action == "Change Email" )
					{
						$email = get_post('email');
						$email2 = get_post('email2');
						$pass0 = get_post('pass0');
						
						//Verify input
						$continue = true;
						if ( $pass0 == "" ) { $alert_text .= "You must supply your old password.<br />"; $continue = false;}
						if ( $email == "" ) { $alert_text .= "You must supply your current email address.<br />"; $continue = false;}
						if ( $email2 == "" ) { $alert_text .= "You must supply a new email address.<br />"; $continue = false;}

						$query = "SELECT * FROM `Account` WHERE `Email`='$email2'";
						$result = mysql_get($query);
						if ( mysql_fetch_array($result) != false ) { $alert_text .= "That email address is already being used.<br />"; $continue = false; }
		

						if ( $continue )
						{
							$query = "UPDATE `Account` SET `Email`='$email2' WHERE `Email`='$email' AND `Password`='$pass0'";
							mysql_get($query);

							$query = "SELECT * FROM `Account` WHERE `Email`='$email2' AND `Password`='$pass0'";
							$result2 = mysql_get($query);
							if ( $result2 == false ) { Alert("Uh oh!",$query); }
							
							if ( $row = mysql_fetch_array($result2) )
							{
	
								$subject = "Account Update";
								$website = WEBSITE;
								$body = "Dear ".$row['Name'].",\n\nYour new registered email address:".$row['Email']."\n\nThis is an automated message.  Do not reply.\n-Volunteer Lake Monitoring Program";
					
								$from = "From: VLMP_admin";
								if (!mail($email2, $subject, $body,$from)) { $alert_text .= "Error sending email."; }
								else { Alert("Notice","Email updated.  If the automated reply does not reach the new address soon, contact an administrator."); }
	
								//Warn about hotmail/yahoo spam filters
								if ( preg_match("/(hotmail|yahoo)/i",$email2,$matches)) { Alert("Warning", "The email provider '".strtoupper($matches[1])."' has been detected. <br /><font color='red'>Make sure VLMP messages aren't redirected to your Junk or Spam folder.</font><br /><br />"); }

								//Notify admins
								$account = $row['Username'];
								$name = $row['Name'];
								$query = "SELECT * FROM `Account` WHERE `Privileges`='a' AND `Status`='a'";
								$result = mysql_get($query);
								while($row2 = mysql_fetch_array($result)) {
										$subject = "Volunteer Account Update";
										$website = WEBSITE;
										$body = "Dear ".$row2['Name'].",\n\n$account ($name) has modified his/her email address:\nNew Email: $email2\n\nThis is an automated message.  Do not reply.\n-Volunteer Lake Monitoring Program";
							
										$from = "From: VLMP_admin";
										if (!mail($row2['Email'], $subject, $body,$from)) { $alert_text .= "Error sending email."; }
								}

							} else { 
								$Alert("Notice","That email address does not exist in the user lists, or the password is wrong."); 
							}
						} else { Alert("Notice",$alert_text); }
					}

					delimit();

						//Text field 1
						if ( $TB_admin )
						{
							echo "<br /><center>".newlines($textblob1) . "</center><br /><center><form action='' method='POST'><textarea cols='70' rows='10' name='tbcontent'>$textblob1</textarea><br />\n";
							echo form_submit_name("Update Text 1","tbaction")."</form></center>";
						} else { echo "<br /><center>".newlines($textblob1) . "</center>"; }
	
					delimit();

						//Log in
						echo "<form action='index.php' method='POST' >\n".form_hidden("trig","boo");
						echo "<table bgcolor='#336799' style='border: solid thick #000044;color: #ffffff;' align='center'>\n";
						echo "<th colspan='2'>Certified Monitor Login</th>\n";
						echo "<tr><td align='left'>Username </td><td align='left'><input type='text' name='user' /></td></tr>\n";
						echo "<tr><td align='left'>Password: </td><td align='left'><input type='password' name='pass' /></td></tr>\n";
						echo "<tr><td><input type='submit' value='Login' name='action' /></td><td align='center'><input type='submit' value='Password/Email Help' name='action' /></td></tr>\n";	
						echo "</table></form>\n";

					delimit();

						//Text field 1
						if ( $TB_admin )
						{
							echo "<br /><center>".newlines($textblob2) . "</center><br /><center><form action='' method='POST'><textarea cols='70' rows='10' name='tbcontent'>$textblob2</textarea><br />\n";
							echo form_submit_name("Update Text 2","tbaction")."</form></center>";
						} else { echo "<br /><center>".newlines($textblob2) . "</center>"; }

					delimit(); //2.2 Go to secchi disk
		
						//Secchi simulator lnk
						echo "<form action='secchi-disk_v2/index.html' method='POST'>\n";
						echo "<table bgcolor='#336799' style='border: solid thick #000044;color: #ffffff;' align='center'>\n";
						echo "<th colspan='1'>Secchi Disk Simulator</th>\n";
						echo "<tr><td align='left'>".form_submit("Try It Out!")."</td></tr>\n";
						echo "</table></form>\n";


				} else {

						echo "<form action='' method='POST' >\n".form_hidden("trig","boo");
						echo "<table bgcolor='#336799' style='border: solid thick #000044;color: #ffffff;' align='center' width='60%'>\n";
						echo "<th colspan='2'><b>Request Your Password</b><br /><font style='font-size:11px;'>Supply the email address of your account in this system.</font></th>\n";
						echo "<tr><td align='left'>Email: </td><td align='left'><input type='text' name='email' size='40' /></td></tr>\n";
						echo "<tr><td></td><td><input type='submit' value='Request Password' name='action' />&nbsp;<input type='submit' value='Cancel' name='action' /></td></tr>\n";	
						echo "</table></form>\n";

						echo "<form action='' method='POST' >\n".form_hidden("trig","boo");
						echo "<table bgcolor='#336799' style='border: solid thick #000044;color: #ffffff;' align='center' width='60%'>\n";
						echo "<th colspan='2'><b>To Change Password</b><br /><font style='font-size:11px;'>Supply the email address of your account in this system, old password, and new.</font></th>\n";
						echo "<tr><td align='left' width='100'>Email: </td><td align='left'><input type='text' name='email' size='40' /></td></tr>\n";
						echo "<tr><td align='left' width='100'>Old Password: </td><td align='left'><input type='password' name='pass0' size='20' /></td></tr>\n";
						echo "<tr><td align='left' width='100'>New Password: </td><td align='left'><input type='password' name='pass1' size='20' /></td></tr>\n";
						echo "<tr><td align='left' width='100'>Verify New Password: </td><td align='left'><input type='password' name='pass2' size='20' /></td></tr>\n";
						echo "<tr><td width='100'></td><td><input type='submit' value='Change Password' name='action' />&nbsp;<input type='submit' value='Cancel' name='action' /></td></tr>\n";	
						echo "</table></form>\n";

						echo "<form action='' method='POST' >\n".form_hidden("trig","boo");
						echo "<table bgcolor='#336799' style='border: solid thick #000044;color: #ffffff;' align='center' width='60%'>\n";
						echo "<th colspan='2'><b>Change Email</b><br /><font style='font-size:11px;'>Enter the old email address of your account, your new email address, your password.</font></th>\n";
						echo "<tr><td align='left'>Email: </td><td align='left'><input type='text' name='email' size='40' /></td></tr>\n";
						echo "<tr><td align='left'>New Email: </td><td align='left'><input type='text' name='email2' size='40' /></td></tr>\n";
						echo "<tr><td align='left'>Password: </td><td align='left'><input type='password' name='pass0' size='20' /></td></tr>\n";
						echo "<tr><td></td><td><input type='submit' value='Change Email' name='action' />&nbsp;<input type='submit' value='Cancel' name='action' /></td></tr>\n";	
						echo "</table></form>\n";

						echo "<table bgcolor='#336799' style='border: solid thick #000044;color: #ffffff;' align='center' width='60%'>\n";
						echo "<th colspan='2'><b>Need Additional Help?</b><br /><font style='font-size:11px;'>Email <a href='mailto:vlmp@mainevlmp.org'>VLMP@MaineVLMP.ORG</a> for assistance.</font></th>\n";
						echo "</table>\n";
				}

		
		delimiter_end();
	
	print_content_to_end();
?>