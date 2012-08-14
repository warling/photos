<?php

////////////////////////////////////////////////////////////////////////////////

//namespace org\givingtree\user;

////////////////////////////////////////////////////////////////////////////////

require( 'include.user.'.language.'.php' );

////////////////////////////////////////////////////////////////////////////////

//	Note that the user id and the user number are equal. Note also that user ids
//	and user names are both unique, and either will uniquely identify a user.
define( 'keyUserId', 'u' );
define( 'keyUserNumber', keyUserId );

////////////////////////////////////////////////////////////////////////////////

define( 'fieldUserId', 'userId' );
define( 'fieldUserNumber', fieldUserId );
define( 'fieldUserName', 'userName' );
define( 'fieldUserPassword', 'userPassword' );
define( 'fieldUserCookie', 'userCookie' );
define( 'fieldUserSessionId', 'userSessionId' );
define( 'fieldUserIpAddressLoginFirst', 'userIpAddressLoginFirst' );
define( 'fieldUserIpAddressLoginLast', 'userIpAddressLoginLast' );
define( 'fieldUserTimestampLoginFirst', 'userTimestampLoginFirst' );
define( 'fieldUserTimestampLoginLast', 'userTimestampLoginLast' );
define( 'fieldUserEmailAddress', 'userEmailAddress' );
define( 'fieldUserType', 'userType' );

////////////////////////////////////////////////////////////////////////////////

define( 'userTypeInvalid', 0 );
define( 'userTypeAdmin', 1 );
define( 'userTypeWriter', 2 );
define( 'userTypeReader', 3 );

////////////////////////////////////////////////////////////////////////////////

define( 'userTableName', 'users' );

////////////////////////////////////////////////////////////////////////////////

define( 'userIdDigits', '3' );
define( 'userIdFormat', '%0'.userIdDigits.'d' );

////////////////////////////////////////////////////////////////////////////////

class User extends Object
{
	//	Persistent:
	private $userId;
	private $userName;
	private $userPassword;
	private $userTimestampLoginFirst;
	private $userTimestampLoginLast;
	private $userIpAddressLoginFirst;
	private $userIpAddressLoginLast;
	private $userEmailAddress;
	private $userType;

	////////////////////////////////////////////////////////////////////////////

	protected function objectId()
	{
		return $this->userId();
	}

	////////////////////////////////////////////////////////////////////////////

	protected function objectIdField()
	{
		return fieldUserId;
	}

	////////////////////////////////////////////////////////////////////////////

	protected function objectTableName()
	{
		return userTableName;
	}

	////////////////////////////////////////////////////////////////////////////

	public function isValid()
	{
		assert( 'parent::isValid()' );

		assert( 'isPositiveIntString( $this->userId )' );
		assert( 'isNonEmptyString( $this->userName )' );
		assert( 'isNonEmptyString( $this->userPassword )' );
		assert( 'isNonEmptyString( $this->userTimestampLoginFirst )' );
		assert( 'isNonEmptyString( $this->userTimestampLoginLast )' );
		assert( 'isNonEmptyString( $this->userIpAddressLoginFirst )' );
		assert( 'isNonEmptyString( $this->userIpAddressLoginLast )' );
		assert( 'isString( $this->userEmailAddress )' );
		assert( '( $this->userType === userTypeReader ) || ( $this->userType === userTypeWriter ) || ( $this->userType === userTypeAdmin )' );

		return true;
	}

	////////////////////////////////////////////////////////////////////////////

	protected static function schema()
	{
		return
		parent::schema().
		fieldUserId.space.'INT UNSIGNED PRIMARY KEY NOT NULL'.comma.
		fieldUserName.space.'VARCHAR(20) NOT NULL'.comma.
		fieldUserPassword.space.'CHAR(32) BINARY NOT NULL'.comma.
		fieldUserCookie.space.'CHAR(32) BINARY NOT NULL'.comma.
		fieldUserSessionId.space.'CHAR(32) BINARY NOT NULL'.comma.
		fieldUserIpAddressLoginFirst.space.'VARCHAR(39) BINARY NOT NULL'.comma.
		fieldUserIpAddressLoginLast.space.'VARCHAR(39) BINARY NOT NULL'.comma.
		fieldUserTimestampLoginFirst.space.'DATETIME NOT NULL'.comma.
		fieldUserTimestampLoginLast.space.'DATETIME'.comma.
		fieldUserEmailAddress.space.'TEXT'.comma.
		fieldUserType.space.'INT UNSIGNED NOT NULL';
	}

	////////////////////////////////////////////////////////////////////////////

