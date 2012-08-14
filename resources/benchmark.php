<?php

$startTime = microtime( true );

define( 'includePath2', './' );

require( 'configuration.php' );
require( includePath2.'include.constants.php' );
require( includePath2.'include.entities.php' );
require( includePath2.'include.tags.php' );
require( includePath2.'include.types.php' );
require( includePath2.'include.'.language.'.php' );
require( includePath2.'include.utilities.php' );
require( includePath2.'include.protocol.php' );
require( includePath2.'include.time.php' );
require( includePath2.'include.cookies.php' );
require( includePath2.'include.database.php' );
require( includePath2.'include.user.php' );
require( includePath2.'include.photouser.php' );
//require( includePath2.'include.markdown.php' );
require( includePath2.'include.smartypants.php' );

$baseStyle = $pageStyle = $pageIcon = $pageTitle = $pageContent = '';

$pageContent .= '<p>Hello, World!</p>';

require( includePath2.'include.template.php' );

echo '<!-- Executed in ' .( microtime( true ) - $startTime ). ' seconds. -->' ;
?>