<?php

////////////////////////////////////////////////////////////////////////////////

//namespace org\givingtree\object;

////////////////////////////////////////////////////////////////////////////////

//require( 'include.object.'.language.'.php' );

////////////////////////////////////////////////////////////////////////////////

define( 'idTableName', 'ids' );
define( 'fieldMostRecentId', 'mostRecentId' );

////////////////////////////////////////////////////////////////////////////////

class Object
{
	//	Non-persistent:
	private $isNew = false;
	private $isModified = false;

	//	Static:
	private static $objects = array();
	private static $modifiedObjects = array();

	////////////////////////////////////////////////////////////////////////////

	public function isValid()
	{
		assert( 'isBool( $this->isModified )' );
		assert( 'Object::$objects[$this->objectId()] === $this' );

		return true;
	}

	////////////////////

	public function isNotValid()
	{
		return !$this->isValid();
	}

	////////////////////////////////////////////////////////////////////////////

	protected function createObject( $databaseConnection )
	{
		$result = executeQuery( 'INSERT INTO '.idTableName.' VALUES (0)', 'Unable to assign new object id.', $databaseConnection );
		assert( 'isNotEmpty( $result )' );

		$objectId = mysql_insert_id( $databaseConnection );
		assert( 'isPositiveInt( $objectId )' );

		$objectId = (string)$objectId;
		assert( 'isPositiveIntString( $objectId )' );

		Object::$objects[$objectId] = $this;

		$this->setIsNew( true, $objectId );

		return $objectId;
	}

	////////////////////////////////////////////////////////////////////////////

	protected function objectId()
	{
		assert( 'false' );	//	Override this function. Do not call base class function.
	}

	////////////////////////////////////////////////////////////////////////////

	protected function objectIdField()
	{
		assert( 'false' );	//	Override this function. Do not call base class function.
	}

	////////////////////////////////////////////////////////////////////////////

	protected function objectTableName()
	{
		assert( 'false' );	//	Override this function. Do not call base class function.
	}

	////////////////////////////////////////////////////////////////////////////

	protected static function schema()
	{
		return emptyString;
	}

	////////////////////////////////////////////////////////////////////////////

	protected function fromSchemaArray( $array )
	{
		//	Call this at the end of your base class override.

		assert( '!isset( Object::$objects[$this->objectId()] )' );
		Object::$objects[$this->objectId()] = $this;

		assert( '$this->isValid()' );

		return true;
	}

	////////////////////////////////////////////////////////////////////////////

	protected function toSchemaString()
	{
		return emptyString;
	}

	////////////////////////////////////////////////////////////////////////////

	protected function toXmlString()
	{
		return emptyString;
	}

	////////////////////

	protected function fromXmlString( $string )
	{
		$this->fromSchemaArray( fromXmlString( $string ) );
	}

	////////////////////////////////////////////////////////////////////////////

	public function __toString()
	{
		return $this->toXmlString();
	}

	////////////////////////////////////////////////////////////////////////////

	private function isNew()
	{
		return $this->isNew;
	}

	////////////////////

	private function isNotNew()
	{
		return !$this->isModified();
	}

	////////////////////

	private function setIsNew( $isNew = true, $objectId = null )
	{
		assert( 'isBool( $isNew )' );

		$this->isNew = $isNew;

		if ( $isNew ) $this->setIsModified( true, $objectId );
	}

	////////////////////////////////////////////////////////////////////////////

	public function isModified()
	{
		return $this->isModified;
	}

	////////////////////

	public function isNotModified()
	{
		return !$this->isModified();
	}

	////////////////////

	protected function setIsModified( $isModified = true, $objectId = null )
	{
		assert( 'isBool( $isModified )' );

		$this->isModified = $isModified;

		if ( isEmpty( $objectId ) ) $objectId = $this->objectId();
		assert( 'isNonEmptyString( $objectId )' );

		if ( $isModified )
		{
			Object::$modifiedObjects[$objectId] = $this;
		}
		else
		{
			unset( Object::$modifiedObjects[$objectId] );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	protected function store( $databaseConnection )
	{
		assert( '$this->isValid()' );
		assert( '$this->isModified()' );

		$objectTableName = $this->objectTableName();
		$schemaString = $this->toSchemaString();

		if ( $this->isNew() )
		{
			$query = 'INSERT INTO '.$objectTableName.' SET '.$schemaString;
			$error = 'Could not insert new object into table "'.$objectTableName.doubleQuote;
		}
		else
		{
			$query = 'UPDATE '.$objectTableName.' SET '.$schemaString.' WHERE '.$this->objectIdField().equals.singleQuote.$this->objectId().singleQuote;
			$error = 'Could not update object #'.$this->objectId().' in table "'.$objectTableName.doubleQuote;
		}

		$result = executeQuery( $query, $error, $databaseConnection );
		assert( 'isNotEmpty( $result )' );

		$this->setIsModified( false );
		$this->setIsNew( false );

		return true;
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	public static function storeAll( $databaseConnection )
	{
		foreach ( Object::$modifiedObjects as $object )
		{
			if ( $object->isModified() ) $object->store( $databaseConnection );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public static function IsAllStored()
	{
		foreach ( Object::$objects as $object )
		{
			if ( $object->isModified() ) return false;
		}

		return true;
	}

	////////////////////////////////////////////////////////////////////////////

	public static function IsNotAllStored()
	{
		return !Object::IsAllStored();
	}

	////////////////////////////////////////////////////////////////////////////

	protected static function isInMemory( $objectId )
	{
		assert( 'isPositiveInt( $objectId ) || isNonEmptyIntString( $objectId )' );

		return isset( Object::$objects[$objectId] );
	}

	////////////////////////////////////////////////////////////////////////////

	protected static function isNotInMemory( $objectId )
	{
		return !Object::isInMemory( $objectId );
	}

	////////////////////////////////////////////////////////////////////////////

	protected static function inMemoryObject( $objectId )
	{
		if ( Object::isNotInMemory( $objectId ) ) return null;

		$object = Object::$objects[$objectId];
		assert( 'isValidObject( $object )' );

		return $object;
	}

	////////////////////////////////////////////////////////////////////////////

	protected static function objectFromSchemaArray( $array, $fieldName, $className )
	{
		assert( 'isNotEmpty( $array )' );
		assert( 'isNonEmptyString( $className )' );

		$objectId = $array[$fieldName];
		assert( 'isPositiveIntString( $objectId )' );

		$object = Object::inMemoryObject( $objectId );

		if ( isEmpty( $object ) )
		{
			$object = new $className;
			$object->fromSchemaArray( $array );
			Object::$objects[$objectId] = $object;
		}

		return $object;
	}
}

////////////////////////////////////////////////////////////////////////////////

function isValidObject( $object )
{
	return ( isNonEmptyObject( $object ) && $object->isValid() );
}

////////////////////////////////////////////////////////////////////////////////

function isNotValidObject( $object )
{
	return !isValidObject( $object );
}

////////////////////////////////////////////////////////////////////////////////

?>