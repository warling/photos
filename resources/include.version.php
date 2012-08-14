<?php

////////////////////////////////////////////////////////////////////////////////

//namespace org\givingtree\version;

////////////////////////////////////////////////////////////////////////////////

require( 'include.version.'.language.'.php' );

////////////////////////////////////////////////////////////////////////////////

define( 'keyVersionId', 'vi' );
define( 'keyVersionNumber', 'v' );
define( 'keyVersionRotationAngle', 'r' );

////////////////////////////////////////////////////////////////////////////////

define( 'fieldVersionId', 'versionId' );
define( 'fieldVersionNumber', 'versionNumber' );
define( 'fieldVersionWidth', 'versionWidth' );
define( 'fieldVersionHeight', 'versionHeight' );
define( 'fieldVersionFileExtension', 'versionFileExtension' );
define( 'fieldVersionFileBytes', 'versionFileBytes' );

////////////////////////////////////////////////////////////////////////////////

define( 'versionTableName', 'versions' );

////////////////////////////////////////////////////////////////////////////////

define( 'versionNumberDigits', '3' );
define( 'versionNumberFormat', '%0'.versionNumberDigits.'d' );

////////////////////////////////////////////////////////////////////////////////

define( 'versionNumberDNG', 1 );
define( 'versionNumberCR2', 2 );
define( 'versionNumberRAW', 3 );
define( 'versionNumberTIFF', 10 );
define( 'versionNumberTIF', 11 );
define( 'versionNumberJPG', 20 );
define( 'versionNumberJPEG', 21 );
define( 'versionNumberPNG', 22 );
define( 'versionNumberGIF', 23 );
define( 'versionNumberAVI', 30 );
define( 'versionNumberUnedited', 40 );
define( 'versionNumberOriginal', 41 );
define( 'versionNumberOriginals', 42 );
define( 'versionNumberUncropped', 43 );
define( 'versionNumberCropped', 50 );
define( 'versionNumberEdited', 60 );
define( 'versionNumberDefault', 70 );
define( 'versionNumberFull', 80 );
define( 'versionNumberLarge', 81 );
define( 'versionNumberMedium', 82 );
define( 'versionNumberSmall', 83 );
define( 'versionNumberTiny', 84 );
define( 'versionNumberThumbnail', 90 );

////////////////////////////////////////////////////////////////////////////////

class Version extends Object
{
	//	Persistent:
	private $versionId;
	private $imageId;

	private $versionNumber;

	private $versionWidth;
	private $versionHeight;
	private $versionResolution;

	private $versionFileExtension;
	private $versionFileBytes;

	//	Non-persistent:
	private $image;
	private $versionLabel;

	////////////////////////////////////////////////////////////////////////////

	protected function objectId()
	{
		return $this->versionId();
	}

	////////////////////////////////////////////////////////////////////////////

	protected function objectIdField()
	{
		return fieldVersionId;
	}

	////////////////////////////////////////////////////////////////////////////

	protected function objectTableName()
	{
		return versionTableName;
	}

	////////////////////////////////////////////////////////////////////////////

	public function isValid()
	{
		assert( 'parent::isValid()' );

		assert( 'isPositiveIntString( $this->versionId )' );
		assert( 'isPositiveIntString( $this->imageId )' );

		assert( 'isPositiveIntString( $this->versionNumber )' );

		assert( 'isNonNegativeIntString( $this->versionWidth )' );
		assert( 'isNonNegativeIntString( $this->versionHeight )' );
		assert( 'isNonNegativeInt( $this->versionResolution )' );
		assert( '$this->versionResolution === (int)$this->versionWidth * (int)$this->versionHeight' );

		assert( 'isEmpty( $this->image ) || isValidImage( $this->image )' );
		assert( 'isPositiveIntString( $this->versionFileBytes )' );

		return true;
	}

	////////////////////////////////////////////////////////////////////////////

	public static function schema()
	{
		return
		parent::schema().

		fieldVersionId.space.'INT UNSIGNED PRIMARY KEY NOT NULL'.comma.
		fieldImageId.space.'INT UNSIGNED REFERENCES '.imageTableName.leftParenthesis.fieldImageId.rightParenthesis.comma.

		fieldVersionNumber.space.'INT UNSIGNED NOT NULL'.comma.

		fieldVersionWidth.space.'INT UNSIGNED'.comma.
		fieldVersionHeight.space.'INT UNSIGNED'.comma.

		fieldVersionFileExtension.space.'TEXT NOT NULL'.comma.
		fieldVersionFileBytes.space.'INT UNSIGNED NOT NULL'
		;
	}

