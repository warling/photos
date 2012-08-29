<?php

////////////////////////////////////////////////////////////////////////////////

//namespace org\givingtree\maps;

////////////////////////////////////////////////////////////////////////////////

class GeocodeLocation
{
	public function __construct( $latitude, $longitude, $altitude, $address = emptyString )
	{
		assert( 'isNumericString( $latitude )' );
		assert( 'isNumericString( $longitude )' );
		assert( 'isEmptyString( $altitude ) || isNumericString( $altitude )' );
		assert( 'isString( $address )' );

		$this->latitude = $latitude;
		$this->longitude = $longitude;
		$this->altitude = $altitude;
		$this->address = $address;
	}

	////////////////////////////////////////////////////////////////////////////

	public function __toString()
	{
		//	Begin with the left parenthesis:
		$s = leftParenthesis;

		//	Append the latitude and longitude:
		$s = leftParenthesis.$this->latitude().comma.$this->longitude();

		//	If the altitude is defined, append it as well:
		$altitude = $this->altitude();
		if ( isNonEmptyString( $altitude ) ) $s .= comma.$altitude;

		//	If there's a canonical address, append it as well:
		$address = $this->address();
		if ( isNonEmptyString( $address ) ) $s .= comma.$address;

		//	Append the right parenthesis:
		$s .= rightParenthesis;

		//	Return the result:
		return $s;
	}

	////////////////////////////////////////////////////////////////////////////

	public function latitude()
	{
		return $this->latitude;
	}

	////////////////////////////////////////////////////////////////////////////

	public function longitude()
	{
		return $this->longitude;
	}

	////////////////////////////////////////////////////////////////////////////

	public function altitude()
	{
		return $this->altitude;
	}

	////////////////////////////////////////////////////////////////////////////

	public function address()
	{
		return $this->address;
	}

	////////////////////////////////////////////////////////////////////////////

	private $latitude;
	private $longitude;
	private $altitude;
	private $address;
}

////////////////////////////////////////////////////////////////////////////////

function geocodeUsingOpenStreetMap( $address )
{
	//	Preconditions checked by calling function.

	//	Define the URL preamble:
	$url = 'http://nominatim.openstreetmap.org/search?format=xml&email=darin-openstreetmap'.at.'44clarence.com&q=';

	//	Append the address itself:
	$url .= $address;

	//	Get the result:
	$result = fetchContent( $url );

	//	If we had a failure, bug out:
	if ( !isNonEmptyString( $result ) ) return $result;

	//	Convert the result to XML:
	$xml = simplexml_load_string( $result, 'SimpleXMLElement', LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_COMPACT );
	assert( '$xml !== false' );

	//	In the unlikely event the conversion failed, bug out:
	if ( $xml === false ) return $xml;

	//	Extract the 'place' record:
	$place = $xml->place;

	//	Extract the latitude, longitude and canonical address; note that the
	//	SimpleXML subsystem stores everything as objects, so we have to force
	//	a conversion:
	$latitude = (string)$place['lat'];
	$longitude = (string)$place['lon'];
	$altitude = emptyString;	//	OSM does not yet provide this information
	$address = (string)$place['display_name'];

	//	Create a new "geocode location" object from this information:
	$location = new GeocodeLocation( $latitude, $longitude, $altitude, $address );

	//	Return the location:
	return $location;
}

////////////////////////////////////////////////////////////////////////////////

function reverseGeocodeUsingOpenStreetMap( $latitude, $longitude )
{
	//	Preconditions checked by calling function.

	//	Define the URL preamble:
	$url = 'http://nominatim.openstreetmap.org/reverse?format=xml&email=darin-openstreetmap'.at.'44clarence.com';

	//	Append the latitude:
	$url .= '&lat='.$latitude;

	//	Append the longitude:
	$url .= '&lon='.$longitude;

	//	Get the result:
	$result = fetchContent( $url );

	//	If we had a failure, bug out:
	if ( !isNonEmptyString( $result ) ) return $result;

	//	Convert the result to XML:
	$xml = simplexml_load_string( $result, 'SimpleXMLElement', LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_COMPACT );
	assert( '$xml !== false' );

	//	In the unlikely event the conversion failed, bug out:
	if ( $xml === false ) return $xml;

	//	Define the altitude:
	$altitude = emptyString;	//	OSM does not yet provide this information

	//	Extract the result:
	$address = (string)$xml->result[0];

	//	Create a new "geocode location" object from this information:
	$location = new GeocodeLocation( $latitude, $longitude, $altitude, $address );

	//	Return the location:
	return $location;
}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function googleMapsHelperInner( $url )
{
	//	Define the complete URL:
	$url = 'http://maps.googleapis.com/maps/api/'.$url;

	//	Get the result:
	$result = fetchContent( $url );

	//	If we had a failure, bug out:
	if ( !isNonEmptyString( $result ) ) return $result;

	//	Convert the result to XML:
	$xml = simplexml_load_string( $result, 'SimpleXMLElement', LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_COMPACT );

	//	In the unlikely event the conversion failed, bug out:
	if ( $xml === false ) return $xml;

	//	Return the "result" record:
	return $xml->result[0];
}

////////////////////////////////////////////////////////////////////////////////

function googleMapsHelper( $url )
{
	//	Get the result of the URL request:
	$result = googleMapsHelperInner( 'geocode/xml?sensor=false&'.$url );

	//	If the request failed, bug out:
	if ( $result === false ) return false;

	//	Extract the 'location' record:
	$location = $result->geometry->location;

	//	Extract the latitude, longitude and canonical address; note that the
	//	SimpleXML subsystem stores everything as objects, so we have to force
	//	a conversion:
	$latitude = (string)$location->lat;
	$longitude = (string)$location->lng;
	$address = (string)$result->formatted_address;

	//	If we have a non-empty latitude and longitude, try to fetch the
	//	altitude at this location:
	$altitude = emptyString;
	if ( isNumericString( $latitude ) && isNumericString( $longitude ) )
	{
		//	Get the result of the altitude request:
		$result = googleMapsHelperInner( 'elevation/xml?sensor=false&locations='.$latitude.comma.$longitude );

		//	If the request didn't fail, extract the altitude:
		if ( $result !== false ) $altitude = (string)$result->elevation;
	}

	//	Create a new "geocode location" object from this information:
	$location = new GeocodeLocation( $latitude, $longitude, $altitude, $address );

	//	Return the location:
	return $location;
}

////////////////////////////////////////////////////////////////////////////////

function geocodeUsingGoogleMaps( $address )
{
	return googleMapsHelper( 'address='.$address );
}

////////////////////////////////////////////////////////////////////////////////

function reverseGeocodeUsingGoogleMaps( $latitude, $longitude )
{
	return googleMapsHelper( 'latlng='.(string)$latitude.comma.(string)$longitude );
}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function geocode( $address )
{
	assert( 'isNonEmptyString( $address )' );

	$address = urlencode( $address );

	return geocodeUsingGoogleMaps( $address );
}

////////////////////////////////////////////////////////////////////////////////

function reverseGeocode( $latitude, $longitude )
{
	assert( 'isNumeric( $latitude )' );
	assert( 'isNumeric( $longitude )' );

	return reverseGeocodeUsingGoogleMaps( $latitude, $longitude );
}

////////////////////////////////////////////////////////////////////////////////

?>