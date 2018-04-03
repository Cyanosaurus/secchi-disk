<?php

	include_once("libraries.php");

	$text = file_get_contents("input.csv");
	$fh = explode("\r",$text);
echo "Count: " . count($fh) . "<br />";
	foreach($fh as $text)
	{
		$text = str_replace("\n","",$text);
		preg_match('/\"(.+?)\",(.+?),(.*)/',$text,$matches);
		echo "Name: {$matches[1]} Acc: {$matches[2]} Email: {$matches[3]}<br />";

		$query = "SELECT `ID` FROM `Account` WHERE `Username` = '{$matches[2]}'";
		$result = mysql_get($query);
		if ( $row = mysql_fetch_array($result)) 
		{
			echo "____That account already exists!<br />";
		} else {
			$pass = randstring(6,rand(0,100000));
			$query = "INSERT INTO `Account` (`Username`,`Name`,`Email`,`Password`,`Status`,`Privileges`) VALUES ('{$matches[2]}','{$matches[1]}','{$matches[3]}','$pass','a','u')";
			echo " " . $query . "<br />";
mysql_get($query);
		}
	}


echo "Done.";
?>