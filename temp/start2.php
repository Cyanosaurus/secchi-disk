<?php

	include_once("../libraries.php");


	$result = mysql_get("SELECT * FROM 'Account' WHERE 'Username'='admin' OR `Username`='bob17'");
	
	while($row = mysql_fetch_array($result))
	{
echo $row['Username'] . "<br />";
			$subject = "VLMP Launches Virtual Secchi Re-Certification";

			$body = <<<HTML
$row['Email'] ($row['Fullname']),

Your Virtual Secchi Re-Certification Login Information
Username:
Password:
 
Link to site: www.mainevolunteerlakemonitors.org/recertify

 
Welcome to the Maine Volunteer Lake Monitoring Program’s online workshop for Virtual Secchi Re-Certification.  Certified Water Quality Monitors can log-in to test their knowledge of Secchi disk procedures and take a Secchi reading on a virtual lake.  To login, please use your individual username and password included at the top of this message.  The site can be accessed by clicking the link on the VLMP home page or directly at www.mainevolunteerlakemonitors.org/recertify. 

 
The Virtual Secchi Re-Certification site will supplement the field re-certification workshops for Secchi monitors.  After an evaluation period we hope this new site will allow us to extend the time between field re-certification.  The goals are to improve QA/QC, allow more flexibility in timing of Secchi re-certifications and to reduce travel costs for volunteers and the program.
 
We look forward to your feedback.  Please visit the site, take a few readings, answer a few questions then share your comments and suggestions with us by phone at 207-783-7733 or by email at vlmp@mainevlmp.org.

 
There is also an option for the public to take a demonstration Secchi reading by clicking on the “Try It Out” button.  Certified monitors must login however in order for the full test version and to have their results submitted to the VLMP.
 
The Virtual Secchi Re-Certification site is made possible by support from the Maine Department of Environmental Protection and individual donors to the VLMP.  The magic behind the site was designed and programmed by ASAP Media Services at the University of Maine.  
 
 
 
Jim Entwood
VLMP Program Coordinator
 
Maine Volunteer Lake Monitoring Program

24 Maple Hill Rd, Auburn, ME 04210
 
vlmp@mainevlmp.org
www.MaineVolunteerLakeMonitors.org 
207-783-7733
HTML;
			$from = "From: vlmp@mainevlmp.org";
			if (!mail($row['Email'], $subject, $body, $from)) { $alert_text .= "Error sending email."; }
			echo "Sent {$row['Email']}<br />";

			$timeend = time() + 1;
			while(time() < $timeend) {}

	}
echo "Done.";
?>