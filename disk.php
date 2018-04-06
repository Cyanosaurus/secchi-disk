<?php
	include_once("libraries.php");
	
//Process incoming actions ----------------------------------

$alert_text = "";				//Message User, if Any

//Process incoming
	$number_attempts = get_post('number_attempts'); //this isn't currently handled in Flash
	$measured_values = get_post('measured_value'); 
		//Process values
		$values = explode("_",$measured_values);
		foreach($values as $val)
		{
			$val = str_replace("_","",$val);
			if ( $val != "" ) { $measured_value = $val; }
		}

	$targetDepth = get_post('targetDepth');
	$TestID = get_post("TestID");
	$AccountID = $_SESSION["AccountID"];

    if ( isset($_GET['gosmallvers'])) { 
        if ( $_GET['gosmallvers'] == "yes" ) {
            $_SESSION['__smallvers'] = true; 
        } else if ($_GET['gosmallvers'] == "no") {
            $_SESSION['__smallvers'] = false; 
        }
    } else if ( !isset($_SESSION['__smallvers']) ) { 
        $_SESSION['__smallvers'] = false; 
    }


	if( ($currentType = get_post("ltype")) == "" ) { $currentType=get_post("intype"); }

	//Look up lake data
		$query = "SELECT * FROM `Lake_type` ORDER BY `ID` ASC";
		$results = mysql_get($query);

		while($row = mysql_fetch_array($results))
		{
			if ( $currentType == $row['ID'] ) {
				 $currentTypeName = $row['Name'];
				 $tolerance = $row['Tolerance']; 
				 $bgcolor = "bgcolor='#777777'";
				 $lakeColor = "0x".substr($row['Color'],1,6);
			} 
		}
		
	$buttonname = "Switch Lake Type";
	if ( isset($_POST['measured_value']) && $_SESSION['level'] == 1 )
	{
		$query = "SELECT `ID` FROM `Account` WHERE `Login_key`='".$_SESSION['sekey']."' AND `ID`='$AccountID'";
		$result = mysql_get($query);			

		if ( $row = mysql_fetch_array($result) )
		{
			//Look up person's ID number
			foreach($values as $val)
			{
				$val = str_replace("_","",$val);
				if ( $val != "" ) { 
					$query = "INSERT INTO `Reading` (`TestID`,`AccountID`,`Reading`,`Generated`,`Lake_type`,`Attempt`) VALUES ('$TestID','$AccountID','$val','$targetDepth','$currentType','1')"; 
					$result = mysql_get($query);
				}
			}
			$alert_text .= "(<font color='#ccccff' style='font-size:14px;'>$alert_text</font>)";
			$buttonname = "Try again";
		}
	}
	
