<?php

////////////////////////////////////////////////////////////////////////////////

//namespace org\givingtree\image;

////////////////////////////////////////////////////////////////////////////////

require( 'include.image.'.language.'.php' );

////////////////////////////////////////////////////////////////////////////////

define( 'keyImageId', 'ii' );
define( 'keyImageNumber', 'i' );

////////////////////////////////////////////////////////////////////////////////

define( 'fieldImageId', 'imageId' );
define( 'fieldImageNumber', 'imageNumber' );
define( 'fieldImageTitle', 'imageTitle' );
define( 'fieldImageDescription', 'imageDescription' );
define( 'fieldImageTags', 'imageTags' );
define( 'fieldImageRating', 'imageRating' );
define( 'fieldImagePhotographer', 'imagePhotographer' );
define( 'fieldImageTimestamp', 'imageTimestamp' );
define( 'fieldImageAddress', 'imageAddress' );
define( 'fieldImageLatitude', 'imageLatitude' );
define( 'fieldImageLongitude', 'imageLongitude' );
define( 'fieldImageAltitude', 'imageAltitude' );
define( 'fieldImageHeading', 'imageHeading' );
define( 'fieldImageExif', 'imageExif' );
define( 'fieldImageVersionIdMagnified', 'imageVersionIdMagnified' );

////////////////////////////////////////////////////////////////////////////////

define( 'imageTableName', 'images' );

////////////////////////////////////////////////////////////////////////////////

define( 'imageNumberDigits', '3' );
define( 'imageNumberFormat', '%0'.imageNumberDigits.'d' );

////////////////////////////////////////////////////////////////////////////////

class Image extends Object
{
	//	Persistent:
	private $imageId;
	private $albumId;

	private $imageNumber;

	private $imageTitle;
	private $imageDescription;
	private $imageTags;
	private $imageRating;
	private $imagePhotographer;
	private $imageTimestamp;
	private $imageAddress;
	private $imageLatitude;
	private $imageLongitude;
	private $imageAltitude;
	private $imageHeading;
	private $imageExif;

	private $imageVersionIdMagnified = null;

	//	Non-persistent:
	private $album;
	private $imageThumbnail;	//	Version

	////////////////////////////////////////////////////////////////////////////

	protected function objectId()
	{
		return $this->imageId();
	}

	////////////////////////////////////////////////////////////////////////////

	protected function objectIdField()
	{
		return fieldImageId;
	}

	////////////////////////////////////////////////////////////////////////////

	protected function objectTableName()
	{
		return imageTableName;
	}

	////////////////////////////////////////////////////////////////////////////

	public function isValid()
	{
		assert( 'parent::isValid()' );

		assert( 'isPositiveIntString( $this->imageId )' );
		assert( 'isPositiveIntString( $this->albumId )' );

		assert( 'isPositiveIntString( $this->imageNumber )' );

		assert( 'isNonEmptyString( $this->imageTitle )' );
		assert( 'isString( $this->imageDescription )' );
		assert( 'isString( $this->imageTags )' );
		assert( 'isEmpty( $this->imageRating ) || isPositiveIntString( $this->imageRating )' );
		assert( 'isString( $this->imagePhotographer )' );
		assert( 'isString( $this->imageTimestamp )' );
		assert( 'isString( $this->imageAddress )' );
		assert( 'isEmpty( $this->imageLatitude ) || isNumericString( $this->imageLatitude )' );
		assert( 'isEmpty( $this->imageLongitude ) || isNumericString( $this->imageLongitude )' );
		assert( 'isEmpty( $this->imageAltitude ) || isNumericString( $this->imageAltitude )' );
		assert( 'isEmpty( $this->imageHeading ) || isNumericString( $this->imageHeading )' );
		assert( 'isString( $this->imageExif )' );

		assert( 'isUndefinedString( $this->imageLatitude ) || ( isNumericString( $this->imageLatitude ) && ( (double)($this->imageLatitude) >= -90.0 ) )' );
		assert( 'isUndefinedString( $this->imageLatitude ) || ( isNumericString( $this->imageLatitude ) && ( (double)($this->imageLatitude) <= +90.0 ) )' );

		assert( 'isUndefinedString( $this->imageLongitude ) || ( isNumericString( $this->imageLongitude ) && ( (double)($this->imageLongitude) >= -180.0 ) )' );
		assert( 'isUndefinedString( $this->imageLongitude ) || ( isNumericString( $this->imageLongitude ) && ( (double)($this->imageLongitude) <= +180.0 ) )' );

		assert( 'isUndefinedString( $this->imageAltitude ) || ( isNumericString( $this->imageAltitude ) && ( (double)($this->imageAltitude) >= -10000.0 ) )' );
		assert( 'isUndefinedString( $this->imageAltitude ) || ( isNumericString( $this->imageAltitude ) && ( (double)($this->imageAltitude) <= +10000.0 ) )' );

		assert( 'isUndefinedString( $this->imageHeading ) || ( isNumericString( $this->imageHeading ) && ( (double)($this->imageHeading) >= 0.0 ) )' );
		assert( 'isUndefinedString( $this->imageHeading ) || ( isNumericString( $this->imageHeading ) && ( (double)($this->imageHeading) <= 360.0 ) )' );

		assert( 'isNull( $this->imageVersionIdMagnified ) || isPositiveIntString( $this->imageVersionIdMagnified )' );	//	NB: This can only be null upon creation; it must eventually be set via setImageVersionIdMagnified().

		assert( 'isEmpty( $this->album ) || isValidAlbum( $this->album )' );
		assert( 'isEmpty( $this->imageThumbnail ) || isValidVersion( $this->imageThumbnail )' );

		return true;
	}

