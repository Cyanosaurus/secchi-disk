<?php //Color triangle selector related functions

//Generates a color-triangle with javascript to update input field id='color'
function generate_ctable($factor)
{	
	$dim = 10;
	$table = "<table width='20%' height='20%' border='0' cellspacing='0' cellpadding='0' class='colortriangle'>";
	$increment = 8;
	for($y = -8; $y < 256; $y += $increment)
	{
		$table .= "<tr height='".$dim."px'>";
		for($x = -8; $x < 256; $x+= $increment)
		{
			if ( $x >= 128 - round($y/2) && $x <= 128+round($y/2) && $y >= 0 && $y <= 256 )
			{
				$r = dechex(cap($factor*256*(distanceINV($x,$y,128,0,290)/290),0,255));
				$g = dechex(cap($factor*256*(distanceINV($x,$y,256,256,290)/290),0,255));
				$b = dechex(cap($factor*256*(distanceINV($x,$y,0,256,290)/290),0,255));

				$r = pad(dechex(hexdec($r)));
				$g = pad(dechex(hexdec($g)));
				$b = pad(dechex(hexdec($b)));


				$color = strtoupper("#".$r.$g.$b);
				$table .= "<td bgcolor='$color' width='".$dim."px'><a class='colortriangle' onClick=\"col=document.getElementById('color'); col.value='$color';col2=document.getElementById('color2'); col2.style.backgroundColor='$color';\">&nbsp;&nbsp;</a></td>";
			} else { $table .= "<td width='".$dim."px'></td>"; }
		}

		$table .= "</tr>";
	}

	return $table . "</table>";
}



?>