	protected function fromSchemaArray( $array )
	{
		$this->userId = $array[fieldUserId];
		$this->userName = $array[fieldUserName];
		$this->userPassword = $array[fieldUserPassword];
		$this->userIpAddressLoginFirst = $array[fieldUserIpAddressLoginFirst];
		$this->userIpAddressLoginLast = $array[fieldUserIpAddressLoginLast];
		$this->userTimestampLoginFirst = $array[fieldUserTimestampLoginFirst];
		$this->userTimestampLoginLast = $array[fieldUserTimestampLoginLast];
		$this->userEmailAddress = $array[fieldUserEmailAddress];
		$this->userType = (int)$array[fieldUserType];

		parent::fromSchemaArray( $array );
	}

	////////////////////////////////////////////////////////////////////////////

	protected function toSchemaString()
	{
		return
		parent::toSchemaString().
		fieldUserId.equals.singleQuote.$this->userId().singleQuote.comma.
		fieldUserName.equals.singleQuote.$this->userName().singleQuote.comma.
		fieldUserPassword.equals.singleQuote.$this->userPassword().singleQuote.comma.
		fieldUserIpAddressLoginFirst.equals.singleQuote.$this->userIpAddressLoginFirst().singleQuote.comma.
		fieldUserIpAddressLoginLast.equals.singleQuote.$this->userIpAddressLoginLast().singleQuote.comma.
		fieldUserTimestampLoginFirst.equals.singleQuote.$this->userTimestampLoginFirst().singleQuote.comma.
		fieldUserTimestampLoginLast.equals.singleQuote.$this->userTimestampLoginLast().singleQuote.comma.
		fieldUserEmailAddress.equals.singleQuote.$this->userEmailAddress().singleQuote.comma.
		fieldUserType.equals.singleQuote.$this->userType().singleQuote;
	}

	////////////////////////////////////////////////////////////////////////////

	public function toHtmlString()
	{
		return
		tab.'<tr class="userId"><td class="label">'.labelUserId.tdTagEnd.'<td class="value">'.$this->userId().tdTagEnd.trTagEnd.newline.
		tab.'<tr class="userName"><td class="label">'.labelUserName.tdTagEnd.'<td class="value">'.$this->userName().tdTagEnd.trTagEnd.newline.
		tab.'<tr class="userLoginFirst"><td class="label">'.labelUserAccountCreated.tdTagEnd.'<td class="value">'.$this->userTimestampLoginFirst().' from '.$this->userIpAddressLoginFirst().tdTagEnd.trTagEnd.newline.
		tab.'<tr class="userLoginLast"><td class="label">'.labelUserMostRecentLogin.tdTagEnd.'<td class="value">'.$this->userTimestampLoginLast().' from '.$this->userIpAddressLoginLast().tdTagEnd.trTagEnd.newline.
		tab.'<tr class="userEmailAddress"><td class="label">'.labelUserEmailAddress.tdTagEnd.'<td class="value">'.$this->userEmailAddress().tdTagEnd.trTagEnd.newline.
		tab.'<tr class="userType"><td class="label">'.labelUserType.tdTagEnd.'<td class="value">'.$this->userType().tdTagEnd.trTagEnd.newline.
		tab.'<tr class="isModified"><td class="label">'.labelIsModified.tdTagEnd.'<td class="value">'.( $this->isModified() ? textUserTrue : textUserFalse ).tdTagEnd.trTagEnd;
	}

	////////////////////////////////////////////////////////////////////////////

	protected function toXmlString()
	{
		$values = array(
			fieldUserId => $this->userId(),
			fieldUserName => $this->userName(),
			fieldUserPassword => $this->userPassword(),
			fieldUserIpAddressLoginFirst => $this->userIpAddressLoginFirst(),
			fieldUserIpAddressLoginLast => $this->userIpAddressLoginLast(),
			fieldUserTimestampLoginFirst => $this->userTimestampLoginFirst(),
			fieldUserTimestampLoginLast => $this->userTimestampLoginLast(),
			fieldUserEmailAddress => $this->userEmailAddress(),
			fieldUserType => $this->userType()
			);

		return
			parent::toXmlString().
			toXmlString( $values );
	}

	////////////////////////////////////////////////////////////////////////////

	protected function createUser( $userName, $userPassword, $databaseConnection )
	{
		assert( 'isNonEmptyString( $userName )' );
		assert( 'isNonEmptyString( $userPassword )' );

		$this->userId = parent::createObject( $databaseConnection );
		$this->userName = $userName;
		$this->userPassword = password( $userPassword );
		$this->userIpAddressLoginFirst = $this->userIpAddressLoginLast = clientIpAddress;
		$this->userTimestampLoginFirst = $this->userTimestampLoginLast = date( timestampFormat );
		$this->userEmailAddress = emptyString;
		$this->userType = userTypeWriter;
	}

