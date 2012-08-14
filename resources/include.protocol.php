<?php

////////////////////////////////////////////////////////////////////////////////

//namespace org::givingtree::protocol;

////////////////////////////////////////////////////////////////////////////////

define( slash, '/' );

////////////////////////////////////////////////////////////////////////////////

define( 'scriptProtocol', strtolower( strtok( $_SERVER['SERVER_PROTOCOL'], slash ) ).'://' );
define( 'scriptIpAddress', $_SERVER['HTTP_HOST'] );
define( 'scriptPath', $_SERVER['SCRIPT_NAME'] );
define( 'scriptURL', scriptProtocol.scriptIpAddress.scriptPath );
define( 'scriptDirectory', substr( scriptURL, 0, strrpos( scriptURL, slash ) + 1 ) );
define( 'scriptDirectoryNameTmp', rtrim( scriptDirectory, slash ) );
define( 'scriptDirectoryName', substr( scriptDirectoryNameTmp, strrpos( scriptDirectoryNameTmp, slash ) + 1 ) );
define( 'scriptName', substr( scriptURL, strrpos( scriptURL, slash ) + 1 ) );
define( 'clientIpAddress', $_SERVER['REMOTE_ADDR'] );
define( 'clientAgent', $_SERVER['HTTP_USER_AGENT'] );

////////////////////////////////////////////////////////////////////////////////

define( 'isIE', ( stripos( clientAgent, 'msie' ) != false ) );

////////////////////////////////////////////////////////////////////////////////

?>