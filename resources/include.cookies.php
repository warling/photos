<?php

////////////////////////////////////////////////////////////////////////////////

//namespace org\givingtree\cookies;

////////////////////////////////////////////////////////////////////////////////

function cookie( $cookieName, $cookieValueDefault = emptyString )
{
	assert( 'isNonEmptyString( $cookieName )' );

	return ( cookieExists( $cookieName ) ? $_COOKIE[$cookieName] : $cookieValueDefault );
}

////////////////////////////////////////////////////////////////////////////////

function cookieExists( $cookieName )
{
	assert( 'isNonEmptyString( $cookieName )' );

	return isSet( $_COOKIE[$cookieName] );
}

////////////////////////////////////////////////////////////////////////////////

function cookieDoesNotExist( $cookieName )
{
	return !cookieExists( $cookieName );
}

////////////////////////////////////////////////////////////////////////////////

function setCookieExpiring( $cookieName, $cookieValue, $cookieExpirationTimestamp )
{
	assert( 'isNonEmptyString( $cookieName )' );
	assert( 'isNonEmptyString( $cookieValue )' );	//	May need to remove this
	assert( 'isNonEmptyInt( $cookieExpirationTimestamp )' );
	assert( '$cookieExpirationTimestamp > time()' );

	$result = setCookie( $cookieName, $cookieValue, $cookieExpirationTimestamp );
	assert( '$result === true' );

	$_COOKIE[$cookieName] = $cookieValue;
	assert( '$_COOKIE[$cookieName] === $cookieValue' );

//	echo '<!-- Setting cookie '.$cookieName.' to '.$cookieValue.' -->'.doubleNewline;

	return $result;
}

////////////////////////////////////////////////////////////////////////////////

function setCookieEternal( $cookieName, $cookieValue )
{
	return setCookieExpiring( $cookieName, $cookieValue, time() + 10 * 365 * 24 * 60 * 60 );	//	Approximately 10 years, in seconds
}

////////////////////////////////////////////////////////////////////////////////

function deleteCookie( $cookieName )
{
	assert( 'isNonEmptyString( $cookieName )' );

	$result = setCookie( $cookieName, false, time() - 3600 );
	assert( '$result === true' );

	unset( $_COOKIE[$cookieName] );
	assert( 'cookieDoesNotExist( $cookieName )' );

//	echo '<!-- Deleting cookie '.$cookieName.' -->'.doubleNewline;

	return $result;
}

////////////////////////////////////////////////////////////////////////////////

?>