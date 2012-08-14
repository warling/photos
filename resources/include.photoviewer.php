<?php

$style = <<<EOF
div#photo table { position:fixed; top:0; left:0; width:100%; height:90%; }
div#photo td { vertical-align:middle; text-align:center; }
div#photo img { border:solid 1px black; }
div#footer { display:none; }
EOF;

$id = $_SERVER['QUERY_STRING'];
if ( $id != '' )
{
	$imageSize = getimagesize( $id );
	if ( $imageSize != FALSE )
	{
		$title = $id.' :: PrattSchool.org';
		$content = '<div id="photo">'."\n".'<table><tr><td><img src="'.$id.'" width="'.$imageSize[0].'" height="'.$imageSize[1].'" alt="Photo" /></td></tr></table>'."\n</div>\n";
	}
}

require( 'template2.php' );
?>