//-----------------------------------------------------------

	echo "<html><head><title>Secchi Disk Reading Simulator</title></head><body bgcolor='#000000' style='color: #ffffff;font-family:arial;'>\n";
			
			//Show selector & home

			echo "<table align='center'><tr>";
			if ( $_SESSION['level'] == 1 ) 
			{
				echo "<td><form action='show_tests.php'>".form_submit("Home")."</form></td>";
			} else if ( $_SESSION['level'] == 0 ) {
				echo "<td><form action='index.php'>".form_submit("Home")."</form></td>";
			} else if ( $_SESSION['level'] == 2 ) {
				echo "<td><form action='view_volunteers.php'>".form_submit("Home")."</form></td>";
			}

			if ( $_SESSION['level'] >= 1 ) 
			{
				echo "<td><form action='logout.php'>".form_submit("Logout")."</form></td>";
			}

			//Get lake type
			if ( $currentType == "" ||  (isset($_POST['measured_value']))) {
				echo "</tr></table>\n";

				if ( $_SESSION['level']==1 )
				{
					if (  isset($_POST['measured_value']) )
					{
						if ( $measured_value >= $targetDepth - $tolerance && $measured_value <= $targetDepth + $tolerance ) 
						{ 
							$NOTE = "You have completed the test and your results have been submitted to the VLMP.<br /> Your Secchi reading qualifies for re-certification.  For details see your results below.  To take another Secchi reading click the Take Reading button below.";
						} else { 
							$NOTE = "You have completed the test and your results have been submitted to the VLMP.<br /> Your Secchi reading <font color='red'>does not qualify</font> for re-certification.  For details see your results below.  To take another Secchi reading click the Take Reading button below.";
						}
					} else {
						$NOTE = 'Volunteers need to take only one qualifying reading for re-certification.  Please select a lake type that is most similar to the lake that you monitor.  (If you are not sure what lake is closest to the one you monitor please select the Clear Lake.)';
					}
				} else { $NOTE = ""; }


				if ( $NOTE == "" ) { $NOTE0 = "Click 'Take Reading' to try one of the Lake Types."; } else { $NOTE0 = ""; }
				echo "<center><strong><font style='font-size:20px'>Secchi Reading Simulator</font></strong></center><br />\n";
				echo "<center><font style='font-size:12px'>$NOTE0<br /><b>$NOTE</b></font></center><br />\n";
				echo "<table width='90%'  align='center'><tr><td width='100%' align='right'>";

				echo "<table width='100%'><tr valign='top'><td>";
				//display results
				if (  isset($_POST['measured_value'])  )
				{


					if ( $measured_value >= $targetDepth - $tolerance && $measured_value <= $targetDepth + $tolerance ) { $procTolerance = "Yes"; } else { $procTolerance = "<font color='#FF8888'>No</font>"; }
					echo "<br /><table align='right' style='background: #111111;font-size:14px;' width='100%' cellpadding='4' cellspacing='0' align='center'>\n";
					echo "<tr><td class='data_bold' width='10%' style='border-bottom:solid thick #444444;'>Reading&nbsp;Results</td><td style='border-bottom:solid thick #444444;'>&nbsp;</td></tr>\n";
					echo "<tr><td style='border-right:solid thin #444444;' align='right' width='10%'>Lake&nbsp;Type</td><td style='color: #cccccc;' align='left'><i>".$currentTypeName."</i></td></tr>\n";
					echo "<tr><td style='border-right:solid thin #444444;' align='right' width='10%'>Actual&nbsp;Target Value</td><td style='color: #cccccc;' align='left'><i>".padme(round($targetDepth*100)/100)." (&plusmn;".padme(round($tolerance*100)/100).") meters</i></td></tr>\n";
					echo "<tr><td style='border-right:solid thin #444444;' align='right' width='10%'>Measured&nbsp;Value</td><td style='color: #cccccc;' align='left'><i>".padme(round($measured_value*100)/100)." meters</i></td></tr>\n";
					echo "<tr><td style='border-right:solid thin #444444;' align='right' width='10%'>Error&nbsp;(absolute)</td><td style='color: #cccccc;' align='left'><i>".padme(round(100*abs($targetDepth-$measured_value))/100)." meters</i></td></tr>\n";
					echo "<tr><td style='border-right:solid thin #444444;' align='right' width='10%'>Error&nbsp;(relative)</td><td style='color: #cccccc;' align='left'><i>".padme(round(abs(1000*($targetDepth-$measured_value)/$targetDepth))/10)."%</i></td></tr>\n";
					echo "<tr><td style='border-right:solid thin #444444;' align='right' width='10%'>Within&nbsp;Tolerance?</td><td style='color: #cccccc;' align='left'><i>$procTolerance</i></td></tr>\n";
					echo "</table>";
				}

				echo "</td></tr><tr><td width='0'><table align='right' width='100%' height='300'  align='center'><tr><td><table align='right' style='background: #111111; font-size:14px;' width='100%' height='300' cellspacing='0' cellpadding='5'>\n";
					echo "<tr><td style='border-bottom:solid thick #555555;' width='0'>&nbsp;</td><td class='data_bold' style='border-bottom:solid thick #555555;' width='0'>Lake Type</td><td style='border-bottom:solid thick #555555;' width='100%'>Description</td></tr>\n";
		
					$query = "SELECT * FROM `Lake_type` ORDER BY `ID` ASC";
					$results = mysql_get($query);
		
					while($row = mysql_fetch_array($results))
					{
						if ( $currentType == $row['ID'] ) {
							 $currentTypeName = $row['Name'];
							 $tolerance = $row['Tolerance']; 
							 $bgcolor = "bgcolor='#777777'";
							 $lakeColor = "0x".substr($row['Color'],1,6);
						} else { $bgcolor = ""; }
						$forms = "<form action = '' method='post'>".form_hidden("ltype",$row['ID']).form_submit("Take Reading")."</form>";

						//Normal/Small version selector
			
						echo "<tr $bgcolor><td style='border-right:solid thin #555555;' width='0'>$forms</td><td class='data_bold' width='0'>".str_replace(" ","&nbsp;",$row['Name'])."</td><td width='100%'>".newlines($row['Description'])."</td></tr>\n";
					}
					if ( !$_SESSION['__smallvers']) { $forms2 = "<span>Simulator Size: </span><span><input type='radio' checked='checked' name='myrad' />Normal</span><span><input type='radio' onClick=\"window.location='disk.php?gosmallvers=yes';\"  onChange=\"window.location='disk.php?gosmallvers=yes';\" name='myrad' />Smaller</span>"; }
					else {$forms2 = "<b>Simulator Size: </b><i><input type='radio' onClick=\"window.location='disk.php?gosmallvers=no';\" onChange=\"window.location='disk.php?gosmallvers=no';\" name='myrad' />Normal</span><span><input type='radio' checked='checked' name='myrad' />Smaller</i>";  }
					$forms2 .= "<br/><font style='font-size:12px;'>Note: Smaller animation size may improve performance on older computers.</font>";
				echo "</table></td></tr></table></tr></td></table><br />$forms2</td><td width='0'>\n";
				
				//display results
				if (  isset($_POST['measured_value'])  )
				{
							$flashfile = "diskresult_0.1.swf";
					$fit = 'noborder';
					$resX = 150;
					$resY = 600;
					$currentDepth=$measured_value;
					
					echo "<table height='$resY' width='$resX' valign='top'><tr><td>
						<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0\" width=\"$resX\" height=\"$resY\" id=\"$flashfile\">
						<param name=\"FlashVars\" value=\"lakeColor_in=$lakeColor&targetDepth_in=$targetDepth&currentDepth_in=$currentDepth&tolerance_in=$tolerance\" />
						<param name=\"allowScriptAccess\" value=\"sameDomain\" /><param name=\"scale\" value=\"$fit\" />
						<param name=\"movie\" value=\"$flashfile\" /><param name=\"quality\" value=\"high\" /><param name=\"bgcolor\" value=\"#000000\" /><embed src=\"$flashfile\" FlashVars='lakeColor_in=$lakeColor&targetDepth_in=$targetDepth&currentDepth_in=$currentDepth&tolerance_in=$tolerance' quality=\"high\" bgcolor=\"#000000\" width=\"$resX\" height=\"$resY\" name=\"$flashfile\" scale='$fit' align=\"middle\" allowScriptAccess=\"sameDomain\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />
						</object></td></tr></table>";

				} 
				echo "</td></tr></table>";
			} else {

				echo "<td><form action=''>".form_submit("Try Another Laketype")."</form></td>";
			echo "</tr></table>\n";

				//Look up type data
				$query = "SELECT * FROM `Lake_type` WHERE `ID`='$currentType'";
				$result = mysql_get($query);
				$row = mysql_fetch_array($result);
				if ( $row != false )
				{
	
					$lakeType = $row['ID'];
					$lakeColor = "0x".substr($row['Color'],1,6);
					$lakeGlimmer = ($row['Range_low']>=5.0)*15;

					$targetDepth = rand($row['Range_low']*100,$row['Range_high']*100)/100;
					$tolerance = $row['Tolerance'];
	
					//Look up test and account data
					$query = "SELECT `ID` FROM `Account` WHERE `Login_key`='".$_SESSION['sekey']."'";
					$result2 = mysql_get($query);
					$temp = mysql_fetch_array($result2);
					$AccID = $temp['ID'];
	
					$query = "SELECT `ID` FROM `Test` WHERE `Current`='1'";
					$result2 = mysql_get($query);
					$temp = mysql_fetch_array($result2);
					$TestID = $temp['ID'];
	
					$flashfile = "disk_2.17.swf";
					$fit = 'exactfit';
					if ( !$_SESSION['__smallvers'] ) {
						$resX = round(800*0.90);
						$resY = round(569*0.90);
						$quality = 'high';
					}
					else {
						$resX = round(800*0.60);
						$resY = round(569*0.60);
						$quality = 'medium';
					}
					$content = "<table height='$resY' width='$resX'><tr><td>
						<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0\" width=\"100%\" height=\"100%\" id=\"$flashfile\">
						<param name=\"FlashVars\" value=\"lakeColor_in=$lakeColor&lakeType_in=$lakeType&targetDepth_in=$targetDepth&lakeGlimmer_in=$lakeGlimmer&tolerance_in=$tolerance&TestID_in=$TestID&AccountID_in=$AccID\" />
						<param name=\"allowScriptAccess\" value=\"sameDomain\" /><param name=\"scale\" value=\"$fit\" />
						<param name=\"movie\" value=\"$flashfile\" /><param name=\"quality\" value=\"$quality\" /><param name=\"bgcolor\" value=\"#000000\" /><embed src=\"$flashfile\" FlashVars='lakeColor_in=$lakeColor&lakeType_in=$lakeType&targetDepth_in=$targetDepth&lakeGlimmer_in=$lakeGlimmer&tolerance_in=$tolerance&TestID_in=$TestID&AccountID_in=$AccID' quality=\"$quality\" bgcolor=\"#000000\" width=\"100%\" height=\"100%\" name=\"$flashfile\" scale='$fit' align=\"middle\" allowScriptAccess=\"sameDomain\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />
						</object></td></tr></table>";

					echo "<center><font style='color:#777777;font-size:20px;'>".$row['Name']." Lake</font></center>\n";
					echo "<center>$content</center><br />";
				}
			}


	echo "</body></head>\n";

?> 