	////////////////////////////////////////////////////////////////////////////

	public static function schema()
	{
		return
		parent::schema().

		fieldImageId.space.'INT UNSIGNED PRIMARY KEY NOT NULL'.comma.
		fieldAlbumId.space.'INT UNSIGNED REFERENCES '.albumTableName.leftParenthesis.fieldAlbumId.rightParenthesis.comma.

		fieldImageNumber.space.'INT UNSIGNED NOT NULL'.comma.

		fieldImageTitle.space.'TEXT NOT NULL'.comma.
		fieldImageDescription.space.'TEXT'.comma.
		fieldImageTags.space.'TEXT'.comma.
		fieldImageRating.space.'INT UNSIGNED'.comma.
		fieldImagePhotographer.space.'TEXT'.comma.
		fieldImageTimestamp.space.'DATETIME'.comma.
		fieldImageAddress.space.'TEXT'.comma.
		fieldImageLatitude.space.'DOUBLE PRECISION'.comma.
		fieldImageLongitude.space.'DOUBLE PRECISION'.comma.
		fieldImageAltitude.space.'DOUBLE PRECISION'.comma.
		fieldImageHeading.space.'DOUBLE PRECISION'.comma.
		fieldImageExif.space.'TEXT'.comma.

		fieldImageVersionIdMagnified.space.'INT UNSIGNED REFERENCES '.versionTableName.leftParenthesis.fieldVersionId.rightParenthesis
		;
	}

	////////////////////////////////////////////////////////////////////////////

	public function fromSchemaArray( $array )
	{
		$this->imageId = $array[fieldImageId];
		$this->albumId = $array[fieldAlbumId];

		$this->imageNumber = $array[fieldImageNumber];

		$this->imageTitle = $array[fieldImageTitle];
		$this->imageDescription = $array[fieldImageDescription];
		$this->imageTags = $array[fieldImageTags];
		$this->imageRating = $array[fieldImageRating];
		$this->imagePhotographer = $array[fieldImagePhotographer];
		$this->imageTimestamp = $array[fieldImageTimestamp];
		$this->imageAddress = $array[fieldImageAddress];
		$this->imageLatitude = $array[fieldImageLatitude];
		$this->imageLongitude = $array[fieldImageLongitude];
		$this->imageAltitude = $array[fieldImageAltitude];
		$this->imageHeading = $array[fieldImageHeading];
		$this->imageExif = $array[fieldImageExif];

		$this->imageVersionIdMagnified = $array[fieldImageVersionIdMagnified];

		parent::fromSchemaArray( $array );
	}

	////////////////////////////////////////////////////////////////////////////

