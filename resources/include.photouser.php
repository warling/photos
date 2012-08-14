<?php

////////////////////////////////////////////////////////////////////////////////

//namespace org\givingtree\photouser;

////////////////////////////////////////////////////////////////////////////////

require( 'include.photouser.'.language.'.php' );

////////////////////////////////////////////////////////////////////////////////

define( 'fieldUserNextAlbumNumber', 'userNextAlbumNumber' );
define( 'fieldUserShowDetailedTimestamps', 'userShowDetailedTimestamps' );

////////////////////////////////////////////////////////////////////////////////

define( 'displayNone', 'none' );
define( 'displayBlock', 'block' );

////////////////////////////////////////////////////////////////////////////////

define( 'photoDatabaseName', 'photos' );

////////////////////////////////////////////////////////////////////////////////

class PhotoUser extends User
{
	//	Persistent:
	private $userNextAlbumNumber;
	private $userShowDetailedTimestamps;

	//	Non-persistent:
	private $userDirectoryName;

	////////////////////////////////////////////////////////////////////////////

	public function isValid()
	{
		assert( 'isNonEmptyIntString( $this->userNextAlbumNumber )' );
		assert( 'isString( $this->userShowDetailedTimestamps )' );
		assert( '$this->userShowDetailedTimestamps === displayBlock || $this->userShowDetailedTimestamps === displayNone' );
		assert( 'isNonEmptyString( $this->userDirectoryName )' );
		return parent::isValid();
	}

	////////////////////////////////////////////////////////////////////////////

	protected static function schema()
	{
		return
		parent::schema().comma.
		fieldUserNextAlbumNumber.space.'INT UNSIGNED NOT NULL'.comma.
		fieldUserShowDetailedTimestamps.space.'CHAR(6)';
	}

	////////////////////////////////////////////////////////////////////////////

	protected function fromSchemaArray( $array )
	{
		$this->userNextAlbumNumber = $array[fieldUserNextAlbumNumber];
		$this->userShowDetailedTimestamps = $array[fieldUserShowDetailedTimestamps];
		$this->userDirectoryName = strtolower( $array[fieldUserName] ).pathSeparator;	//	ASCII-only
		parent::fromSchemaArray( $array );
	}

	////////////////////////////////////////////////////////////////////////////

	protected function toSchemaString()
	{
		return
		parent::toSchemaString().comma.
		fieldUserNextAlbumNumber.equals.singleQuote.$this->userNextAlbumNumber().singleQuote.comma.
		fieldUserShowDetailedTimestamps.equals.singleQuote.$this->userShowDetailedTimestamps().singleQuote;
	}

	////////////////////////////////////////////////////////////////////////////

	public function toHtmlString()
	{
		return
		parent::toHtmlString().newline.
		tab.'<tr class="userNextAlbumNumber"><td class="label">'.labelUserNextAlbumNumber.tdTagEnd.'<td class="value">'.$this->userNextAlbumNumber().tdTagEnd.trTagEnd.newline.
		tab.'<tr class="userShowDetailedTimestamps"><td class="label">'.labelUserShowDetailedTimestamps.tdTagEnd.'<td class="value">'.$this->userShowDetailedTimestamps().tdTagEnd.trTagEnd;
	}

	////////////////////////////////////////////////////////////////////////////

	protected function toXmlString()
	{
		$values = array(
			fieldUserNextAlbumNumber => $this->userNextAlbumNumber(),
			fieldUserShowDetailedTimestamps => $this->userShowDetailedTimestamps()
			);

		return
		parent::toXmlString().
		toXmlString( $values );
	}

	////////////////////////////////////////////////////////////////////////////

	public function createPhotoUser( $userName, $userPassword, $databaseConnection )
	{
		$this->userNextAlbumNumber = one;
		$this->userShowDetailedTimestamps = displayNone;
		$this->userDirectoryName = strtolower( $userName ).pathSeparator;	//	ASCII-only

		$userDirectoryPath = rootDirectoryPath.$this->userDirectoryName();

		$result = createDirectory( $userDirectoryPath, permissionsDirectoryUser );
		assert( '$result === true' );

		$result = copy( includePath.'userindex.php', $userDirectoryPath.'index.php' );
		assert( '$result === true' );

		parent::createUser( $userName, $userPassword, $databaseConnection );
	}

	////////////////////////////////////////////////////////////////////////////

	public function userNextAlbumNumber()
	{
		return $this->userNextAlbumNumber;
	}

	////////////////////

	private function incrementUserNextAlbumNumber()
	{
		$this->setIsModified();

		$this->userNextAlbumNumber = (string)( ( (int)$this->userNextAlbumNumber ) + 1 );

		assert( '$this->isValid()' );
	}

	////////////////////////////////////////////////////////////////////////////

	public function userShowDetailedTimestamps()
	{
		return $this->userShowDetailedTimestamps;
	}

	////////////////////

	public function setUserShowDetailedTimestamps( $showDetailedTimestamps )
	{
		assert( '$showDetailedTimestamps === displayBlock || $showDetailedTimestamps === displayNone' );

		if ( $showDetailedTimestamps !== $this->userShowDetailedTimestamps )
		{
			$this->setIsModified();

			$this->userShowDetailedTimestamps = $showDetailedTimestamps;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function userDirectoryName()
	{
		return $this->userDirectoryName;
	}

	////////////////////////////////////////////////////////////////////////////

	public function albums( $databaseConnection)
	{
		return Album::albumsByUserId( $this->userId(), $databaseConnection );
	}

	////////////////////////////////////////////////////////////////////////////

	protected function store( $databaseConnection )
	{
		$result = writeXmlStringFile( rootDirectoryPath.$this->userDirectoryName().keyUserId.sprintf( userIdFormat, $this->userId() ).xmlFileExtension, $this->toXmlString() );
		assert( 'isNotEmpty( $result )' );

		return parent::store( $databaseConnection );
	}

	////////////////////////////////////////////////////////////////////////////

	public function addAlbum( $databaseConnection )
	{
		$album = new Album();

		$album->createAlbum( $this, $databaseConnection );

		$this->incrementUserNextAlbumNumber();

		return $album;
	}
}

////////////////////////////////////////////////////////////////////////////////

?>