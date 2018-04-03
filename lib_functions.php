<?php /* COMMON FUNCTIONS */

/* File Index

--Generic
(string) clantext($val)
(string) super_cleantext($val)
(string) randstring($length,$seed)
(string) get_post($tag)
(string) get_get($tag)
(string) chop_array($array_in)

(number) distanceINV($x1,$y1, $x2,$y2,$max)
(number) pad($in)
(number) cap($in,$low,$high)
(string) clean_shortanswer($val)
(number) count_newlines($file)
(string) convert_date($date)
(string) function randpass()
*/

session_start();



/*Hack to make this work with PhP 4.2.3*/
/* 
function file_get_contents($file)
{
	$text = "";
	$fh = fopen("$file","r");
	while(!feof($fh))
	{
		$text .= fgets($fh,4096);
	}
	fclose($fh);
	return $text;
}
*/ 

//Keeps the incoming input from user 'clean' from messing up internal function
function cleantext($val)
{
	$val = str_replace("\"","&quot;",$val);
	$val = str_replace("'","`",$val);
	$val = addslashes($val);
	$val = strip_tags($val);
	$val = trim($val);
	return $val;
}

//Takes what cleantext does, and makes the text password/account-name friendly
//(no spaces, weird symbols, etc)
function super_cleantext($val)
{
	return preg_replace("/([^_a-zA-Z0-9\\-])/","",$val);
}

//Produces a random string of digits
function randstring($length,$seed)
{
   srand($seed);
   $temp = "";
   for($i = 0; $i < $length; $i++)
   {
      $temp .= rand(0,9);
   }
   return $temp;
}

//Retreives and cleans (security) the element of $_POST
function get_post($tag)
{
	if ( isset($_POST[$tag]) ) { $val = $_POST[$tag]; } else { $val = ""; } 
	return cleantext($val);
}

//Retreives and cleans (security) the element of $_GET
function get_get($tag)
{
	if ( isset($_GET[$tag]) ) { $val = $_GET[$tag]; } else { $val = ""; } 
	return cleantext($val);
}


//Removes the end element of an array
function chop_array($arr)
{
	$count = count($arr);
	$new_arr = Array();
	for ($i = 0; $i < $count - 1; $i++)
	{
		$new_arr[] = $arr[$i];
	}
	return $new_arr;
}


//Inverse distance from maximum possible value
function distanceINV($x1,$y1, $x2,$y2,$max) {
	return $max-sqrt(($x2-$x1)*($x2-$x1) + ($y2-$y1)*($y2-$y1));
}

//Pads a 1digit number with a '0'
function pad($in)
{
	if ( strlen($in) == 1 ) { return "0".$in; } else { return $in; }	
}

//Keeps the input value between low and high
function cap($in,$low,$high)
{
	if ( $in < $low ) { return $low; }
	else if ( $in > $high ) { return $high; }
	else { return $in; }
}

//Digits of accuracy
function padme($val) {
	return number_format($val, 2, '.', '');
}

//Clean up short answers
function clean_shortanswer($val)
{
	$val = preg_replace("/\s{3,}/is","",$val);
	$val = substr($val,0,1000);
	return $val;
}

//Count how many newlines are in a file
function count_newlines($file)
{
	$stuff = file_get_contents($file);
	$len = strlen($stuff);
	$stuffA = preg_split("//",$stuff);
	$count = 0;
	for($i = 0; $i <= $len; $i++)
	{
		if ( $stuffA[$i] == "\n" ) { $count++; }
	}
	return $count;
}

//Change Y-m-d to m-d-Y
function convertdate($mdate) {
	preg_match("/(\d{4})\-(\d{1,2})\-(\d{1,2})/",$mdate,$matches);
	return $matches[2]."-".$matches[3]."-".$matches[1];
}

//Returns a random passowrd
function randpass()	{
	$tmp = "";
	for($i = 0; $i < 8; $i++)
	{
		if ( rand(0,1) % 2 == 0 ) { $tmp .= rand(0,9); }
		else {
			$tmp .= chr(rand(ord("a"),ord("z")));
		}
	}
	return $tmp;
}

?>
