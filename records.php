<?php
/* records.php
   Minimum Access Level: Admin(2)
   Date: 6/11/07
   Created by: Jasper Turcotte
   Modifiers:
		<name> on <date>
		Jacob Landry on 5/27/08 (bug in file download part)
   Purpose: Downloadable file summaries of tests

   Incoming variables:
	* None

   Code Map:


   Main Outgoing Variables 
	-> Null
		* None

*/

	include_once("libraries.php");
	
//Process incoming actions ----------------------------------

if ( $_SESSION['level'] < 2 ) { header("Location: index.php"); exit; }

function EXPLO($arry,$level)
{
	$tmp = "";
	for($i = 0; $i < $level-1; $i++) { $tmp .= "&nbsp;&nbsp;&nbsp;&nbsp;"; }
	if ( is_array($arry) )
	{
		echo $tmp . "[<br />";
		foreach($arry as $branch)
		{
			echo $tmp . "&nbsp;&nbsp;&nbsp;&nbsp;";
			EXPLO($branch,$level+1);
			echo"<br />";
		}
		echo $tmp . "]<br />";
	} else {
		echo $arry;
	}
}


//User list paging
$numPerPage = 10;
page_action("records");
$PAGE_r = get_page('records');
page_action("tests");
$PAGE_t = get_page('tests');

$action = get_post('action');
$alert_text = "";				//Message User, if Any
$target = super_cleantext(get_post('target'));

if ( $action == "Generate Report" ) 
{
	
	if ( isTestLocked($target) && isTestLocked_Taken($target) && !sqlTest_isReported($target))
	{
		
		//Step 0 - Look up test info
		$result0 = sqlTest_getTest($target);
		$row0 = mysql_fetch_array($result0);
	
		//Step 1 - look up any tests taken from the test in question
		$result1 = sqlTest_getTestTaken($target);
		$outfile = "";
		
		while($row1 = mysql_fetch_array($result1))
		{
			//Step 2 - look up secchi readings (4 most recent)
			/*
				Lake_type:
				00 - ID
				01 - Name
				02 - Range_low
				03 - Range_high
				04 - Tolerance
				05 - Description
				06 - Color
				Reading:
				07 - Id
				08 - TestID
				09 - AccountId
				10 - Reading
				11 - Generated
				12 - Lake_type
				13 - Attempt
				Test:
				14 - Version
				15 - Current
				16 - Date
			*/
			$result2 = sqlTest_getReadings($target,$row1['AccountID']);
			$readings = Array();	//Array of Array(generated,measured,diff)
			$readings2 = Array();	//List of $readingss
			$lasttype = Array(-1,-1.0);
			while($row2=mysql_fetch_row($result2))
			{
				if ( $lasttype[0] == -1 ) { $lasttype = Array($row2[12],$row2[11]); }

				if ( $lasttype[0] != $row2[12] || $lasttype[1] != $row2[11] )
				{
					$readings2[] = $readings;
					$readings = Array();
					$lasttype = Array($row2[12],$row2[11]);
				}
				$readings[] = Array($row2[11],$row2[10],$row2[11]-$row2[10]);


			}
			$readings2[] = $readings;
	
			//Step 3 - Look up Account Data
			$result2 = sqlTest_getAccount($row1['AccountID']);
			$row2 = mysql_fetch_array($result2);
	
			//Step 4 - Write line
				foreach($readings2 as $readings)
				{
					$mdate = convertdate($row1['Date']);
					$yr = substr($mdate,strlen($mdate)-4,4);
					$outfile .= implode("\t",Array($row2['Username'],$row2['Name'],"Virtual Test #".$row0['Version'],$mdate,$yr,$readings[0][0],$readings[0][1],$readings[0][2],$readings[1][0],$readings[1][1],$readings[1][2],$readings[2][0],$readings[2][1],$readings[2][2],$readings[3][0],$readings[3][1],$readings[3][2],"","")) . "\n";
				}	
		}
	
		//Write File
		$fh = fopen("reports/Report_VTest_".$row0['Version'].".txt","w+");
		fwrite($fh,$outfile);
		fclose($fh);
		$query = "INSERT INTO `Report_history` (`TestID`) VALUES ('$target')";
		//mysql_get($query);
		$alert_text .= "Report Generated.<br />That test can no longer be enabled.";
	} else { $alert_text .= "A Test must be disabled and it must have test history in order to be processed."; }

} else if ( $action == "Update Total Report" ) 
{
	$outfile = "";
	//Process tests already set
	$tests = mysql_get("SELECT * FROM `Report_history` ORDER BY `TestID` ASC");
	while ($test = mysql_fetch_array($tests)) {

		$target = $test['TestID'];
		//Step 0 - Look up test info
		$result0 = sqlTest_getTest($target);
		$row0 = mysql_fetch_array($result0);
		$takenlist = Array();
	
		//Step 1 - look up any tests taken from the test in question
		$result1 = sqlTest_getTestTaken($target);
		
		while($row1 = mysql_fetch_array($result1))
		{
				//Step 2 - look up secchi readings (last 4 laketypes)
				/*
					Lake_type:
					00 - ID
					01 - Name
					02 - Range_low
					03 - Range_high
					04 - Tolerance
					05 - Description
					06 - Color
					Reading:
					07 - Id
					08 - TestID
					09 - AccountId
					10 - Reading
					11 - Generated
					12 - Lake_type
					13 - Attempt
					Test:
					14 - Version
					15 - Current
					16 - Date
				*/
				$result2 = sqlTest_getReadings($target,$row1['AccountID']);
				$readings = Array();	//Array of Array(generated,measured,diff)
				$readings2 = Array();	//List of $readingss
				$lasttype = Array(-1,-1.0);
				while($row2=mysql_fetch_row($result2))
				{
					if ( $lasttype[0] == -1 ) { $lasttype = Array($row2[12],$row2[11]); }
	
					if ( $lasttype[0] != $row2[12] || $lasttype[1] != $row2[11] )
					{
						$readings2[] = $readings;
						$readings = Array();
						$lasttype = Array($row2[12],$row2[11]);
					}
					$readings[] = Array($row2[11],$row2[10],$row2[11]-$row2[10]);
	
				}
				$readings2[] = $readings;
		
				//Step 3 - Look up Account Data
				$result2 = sqlTest_getAccount($row1['AccountID']);
				$row2 = mysql_fetch_array($result2);
		
				//Step 4 - Write line
					foreach($readings2 as $readings)
					{
						$mdate = convertdate($row1['Date']);
						$yr = substr($mdate,strlen($mdate)-4,4);
						$outfile .= implode("\t",Array($row2['Username'],$row2['Name'],"Virtual Test #".$row0['Version'],$mdate,$yr,$readings[0][0],$readings[0][1],$readings[0][2],$readings[1][0],$readings[1][1],$readings[1][2],$readings[2][0],$readings[2][1],$readings[2][2],$readings[3][0],$readings[3][1],$readings[3][2],"","")) . "\n";
					}	
			} 
	}
	//Write File
	$fh = fopen("reports/Report_Total.txt","w");
	fwrite($fh,$outfile);
	fclose($fh);
	$alert_text .= "Report Updated.<br />";
}