	public function toSchemaString()
	{
		return
		parent::toSchemaString().

		fieldImageId.equals.singleQuote.$this->imageId.singleQuote.comma.
		fieldAlbumId.equals.singleQuote.$this->albumId.singleQuote.comma.

		fieldImageNumber.equals.singleQuote.$this->imageNumber.singleQuote.comma.

		fieldImageTitle.equals.singleQuote.mysql_real_escape_string( $this->imageTitle ).singleQuote.comma.
		fieldImageDescription.equals.singleQuote.mysql_real_escape_string( $this->imageDescription ).singleQuote.comma.
		fieldImageTags.equals.singleQuote.mysql_real_escape_string( $this->imageTags ).singleQuote.comma.
		fieldImageRating.equals.singleQuote.$this->imageRating.singleQuote.comma.
		fieldImagePhotographer.equals.singleQuote.mysql_real_escape_string( $this->imagePhotographer ).singleQuote.comma.
		fieldImageTimestamp.equals.singleQuote.$this->imageTimestamp.singleQuote.comma.
		fieldImageAddress.equals.singleQuote.mysql_real_escape_string( $this->imageAddress ).singleQuote.comma.
		fieldImageLatitude.equals.singleQuote.$this->imageLatitude.singleQuote.comma.
		fieldImageLongitude.equals.singleQuote.$this->imageLongitude.singleQuote.comma.
		fieldImageAltitude.equals.singleQuote.$this->imageAltitude.singleQuote.comma.
		fieldImageHeading.equals.singleQuote.$this->imageHeading.singleQuote.comma.
		fieldImageExif.equals.singleQuote.$this->imageExif.singleQuote.comma.

		fieldImageVersionIdMagnified.equals.singleQuote.$this->imageVersionIdMagnified.singleQuote
		;
	}

	////////////////////////////////////////////////////////////////////////////

	public function toXmlString()
	{
		$values = array(
			fieldImageId => $this->imageId,
			fieldAlbumId => $this->albumId,

			fieldImageNumber => $this->imageNumber,

			fieldImageTitle => $this->imageTitle,
			fieldImageDescription => $this->imageDescription,
			fieldImageTags => $this->imageTags,
			fieldImageRating => $this->imageRating,
			fieldImagePhotographer => $this->imagePhotographer,
			fieldImageTimestamp => ( $this->imageTimestamp === undefinedTimestamp ? emptyString : $this->imageTimestamp ),
			fieldImageAddress => $this->imageAddress,
			fieldImageLatitude => ( $this->imageLatitude === undefinedString ? emptyString : $this->imageLatitude ),
			fieldImageLongitude => ( $this->imageLongitude === undefinedString ? emptyString : $this->imageLongitude ),
			fieldImageAltitude => ( $this->imageAltitude === undefinedString ? emptyString : $this->imageAltitude ),
			fieldImageHeading => ( $this->imageHeading === undefinedString ? emptyString : $this->imageHeading ),
			fieldImageExif => $this->imageExif,
			fieldImageVersionIdMagnified => $this->imageVersionIdMagnified
			);

		return
			parent::toXmlString().
			toXmlString( $values );
	}

	////////////////////////////////////////////////////////////////////////////

	public function createImage( $album, $imageNumber, $imageTitle, $databaseConnection )
	{
		assert( 'isValidAlbum( $album )' );
		assert( 'isPositiveIntString( $imageNumber )' );
		assert( 'isNonEmptyString( $imageTitle )' );

		$this->imageId = parent::createObject( $databaseConnection );
		$this->albumId = $album->albumId();

		$this->imageNumber = $imageNumber;

		$this->imageTitle = $imageTitle;
		$this->imageDescription = emptyString;
		$this->imageTags = emptyString;
		$this->imageRating = zero;
		$this->imagePhotographer = emptyString;
		$this->imageTimestamp = undefinedTimestamp;
		$this->imageAddress = emptyString;
		$this->imageLatitude = undefinedString;
		$this->imageLongitude = undefinedString;
		$this->imageAltitude = undefinedString;
		$this->imageHeading = undefinedString;
		$this->imageExif = emptyString;
		$this->imageVersionIdMagnified = null;

		$this->album = $album;
	}

	////////////////////////////////////////////////////////////////////////////

