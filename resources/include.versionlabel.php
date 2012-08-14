<?php

////////////////////////////////////////////////////////////////////////////////

//namespace org\givingtree\versionLabel;

////////////////////////////////////////////////////////////////////////////////

require( 'include.versionlabel.'.language.'.php' );

////////////////////////////////////////////////////////////////////////////////

define( 'keyVersionLabelId', 'li' );
define( 'keyVersionLabelNumber', 'l' );

////////////////////////////////////////////////////////////////////////////////

define( 'fieldVersionLabelId', 'versionLabelId' );
define( 'fieldVersionLabelNumber', 'versionLabelNumber' );
define( 'fieldVersionLabelTitle', 'versionLabelTitle' );
define( 'fieldVersionLabelDirectoryName', 'versionLabelDirectoryName' );

////////////////////////////////////////////////////////////////////////////////

define( 'versionLabelTableName', 'versionLabels' );

////////////////////////////////////////////////////////////////////////////////

define( 'versionNumberCustom', 100 );

////////////////////////////////////////////////////////////////////////////////

class VersionLabels
{
	private $albumId;
	private $versionLabelIds = array();
	private $versionLabelNumbers = array();
	private $versionLabelTitles = array();
	private $versionLabelDirectoryNames = array();

	private $isModified = false;

	public function isValid()
	{
		assert( 'isArray( $this->versionLabelIds )' );
		assert( 'isNonEmptyIntString( $this->albumId )' );
		assert( 'isArray( $this->versionLabelNumbers )' );
		assert( 'isArray( $this->versionLabelTitles )' );
		assert( 'isArray( $this->versionLabelDirectoryNames )' );
		assert( 'count( $this->versionLabelNumbers ) === count( $this->versionLabelTitles )' );
		assert( 'count( $this->versionLabelNumbers ) === count( $this->versionLabelDirectoryNames )' );
		assert( 'isBool( $this->isModified )' );

		return true;
	}

	public function isNotValid()
	{
		return !$this->isValid();
	}

	public static function schema()
	{
		return
		fieldVersionLabelId.space.'INT PRIMARY KEY NOT NULL AUTO_INCREMENT'.comma.
		fieldAlbumId.space.'INT REFERENCES '.albumTableName.leftParenthesis.fieldAlbumId.rightParenthesis.comma.
		fieldVersionLabelNumber.space.'INT NOT NULL'.comma.
		fieldVersionLabelTitle.space.'TEXT NOT NULL'.comma.
		fieldVersionLabelDirectoryName.space.'TEXT NOT NULL';
	}

	public function fromSchemaArray( $rows )
	{
		assert( 'isNotEmpty( $rows )' );

		while ( $array = nextRow( $rows ) )
		{
			$this->versionLabelIds[] = $array[fieldVersionLabelId];
			$this->versionLabelNumbers[] = $array[fieldVersionLabelNumber];
			$this->versionLabelTitles[] = $array[fieldVersionLabelTitle];
			$this->versionLabelDirectoryNames[] = $array[fieldVersionLabelDirectoryName];
		}

		assert( '$this->isValid()' );
	}

	public function toSchemaString( $row, $isForUpdate )
	{
		assert( 'isInt( $row )' );
		assert( '$row > -1' );
		assert( '$row < $this->count()' );
		assert( 'isBool( $isForUpdate )' );

		return
//		fieldVersionLabelId.equals.singleQuote.$this->versionLabelId.singleQuote.comma.
		( $isForUpdate ? fieldAlbumId.equals : emptyString ).singleQuote.$this->albumId.singleQuote.comma.
		( $isForUpdate ? fieldVersionLabelNumber.equals : emptyString ).singleQuote.$this->versionLabelNumbers[$row].singleQuote.comma.
		( $isForUpdate ? fieldVersionLabelTitle.equals : emptyString ).singleQuote.mysql_real_escape_string( $this->versionLabelTitles[$row] ).singleQuote.comma.
		( $isForUpdate ? fieldVersionLabelDirectoryName.equals : emptyString ).singleQuote.mysql_real_escape_string( $this->versionLabelDirectoryNames[$row] ).singleQuote;
	}

