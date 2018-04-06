<?php
	include_once("libraries.php");

	
if ( $_SESSION['level'] < 2 ) { header("Location: index.php"); exit; }

//Process incoming actions ----------------------------------

$action = get_post('action');
$alert_text = "";				//Message User, if Any

$title = get_post("title");
$description = get_post("description");
$resize_size = get_post("size");
$target = get_post("ID");

$reset_continue_FLAG = false;

$numPerPage = 5;
page_action("image");

//Functions

//Find out if image is being used by current test, or not
function image_is_used($TestID,$ImageID)
{
	$query = "SELECT `ID` FROM `Test` WHERE `Current`='1'";
	$result2 = mysql_get($query);
	$current = mysql_fetch_array($result2);
	$currID = $current['ID'];
	$query = "SELECT `ID` FROM `Question` WHERE `TestID`='$TestID' AND `ImageID`='$ImageID'";
	$result2 = mysql_get($query);
	return ($row2 = mysql_fetch_array($result2)) != false;
}

//ADD New Image
if ( $action == "Add Image" )
{
		$continueflag = true; 	//Actually do it if we pass inspection
		$final_filename = "";
		$newimage_FLAG = ( $_FILES['imgfile']['name'] != "" );

		//First, check to see if image is there

		//if ( $_FILES['imgfile']['type'] != "image/jpeg")
		if ( !preg_match("/.+?\.(jpg|jpeg|png)/i",$_FILES['imgfile']['name'],$matches) )
		{
		      $alert_text .= "Wrong image filetype (Must be .JPG). <br />";
		      $continueflag = false;
		} else {
			$final_filename =  "F" . randstring(5,time()) . preg_replace("/[^_a-zA-Z0-9.]/","_",$_FILES['imgfile']['name']);
			if ( !copy ($_FILES['imgfile']['tmp_name'], "images/" . $final_filename) )
			{
		      $alert_text .= "Could not save file (Internal Error). <br />";
		      $continueflag = false;				
			}
		}


		//Check title, etc

		if ( $title == "" || $resize_size == "" || $newimage_FLAG == false ) 
		{
			$alert_text .= "All fields must be entered in order to add an entry.";
			$continueflag = false;
		}

		//If continue flag is okay, proceed
		if ( $continueflag )
		{

			//Resize Image
			$is = getimagesize( "images/$final_filename" );
			$img_src = imagecreatefromjpeg( "images/$final_filename" );			//Main
			$img_src_thumb = imagecreatefromjpeg( "images/$final_filename" );	//Thumbnai

			//Aspect Ratio
			$ratio = $is[0] / $is[1];

			if ( $resize_size == 0 ) { $rx = round($is[0] * 0.25); }
			else if ( $resize_size == 1 ) { $rx = round($is[0] * 0.5);  }
			else if ( $resize_size == 2 ) { $rx = round($is[0] * 0.75);  }
			else if ( $resize_size == 3 ) { $rx = $is[0];  }
			else if ( $resize_size == 4 ) { $rx = 160; }
			else if ( $resize_size == 5 ) { $rx = 320; }
			else if ( $resize_size == 6 ) { $rx = 640; }
			else { $rx = 1024; }

			$ry = round($rx / $ratio);

            $img_dst = imagecreate( $rx, $ry );

            imagecopyresized( $img_dst, $img_src, 0, 0, 0, 0, $rx, $ry, $is[0], $is[1] );

			//Keep the thumbnail withn that 160x120 space
			if ( $ratio < 1.0 )
			{
				$trx = round(160*$ratio);
				$try = 120;
			} else {
				$trx = 160;
				$try = round(120/$ratio);
			}

            $img_dst_thumb = imagecreate( $trx, $try );
			imagecopyresized( $img_dst_thumb, $img_src_thumb, 0, 0, 0, 0, $trx, $try, $is[0], $is[1] );

		$pf1 = imagejpeg( $img_dst, "images/$final_filename", 70 );
		$pf2 = imagejpeg( $img_dst_thumb, "thumbs/$final_filename", 70 );
            if( !$pf1 || !pf2 )
            {
                $alert_text .= "Error resizing thumbnail (Internal Error).";
		    if ( !$pf1 ) { $alert_text .= "..Regular image."; }
		    if ( !$pf2 ) { $alert_text .= "..Thumbnail image."; }
            } 
			else { 
				//Add SQL stuff
				$query = "INSERT INTO `Image` (`Title`,`Description`,`Content`) VALUES (\"$title\",\"$description\",\"$final_filename\")";
				mysql_get($query);

				$alert_text .= "Image Added"; 
			}
		} 

}

