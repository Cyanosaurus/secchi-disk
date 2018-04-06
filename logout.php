<?php
/* index.php
   Minimum Access Level: Public (0)
   Date: 5/29/07
   Created by: Jasper Turcotte
   Modifiers:
		<name> on <date>
   Purpose: Clears the user's session login stuff, and goes to front page

   Incoming variables:
	* N/A

   Code Map:
	-1.0 Clear content and redirect

   Main Outgoing Variables 
	* N/A
	
*/


	include_once("libraries.php");

	//1.0 Clear content and redirect
	$_SESSION['level'] = 0;
	$_SESSION['sekey'] = "";
	$_SESSION['AccountID'] = "";

	header("Location: index.php"); exit;

	
?>