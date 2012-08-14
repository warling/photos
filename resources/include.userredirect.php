<?php

////////////////////////////////////////////////////////////////////////////////

require( 'configuration.php' );
define( 'includePath2', '' );
require( includePath2.'include.constants.php' );
require( includePath2.'include.types.php' );
require( includePath2.'include.protocol.php' );
require( includePath2.'include.database.php' );
require( includePath2.'include.object.php' );
require( includePath2.'include.user.php' );
require( includePath2.'include.photouser.php' );

////////////////////////////////////////////////////////////////////////////////

$databaseConnection = connectToDatabaseServer( databaseAddress, databaseLogin, databasePassword );

openDatabase( photoDatabaseName, $databaseConnection );

$user = User::userByUserId( scriptDirectoryName, $databaseConnection );
assert( 'isNotEmpty( $user )' );

$userIdString = emptyString;
if ( isValidUser( $user ) )
{
	$userIdString = questionMark.keyUserId.equals.$user->userId();
}

$url = substr( scriptDirectoryNameTmp, 0, strrpos( scriptDirectoryNameTmp, slash ) + 1 ).scriptName.$userIdString;

header( 'Location: '.$url );

////////////////////////////////////////////////////////////////////////////////

?>