//Update the Contents
if ( $action == "Edit Image" )
{
		$continueflag = true; 	//Actually do it if we pass inspection
		$final_filename = "";
		//First, check to see if image is there

		$newimage_FLAG = ( $_FILES['imgfile']['name'] != "" );

		//Process new image, if given
		$query = "SELECT `Content` FROM `Image` WHERE `ID`='$target'";
		$result = mysql_get($query);
		$row = mysql_fetch_array($result);
		$old_content = $row['Content'];	

		//Update Image
		if ( $newimage_FLAG ) {

			if ( $row == false ) { Alert("Error","Image content not found. (Internal Error)"); }
			else {
				//Get rid of old ones
					unlink("images/".$row['Content']);
					unlink("thumbs/".$row['Content']);
	
				if ( strtolower($_FILES['imgfile']['type']) != "image/jpeg")
				{
				      $alert_text .= "Wrong image filetype (Must be .JPG). <br />";
				      $continueflag = false;
				} else {
					$final_filename =   "F" . randstring(5,time()) . preg_replace("/[^_a-zA-Z0-9.]/","_",$_FILES['imgfile']['name']);
					if ( !copy ($_FILES['imgfile']['tmp_name'], "images/" . $final_filename) )
					{
				      $alert_text .= "Could not save file (Internal Error). <br />";
				      $continueflag = false;				
					}
				}
			}
		}

		//Check title, etc

		if ( $title == "" || $resize_size == "" ) 
		{
			$alert_text .= "All fields must be entered in order to add an entry.";
			$continueflag = false;
		}

		//If continue flag is okay, proceed
		if ( $continueflag )
		{

			if ( $newimage_FLAG ) {
				//Resize Image
				$is = getimagesize( "images/$final_filename" );
				$img_src = imagecreatefromjpeg( "images/$final_filename" );			//Main
				$img_src_thumb = imagecreatefromjpeg( "images/$final_filename" );	//Thumbnai
	
				//Aspect Ratio
				$ratio = $is[0] / $is[1];
	
				if ( $resize_size == 0 ) { $rx = round($is[0] * 0.25); }
				else if ( $resize_size == 1 ) { $rx = round($is[0] * 0.5);  }
				else if ( $resize_size == 2 ) { $rx = round($is[0] * 0.75);  }
				else if ( $resize_size == 3 ) { $rx = $is[0];  }
				else if ( $resize_size == 4 ) { $rx = 160; }
				else if ( $resize_size == 5 ) { $rx = 320; }
				else if ( $resize_size == 6 ) { $rx = 640; }
				else { $rx = 1024; }
	
				$ry = round($rx / $ratio);
	
	            $img_dst = imagecreatetruecolor( $rx, $ry );
	            imagecopyresampled( $img_dst, $img_src, 0, 0, 0, 0, $rx, $ry, $is[0], $is[1] );

				//Keep the thumbnail withn that 160x120 space
				if ( $ratio < 1.0 )
				{
					$trx = round(160*$ratio);
					$try = 120;
				} else {
					$trx = 160;
					$try = round(120/$ratio);
				}

          		$img_dst_thumb = imagecreatetruecolor( $trx, $try );
				imagecopyresampled( $img_dst_thumb, $img_src_thumb, 0, 0, 0, 0, $trx, $try, $is[0], $is[1] );

	            if( !imagejpeg( $img_dst, "images/$final_filename", 70 ) || !imagejpeg( $img_dst_thumb, "thumbs/$final_filename", 70 ) )
	            {
	                $alert_text .= "Error resizing thumbnail (Internal Error).";
	            } 
			}
 
			//Remove old SQL entry
			$query = "DELETE FROM `Image` WHERE `ID`='$target'";
			mysql_get($query);

			//Add SQL stuff
			if ( !$newimage_FLAG ) 
				{ $query = "INSERT INTO `Image` (`Title`,`Description`,`Content`) VALUES (\"$title\",\"$description\",\"$old_content\")"; }
			else
				{ $query = "INSERT INTO `Image` (`Title`,`Description`,`Content`) VALUES (\"$title\",\"$description\",\"$final_filename\")"; }
			
			mysql_get($query);

			$alert_text .= "Image Edited"; 

		}
}

//Deletes an Image
if ( $action == "Delete" )
{
	//Find out if image is being used by current test, or not
	$used_FLAG = image_is_used($currID,$target);

	if ( $used_FLAG ) { $alert_text .= "Cannot delete image if being used by an active test. <br />"; }
	else {
		//Look up image content file
		$query = "SELECT `Content` FROM `Image` WHERE `ID`='$target'";
		$result = mysql_get($query);
		if ( ($row = mysql_fetch_array($result)) == false ) { $alert_text .= "Image content not found. (Internal Error)"; }
		else {
			unlink("images/".$row['Content']);
			unlink("thumbs/".$row['Content']);

			$query = "DELETE FROM `Image` WHERE `ID`='$target'";
			mysql_get($query);
			$alert_text .= "Image successfully removed.";
		}
	}
}