	////////////////////////////////////////////////////////////////////////////

	public function fromSchemaArray( $array )
	{
		$this->versionId = $array[fieldVersionId];
		$this->imageId = $array[fieldImageId];

		$this->versionNumber = $array[fieldVersionNumber];

		$this->versionWidth = $array[fieldVersionWidth];
		$this->versionHeight = $array[fieldVersionHeight];
		$this->versionResolution = ( (int)$this->versionWidth * (int)$this->versionHeight );

		$this->versionFileExtension = $array[fieldVersionFileExtension];
		$this->versionFileBytes = $array[fieldVersionFileBytes];

		parent::fromSchemaArray( $array );
	}

	////////////////////////////////////////////////////////////////////////////

	public function toSchemaString()
	{
		return
		parent::toSchemaString().

		fieldVersionId.equals.singleQuote.$this->versionId.singleQuote.comma.
		fieldImageId.equals.singleQuote.$this->imageId.singleQuote.comma.

		fieldVersionNumber.equals.singleQuote.$this->versionNumber.singleQuote.comma.

		fieldVersionWidth.equals.singleQuote.$this->versionWidth.singleQuote.comma.
		fieldVersionHeight.equals.singleQuote.$this->versionHeight.singleQuote.comma.

		fieldVersionFileExtension.equals.singleQuote.$this->versionFileExtension.singleQuote.comma.
		fieldVersionFileBytes.equals.singleQuote.$this->versionFileBytes.singleQuote
		;
	}

	////////////////////////////////////////////////////////////////////////////

	public function toXmlString()
	{
		$values = array(
			fieldVersionId => $this->versionId,
			fieldImageId => $this->imageId,

			fieldVersionNumber => $this->versionNumber,

			fieldVersionWidth => $this->versionWidth,
			fieldVersionHeight => $this->versionHeight,

			fieldVersionFileExtension => $this->versionFileExtension,
			fieldVersionFileBytes => $this->versionFileBytes
			);

		return
			parent::toXmlString().
			toXmlString( $values );
	}

	////////////////////////////////////////////////////////////////////////////

	public function createVersion( $image, $versionNumber, $versionLabel, $versionWidth, $versionHeight, $versionFileBytes, $versionFileExtension, $databaseConnection )
	{
		assert( 'isValidImage( $image )' );
		assert( 'isPositiveIntString( $versionNumber )' );
		assert( 'isNonEmptyString( $versionLabel )' );
		assert( 'isNonNegativeIntString( $versionWidth )' );
		assert( 'isNonNegativeIntString( $versionHeight )' );
		assert( 'isPositiveIntString( $versionFileBytes )' );
		assert( 'isNonEmptyString( $versionFileExtension )' );

		$this->versionId = parent::createObject( $databaseConnection );
		$this->imageId = $image->imageId();

		$this->versionNumber = $versionNumber;

		$this->versionWidth = $versionWidth;
		$this->versionHeight = $versionHeight;
		$this->versionResolution = ( (int)$versionWidth * (int)$versionHeight );

		$this->versionFileBytes = $versionFileBytes;
		$this->versionFileExtension = $versionFileExtension;

		$this->image = $image;
		$this->versionLabel = $versionLabel;
	}

	////////////////////////////////////////////////////////////////////////////

	public function versionId()
	{
		return $this->versionId;
	}

	////////////////////////////////////////////////////////////////////////////

	public function imageId()
	{
		return $this->imageId;
	}

	////////////////////////////////////////////////////////////////////////////

	public function image()
	{
		assert( 'isValidImage( $this->image )' );

		return $this->image;
	}

	////////////////////////////////////////////////////////////////////////////

	public function versionNumber()
	{
		return $this->versionNumber;
	}

	////////////////////

