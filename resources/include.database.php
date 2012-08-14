<?php

////////////////////////////////////////////////////////////////////////////////

//namespace org\givingtree\database;

////////////////////////////////////////////////////////////////////////////////

require( 'include.database.'.language.'.php' );

////////////////////////////////////////////////////////////////////////////////

function connectToDatabaseServer( $databaseAddress, $databaseLogin, $databasePassword )
{
	assert( 'isNonEmptyString( $databaseAddress )' );
	assert( 'isNonEmptyString( $databaseAddress )' );
	assert( 'isNonEmptyString( $databasePassword )' );

	$databaseConnection = mysql_connect( $databaseAddress, $databaseLogin, $databasePassword );

	if ( $databaseConnection )
		return $databaseConnection;
	else
		die( errorDatabaseServer.space.doubleQuote.$databaseAddress.doubleQuoteColonSpace.mysql_error( $databaseConnection ) );
}

////////////////////////////////////////////////////////////////////////////////

function createDatabase( $databaseName, $databaseConnection )
{
	return executeQuery( 'CREATE DATABASE IF NOT EXISTS '.$databaseName.' CHARACTER SET utf8', errorDatabaseCreate.space.doubleQuote.$databaseName.doubleQuote, $databaseConnection );
}

////////////////////////////////////////////////////////////////////////////////

function openDatabase( $databaseName, $databaseConnection )
{
	assert( 'isNonEmptyString( $databaseName )' );
	assert( 'isNonEmptyResource( $databaseConnection )' );

	$result = mysql_select_db( $databaseName, $databaseConnection );

	if ( $result )
		return $result;
	else
		die( errorDatabaseOpen.space.doubleQuote.$databaseName.doubleQuoteColonSpace.mysql_error( $databaseConnection ) );
}

////////////////////////////////////////////////////////////////////////////////

function deleteDatabase( $databaseName, $databaseConnection )
{
	return executeQuery( 'DROP DATABASE '.$databaseName, errorDatabaseDelete.space.doubleQuote.$databaseName.doubleQuote, $databaseConnection );
}

////////////////////////////////////////////////////////////////////////////////

function recreateDatabase( $databaseName, $databaseConnection )
{
	return deleteDatabase( $databaseName, $databaseConnection ) && createDatabase( $databaseName, $databaseConnection );
}

////////////////////////////////////////////////////////////////////////////////

function createTable( $tableName, $tableSchema, $databaseConnection )
{
	return executeQuery( 'CREATE TABLE '.$tableName.' ('.$tableSchema.')', errorDatabaseTableCreate.space.doubleQuote.$tableName.doubleQuote, $databaseConnection );
}

////////////////////////////////////////////////////////////////////////////////

function deleteTable( $tableName, $databaseConnection )
{
	return executeQuery( 'DROP TABLE IF EXISTS '.$tableName, errorDatabaseTableDelete.space.doubleQuote.$tableName.doubleQuote, $databaseConnection );
}

////////////////////////////////////////////////////////////////////////////////

function executeQuery( $query, $errorMessage, $databaseConnection )
{
	assert( 'isNonEmptyString( $query )' );
	assert( 'isNonEmptyString( $errorMessage )' );
	assert( 'isNonEmptyResource( $databaseConnection )' );

	$startTime = microtime( true );
	$result = mysql_query( $query, $databaseConnection );
	$endTime = microtime( true );

	if ( true )
	{
		if ( $result === false )
		{
			echo mysql_error().brTag.newline;
		}

		global $pageContent;
		$pageContent .=
			'<!-- query = '.preg_replace( '/\-\-+/', hyphen, $query ).' -->'.newline.	//	ASCII-only
			'<!-- result = '.$result.' -->'.newline.
			'<!-- time = '.( $endTime - $startTime ).' seconds -->'.doubleNewline;
	}

	assert( '$result !== false' );

	if ( $result )
		return $result;
	else
		die( $errorMessage.colonSpace.mysql_error( $databaseConnection ) );
}

////////////////////////////////////////////////////////////////////////////////

function rowCount( $queryResult )
{
	assert( 'isNonEmptyResource( $queryResult )' );

	return mysql_num_rows( $queryResult );
}

////////////////////////////////////////////////////////////////////////////////

function nextRow( $queryResult )
{
	assert( 'isNonEmptyResource( $queryResult )' );

	return mysql_fetch_array( $queryResult );
}

?>