//-----------------------------------------------------------

	print_start_to_navbar("Manage Images");
	print_navbar_to_content();
	
		delimiter_start();
		
		delimit();

			if ( $alert_text != "" ) { Alert("Notice",$alert_text); $reset_continue_FLAG = true; }

		delimit();

			$numImages = sqlTest_numImages();
			$PAGE = get_page('image');

			table1_start("Images - Page ".($PAGE+1)." of ".floor(($numImages/$numPerPage)+1));
			table1_header(Array("Title/50%","Description/50%","Filesize [<font color='red'>*</font>]/0","Thumbnail/0","/0"));

				//Get Image List
				$rowcount = 0;	//To feed to table1_row()
				$query = "SELECT * FROM `Image` ORDER BY `Title`,`ID` ASC LIMIT ".($PAGE*$numPerPage).",$numPerPage";
				$result = mysql_get($query);

				while($row = mysql_fetch_array($result))
				{

					//Forms - View/Edit
						//Find out if image is being used by current test, or not
						$used_FLAG = image_is_used($currID,$row['ID']);

						//If it's being used, don't allow for delete
						if ( $used_FLAG )
						{
							$view_edit = "<form action='images/".$row['Content']."' target='_blank'>" . form_hidden("ID",$row['ID']) . form_hidden("action","View") . form_submit("View") . "</form><br /> ";
							$delete_image = "";
							$edit_image = "";
						} else {
							$view_edit = "<form action='images/".$row['Content']."' target='_blank'>" . form_hidden("ID",$row['ID']) . form_hidden("action","View") . form_submit("View") . "</form><br /> ";
							$delete_image = "<form action='' method='POST' onSubmit=\"return confirm('Are you sure you want to delete this?');\" >" . form_hidden("ID",$row['ID']) . form_hidden("action","Delete") . "<span class='warning'>" . form_submit("Delete") . "</span></form><br /> ";
							$edit_image = "<form action='' method='POST'>" . form_hidden("ID",$row['ID']) . form_hidden("action","Edit") . form_submit("Edit") . "</form><br /> ";
						}

					$img = "<img src=\"thumbs/" . $row['Content'] . "\" alt=\"Image for " . $row['Title'] . "\" />";

					$filesize = (filesize("images/".$row['Content']) / 1024);
					$download = round($filesize / 5.6)."s";
					$filesize = round($filesize)."KB [$download]";

					$row_array = Array($row['Title'],$row['Description'],$filesize,$img,$view_edit.$edit_image.$delete_image);
					table1_row($row_array,$rowcount);

					$rowcount++;
				}
			table1_end(); 
			echo "		<center><font color='#999999' style='font-size:10px;'>* Approximate seconds needed to download image with 56K dialup access.</font></center>";

			page_controls("image",$numPerPage,$numImages,"");

		delimit();

			if ( $action == "Edit" )
			{
				//form variables
				$optional = "";
				$submitval = "Edit Image";

				//Fetch image information
				$query = "SELECT * FROM `Image` WHERE `ID`='$target'";
				$result = mysql_get($query);
				if ( ($row = mysql_fetch_array($result)) == false ) { Alert("Error","That image does not exist."); }
				else {
					$edit_title = $row['Title'];
					$edit_description = $row['Description'];
				}
			} else {
				$submitval = "Add Image";
				//If adding conjured an error, put the fields back in
				if ( $reset_continue_FLAG )
				{
					$edit_title = $title;
					$edit_description = $description;
				} else {
					//form variables

					$optional = "(Optional to replace)";
					$edit_title = "";
					$edit_description = "";
				}

			}


			//Upload New Image / Edit Form
			echo "<form action='' method='POST' enctype='multipart/form-data'>\n" . form_hidden("ID",$target) . "\n";
			echo "<table class='data'>\n";
			echo "<tr><td align='left'>Title (displayed)</td><td align='left'><input type='text' size='70' name='title' value=\"$edit_title\" /></td></tr>\n";
			echo "<tr><td align='left'>Description (optional): </td><td align='left'><textarea cols='68' rows='2' name='description'>$edit_description</textarea></td></tr>\n";
			echo "<tr><td align='left'>Image File (JPEG):</td><td align='left'><input type='file' name='imgfile'></td></tr>\n";
			echo "<tr><td align='left'>Resize Image Size:</td><td align='left'><select name='size'><optgroup label='Relative Size'><option value='0'>Small (25% Original)</option><option value='1'>Medium (50% Original)</option><option value='2'>Large (75% Original)</option><option value='3'>No Resize (100% Original)</option></optgroup><optgroup label='Fixed Width'><option value='4'>Tiny (160 pixels)</option><option value='5' selected='selected'>Small (320 pixels)</option><option value='6'>Medium (640 pixels)</option><option value='7'>Large (1024 pixels)</option></optgroup></select></td></tr>\n";
			echo "<tr><td><input type='submit' value='$submitval' name='action' /></td><td align='center'>(<font color='#999999' style='font-size:11px;'>Most current webpages are designed around an assumed minimum size of 800 by 600 pixels.</font>)</td></tr>\n";	
			echo "</table>\n";
		
		delimiter_end();
	
	print_content_to_end();
?>