	public function setVersionNumber( $versionNumber )
	{
		assert( 'isPositiveIntString( $versionNumber )' );

		if ( $versionNumber !== $this->versionNumber )
		{
			$this->setIsModified();

			$this->versionNumber = $versionNumber;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function versionWidth()
	{
		assert( '$this->isValid()' );

		return $this->versionWidth;
	}

	////////////////////////////////////////////////////////////////////////////

	public function versionHeight()
	{
		assert( '$this->isValid()' );

		return $this->versionHeight;
	}

	////////////////////////////////////////////////////////////////////////////

	public function versionResolution()
	{
		assert( '$this->isValid()' );

		return $this->versionResolution;
	}

	////////////////////

	public function setVersionResolution( $versionWidth, $versionHeight )
	{
		assert( 'isInt( $versionWidth )' );
		assert( '$versionWidth > -1' );
		assert( 'isInt( $versionHeight )' );
		assert( '$versionHeight > -1' );

		$versionResolution = ( $versionWidth * $versionHeight );

		$versionWidth = (string)$versionWidth;
		$versionHeight = (string)$versionHeight;

		if ( ( $versionWidth !== $this->versionWidth ) || ( $versionHeight != $this->versionHeight ) )
		{
			$this->setIsModified();

			$this->versionWidth = $versionWidth;
			$this->versionHeight = $versionHeight;
			$this->versionResolution = (string)$versionResolution;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function versionFileExtension()
	{
		return $this->versionFileExtension;
	}

	////////////////////

	public function setVersionFileExtension( $versionFileExtension )
	{
		assert( 'isNonEmptyString( $versionFileExtension )' );

		if ( $versionFileExtension !== $this->versionFileExtension )
		{
			$this->setIsModified();

			$this->versionFileExtension = $versionFileExtension;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function versionFileBytes()
	{
		return $this->versionFileBytes;
	}

	////////////////////

	public function setVersionFileBytes( $versionFileBytes )
	{
		assert( 'isNonEmptyInt( $versionFileBytes )' );
		assert( '$versionFileBytes > 0' );

		$versionFileBytes = (string)$versionFileBytes;

		if ( $versionFileBytes !== $this->versionFileBytes )
		{
			$this->setIsModified();

			$this->versionFileBytes = $versionFileBytes;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function versionFilePath( $databaseConnection )
	{
		$image = $this->image();
		$album = $image->album();
		$user  = $album->user();

		$userId = $user->userId();
		$albumNumber = $album->albumNumber();
		$versionNumber = $this->versionNumber();
		$imageNumber = $image->imageNumber();

		$userId = sprintf( userIdFormat, $userId );	//	ASCII-only
		$albumNumber = sprintf( albumNumberFormat, $albumNumber );	//	ASCII-only
		$versionNumber = sprintf( versionNumberFormat, $versionNumber );	//	ASCII-only
		$imageNumber = sprintf( imageNumberFormat, $imageNumber );	//	ASCII-only
		$imageTitle = sanitizedFileName( $image->imageTitle(), $databaseConnection );

		$userDirectoryName = $user->userDirectoryName();
		$albumDirectoryName = $album->albumDirectoryNameOld();
		$versionDirectoryName = $this->versionLabel.pathSeparator;

		$versionFileExtension = $this->versionFileExtension();

		return rootDirectoryPath.$userDirectoryName.$albumDirectoryName.$versionDirectoryName.keyUserId.$userId.keyAlbumNumber.$albumNumber.keyVersionNumber.$versionNumber.keyImageNumber.$imageNumber.space.$imageTitle.dot.$versionFileExtension;
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	public static function versionByVersionId( $versionId, $databaseConnection )
	{
		assert( 'isPositiveInt( $versionId ) || isPositiveIntString( $versionId )' );

		if ( $version = Object::inMemoryObject( $versionId ) )
		{
			assert( 'isValidVersion( $version )' );
			return $version;
		}

		$and = ' AND ';
		$query =
			'SELECT * FROM '.
			userTableName.comma.
			albumTableName.comma.
			imageTableName.comma.
			versionTableName.comma.
			versionLabelTableName.
			' WHERE '.
			versionLabelTableName.dot.fieldVersionLabelNumber.equals.versionTableName.dot.fieldVersionNumber.$and.
			versionLabelTableName.dot.fieldAlbumId.equals.albumTableName.dot.fieldAlbumId.$and.
			versionTableName.dot.fieldVersionId.equals.singleQuote.$versionId.singleQuote.$and.
			versionTableName.dot.fieldImageId.equals.imageTableName.dot.fieldImageId.$and.
			imageTableName.dot.fieldAlbumId.equals.albumTableName.dot.fieldAlbumId.$and.
			albumTableName.dot.fieldUserId.equals.userTableName.dot.fieldUserId;

		return Version::versionByQuery( $query, $databaseConnection );
	}

	////////////////////

	public static function versionByVersionNumber( $userId, $albumNumber, $imageNumber, $versionNumber, $databaseConnection )
	{
		assert( 'isPositiveInt( $userId )        || isNonEmptyString( $userId )' );
		assert( 'isPositiveInt( $albumNumber )   || isPositiveIntString( $albumNumber )' );
		assert( 'isPositiveInt( $imageNumber )   || isPositiveIntString( $imageNumber )' );
		assert( 'isPositiveInt( $versionNumber ) || isNonEmptyString( $versionNumber )' );

		$fieldUserId = ( isPositiveInt( $userId ) || isPositiveIntString( $userId ) ) ? fieldUserId : fieldUserName;

		$and = ' AND ';
		$where =
			versionLabelTableName.dot.fieldVersionLabelNumber.equals.versionTableName.dot.fieldVersionNumber.$and.
			versionLabelTableName.dot.fieldAlbumId.equals.albumTableName.dot.fieldAlbumId.$and.
			versionTableName.dot.fieldImageId.equals.imageTableName.dot.fieldImageId.$and.
			imageTableName.dot.fieldImageNumber.equals.singleQuote.$imageNumber.singleQuote.$and.
			imageTableName.dot.fieldAlbumId.equals.albumTableName.dot.fieldAlbumId.$and.
			albumTableName.dot.fieldAlbumNumber.equals.singleQuote.$albumNumber.singleQuote.$and.
			albumTableName.dot.fieldUserId.equals.userTableName.dot.fieldUserId.$and.
			userTableName.dot.$fieldUserId.equals.singleQuote.$userId.singleQuote.$and;

		if ( isPositiveInt( $versionNumber ) || isPositiveIntString( $versionNumber ) )
		{
			$where .=
				versionTableName.dot.fieldVersionNumber.equals.singleQuote.$versionNumber.singleQuote;
		}
		else
		{
			$where .=
				versionLabelTableName.dot.fieldVersionLabelTitle.equals.singleQuote.$versionNumber.singleQuote;
		}

		$query =
			'SELECT * FROM '.
			userTableName.comma.
			albumTableName.comma.
			imageTableName.comma.
			versionTableName.comma.
			versionLabelTableName.
			' WHERE '.
			$where;

		return Version::versionByQuery( $query, $databaseConnection );
	}

	////////////////////

	public static function versionsByImageId( $imageId, $databaseConnection )
	{
		assert( 'isPositiveIntString( $imageId )' );

		$and = ' AND ';
		$query =
			'SELECT * FROM '.
			userTableName.comma.
			albumTableName.comma.
			imageTableName.comma.
			versionTableName.comma.
			versionLabelTableName.
			' WHERE '.
			versionLabelTableName.dot.fieldVersionLabelNumber.equals.versionTableName.dot.fieldVersionNumber.$and.
			versionLabelTableName.dot.fieldAlbumId.equals.albumTableName.dot.fieldAlbumId.$and.
			versionTableName.dot.fieldImageId.equals.imageTableName.dot.fieldImageId.$and.
			imageTableName.dot.fieldImageId.equals.singleQuote.$imageId.singleQuote.$and.
			imageTableName.dot.fieldAlbumId.equals.albumTableName.dot.fieldAlbumId.$and.
			albumTableName.dot.fieldUserId.equals.userTableName.dot.fieldUserId
			;

		return Version::versionsByQuery( $query, $databaseConnection );
	}

	////////////////////

	public static function versionsByAlbumId( $albumId, $databaseConnection )
	{
		assert( 'isPositiveIntString( $albumId )' );

		$and = ' AND ';
		$query =
			'SELECT * FROM '.
			userTableName.comma.
			albumTableName.comma.
			imageTableName.comma.
			versionTableName.comma.
			versionLabelTableName.
			' WHERE '.
			versionLabelTableName.dot.fieldVersionLabelNumber.equals.versionTableName.dot.fieldVersionNumber.$and.
			versionLabelTableName.dot.fieldAlbumId.equals.albumTableName.dot.fieldAlbumId.$and.
			versionTableName.dot.fieldImageId.equals.imageTableName.dot.fieldImageId.$and.
			imageTableName.dot.fieldAlbumId.equals.albumTableName.dot.fieldAlbumId.$and.
			albumTableName.dot.fieldAlbumId.equals.singleQuote.$albumId.singleQuote.$and.
			albumTableName.dot.fieldUserId.equals.userTableName.dot.fieldUserId
			;

		return Version::versionsByQuery( $query, $databaseConnection );
	}

	////////////////////

	private static function versionByQuery( $query, $databaseConnection )
	{
		assert( 'isNonEmptyString( $query )' );

		$result = executeQuery( $query, 'Could not retrieve version using query "'.$query.doubleQuote, $databaseConnection );
		assert( 'isNotEmpty( $result )' );

		if ( isEmpty( $result ) ) return null;

		$rowCount = rowCount( $result );
		assert( 'rowCount( $result ) > -1' );
		assert( 'rowCount( $result ) < 2' );

		if ( $rowCount !== 1 ) return null;

		$array = nextRow( $result );

		$user = User::userFromSchemaArray( $array );
		assert( 'isValidUser( $user )' );

		if ( isNotValidUser( $user ) ) return null;

		$album = Album::albumFromSchemaArray( $array, $user, $databaseConnection );
		assert( 'isValidAlbum( $album )' );

		if ( isEmpty( $album ) ) return null;

		$image = Image::imageFromSchemaArray( $array, $album );
		assert( 'isNotEmpty( $image )' );

		if ( isEmpty( $image ) ) return null;

		$versionLabel = $array[fieldVersionLabelTitle];
		assert( 'isNonEmptyString( $versionLabel )' );

		return Version::versionFromSchemaArray( $array, $image, $versionLabel );
	}

	////////////////////////////////////////////////////////////////////////////

	private static function versionsByQuery( $query, $databaseConnection )
	{
		assert( 'isNonEmptyString( $query )' );

		$queryResult = executeQuery( $query, 'Could not retrieve versions using query "'.$query.doubleQuote, $databaseConnection );
		assert( 'isNotEmpty( $queryResult )' );

		if ( isEmpty( $queryResult ) ) return null;
		assert( 'rowCount( $queryResult ) > -1' );

		$versions = array();
		while ( $array = nextRow( $queryResult ) )
		{
			$user = User::userFromSchemaArray( $array );
			assert( 'isValidUser( $user )' );

			if ( isNotValidUser( $user ) ) return null;

			$album = Album::albumFromSchemaArray( $array, $user, $databaseConnection );
			assert( 'isValidAlbum( $album )' );

			if ( isNotValidAlbum( $album ) ) return null;

			$image = Image::imageFromSchemaArray( $array, $album );
			assert( 'isValidImage( $image )' );

			if ( isNotValidImage( $image ) ) return null;

			$versionLabel = $array[fieldVersionLabelTitle];
			assert( 'isNonEmptyString( $versionLabel )' );

			$version = Version::versionFromSchemaArray( $array, $image, $versionLabel );
			assert( 'isValidVersion( $version )' );

			if ( isNotValidVersion( $version ) ) return null;

			$versions[] = $version;
		}

		return $versions;
	}

	////////////////////

	public static function versionFromSchemaArray( $array, $image, $versionLabel )
	{
		assert( 'isValidImage( $image )' );
		assert( 'isNonEmptyString( $versionLabel )' );

		$version = Object::objectFromSchemaArray( $array, fieldVersionId, classNameVersion );
		assert( 'isValidVersion( $version )' );

		if ( isNotValidVersion( $version ) ) return null;

		$version->image = $image;
		assert( '$version->imageId() == $image->imageId()' );

		$version->versionLabel = $versionLabel;

		return $version;
	}
}

////////////////////////////////////////////////////////////////////////////////

function isValidVersion( $object )
{
	return isValidObject( $object ) && is_a( $object, 'Version' );
}

////////////////////////////////////////////////////////////////////////////////

function isNotValidVersion( $object )
{
	return !isValidVersion( $object );
}

////////////////////////////////////////////////////////////////////////////////

function formattedVersionNumber( $versionNumber )
{
	assert( 'isNonEmptyInt( $versionNumber )' );
	assert( '$versionNumber > 0' );

	return sprintf( versionNumberFormat, $versionNumber );	//	ASCII-only
}

////////////////////////////////////////////////////////////////////////////////

?>