	public function imageId()
	{
		return $this->imageId;
	}

	////////////////////////////////////////////////////////////////////////////

	public function albumId()
	{
		return $this->albumId;
	}

	////////////////////////////////////////////////////////////////////////////

	public function album()
	{
		assert( 'isValidAlbum( $this->album )' );

		return $this->album;
	}

	////////////////////////////////////////////////////////////////////////////

	public function imageNumber()
	{
		return $this->imageNumber;
	}

	////////////////////

	public function setImageNumber( $imageNumber )
	{
		assert( 'isPositiveIntString( $imageNumber )' );

		if ( $imageNumber !== $this->imageNumber )
		{
			$this->setIsModified();

			$this->imageNumber = $imageNumber;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function imageTitle()
	{
		return $this->imageTitle;
	}

	////////////////////

	public function setImageTitle( $imageTitle )
	{
		assert( 'isNonEmptyString( $imageTitle )' );

		if ( $imageTitle !== $this->imageTitle )
		{
			$this->setIsModified();

			$this->imageTitle = $imageTitle;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function imageDescription()
	{
		return $this->imageDescription;
	}

	////////////////////

	public function setImageDescription( $imageDescription )
	{
		assert( 'isString( $imageDescription )' );

		if ( $imageDescription !== $this->imageDescription )
		{
			$this->setIsModified();

			$this->imageDescription = $imageDescription;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function imageTags()
	{
		return $this->imageTags;
	}

	////////////////////

	public function setImageTags( $imageTags )
	{
		assert( 'isString( $imageTags )' );

		if ( $imageTags !== $this->imageTags )
		{
			$this->setIsModified();

			$this->imageTags = $imageTags;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function imageRating()
	{
		return $this->imageRating;
	}

	////////////////////

	public function setImageRating( $imageRating )
	{
		assert( 'isString( $imageRating )' );

		if ( $imageRating !== $this->imageRating )
		{
			$this->setIsModified();

			$this->imageRating = $imageRating;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function imagePhotographer()
	{
		return $this->imagePhotographer;
	}

	////////////////////

	public function setImagePhotographer( $imagePhotographer )
	{
		assert( 'isString( $imagePhotographer )' );

		if ( $imagePhotographer !== $this->imagePhotographer )
		{
			$this->setIsModified();

			$this->imagePhotographer = $imagePhotographer;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function imageTimestamp()
	{
		return $this->imageTimestamp;
	}

	////////////////////

	public function setImageTimestamp( $imageTimestamp )
	{
		assert( 'isString( $imageTimestamp )' );

		if ( $imageTimestamp !== $this->imageTimestamp )
		{
			$this->setIsModified();

			$this->imageTimestamp = $imageTimestamp;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function imageAddress()
	{
		return $this->imageAddress;
	}

	////////////////////

	public function setImageAddress( $imageAddress )
	{
		assert( 'isString( $imageAddress )' );

		if ( $imageAddress !== $this->imageAddress )
		{
			$this->setIsModified();

			$this->imageAddress = $imageAddress;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function imageLatitude()
	{
		return $this->imageLatitude;
	}

	////////////////////

	public function setImageLatitude( $imageLatitude )
	{
		assert( 'isEmptyString( $imageLatitude ) || isNumericString( $imageLatitude )' );

		if ( $imageLatitude !== $this->imageLatitude )
		{
			$this->setIsModified();

			$this->imageLatitude = $imageLatitude;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function imageLongitude()
	{
		return $this->imageLongitude;
	}

	////////////////////

	public function setImageLongitude( $imageLongitude )
	{
		assert( 'isEmptyString( $imageLongitude ) || isNumeric( $imageLongitude )' );

		if ( $imageLongitude !== $this->imageLongitude )
		{
			$this->setIsModified();

			$this->imageLongitude = $imageLongitude;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function imageAltitude()
	{
		return $this->imageAltitude;
	}

	////////////////////

	public function setImageAltitude( $imageAltitude )
	{
		assert( 'isEmptyString( $imageAltitude ) || isNumeric( $imageAltitude )' );

		if ( $imageAltitude !== $this->imageAltitude )
		{
			$this->setIsModified();

			$this->imageAltitude = $imageAltitude;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function imageHeading()
	{
		return $this->imageHeading;
	}

	////////////////////

	public function setImageHeading( $imageHeading )
	{
		assert( 'isEmptyString( $imageHeading ) || isNumeric( $imageHeading )' );

		if ( $imageHeading !== $this->imageHeading )
		{
			$this->setIsModified();

			$this->imageHeading = $imageHeading;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function imageExif()
	{
		return $this->imageExif;
	}

	////////////////////

	public function setImageExif( $imageExif )
	{
		assert( 'isString( $imageExif )' );

		if ( $imageExif !== $this->imageExif )
		{
			$this->setIsModified();

			$this->imageExif = $imageExif;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function imageThumbnail( $databaseConnection )
	{
		if ( isEmpty( $this->imageThumbnail ) )
		{
			$result = executeQuery( 'SELECT * FROM '.versionTableName.' WHERE '.fieldImageId.equals.singleQuote.$this->imageId().singleQuote.' AND '.fieldVersionNumber.equals.singleQuote.versionNumberThumbnail.singleQuote, 'Could not retrieve thumbnail for image '.$this->imageId(), $databaseConnection );
			assert( '$result != false' );
			assert( 'rowCount( $result ) === 1' );

			while ( $row = nextRow( $result ) )
			{
				$this->imageThumbnail = new Version;

				$this->imageThumbnail->fromSchemaArray( $row );
			}
		}

		return $this->imageThumbnail;
	}

	////////////////////////////////////////////////////////////////////////////

	public function imageVersionIdMagnified()
	{
		//	This value can initially be empty, but by the time it's requested it must be set:
		assert( 'isPositiveIntString( $this->imageVersionIdMagnified )' );

		return $this->imageVersionIdMagnified;
	}

	////////////////////////////////////////////////////////////////////////////

	public function setimageVersionIdMagnified( $databaseConnection )
	{
		//	Get the array of all Version objects associated with this image:
		$versions = Version::versionsByImageId( $this->imageId(), $databaseConnection );
		assert( 'isNonEmptyArray( $versions )' );

		//	Create an empty map of version id's to resolutions (width x height):
		$resolutions = array();

		//	We're going to create an ordered list of resolutions:
		foreach ( $versions as $version )
		{
			//	Ensure the version is valid:
			assert( 'isValidVersion( $version )' );

			//	Extract the version's id; this is a string:
			$versionId = $version->versionId();
			assert( 'isPositiveIntString( $versionId )' );

			//	Extract the version's resolution; this is a string equal to width x height:
			$resolution = $version->versionResolution();
			assert( 'isNonNegativeInt( $resolution )' );

			//	If the resolution is zero, skip it:
			if ( $resolution === 0 ) continue;

			//	Extract the version's number; this is also a string:
			$versionNumber = $version->versionNumber();
			assert( 'isPositiveIntString( $versionNumber )' );

			//	If this version number corresponds to "large" we're just going
			//	to use that automatically:
			if ( $versionNumber === versionNumberLarge )
			{
				//	If the version id has changed, update it:
				if ( $this->imageVersionIdMagnified !== $versionId )
				{
					//	Signal that we've been modified:
					$this->setIsModified();

					//	Store the new version id:
					$this->imageVersionIdMagnified = $versionId;
				}

				//	Short-circuit:
				return;
			}

			//	Map the version id to its resolution:
			$resolutions[$versionId] = $resolution;
		}

		$resolutionCount = count( $resolutions );
		assert( 'isPositiveInt( $resolutionCount )' );

		//	Sort the resolutions numerically from lowest to highest, removing duplicate resolutions:
		$resolutions = array_unique( $resolutions, SORT_NUMERIC );

		//	Rewind the array pointer to the beginning, which is the lowest (thumbnail) resolution:
		$resolution = reset( $resolutions );
		assert( 'isPositiveInt( $resolution )' );

		//	Move to the next resolution up:
		$resolution = next( $resolutions );

		//	If we only have image (the thumbnail) reset it; note that this really shouldn't happen,
		//	but it could if all versions are deleted save the thumbnail:
		if ( $resolution === false )
		{
			$resolution = reset( $resolutions );
		}
		assert( 'isPositiveInt( $resolution )' );

		//	Extract the version id associated with this resolution:
		$versionId = (string)key( $resolutions );
		assert( 'isPositiveIntString( $versionId )' );

		//	If the version id has changed, update it:
		if ( $this->imageVersionIdMagnified !== $versionId )
		{
			//	Signal that we've been modified:
			$this->setIsModified();

			//	Store the new version id:
			$this->imageVersionIdMagnified = $versionId;
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function addVersion( $versionNumber, $versionLabel, $versionWidth, $versionHeight, $versionFileBytes, $versionFileExtension, $databaseConnection )
	{
		$version = new Version;

		$version->createVersion( $this, $versionNumber, $versionLabel, $versionWidth, $versionHeight, $versionFileBytes, $versionFileExtension, $databaseConnection );

		return $version;
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	public static function imageByImageId( $imageId, $databaseConnection )
	{
		assert( 'isPositiveInt( $imageId ) || isPositiveIntString( $imageId )' );

		if ( $image = Object::inMemoryObject( $imageId ) )
		{
			assert( 'isValidImage( $image )' );
			return $image;
		}

		$query =
			'SELECT * FROM '.
			userTableName.comma.
			albumTableName.comma.
			imageTableName.
			' WHERE '.
			imageTableName.dot.fieldImageId.equals.singleQuote.$imageId.singleQuote.' AND '.
			imageTableName.dot.fieldAlbumId.equals.albumTableName.dot.fieldAlbumId.' AND '.
			albumTableName.dot.fieldUserId.equals.userTableName.dot.fieldUserId;

		return Image::imageByQuery( $query, $databaseConnection );
	}

	////////////////////////////////////////////////////////////////////////////

	public static function imageByImageNumber( $userId, $albumNumber, $imageNumber, $databaseConnection )
	{
		assert( 'isPositiveInt( $userId )      || isNonEmptyString( $userId )' );
		assert( 'isPositiveInt( $albumNumber ) || isPositiveIntString( $albumNumber )' );
		assert( 'isPositiveInt( $imageNumber ) || isPositiveIntString( $imageNumber )' );

		$fieldUserId = ( isPositiveInt( $userId ) || isPositiveIntString( $userId ) ) ? fieldUserId : fieldUserName;

		$query =
			'SELECT * FROM '.
			userTableName.comma.
			albumTableName.comma.
			imageTableName.
			' WHERE '.
			imageTableName.dot.fieldImageNumber.equals.singleQuote.$imageNumber.singleQuote.' AND '.
			imageTableName.dot.fieldAlbumId.equals.albumTableName.dot.fieldAlbumId.' AND '.
			albumTableName.dot.fieldAlbumNumber.equals.singleQuote.$albumNumber.singleQuote.' AND '.
			albumTableName.dot.fieldUserId.equals.userTableName.dot.fieldUserId.' AND '.
			userTableName.dot.$fieldUserId.equals.singleQuote.$userId.singleQuote;

		return Image::imageByQuery( $query, $databaseConnection );
	}

	////////////////////////////////////////////////////////////////////////////

	private static function imageByQuery( $query, $databaseConnection )
	{
		assert( 'isNonEmptyString( $query )' );

		$result = executeQuery( $query, 'Could not retrieve image using query "'.$query.doubleQuote, $databaseConnection );
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

		if ( isNotValidAlbum( $album ) ) return null;

		return Image::imageFromSchemaArray( $array, $album );
	}

	////////////////////////////////////////////////////////////////////////////

	public static function imageFromSchemaArray( $array, $album )
	{
		assert( 'isValidAlbum( $album )' );

		$image = Object::objectFromSchemaArray( $array, fieldImageId, classNameImage );
		assert( 'isValidImage( $image )' );

		if ( isNotValidImage( $image ) ) return null;

		$image->album = $album;
		assert( '$image->albumId() === $album->albumId()' );

		return $image;
	}
}

////////////////////////////////////////////////////////////////////////////////

function isValidImage( $object )
{
	return isValidObject( $object ) && is_a( $object, 'Image' );
}

////////////////////////////////////////////////////////////////////////////////

function isNotValidImage( $object )
{
	return !isValidImage( $object );
}

////////////////////////////////////////////////////////////////////////////////

?>