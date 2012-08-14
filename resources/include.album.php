<?php

////////////////////////////////////////////////////////////////////////////////

//namespace org\givingtree\album;

////////////////////////////////////////////////////////////////////////////////

require( 'include.album.'.language.'.php' );

////////////////////////////////////////////////////////////////////////////////

define( 'keyAlbumId', 'ai' );
define( 'keyAlbumNumber', 'a' );

////////////////////////////////////////////////////////////////////////////////

define( 'fieldAlbumId', 'albumId' );
define( 'fieldAlbumTimestampCreation', 'albumCreationTimestamp' );
define( 'fieldAlbumTimestampPublication', 'albumPublicationTimestamp' );
define( 'fieldAlbumTimestampModification', 'albumModificationTimestamp' );

define( 'fieldAlbumNumber', 'albumNumber' );
define( 'fieldAlbumTitle', 'albumTitle' );
define( 'fieldAlbumDescription', 'albumDescription' );
define( 'fieldAlbumTags', 'albumTags' );
define( 'fieldAlbumDoPublish', 'albumDoPublish' );
define( 'fieldAlbumDoResize', 'albumDoResize' );

define( 'fieldAlbumPhotoBundle', 'albumPhotoBundle' );

////////////////////////////////////////////////////////////////////////////////

define( 'albumTableName', 'albums' );

////////////////////////////////////////////////////////////////////////////////

define( 'albumNumberDigits', '4' );
define( 'albumNumberFormat', '%0'.albumNumberDigits.'d' );

////////////////////////////////////////////////////////////////////////////////

class Album extends Object
{
	//	Persistent:
	private $albumId;
	private $userId;

	private $albumTimestampCreation;
	private $albumTimestampModification;
	private $albumTimestampPublication;

	private $albumNumber;
	private $albumTitle;
	private $albumDescription;
	private $albumTags;
	private $albumDefaultVersionNumber;
	private $albumDoPublish;
	private $albumDoResize = true;

	//	Non-persistent:
	private $user;
	private $albumImages;
	private $albumThumbnails;
	private $albumDirectoryNameOld;
	private $albumDirectoryNameNew;

	////////////////////////////////////////////////////////////////////////////

	protected function objectId()
	{
		return $this->albumId();
	}

	////////////////////////////////////////////////////////////////////////////

	protected function objectIdField()
	{
		return fieldAlbumId;
	}

	////////////////////////////////////////////////////////////////////////////

	protected function objectTableName()
	{
		return albumTableName;
	}

	////////////////////////////////////////////////////////////////////////////

	public function isValid()
	{
		assert( 'parent::isValid()' );

		assert( 'isPositiveIntString( $this->albumId )' );
		assert( 'isPositiveIntString( $this->userId )' );

		assert( 'isNonEmptyString( $this->albumTimestampCreation )' );
		assert( 'isNonEmptyString( $this->albumTimestampModification )' );
		assert( 'isNonEmptyString( $this->albumTimestampPublication )' );

		assert( 'isPositiveIntString( $this->albumNumber )' );
		assert( 'isNonEmptyString( $this->albumTitle )' );
		assert( 'isString( $this->albumDescription )' );
		assert( 'isString( $this->albumTags )' );
		assert( 'isBooleanString( $this->albumDoPublish )' );
		assert( 'isBooleanString( $this->albumDoResize )' );

		assert( 'isEmpty( $this->user ) || isValidUser( $this->user )' );

		assert( 'isEmpty( $this->albumDirectoryNameOld ) || isDirectoryPath( $this->albumDirectoryNameOld )' );
		assert( 'isEmpty( $this->albumDirectoryNameNew ) || isDirectoryPath( $this->albumDirectoryNameNew )' );
		assert( 'isEmpty( $this->albumImages ) || isArray( $this->albumImages )' );
		assert( 'isEmpty( $this->albumThumbnails ) || isArray( $this->albumThumbnails )' );

		return true;
	}

	////////////////////////////////////////////////////////////////////////////