//-----------------------------------------------------------

	//1.0 Main Content
	print_start_to_navbar("Volunteer Records");
	print_navbar_to_content();
	
		delimiter_start();
		
		delimit();

			if ( $alert_text != "" ) { Alert("Notice",$alert_text); $reset_continue_FLAG = true; }

		delimit();

				//display data
				table1_start("Tests");
				table1_header(Array("Test/0","Date/70%","<font color='red'>*</font>Report/30%"));
					$result = sqlTest_getTestsLimit($PAGE_t*$numPerPage,$numPerPage);

					$row_number = 0;
					while($row = mysql_fetch_array($result))
					{
						$reported = sqlTest_isReported($row['ID']) || file_exists("reports/Report_VTest_{$row['Version']}.txt");
						if ( $reported ) { $report = "[Report Generated]"; } else { 
							if ( isTestLocked($row['ID']) && isTestLocked_Taken($row['ID']) && sqlTest_getCurrentTest() != $row['ID']) {
								$report = "<form action='' method='POST' onsubmit=\"return confirm('Are you sure you want to generate a report? The test cannot be enabled after this point if you do.');\">".form_hidden("target",$row['ID']).form_submit_name("Generate Report","action")."</form>";
							} else {
								$report = "<font color='#999999'>[Test Not Ready]</font>";
							}
						}
						$line = Array("Version&nbsp;".$row['Version'],convertdate($row['Date']),$report);
						table1_row($line,$row_number);
						$row_number++;
					}
				table1_end_total($row_number);
				echo "		<center><font color='#999999' style='font-size:10px;'>* A test must be disabled and with test history before a report can be generated.</font></center>";
				echo "<br />\n";
				page_controls("tests",$numPerPage,sqlTest_numTests(),"");

		delimit(); //1.1 Generated Reports  

				//display data
				$cnt = 0;
				echo "<form action='' method='POST'>" . form_submit_name("Update Total Report","action") . "</form>";
				table1_start("Generated Reports");
				table1_header(Array("<font color='red'>*</font>File/0","Date/100%","Number&nbsp;of&nbsp;Entries/0"));
					$dh = opendir("reports");	
					$row_number = 0;
					while($file = readdir($dh))
					{
						if ( strpos($file,".txt") > 1 && $cnt >= $PAGE_r*$numPerPage && $cnt < ($PAGE_r*$numPerPage + $numPerPage)) {
							$line = Array("<a href='reports/$file' type = 'application/x-force-download' target='_blank'>$file</a>",date("m-d-Y",filemtime("reports/".$file)),count_newlines("reports/".$file));
							table1_row($line,$row_number);
							$row_number++;

							//$cnt++;
						}
						/*else {
							echo "File: ".$file."<BR>";
							echo "1: ".strpos($file,".txt")."<BR>";
							echo "2: ".$cnt."<BR>";
							echo "3: ".$PAGE_r*$numPerPage." = ".$PAGE_r." * ".$numPerPage."<BR>";
							echo "4: ".$cnt."<BR>5: ";
							echo $PAGE_r*$numPerPage + $numPerPage." = ".$PAGE_r." * ".$numPerPage. " + ".$numPerPage."<BR><BR>";
						}*/
						//$cnt++;
					}
				table1_end_total($row_number);
				echo "		<center><font color='#999999' style='font-size:10px;'>* To download file, right-click the link and select either 'Save Link As...' or 'Save Target As...'</font></center>";
				echo "<br />\n";
				page_controls("records",$numPerPage,$cnt-2,"");

		delimiter_end();
	
	print_content_to_end();
?>