	public function __toString()
	{
		$string = fieldAlbumId.equals.$this->albumId.newline;
		foreach ( $this->versionLabelIds as $row => $versionLabelId )
		{
			$string .=
				tab.fieldVersionLabelId.equals.$versionLabelId.newline.
				tab.fieldVersionLabelNumber.equals.$this->versionLabelNumbers[$row].newline.
				tab.fieldVersionLabelTitle.equals.$this->versionLabelTitles[$row].newline.
				tab.fieldVersionLabelDirectoryName.equals.$this->versionLabelDirectoryNames[$row].newline;
		}
		return $string;
	}

	public function create( $albumId )
	{
		assert( 'isNonEmptyString( $albumId )' );

		$this->albumId = $albumId;
	}

	public function count()
	{
		assert( '$this->isValid()' );

		return count( $this->versionLabelNumbers );
	}

	public function versionNumber( $versionTitle )
	{
		//	Preconditions:
		assert( 'isNonEmptyString( $versionTitle )' );

		//	Get the version number corresponding to this version title:
		$row = array_isearch( $versionTitle, $this->versionLabelTitles );

		//	If we found a corresponding version number, return it:
		if ( $row !== false ) return $this->versionLabelNumbers[$row];

		//	Ugh:
		global $standardVersions;

		//	Get the version number from the list of standard versions:
		$versionNumber = array_valuei( $standardVersions, $versionTitle );

		//	If it's not one of the standard versions, assign it the next highest custom version:
		if ( $versionNumber === null )
		{
			//	Get the list of version numbers associated with this album (so far):
			$versionNumbers = array_values( $this->versionLabelNumbers );

			//	Start with the first available custom version number:
			$versionNumber = ( versionNumberCustom - 1);

			//	Convert each of these to integers, compare, and trap the highest:
			foreach ( $versionNumbers as $v )
			{
				assert( 'isNonEmptyIntString( $v )' );

				//	Convert this string to an integer:
				$v = (int)$v;

				//	Trap the highest version number used so far:
				if ( $v > $versionNumber ) $versionNumber = $v;
			}

			//	Reformat the custom version number into a string:
			$versionNumber = formattedVersionNumber( $versionNumber + 1 );
		}

		//	Add this version number to the list of version numbers associated with this album:
		$this->versionLabelNumbers[] = $versionNumber;
		$this->versionLabelTitles[] = $versionTitle;
		$this->versionLabelDirectoryNames[] = $versionTitle;

		//	Signal that we've been modified:
		$this->setIsModified();

		//	Return the newly-assigned version number:
		return $versionNumber;
	}

	public function versionDirectoryName( $versionLabelNumber )
	{
		assert( 'isPositiveIntString( $versionLabelNumber )' );

		$key = array_search( $versionLabelNumber, $this->versionLabelNumbers, true );
		assert( '$key !== false' );

		return $this->versionLabelDirectoryNames[$key];
	}

	public function updatedVersionLabels()
	{
		$queryString = emptyString;
		foreach ( $this->versionLabelNumbers as $row => $versionLabelNumber )
		{
			if ( array_key_exists( $row, $this->versionLabelIds ) === false )
			{
				if ( $queryString !== emptyString ) $queryString .= comma;

				$queryString .= leftParenthesis.$this->toSchemaString( $row, false ).rightParenthesis;
			}
		}

		if ( $queryString !== emptyString ) $queryString =
			leftParenthesis.
			fieldAlbumId.comma.
			fieldVersionLabelNumber.comma.
			fieldVersionLabelTitle.comma.
			fieldVersionLabelDirectoryName.
			rightParenthesis.space.'VALUES'.space.$queryString;

		return $queryString;
	}

	public function isModified()
	{
		return $this->isModified;
	}

	public function isNotModified()
	{
		return !$this->isModified();
	}

	protected function setIsModified( $isModified = true )
	{
		$this->isModified = $isModified;
	}
}

////////////////////////////////////////////////////////////////////////////////

function isValidVersionLabel( $object )
{
	return isValidObject( $object ) && is_a( $object, 'VersionLabels' );
}

////////////////////////////////////////////////////////////////////////////////

function isNotValidVersionLabel( $object )
{
	return !isValidVersionLabel( $object );
}

////////////////////////////////////////////////////////////////////////////////

?>