	////////////////////////////////////////////////////////////////////////////

	public function userId()
	{
		return $this->userId;
	}

	////////////////////////////////////////////////////////////////////////////

	public function userName()
	{
		return $this->userName;
	}

	////////////////////////////////////////////////////////////////////////////

	public function userPassword()
	{
		return $this->userPassword;
	}

	////////////////////////////////////////////////////////////////////////////

	public function userEmailAddress()
	{
		return $this->userEmailAddress;
	}

	////////////////////

	public function setUserEmailAddress( $userEmailAddress )
	{
		assert( 'isString( $userEmailAddress )' );

		if ( $userEmailAddress !== $this->userEmailAddress )
		{
			$this->setIsModified();

			$this->userEmailAddress = $userEmailAddress;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function userIpAddressLoginFirst()
	{
		return $this->userIpAddressLoginFirst;
	}

	////////////////////////////////////////////////////////////////////////////

	public function userIpAddressLoginLast()
	{
		return $this->userIpAddressLoginLast;
	}

	////////////////////

	public function setUserIpAddressLoginLast( $userIpAddressLoginLast )
	{
		assert( 'isNonEmptyString( $userIpAddressLoginLast )' );

		if ( $userIpAddressLoginLast !== $this->userIpAddressLoginLast )
		{
			$this->setIsModified();

			$this->userIpAddressLoginLast = $userIpAddressLoginLast;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function userTimestampLoginFirst()
	{
		return $this->userTimestampLoginFirst;
	}

	////////////////////////////////////////////////////////////////////////////

	public function userTimestampLoginLast()
	{
		return $this->userTimestampLoginLast;
	}

	////////////////////////////////////////////////////////////////////////////

	public function setUserTimestampLoginLast( $userTimestampLoginLast )
	{
		assert( 'isNonEmptyString( $userTimestampLoginLast )' );

		if ( $userTimestampLoginLast !== $this->userTimestampLoginLast )
		{
			$this->setIsModified();

			$this->userTimestampLoginLast = $userTimestampLoginLast;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function userType()
	{
		return $this->userType;
	}

	////////////////////

	public function isAdmin()
	{
		return ( $this->userType === userTypeAdmin );
	}

	////////////////////

	public function isNotAdmin()
	{
		return !$this->isAdmin();
	}

	////////////////////////////////////////////////////////////////////////////

	public static function userByUserId( $userId, $databaseConnection )
	{
		assert( 'isPositiveInt( $userId ) || isNonEmptyString( $userId )' );

		if ( isPositiveInt( $userId ) || isPositiveIntString( $userId ) )
		{
			if ( $user = Object::inMemoryObject( $userId ) ) return $user;
			$fieldUserId = fieldUserId;
		}
		else
		{
			$fieldUserId = fieldUserName;
		}

		$query =
			'SELECT * FROM '.
			userTableName.
			' WHERE '.
			$fieldUserId.equals.singleQuote.$userId.singleQuote;

		return User::userByQuery( $query, $databaseConnection );
	}

	////////////////////////////////////////////////////////////////////////////

	private static function userByQuery( $query, $databaseConnection )
	{
		assert( 'isNonEmptyString( $query )' );

		$result = executeQuery( $query, 'Could not retrieve user using query "'.$query.doubleQuote, $databaseConnection );
		assert( 'isNotEmpty( $result )' );

		if ( isEmpty( $result ) ) return null;

		$rowCount = rowCount( $result );
		assert( 'rowCount( $result ) > -1' );
		assert( 'rowCount( $result ) < 2' );

		if ( $rowCount !== 1 ) return null;

		return User::userFromSchemaArray( nextRow( $result ) );
	}

	////////////////////////////////////////////////////////////////////////////

	public static function userFromSchemaArray( $array )
	{
		$user = Object::objectFromSchemaArray( $array, fieldUserId, classNameUser );
		assert( 'isValidUser( $user )' );

		return ( isValidUser( $user ) ? $user : null );
	}
}

////////////////////////////////////////////////////////////////////////////////

function isValidUser( $object )
{
	return isValidObject( $object ) && is_a( $object, 'User' );
}

////////////////////////////////////////////////////////////////////////////////

function isNotValidUser( $object )
{
	return !isValidUser( $object );
}

////////////////////////////////////////////////////////////////////////////////

?>