	protected static function schema()
	{
		return
		parent::schema().

		fieldAlbumId.space.'INT UNSIGNED PRIMARY KEY NOT NULL'.comma.
		fieldUserId.space.'INT UNSIGNED NOT NULL REFERENCES '.userTableName.leftParenthesis.fieldUserId.rightParenthesis.comma.
		fieldAlbumTimestampCreation.space.'DATETIME NOT NULL'.comma.
		fieldAlbumTimestampModification.space.'DATETIME NOT NULL'.comma.
		fieldAlbumTimestampPublication.space.'DATETIME NOT NULL'.comma.

		fieldAlbumNumber.space.'INT UNSIGNED NOT NULL'.comma.
		fieldAlbumTitle.space.'TEXT NOT NULL'.comma.
		fieldAlbumDescription.space.'TEXT'.comma.
		fieldAlbumTags.space.'TEXT'.comma.
		fieldAlbumDoPublish.space.'BOOL NOT NULL'.comma.
		fieldAlbumDoResize.space.'BOOL NOT NULL';
	}

	////////////////////////////////////////////////////////////////////////////

	protected function fromSchemaArray( $array )
	{
		$this->albumId = $array[fieldAlbumId];
		$this->userId = $array[fieldUserId];
		$this->albumTimestampCreation = $array[fieldAlbumTimestampCreation];
		$this->albumTimestampPublication = $array[fieldAlbumTimestampPublication];
		$this->albumTimestampModification = $array[fieldAlbumTimestampModification];

		$this->albumNumber = $array[fieldAlbumNumber];
		$this->albumTitle = $array[fieldAlbumTitle];
		$this->albumDescription = $array[fieldAlbumDescription];
		$this->albumTags = $array[fieldAlbumTags];
		$this->albumDoPublish = $array[fieldAlbumDoPublish];
		$this->albumDoResize = $array[fieldAlbumDoResize];

		parent::fromSchemaArray( $array );
	}

	////////////////////////////////////////////////////////////////////////////

	protected function toSchemaString()
	{
		return
		parent::toSchemaString().

		fieldAlbumId.equals.singleQuote.$this->albumId.singleQuote.comma.
		fieldUserId.equals.singleQuote.$this->userId.singleQuote.comma.
		fieldAlbumTimestampCreation.equals.singleQuote.$this->albumTimestampCreation.singleQuote.comma.
		fieldAlbumTimestampModification.equals.singleQuote.$this->albumTimestampModification.singleQuote.comma.
		fieldAlbumTimestampPublication.equals.singleQuote.$this->albumTimestampPublication.singleQuote.comma.

		fieldAlbumNumber.equals.singleQuote.$this->albumNumber.singleQuote.comma.
		fieldAlbumTitle.equals.singleQuote.mysql_real_escape_string( $this->albumTitle ).singleQuote.comma.
		fieldAlbumDescription.equals.singleQuote.mysql_real_escape_string( $this->albumDescription ).singleQuote.comma.
		fieldAlbumTags.equals.singleQuote.mysql_real_escape_string( $this->albumTags ).singleQuote.comma.
		fieldAlbumDoPublish.equals.singleQuote.$this->albumDoPublish.singleQuote.comma.
		fieldAlbumDoResize.equals.singleQuote.$this->albumDoResize.singleQuote;
	}

	////////////////////////////////////////////////////////////////////////////

	protected function toXmlString()
	{
		$values = array(
			fieldAlbumId => $this->albumId,
			fieldUserId => $this->userId,
			fieldAlbumTimestampCreation => $this->albumTimestampCreation,
			fieldAlbumTimestampModification => $this->albumTimestampModification,
			fieldAlbumTimestampPublication => $this->albumTimestampPublication,

			fieldAlbumNumber => $this->albumNumber,
			fieldAlbumTitle => $this->albumTitle,
			fieldAlbumDescription => $this->albumDescription,
			fieldAlbumTags => $this->albumTags,
			fieldAlbumDoPublish => $this->albumDoPublish,
			fieldAlbumDoResize => $this->albumDoResize
			);

		return
			parent::toXmlString().
			toXmlString( $values );
	}

	////////////////////////////////////////////////////////////////////////////

