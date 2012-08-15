<?php

////////////////////////////////////////////////////////////////////////////////

//namespace com\44clarence\utilities;

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

define( 'limitStringShort', 512 );
define( 'limitStringMedium', 1024 * 2 );
define( 'limitStringLong', 1024 * 10 );
define( 'limitFileName', 32 );

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function parameterExists( $parameterName )
{
	return isset( $_REQUEST[$parameterName] );
}

////////////////////////////////////////////////////////////////////////////////

function parameterDoesNotExist( $parameterName )
{
	return !parameterExists( $parameterName );
}

////////////////////////////////////////////////////////////////////////////////

function parameter( $parameterName, $databaseConnection, $defaultValue = emptyString )
{
	if ( parameterExists( $parameterName ) )
	{
		return sanitizedString( $_REQUEST[$parameterName], $databaseConnection );
	}
	else
	{
		return $defaultValue;
	}
}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function lastCharacter( $string )
{
	assert( 'isNonEmptyString( $string )' );

	return $string[strlen( $string ) - 1];	//	ASCII-only
}

////////////////////////////////////////////////////////////////////////////////

function endsWith( $string, $character )
{
	assert( 'isNonEmptyString( $character )' );
	assert( 'strlen( $character ) === 1' );	//	ASCII-only

	return ( lastCharacter( $string ) === $character );
}

////////////////////////////////////////////////////////////////////////////////

function doesNotEndWith( $string, $character )
{
	return !endsWith( $string, $character );
}

////////////////////////////////////////////////////////////////////////////////

function isLowercase( $string )
{
	assert( 'isString( $string )' );

	return ( $string === strtolower( $string ) );	//	ASCII-only
}

////////////////////////////////////////////////////////////////////////////////

function isNotLowercase( $string )
{
	return !isLowercase( $string );
}

////////////////////////////////////////////////////////////////////////////////

function sanitizedString( $string, $databaseConnection )
{
	assert( 'isString( $string )' );
	assert( 'isNonEmptyResource( $databaseConnection )' );

	$string = substr( trim( mysql_real_escape_string( strip_tags( $string ), $databaseConnection ) ), 0, limitStringLong );	//	ASCII-only

	return ( $string === false ? emptyString : $string );
}

////////////////////////////////////////////////////////////////////////////////

function randomCharacterString( $length )
{
	assert( 'isInt( $length )' );
	assert( '$length > 0' );

	$string = emptyString;
	for ( $i = 0; $i < $length; $i++ ) $string .= substr( randomCharacterList, rand()%randomCharacterListLength, 1 );	//	ASCII-only

	return $string;
}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function xmlStringValue( $tagName, $xmlString, $default = emptyString )
{
	assert( 'isNonEmptyString( $tagName )' );
	assert( 'isString( $xmlString )' );

	$tagOpen  = leftBracket.$tagName.rightBracket;
	$tagClose = leftBracket.slash.$tagName.rightBracket;

	$tagStart = strpos( $xmlString, $tagOpen );

	if ( $tagStart === false ) return $default;

	$valueStart = ( $tagStart + strlen( $tagOpen ) );

	$valueEnd = strpos( $xmlString, $tagClose, $valueStart + 1 );
	assert( $valueEnd >= $valueStart );

	$value = substr( $xmlString, $valueStart, $valueEnd - $valueStart );
	assert( 'isString( $value )' );

	return $value;
}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function randomFileName()
{
	return randomCharacterString( randomFileNameLength );
}

////////////////////////////////////////////////////////////////////////////////

function sanitizedFileName( $fileName, $databaseConnection )
{
	//	Sanitize the string in every way possible:
	$sanitizedFileName = substr( trim( preg_replace( '/[^a-zA-Z0-9.\- ]/', emptyString, sanitizedString( $fileName, $databaseConnection ) ) ), 0 , limitFileName );	//	ASCII-only

	//	Dump invalid names:
	if ( $sanitizedFileName == emptyString ) return emptyString;
	if ( $sanitizedFileName == directoryNameCurrent ) return emptyString;
	if ( $sanitizedFileName == directoryNameParent ) return emptyString;
	if ( $sanitizedFileName[0] == dot ) return emptyString;

	//	Return the sanitized file name:
	return $sanitizedFileName;
}

////////////////////////////////////////////////////////////////////////////////

function setDirectoryPermissions( $directoryPath, $octalPermissionsFlag )
{
	assert( 'isNonEmptyString( $directoryPath )' );
	assert( 'endsWithPathSeparator( $directoryPath )' );
	assert( 'isInt( $octalPermissionsFlag )' );
	assert( 'file_exists( $directoryPath )' );
	assert( 'is_dir( $directoryPath )' );

	$result = chmod( $directoryPath, $octalPermissionsFlag );
	assert( '$result === true' );

	$iterator = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $directoryPath ), RecursiveIteratorIterator::SELF_FIRST );
	foreach ( $iterator as $item )
	{
		$result = chmod( $item, $octalPermissionsFlag );
		assert( '$result === true' );
	}

	return true;
}

////////////////////////////////////////////////////////////////////////////////

function deletePath( $path )
{
	assert( 'isNonEmptyString( $path )' );
	assert( '$path != rootDirectoryPath' );
	assert( 'file_exists( $path )' );

	if ( $path == rootDirectoryPath ) return false;

	if ( is_file( $path ) )
	{
		$result = unlink( $path );
		assert( '$result === true' );
		return true;
	}

	assert( 'is_dir( $path )' );
	foreach ( new DirectoryIterator( $path ) as $item )
	{
		if ( $item->isDot() )
		{
			unset( $item );
			continue;
		}

		$result = deletePath( $item->isFile() ? $item->getPathName() : $item->getRealPath() );
		assert( '$result === true' );

		unset( $item );
	}

	$result = rmdir( $path );
	assert( '$result === true' );

	return true;
}

