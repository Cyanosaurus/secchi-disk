<?php  //MASTERPAGE DECLARATIONS
session_start();

header ('Expires: Tue, 9 Apr 1982 01:00:00 GMT');
header ('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header ('Cache-Control: no-cache, must-revalidate');
header ('Pragma: no-cache');

include_once("lib_functions_login.php");
check_level();

if ( false )
{
	$txt = "";
	foreach($_SESSION as $key=>$value) { $txt .= "_SESSION[$key]=$value<br />"; }
	echo "<br /><br />";
	foreach($_POST as $key=>$value) { $txt .= "_POST[$key]=$value<br />"; }
	echo "<br /><br />";
	foreach($_GET as $key=>$value) { $txt .= "_GET[$key]=$value<br />"; }
	Alert('$_SESSION',$txt);
}

/* File Index

-- Secchi Specific
(void) print_start_to_navbar($title)
(void) print_navbar_to_content()
(void) print_content_to_end()

*/
//Echos everything from the html/body tags to the beginning of the navbar section
function print_start_to_navbar ($title)
{
	echo "<html>
<head>
	<title>$title</title>
	<link rel='stylesheet' type='text/css' href='secchi.css'/>

</head>
<body onLoad=''>

<table width =' 700' height ='500' border='0' align='center' valign='center'>
	<tr><td>
			<table cellspacing =' 0' cellpadding =' 0' align ='center' height ='100%' width='100%' >
				<tr height='0'><td>
					<table width='100%' height='0' cellspacing='0' cellpadding='0' border='0'>
						<tr height ='0'><td align='center' width='0'><img border='0' style='padding:0;margin:0;' src =' interface/home_header_WB_left.gif' alt='Volunteer Lake Minitoring Program header' /></td><td height =' 10' align='center' width='100%' background=' interface/home_header_WB_mid.gif'></td><td height =' 10' align='right' width='0'><img src =' interface/home_header_WB_right.gif' alt='border image'/></td></tr>
					</table>
				</td></tr>
				<tr height='100%'>

					<td>
							<table align ='center' width='100%' height='100%' cellspacing =' 0' cellpadding =' 0' border='0' >
								<tr height='0'> 
									<td width='0'>
										<table align ='center' width='100%' height='100%' cellspacing =' 0' cellpadding =' 0' border='0'>
											<tr height='100%'><td width='0'>
												
												<table align ='center' width='100%' height='100%' cellspacing =' 0' cellpadding =' 0'border='0'>
												<tr height='100%' valign='top'>
													<td width='0' background='interface/navbar_mid_left.gif'><img src='interface/navbar_mid_left.gif'  alt='Border Image'/></td>
													<td width='100%' background='interface/navbar_mid_mid.gif' bgcolor='white' align='center'>
													<div class='nav'>
<!-- Navbar stuff here ------------------------------------------------------------------------------------------------ -->
\n\n";
	preg_match("/.*?\/([a-zA-Z0-9._]+\.php)/i",$_SERVER['PHP_SELF'],$matches);
	$thispage = $matches[1];
	if ( $_SESSION['level'] == 2 )
	{
			if ( $thispage != 'index.php' ) { echo "<a href='index.php'>Front&nbsp;Page</a>"; } else { echo "<font style='padding:3;'><b>Front&nbsp;Page</b></font>"; }
			if ( $thispage != 'create_account.php' ) { echo "<a href='create_account.php'>Create&nbsp;Account</a>"; } else { echo "<font style='padding:3;'><b>Create&nbsp;Account</b></font>"; }
			if ( $thispage != 'view_volunteers.php' ) { echo "<a href='view_volunteers.php'>View&nbsp;Volunteers</a>"; } else { echo "<font style='padding:3;'><b>View&nbsp;Volunteers</b></font>"; }
			if ( $thispage != 'records.php' ) { echo "<a href='records.php'>Volunteer&nbsp;Records</a>"; } else { echo "<font style='padding:3;'><b>Volunteer&nbsp;Records</b></font>"; }
			if ( $thispage != 'test_manager.php' ) { echo "<a href='test_manager.php'>Manage&nbsp;Tests</a>"; } else { echo "<font style='padding:3;'><b>Manage&nbsp;Tests</b></font>"; }
			if ( $thispage != 'manage_images.php' ) { echo "<a href='manage_images.php'>Manage&nbsp;Images</a>"; } else { echo "<font style='padding:3;'><b>Manage&nbsp;Images</b></font>"; }
			if ( $thispage != 'laketype_manager.php' ) { echo "<a href='laketype_manager.php'>Manage&nbsp;LakeTypes</a>"; } else { echo "<font style='padding:3;'><b>Manage&nbsp;LakeTypes</b></font>"; }
	}
	if ( $_SESSION['level'] == 1 ) 
	{
		if ( $thispage != 'show_tests.php' ) { echo "<br /><a href='show_tests.php'>Home</a>"; }
	} else if ( $_SESSION['level'] == 0 ) {
		if ( $thispage != 'index.php' ) { echo "<br /><a href='index.php'>Home</a>"; } else { echo "<a href='http://www.mainevolunteerlakemonitors.org/'>Maine&nbsp;VLMP&nbsp;Home</a>"; }
	}
	if ( $_SESSION['level'] >= 1 ) 
	{
		echo "<a href='logout.php'><b>Logout</b></a>";
	}
}
//Echos the code from the navbar to the beginning of the page content section
function print_navbar_to_content () 
{

	echo "\n\n
<!-- End Navbar stuff ------------------------------------------------------------------------------------------------ -->
													</div>
													</td>
													<td width='0' background='interface/navbar_mid_right.gif' bgcolor='white'><img src='interface/navbar_mid_right.gif'  alt='Border Image'/></td>
												</tr>

												<tr height='0'>
													<td width='0'><img src='interface/navbar_bot_left.gif' alt='Border Image'/></td><td width='100%' background='interface/navbar_bot_mid.gif' bgcolor='white'></td><td width='0' bgcolor='white'><img src='interface/navbar_bot_right.gif' /></td>
												</tr>
												</table>

											</td></tr>
										</table>
									</td></tr>
									<tr height='100%'>
										<td width='100%'>
										<table align ='center' width='100%' height='100%' cellspacing =' 0' cellpadding =' 0' border='0'>
										
										<td width='0' background='interface/home_body_WB_left.gif'><img src='interface/home_body_WB_left.gif'  alt='Border Image'/></td>
										<td height =' 10' align='center' width='100%' background='interface/home_body_WB_mid.gif'>
<!-- stuff goes here ---------------------------------------------------------------------------------------------------------------------->
\n\n";
}


//Prints everything from the end of the content section to the end of the page
function print_content_to_end ()
{
	echo "\n\n
<!-- stuff goes here ---------------------------------------------------------------------------------------------------------------------->
									</td>
							
									<td height =' 10' align='right' width='0' background=' interface/home_body_WB_right.gif'><img src =' interface/home_body_WB_right.gif'  alt='Border Image'/></td>
									</tr>
								</table>
								</td>
								</tr>
							</table>
					</td>
				</tr>
				<tr height='0'><td>
					<table width='100%' height='0' cellspacing='0' cellpadding='0' border='0'>
						<tr height =' 10'><td  align='center' width='0'><img src =' interface/home_footer_WB_left.gif' alt='Border Image' /></td><td height =' 10' align='center' width='100%' background=' interface/home_footer_WB_mid.gif'></td><td height =' 10' align='center' width='0'><img src =' interface/home_footer_WB_right.gif' alt='border image'/></td></tr>
					</table>
				</td></tr>
			</table>
	</td></tr>
</table>
		<center><font color='#999999' style='font-size:10px;'>Avoid using your browser's BACK button, and use the links or forms instead.<br />© 2007 Maine Volunteer Lake Monitoring Program</font></center>

</body>
";

}

?>