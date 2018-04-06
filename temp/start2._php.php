<?php

	//include_once("libraries.php");


	$emails = Array('jasper.turcotte@umit.maine.edu','jasper_turcotte@umit.maine.edu');

	//Send password email
foreach($emails as $email)
	{
			$subject = "Account Password (Test)";
			$website = WEBSITE;
	
			$words = Array("Heavy.","Bored.","Chicken.");

			$body = "Dear ".$email.",\n\nThe Secchi Online Re-Certification system is now ".rand(0,1000000)." and ".$words[rand(0,3)];		
			$from = "From: vlmp@mainevlmp.org";
			if (!mail($email, $subject, $body, $from)) { $alert_text .= "Error sending email."; }
			echo "Sent $email<br />";

			$timeend = time() + 1;
			while(time() < $timeend) {}

	}
echo "Done.";
?>