	public function createAlbum( $user, $databaseConnection )
	{
		assert( 'isValidUser( $user )' );

		$userId = $user->userId();
		$userName = $user->userName();
		$albumNumber = $user->userNextAlbumNumber();

		$timestamp = date( timestampFormat );	//	ASCII-only

		$this->albumId = parent::createObject( $databaseConnection );
		$this->userId = $userId;
		$this->albumTimestampCreation = $timestamp;
		$this->albumTimestampPublication = $timestamp;
		$this->albumTimestampModification = $timestamp;

		$this->albumNumber = $albumNumber;
		$this->albumTitle = textAlbumNewAlbum;
		$this->albumDescription = emptyString;
		$this->albumTags = emptyString;
		$this->albumDoPublish = zero;
		$this->albumDoResize = one;

		$this->setUser( $user, $databaseConnection );
	}

	////////////////////////////////////////////////////////////////////////////

	public function albumId()
	{
		return $this->albumId;
	}

	////////////////////////////////////////////////////////////////////////////

	public function userId()
	{
		return $this->userId;
	}

	////////////////////

	public function user()
	{
		assert( 'isValidUser( $this->user )' );

		return $this->user;
	}

	////////////////////

	private function setUser( $user, $databaseConnection )
	{
		assert( 'isValidUser( $user )' );
		assert( 'isEmpty( $this->user ) || $this->user === $user' );

		if ( $user != $this->user )
		{
			$this->user = $user;

			$this->albumDirectoryNameNew = $this->albumDirectoryNameOld = Album::canonicalAlbumDirectoryName( $this->albumTimestampCreation(), $user->userName(), $this->albumNumber(), $this->albumTitle(), $databaseConnection );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function albumTimestampCreation()
	{
		return $this->albumTimestampCreation;
	}

	////////////////////

	public function setAlbumTimestampCreation( $albumTimestampCreation )
	{
		assert( 'isNonEmptyString( $albumTimestampCreation )' );

		if ( $albumTimestampCreation !== $this->albumTimestampCreation )
		{
			$this->setIsModified();

			$this->albumTimestampCreation = $albumTimestampCreation;

			$this->albumDirectoryNameNew = null;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function albumTimestampModification()
	{
		return $this->albumTimestampModification;
	}

	////////////////////

	public function setAlbumTimestampModification( $albumTimestampModification )
	{
		assert( 'isNonEmptyString( $albumTimestampModification )' );

		if ( $albumTimestampModification !== $this->albumTimestampModification )
		{
			$this->setIsModified();

			$this->albumTimestampModification = $albumTimestampModification;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function albumTimestampPublication()
	{
		return $this->albumTimestampPublication;
	}

	////////////////////

	public function setAlbumTimestampPublication( $albumTimestampPublication )
	{
		assert( 'isNonEmptyString( $albumTimestampPublication )' );

		if ( $albumTimestampPublication !== $this->albumTimestampPublication )
		{
			$this->setIsModified();

			$this->albumTimestampPublication = $albumTimestampPublication;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function albumDirectoryNameOld()
	{
		return $this->albumDirectoryNameOld;
	}

	////////////////////////////////////////////////////////////////////////////

	public function albumDirectoryNameNew( $databaseConnection )
	{
		if ( isEmpty( $this->albumDirectoryNameNew ) )
		{
			$user = $this->user();

			$this->albumDirectoryNameNew = Album::canonicalAlbumDirectoryName( $this->albumTimestampCreation(), $user->userName(), $this->albumNumber(), $this->albumTitle(), $databaseConnection );
		}

		return $this->albumDirectoryNameNew;
	}

	////////////////////////////////////////////////////////////////////////////

	public function albumNumber()
	{
		return $this->albumNumber;
	}

	////////////////////

	public function setAlbumNumber( $albumNumber )
	{
		assert( 'isPositiveIntString( $albumNumber )' );

		if ( $albumNumber !== $this->albumNumber )
		{
			$this->setIsModified();

			$this->albumNumber = $albumNumber;

			$this->albumDirectoryNameNew = null;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function albumTitle()
	{
		return $this->albumTitle;
	}

	////////////////////

	public function setAlbumTitle( $albumTitle )
	{
		assert( 'isNonEmptyString( $albumTitle )' );

		if ( $albumTitle !== $this->albumTitle )
		{
			$this->setIsModified();

			$this->albumTitle = $albumTitle;

			$this->albumDirectoryNameNew = null;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function albumDescription()
	{
		return $this->albumDescription;
	}

	////////////////////

	public function setAlbumDescription( $albumDescription )
	{
		assert( 'isString( $albumDescription )' );

		if ( $albumDescription !== $this->albumDescription )
		{
			$this->setIsModified();

			$this->albumDescription = $albumDescription;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function albumTags()
	{
		return $this->albumTags;
	}

	////////////////////

	public function setAlbumTags( $albumTags )
	{
		assert( 'isString( $albumTags )' );

		if ( $albumTags !== $this->albumTags )
		{
			$this->setIsModified();

			$this->albumTags = $albumTags;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function albumDoPublish()
	{
		return $this->albumDoPublish;
	}

	////////////////////

	public function albumDoNotPublish()
	{
		return !$this->albumDoPublish();
	}

	////////////////////

	public function setAlbumDoPublish( $albumDoPublish )
	{
		assert( 'isBooleanString( $albumDoPublish )' );

		if ( $albumDoPublish !== $this->albumDoPublish )
		{
			$this->setIsModified();

			$this->albumDoPublish = $albumDoPublish;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function albumDoResize()
	{
		return $this->albumDoResize;
	}

	////////////////////

	public function albumDoNotResize()
	{
		return !$this->albumDoResize();
	}

	////////////////////

	public function setAlbumDoResize( $albumDoResize )
	{
		assert( 'isBooleanString( $albumDoResize )' );

		if ( $albumDoResize !== $this->albumDoResize )
		{
			$this->setIsModified();

			$this->albumDoResize = $albumDoResize;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function albumDefaultVersionNumber()
	{
		if ( isEmpty( $albumDefaultVersionNumber ) )
		{
			$albumDefaultVersionNumber = versionNumberMedium;
		}

		return $albumDefaultVersionNumber;
	}

	////////////////////////////////////////////////////////////////////////////

	public function albumImages( $databaseConnection )
	{
		//	This function should only be called during a read-only operation:
		assert( '$this->isNotModified()' );

		if ( isEmpty( $this->albumImages ) )
		{
			$this->albumImages = array();

			$result = executeQuery( 'SELECT * FROM '.imageTableName.' WHERE '.fieldAlbumId.equals.singleQuote.$this->albumId().singleQuote.' ORDER BY '.fieldImageNumber, 'Could not retrieve image objects for album '.$this->albumId(), $databaseConnection );
			assert( '$result != false' );

			while ( $row = nextRow( $result ) )
			{
				$image = new Image;

				$image->fromSchemaArray( $row );

				$this->albumImages[$image->imageId()] = $image;
			}
			assert( '$this->isValid()' );
		}

		return $this->albumImages;
	}

	////////////////////////////////////////////////////////////////////////////

	public function albumThumbnails( $databaseConnection )
	{
		//	This function should only be called during a read-only operation:
		assert( '$this->isNotModified()' );

		//	Note that images with no thumbnail will not be included.

		if ( isEmpty( $this->albumThumbnails ) )
		{
			$this->albumThumbnails = array();

			$query =
				'SELECT * FROM '.
				versionTableName.comma.
				imageTableName.
				' WHERE '.
				versionTableName.dot.fieldVersionNumber.equals.singleQuote.versionNumberThumbnail.singleQuote.' AND '.
				versionTableName.dot.fieldImageId.equals.imageTableName.dot.fieldImageId.' AND '.
				imageTableName.dot.fieldAlbumId.equals.singleQuote.$this->albumId().singleQuote.
				' ORDER BY '.
				imageTableName.dot.fieldImageNumber;

			$result = executeQuery( $query, 'Could not retrieve thumbnails for album '.$this->albumId(), $databaseConnection );
			assert( '$result != false' );

			while ( $row = nextRow( $result ) )
			{
				$imageThumbnail = new Version;

				$imageThumbnail->fromSchemaArray( $row );

				$this->albumThumbnails[$imageThumbnail->versionId()] = $imageThumbnail;
			}
			assert( '$this->isValid()' );
		}

		return $this->albumThumbnails;
	}

	////////////////////////////////////////////////////////////////////////////

	protected function store( $databaseConnection )
	{
		assert( '$this->isValid()' );
		assert( '$this->isModified()' );

		$user = $this->user();

		$userDirectoryPath = rootDirectoryPath.$user->userDirectoryName();

		$albumTimestampCreation = $this->albumTimestampCreation();
		$userName = $user->userName();
		$albumNumber = $this->albumNumber();
		$albumTitle = $this->albumTitle();

		$albumDirectoryNameNew = $this->albumDirectoryNameNew( $databaseConnection );
		$albumDirectoryPathNew = $userDirectoryPath.$albumDirectoryNameNew;

		$albumDirectoryNameOld = $this->albumDirectoryNameOld();
		$albumDirectoryPathOld = $userDirectoryPath.$albumDirectoryNameOld;

		if ( isNotDirectory( $albumDirectoryPathOld ) )
		{
			$result = createDirectory( $albumDirectoryPathNew, permissionsDirectoryAlbum );
			assert( '$result === true' );

			$result = copy( includePath.'albumindex.php', $albumDirectoryPathNew.'index.php' );
			assert( '$result === true' );
		}
		else if ( $albumDirectoryNameNew != $albumDirectoryNameOld )
		{
			$result = rename( $albumDirectoryPathOld, $albumDirectoryPathNew );
			assert( '$result === true' );
		}

		$this->albumDirectoryNameNew = $albumDirectoryNameNew;

		$result = writeXmlStringFile( $albumDirectoryPathNew.keyUserId.sprintf( userIdFormat, $user->userId() ).keyAlbumNumber.sprintf( albumNumberFormat, $albumNumber ).xmlFileExtension, $this->toXmlString() );
		assert( 'isNotEmpty( $result )' );

	//	$string = readStringFile( rootDirectoryPath.$user->userDirectoryName().$album->albumDirectoryName().xmlFileNameAlbum );
	//	$album = new Album;
	//	$album->fromXmlString( $string );

		return parent::store( $databaseConnection );
	}

	////////////////////////////////////////////////////////////////////////////

	public function addImage( $imageNumber, $imageTitle, $databaseConnection )
	{
		$image = new Image;

		$image->createImage( $this, $imageNumber, $imageTitle, $databaseConnection );

		return $image;
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	public static function albumByAlbumId( $albumId, $databaseConnection )
	{
		assert( 'isPositiveInt( $albumId ) || isPositiveIntString( $albumId )' );

		if ( $album = Object::inMemoryObject( $albumId ) )
		{
			assert( 'isValidAlbum( $album )' );
			return $album;
		}

		$query =
			'SELECT * FROM '.
			userTableName.comma.
			albumTableName.
			' WHERE '.
			albumTableName.dot.fieldAlbumId.equals.singleQuote.$albumId.singleQuote.' AND '.
			userTableName.dot.fieldUserId.equals.albumTableName.dot.fieldUserId;

		return Album::albumByQuery( $query, $databaseConnection );
	}

	////////////////////////////////////////////////////////////////////////////

	public static function albumByAlbumNumber( $userId, $albumNumber, $databaseConnection )
	{
		assert( 'isPositiveInt( $userId )      || isNonEmptyString( $userId )' );
		assert( 'isPositiveInt( $albumNumber ) || isPositiveIntString( $albumNumber )' );

		$fieldUserId = ( isPositiveInt( $userId ) || isPositiveIntString( $userId ) ) ? fieldUserId : fieldUserName;

		$query =
			'SELECT * FROM '.
			userTableName.comma.
			albumTableName.
			' WHERE '.
			albumTableName.dot.fieldAlbumNumber.equals.singleQuote.$albumNumber.singleQuote.' AND '.
			albumTableName.dot.fieldUserId.equals.userTableName.dot.fieldUserId.' AND '.
			userTableName.dot.$fieldUserId.equals.singleQuote.$userId.singleQuote;

		return Album::albumByQuery( $query, $databaseConnection );
	}

	////////////////////////////////////////////////////////////////////////////

	public static function albumsByUserId( $userId, $databaseConnection )
	{
		assert( 'isPositiveIntString( $userId )' );

		$fieldUserId = ( isPositiveInt( $userId ) || isPositiveIntString( $userId ) ) ? fieldUserId : fieldUserName;

		$query =
			'SELECT * FROM '.
			userTableName.comma.
			albumTableName.
			' WHERE '.
			albumTableName.dot.fieldUserId.equals.userTableName.dot.fieldUserId.' AND '.
			userTableName.dot.$fieldUserId.equals.singleQuote.$userId.singleQuote.
			' ORDER BY '.albumTableName.dot.fieldAlbumNumber;

		return Album::albumsByQuery( $query, $databaseConnection );
	}

	////////////////////////////////////////////////////////////////////////////

	private static function albumByQuery( $query, $databaseConnection )
	{
		assert( 'isNonEmptyString( $query )' );

		$result = executeQuery( $query, 'Could not retrieve album using query "'.$query.doubleQuote, $databaseConnection );
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

		return Album::albumFromSchemaArray( $array, $user, $databaseConnection );
	}

	////////////////////////////////////////////////////////////////////////////

	private static function albumsByQuery( $query, $databaseConnection )
	{
		assert( 'isNonEmptyString( $query )' );

		$queryResult = executeQuery( $query, 'Could not retrieve albums using query "'.$query.doubleQuote, $databaseConnection );
		assert( 'isNotEmpty( $queryResult )' );

		if ( isEmpty( $queryResult ) ) return null;
		assert( 'rowCount( $queryResult ) > -1' );

		$albums = array();
		while ( $array = nextRow( $queryResult ) )
		{
			$user = User::userFromSchemaArray( $array );
			assert( 'isValidUser( $user )' );

			if ( isNotValidUser( $user ) ) return null;

			$album = Album::albumFromSchemaArray( $array, $user, $databaseConnection );
			assert( 'isValidAlbum( $album )' );

			if ( isNotValidAlbum( $album ) ) return null;

			$albums[] = $album;
		}

		return $albums;
	}

	////////////////////////////////////////////////////////////////////////////

	public static function albumFromSchemaArray( $array, $user, $databaseConnection )
	{
		assert( 'isValidUser( $user )' );

		$album = Object::objectFromSchemaArray( $array, fieldAlbumId, classNameAlbum );
		assert( 'isValidAlbum( $album )' );

		if ( isNotValidAlbum( $album ) ) return null;

		$album->setUser( $user, $databaseConnection );

		return $album;
	}

	////////////////////////////////////////////////////////////////////////////////

	protected static function canonicalAlbumDirectoryName( $albumTimestampCreation, $userName, $albumNumber, $albumTitle, $databaseConnection )
	{
		assert( 'isNonEmptyString( $albumTimestampCreation )' );
		assert( 'isNonEmptyString( $userName )' );
		assert( 'isPositiveIntString( $albumNumber )' );
		assert( 'isNonEmptyString( $albumTitle )' );

		$albumDate = substr( $albumTimestampCreation, 0, 10 );	//	ASCII-only
		$userName = strtolower( $userName );	//	ASCII-only
		$albumNumber = sprintf( albumNumberFormat, $albumNumber );	//	ASCII-only
		$albumTitle = sanitizedFileName( $albumTitle, $databaseConnection );

		return $albumDate.space.$userName.space.$albumNumber.space.$albumTitle.pathSeparator;
	}
}

////////////////////////////////////////////////////////////////////////////////

function isValidAlbum( $object )
{
	return isValidObject( $object ) && is_a( $object, 'Album' );
}

////////////////////////////////////////////////////////////////////////////////

function isNotValidAlbum( $object )
{
	return !isValidAlbum( $object );
}

////////////////////////////////////////////////////////////////////////////////

?>