////////////////////////////////////////////////////////////////////////////////

function createDirectory( $directoryPath, $octalPermissionsFlag )
{
	assert( 'isNonEmptyString( $directoryPath )' );
	assert( 'endsWithPathSeparator( $directoryPath )' );
	assert( 'isInt( $octalPermissionsFlag )' );
	assert( '!file_exists( $directoryPath )' );

	$result = mkdir( $directoryPath );
	assert( '$result === true' );

	$result = chmod( $directoryPath, $octalPermissionsFlag );
	assert( '$result === true' );

	return true;
}

////////////////////////////////////////////////////////////////////////////////

function endsWithPathSeparator( $string )
{
	return endsWith( $string, pathSeparator );
}

////////////////////////////////////////////////////////////////////////////////

function isDirectoryPath( $directoryPath )
{
	return endsWith( $directoryPath, pathSeparator );
}

////////////////////////////////////////////////////////////////////////////////

function isNotDirectoryPath( $directoryPath )
{
	return !isDirectoryPath( $directoryPath );
}

////////////////////////////////////////////////////////////////////////////////

function isFilePath( $filePath )
{
	return doesNotEndWith( $filePath, pathSeparator );
}

////////////////////////////////////////////////////////////////////////////////

function isNotFilePath( $filePath )
{
	return !isFilePath( $filePath );
}

////////////////////////////////////////////////////////////////////////////////

function isDirectory( $directoryPath )
{
	return isDirectoryPath( $directoryPath ) && is_dir( $directoryPath );
}

////////////////////////////////////////////////////////////////////////////////

function isNotDirectory( $directoryPath )
{
	return !isDirectory( $directoryPath );
}

////////////////////////////////////////////////////////////////////////////////

function isFile( $filePath )
{
	return isFilePath( $filePath ) && is_file( $filePath );
}

////////////////////////////////////////////////////////////////////////////////

function isNotFile( $filePath )
{
	return !isFile( $filePath );
}

////////////////////////////////////////////////////////////////////////////////

function readStringFile( $filePath )
{
	assert( 'isNonEmptyString( $filePath )' );

	return file_get_contents( $filePath );
}

////////////////////////////////////////////////////////////////////////////////

function writeStringFile( $filePath, $fileString )
{
	assert( 'isNonEmptyString( $filePath )' );
	assert( 'isNonEmptyString( $fileString )' );

	$file = fopen( $filePath, 'w' );
	assert( 'isNotEmpty( $file )' );

	$result1 = fwrite( $file, $fileString );
	assert( 'isNotEmpty( $result1 )' );

	$result2 = fclose( $file );
	assert( 'isNotEmpty( $result2 )' );

	return $result1;
}

////////////////////////////////////////////////////////////////////////////////

function writeXmlStringFile( $filePath, $fileString )
{
	assert( 'isNonEmptyString( $fileString )' );

	$fileString =
		'<?xml version="1.0"?>'.newline.
		'<root>'.doubleNewline.
		$fileString.
		'</root>';

	return writeStringFile( $filePath, $fileString );
}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function toXmlString( $values )
{
	assert( 'isNonEmptyArray( $values )' );

	$string = emptyString;
	foreach ( $values as $key => $value ) $string .=
		leftBracket.$key.rightBracket.newline.
		htmlspecialchars( stripcslashes( $value ) ).newline.
		leftBracket.slash.$key.rightBracket.doubleNewline;

	return $string;
}

////////////////////////////////////////////////////////////////////////////////

function fromXmlString( $string )
{
	assert( 'isString( $string )' );

	$xmlArray = (array)simplexml_load_string( $string );

	$array = array();
	foreach ( $xmlArray as $key => $value )
	{
		$array[$key] = trim( $value );
	}

	return $array;
}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function formattedBacktrace( $backtrace )
{
	$argumentSeparator = comma;

	$ignoredFunctions = array( 'formattedBacktrace', 'printFormattedBacktrace', 'assert_callback' );

	$result = emptyString;
	foreach ( $backtrace as $entry )
	{
		$func = array_value( $entry, 'function' );

		if ( in_array( $func, $ignoredFunctions ) ) continue;

		$file = array_value( $entry, 'file' );
		$line = array_value( $entry, 'line' );
		$args = array_value( $entry, 'args' );

		if ( isNotEmpty( $file ) ) $result .=
			'File:      '.$file.brTag.newline;

		if ( isNotEmpty( $line ) ) $result .=
			'Line:      '.$line.brTag.newline;

		if ( isNotEmpty( $func ) ) $result .=
			'Func:  '.$func.brTag.newline;

		if ( isNotEmpty( $args ) ) $result .=
			'Args: '.implode( $argumentSeparator, $args ).brTag.newline;

		$result .= brTag.doubleNewline;
	}

	return $result;
}

////////////////////////////////////////////////////////////////////////////////

function printFormattedBacktrace()
{
	echo formattedBacktrace( debug_backtrace() );
}

////////////////////////////////////////////////////////////////////////////////

function assert_callback( $file, $line, $message )
{
	echo
		codeTag.newline.
		'Assertion Failed'.brTag.newline.
		brTag.doubleNewline;

	printFormattedBacktrace();

	echo
		codeTagEnd.
		hrTag.doubleNewline;
}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

?>