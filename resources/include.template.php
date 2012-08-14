<?php
header( 'Content-Language: en-us' );
define( 'expirationTimestamp', time() + expirationSeconds );
header( 'Expires: '.gmdate( DATE_RFC822, expirationTimestamp ) );
header( 'Cache-Control: max-age='.expirationSeconds.', public' );
require( includePath.'include.template.'.language.'.php' );
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<link rel="shortcut icon" href="<?php echo $pageIcon ?>" type="image/x-icon" />
<link rel="icon" type="image/vnd.microsoft.icon" href="<?php echo $pageIcon ?>" />
<!--link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo( scriptURL.'?feed=rss2' ) ?>" /-->
<script type="text/javascript" src="<?php echo( siteScriptsPath ) ?>"></script>
<?php echo( $pageHead ); ?>
<link rel="stylesheet" type="text/css" href="<?php echo $baseStyle ?>" />
<?php
if ( isNotEmpty( $pageStyle ) ) echo
	'<style type="text/css">'.newline.
	$pageStyle.
	'</style>'.newline;
?>
<title><?php echo( $pageTitle ); ?></title>
</head>
<body>

<?php evalecho( $pageContent ); ?>

<?php
if ( isIE ) echo errorIE;
?>

</body>
</html>

<?php
function evalecho( $string )
{
	$tempFileName = tempnam( '', 'tmp' );
	$tempFilePointer = fopen( $tempFileName, 'w' );
	fwrite( $tempFilePointer, $string );
	fclose( $tempFilePointer );
	require( $tempFileName );
	unlink( $tempFileName );
}
?>