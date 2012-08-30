<?php

$startTime = microtime( true );

ob_start( 'ob_gzhandler' );

error_reporting( E_ALL | E_STRICT );
assert_options( ASSERT_ACTIVE, 1 );
assert_options( ASSERT_BAIL, 1 );
assert_options( ASSERT_WARNING, 0 );
assert_options( ASSERT_CALLBACK, 'assert_callback' );

//ini_set( 'session.gc_maxlifetime', '86400' /*seconds*/ );	//	Uncomment this line to make sessions last 24 hours
//session_start();

require( 'resources/configuration.php' );
require( includePath.'include.constants.php' );
require( includePath.'include.entities.php' );
require( includePath.'include.tags.php' );
require( includePath.'include.types.php' );
require( includePath.'include.'.language.'.php' );
require( includePath.'include.utilities.php' );
require( includePath.'include.protocol.php' );
require( includePath.'include.time.php' );
require( includePath.'include.cookies.php' );
require( includePath.'include.database.php' );
require( includePath.'include.object.php' );
require( includePath.'include.user.php' );
require( includePath.'include.photouser.php' );
require( includePath.'include.album.php' );
require( includePath.'include.image.php' );
require( includePath.'include.version.php' );
require( includePath.'include.versionlabel.php' );
require( includePath.'include.markdown.php' );
require( includePath.'include.smartypants.php' );

////////////////////////////////////////////////////////////////////////////////

function __autoload( $class )
{
	assert( 'isNonEmptyString( $class )' );

	switch ( $class )
	{
		case 'DirectoryItem':
			require( includePath.'include.fsitem.php' );
			break;

		case 'Uploader':
			require( includePath.'include.uploader.php' );
			break;

		default:
		{
			echo 'Missing class '.$class.newline;
    		assert( 'false' );
    	}
    }
}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

define( 'rootDirectoryPath', './' );

define( 'xmlFileExtension', '.xml' );
define( 'xmlFileNameAlbum', 'data.xml' );

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

define( 'keyAction', 'd' );
define( 'keyAccessPassword', 'passwordAccess' );

define( 'keyTimestamp', 'ts' );
define( 'keyLatitudeOrLongitude', 'l' );

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

define( 'exifKeyAperture', 'ApertureFNumber' );
define( 'exifKeyCamera', 'Model' );
define( 'exifKeyExposure', 'ExposureTime' );
define( 'exifKeyFocalLength', 'FocalLength' );
define( 'exifKeyIso', 'ISOSpeedRatings' );
define( 'exifKeyTimestamp', 'DateTime' );

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

define( 'sizeDefaultLarge', 800 );
define( 'sizeDefaultMedium', 640 );
define( 'sizeDefaultSmall', 320 );
define( 'sizeDefaultThumbnail', 96 );

define( 'qualityDefaultLarge', 95 );
define( 'qualityDefaultMedium', 85 );
define( 'qualityDefaultSmall', 75 );
define( 'qualityDefaultThumbnail', 65 );

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

define( 'limitFieldAlbumTimestamp', limitStringShort );
define( 'limitFieldAlbumTitle', limitStringShort );
define( 'limitFieldAlbumDescription', limitStringLong );
define( 'limitFieldAlbumTags', limitStringMedium );
define( 'limitFieldAlbumDirectoryName', limitFileName );

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

define( 'actionAdminResetDatabase', 'actionAdminResetDatabase' );
define( 'actionAdminResetTableId', 'actionAdminResetTableId' );
define( 'actionAdminResetTableUser', 'actionAdminResetTableUser' );
define( 'actionAdminResetTableAlbum', 'actionAdminResetTableAlbum' );
define( 'actionAdminResetTableImage', 'actionAdminResetTableImage' );
define( 'actionAdminResetTableVersion', 'actionAdminResetTableVersion' );
define( 'actionAdminResetTableVersionLabel', 'actionAdminResetTableVersionLabel' );
define( 'actionAdminUserDelete', 'actionAdminUserDelete' );
define( 'actionUserAccess', 'actionUserAccess' );
define( 'actionUserLogin', 'actionUserLogin' );
define( 'actionUserLogout', 'logout' );
define( 'actionUserRegister', 'actionUserRegister' );
define( 'actionUserUpdate', 'actionUserUpdate' );
define( 'actionAlbumCreate', 'actionAlbumCreate' );
define( 'actionAlbumDelete', 'actionAlbumDelete' );
define( 'actionAlbumUpdate', 'actionAlbumUpdate' );
define( 'actionAlbumUpload', 'actionAlbumUpload' );
define( 'actionDisplayAdminPage', 'displayAdminPage' );
define( 'actionDisplayAdminTestPage', 'displayAdminTestPage' );
define( 'actionDisplayAdminUserPage', 'displayAdminUserPage' );
define( 'actionDisplayUserAccessPage', 'displayUserAccessPage' );
define( 'actionDisplayUserLoginPage', 'login' );
define( 'actionDisplayUserUpdatePage', 'account' );
define( 'actionDisplayAlbumDeletePage', 'displayAlbumDeletePage' );
define( 'actionDisplayAlbumEditPage', 'displayAlbumEditPage' );
define( 'actionDisplayAlbumPage', 'displayAlbumPage' );
define( 'actionDisplayAlbumSummariesPage', 'displayAlbumSummariesPage' );
define( 'actionDisplayAlbumUploadPage', 'displayAlbumUploadPage' );
define( 'actionDisplayAlbumCaptionPage', 'displayAlbumCaptionPage' );
define( 'actionDisplayImageEditPage', 'displayImageEditPage' );
define( 'actionDisplayVersion', 'v' );
define( 'actionDisplayVersionPage', 'displayVersionPage' );
define( 'actionGetAlbumXml', 'gax' );
define( 'actionSetAlbumXml', 'sax' );
define( 'actionGetTimestampXml', 'gtx' );
define( 'actionGetLatitudeOrLongitude', 'gl' );
define( 'actionDefault', actionDisplayAlbumSummariesPage );

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

define( 'longSeparator', space.middot.space );

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

define( 'linkPattern', '/\[([^|]*)\|([^\]]*?)\]/' );
define( 'linkReplacement', '<a href="$2" rel="external">$1</a>' );

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

define( 'albumCategoryPeriod', 'p' );
define( 'albumCategoryTag', 't' );

define( 'albumCriteriaPeriodAll', periodAll );
define( 'albumCriteriaPeriodRecent', periodRecent );

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

define( 'cookieUserAccess', 'userAccess' );
define( 'cookieUserUserId', 'userId' );

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

define( 'randomCharacterList', 'abcdefghijklmnopqrstuvwxyz1234567890' );
define( 'randomCharacterListLength', strlen( randomCharacterList ) );	//	ASCII-only

define( 'randomFileNameLength', 5 );

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

define( 'fileTypeJpg', 'jpg' );
define( 'fileTypeJpeg', 'jpeg' );
define( 'fileTypeGif', 'gif' );
define( 'fileTypePNG', 'png' );
define( 'fileTypeDng', 'dng' );
define( 'fileTypeCr2', 'cr2' );

define( 'fileTypeLarge', fileTypeJpg );
define( 'fileTypeMedium', fileTypeJpg );
define( 'fileTypeSmall', fileTypeJpg );
define( 'fileTypeThumbnail', fileTypeJpg );

define( 'fileTypesBasic', fileTypeJpg.space.fileTypeJpeg.space.fileTypeGif.space.fileTypePNG );
define( 'fileTypesRaw', fileTypeDng.space.fileTypeCr2 );
define( 'fileTypesTrusted', fileTypesBasic.space.fileTypesRaw );

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

define( 'directoryNameCurrent', dot );
define( 'directoryNameParent', dot.dot );
define( 'directoryNameCR2', 'CR2' );
define( 'directoryNameDNG', 'DNG' );
define( 'directoryNameRAW', 'RAW' );
define( 'directoryNameJPG', 'JPG' );
define( 'directoryNameJPEG', 'JPEG' );
define( 'directoryNameTIF', 'TIF' );
define( 'directoryNameTIFF', 'TIFF' );
define( 'directoryNamePNG', 'PNG' );
define( 'directoryNameGIF', 'GIF' );
define( 'directoryNameAVI', 'AVI' );

////////////////////////////////////////////////////////////////////////////////

//	Normally this should be done as a defined constant, but defined constants
//	can't use the results of functions such as formattedVersionNumber...
global $standardVersions;
$standardVersions = array(
	directoryNameDNG => formattedVersionNumber( versionNumberDNG ),
	directoryNameCR2 => formattedVersionNumber( versionNumberCR2 ),
	directoryNameRAW => formattedVersionNumber( versionNumberRAW ),
	directoryNameTIF => formattedVersionNumber( versionNumberTIF ),
	directoryNameTIFF => formattedVersionNumber( versionNumberTIFF ),
	directoryNamePNG => formattedVersionNumber( versionNumberPNG ),
	directoryNameJPG => formattedVersionNumber( versionNumberJPG ),
	directoryNameJPEG => formattedVersionNumber( versionNumberJPEG ),
	directoryNameGIF => formattedVersionNumber( versionNumberGIF ),
	directoryNameAVI => formattedVersionNumber( versionNumberAVI ),
	directoryNameUnedited => formattedVersionNumber( versionNumberUnedited ),
	directoryNameOriginal => formattedVersionNumber( versionNumberOriginal ),
	directoryNameOriginals => formattedVersionNumber( versionNumberOriginals ),
	directoryNameUncropped => formattedVersionNumber( versionNumberUncropped ),
	directoryNameFull => formattedVersionNumber( versionNumberFull ),
	directoryNameCropped => formattedVersionNumber( versionNumberCropped ),
	directoryNameEdited => formattedVersionNumber( versionNumberEdited ),
	directoryNameDefault => formattedVersionNumber( versionNumberDefault ),
	directoryNameLarge => formattedVersionNumber( versionNumberLarge ),
	directoryNameMedium => formattedVersionNumber( versionNumberMedium ),
	directoryNameSmall => formattedVersionNumber( versionNumberSmall ),
	directoryNameTiny => formattedVersionNumber( versionNumberTiny ),
	directoryNameThumbnail => formattedVersionNumber( versionNumberThumbnail )
	);

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

define( 'permissionsReadWriteAll', 0777 );
define( 'permissionsDirectoryUser', permissionsReadWriteAll );
define( 'permissionsDirectoryAlbum', permissionsReadWriteAll );

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

define( 'cookieShowDetailedTimestamps', fieldUserShowDetailedTimestamps );

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

define( 'smartyPantsAttributes', 'qBcgDeh+H+' );

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

define( 'digits', '0123456789' );

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function addUser( $userName, $userPassword, $databaseConnection )
{
	$user = new PhotoUser;

	$user->createPhotoUser( $userName, $userPassword, $databaseConnection );

	return $user;
}

////////////////////////////////////////////////////////////////////////////////

function newVersion()
{
	return new Version();
}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

$standardVersions;

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

$log = emptyString;

$baseStyle = siteStylesPath;
$pageStyle = emptyString;
$pageHead = emptyString;
$pageIcon = siteIconPath;
$pageTitle = siteName;
$pageContent = emptyString;
$buttonBarUser = emptyString;
$buttonBarAdmin = emptyString;

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

$databaseConnection = connectToDatabaseServer( databaseAddress, databaseLogin, databasePassword );

openDatabase( photoDatabaseName, $databaseConnection );

if ( false )
{
	deleteCookie( cookieUserUserId );

	deleteDatabase( photoDatabaseName, $databaseConnection );
	createDatabase( photoDatabaseName, $databaseConnection );
	openDatabase( photoDatabaseName, $databaseConnection );

	createUserTable( $databaseConnection );
	createAlbumTable( $databaseConnection );
	createImageTable( $databaseConnection );
	createVersionTable( $databaseConnection );
	createVersionLabelTable( $databaseConnection );
}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

$user = null; $userId = cookie( cookieUserUserId, null );

if ( isNotEmpty( $userId ) )
{
	assert( 'isNonEmptyIntString( $userId )' );

	if ( $userId == (int)$userId )
	{
		$user = User::userByUserId( $userId, $databaseConnection );

		if ( $user && $user->isValid() )
		{
			if ( $user->userIpAddressLoginLast() !== clientIpAddress )
			{
				$user = null;
			}
		}
	}

	if ( isEmpty( $user ) || $user->isNotValid() )
	{
		deleteCookie( cookieUserUserId );
	}
	else
	{
		if ( cookieExists( cookieShowDetailedTimestamps ) )
		{
			$user->setUserShowDetailedTimestamps( cookie( cookieShowDetailedTimestamps, displayNone ) );
			$result = deleteCookie( cookieShowDetailedTimestamps );
			assert( '$result === true' );
		}
	}
}
unset( $userId );

////////////////////////////////////////////////////////////////////////////////

$album = null;
$image = null;

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

$action = actionDefault;

if ( false ) ;
else if ( parameterExists( actionUserLogin ) ) $action = actionUserLogin;
else if ( parameterExists( actionUserRegister ) ) $action = actionUserRegister;
else if ( parameterExists( keyUserId ) )
{
	$action = actionDisplayAlbumSummariesPage;
	if ( parameterExists( keyAlbumNumber ) )
	{
		$action = actionDisplayAlbumPage;
		if ( parameterExists( keyImageNumber ) )
		{
			$action = actionDisplayImageEditPage;
			if ( parameterExists( keyVersionNumber ) )
			{
				$action = actionDisplayVersionPage;
			}
		}
	}
}

$action = parameter( keyAction, $databaseConnection, $action );
//print_r( $_REQUEST );exit;

switch ( $action )
{
	case actionUserAccess:	processActionUserAccess( $action, $pageContent, $databaseConnection ); break;
}

if ( !( $userHasAccess = userHasAccess( $user ) ) ) $action = actionDisplayUserAccessPage;

switch ( $action )
{
	case actionAdminResetDatabase:			processActionAdminResetDatabase( $action, $user, $databaseConnection ); break;
	case actionAdminResetTableId:			processActionAdminResetTableId( $action, $user, $databaseConnection ); break;
	case actionAdminResetTableUser:			processActionAdminResetTableUser( $action, $user, $databaseConnection ); break;
	case actionAdminResetTableAlbum:		processActionAdminResetTableAlbum( $action, $user, $databaseConnection ); break;
	case actionAdminResetTableImage:		processActionAdminResetTableImage( $action, $user, $databaseConnection ); break;
	case actionAdminResetTableVersion:		processActionAdminResetTableVersion( $action, $user, $databaseConnection ); break;
	case actionAdminResetTableVersionLabel:	processActionAdminResetTableVersionLabel( $action, $user, $databaseConnection ); break;
	case actionAdminUserDelete:				processActionAdminUserDelete( $action, $user, $databaseConnection ); break;
	case actionUserLogin:					processActionUserLogin( $action, $user, $pageContent, $databaseConnection ); break;
	case actionUserLogout:					processActionUserLogout( $action, $user, $databaseConnection ); break;
	case actionUserRegister:				processActionUserRegister( $action, $user, $pageContent, $databaseConnection ); break;
	case actionUserUpdate:					processActionUserUpdate( $action, $user, $pageContent, $databaseConnection ); break;
	case actionAlbumCreate:					processActionAlbumCreate( $action, $user, $album, $databaseConnection ); break;
	case actionAlbumDelete:					processActionAlbumDelete( $action, $user, $databaseConnection ); break;
	case actionAlbumUpdate:					processActionAlbumUpdate( $action, $user, $album, $pageContent, $databaseConnection ); break;
	case actionAlbumUpload:					processActionAlbumUpload( $action, $user, $album, $pageContent, $databaseConnection ); break;
}

switch ( $action )
{
	default:							displayAlbumSummariesPage( $pageContent, $user, $databaseConnection ); break;
	case actionDisplayAdminPage:		displayAdminPage( $pageContent, $user, $databaseConnection ); break;
	case actionDisplayAdminTestPage:	displayAdminTestPage( $pageContent, $user, $databaseConnection ); break;
	case actionDisplayAdminUserPage:	displayAdminUserPage( $pageContent, $user, $databaseConnection ); break;
	case actionDisplayUserAccessPage:	displayUserAccessPage( $pageContent ); break;
	case actionDisplayUserLoginPage:	displayUserLoginPage( $pageContent, $databaseConnection ); break;
	case actionDisplayUserUpdatePage:	displayUserUpdatePage( $pageContent, $user, $databaseConnection ); break;
	case actionDisplayAlbumDeletePage:	displayAlbumDeletePage( $pageContent, $user, $buttonBarUser, $databaseConnection ); break;
	case actionDisplayAlbumEditPage:	displayAlbumEditPage( $pageContent, $user, $album, $buttonBarUser, $databaseConnection ); break;
	case actionDisplayAlbumPage:		displayAlbumPage( $pageContent, $pageStyle, $buttonBarUser, $user, $album, $databaseConnection ); break;
	case actionDisplayAlbumUploadPage:	displayAlbumUploadPage( $pageContent, $buttonBarUser, $user, $album, $databaseConnection ); break;
	case actionDisplayImageEditPage:	displayImageEditPage( $pageContent, $pageStyle, $pageHead, $buttonBarUser, $user, $album, $image, $databaseConnection ); break;
	case actionDisplayVersion:			displayVersion( $pageContent, $user, $databaseConnection ); return;
	case actionDisplayVersionPage:		displayVersionPage( $pageContent, $buttonBarUser, $user, $album, $image, $version, $databaseConnection ); break;
	case actionGetAlbumXml:				getAlbumXml( $user, $databaseConnection ); break;
	case actionSetAlbumXml:				setAlbumXml( $user, $databaseConnection ); break;
	case actionGetTimestampXml:			getTimestampXml( $databaseConnection ); break;
	case actionGetLatitudeOrLongitude:	getLatitudeOrLongitude( $databaseConnection ); break;
}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

$accountLinks = emptyString;
if ( $userHasAccess )
{
	if ( isNotEmpty( $user ) )
	{
		$parameters = array();
		$buttonBarUser = button( actionAlbumCreate, buttonAlbumCreate, tooltipAlbumCreate, scriptName, emptyString, emptyString, $parameters ).$buttonBarUser;
		if ( $user->isAdmin() )
		{
			if ( $action !== actionDisplayAdminTestPage )  $buttonBarAdmin = button( actionDisplayAdminTestPage,  buttonAdminTest,  emptyString, scriptName, emptyString, emptyString, $parameters ).$buttonBarAdmin;
			if ( $action !== actionDisplayAdminPage ) $buttonBarAdmin = button( actionDisplayAdminPage, buttonAdmin, emptyString, scriptName, emptyString, emptyString, $parameters ).$buttonBarAdmin;
		}
	}

	$accountLinks = '<div id="accountlinks">';
	if ( $user )
	{
		$accountLinks .= '<span title="'.tooltipUsername.quoteBracket.$user->userName().spanTagEnd.nbsp.middot.space;
		$loginLink = keyAction.equals.actionUserLogout.'" id="logoutlink" title="'.tooltipLogout.quoteBracket.textLogout.aTagEnd.nbsp.pipe.space.'<a href="'.scriptName.questionMark.keyAction.equals.actionDisplayUserUpdatePage.'" id="accountlink" title="'.tooltipMyAccount.quoteBracket.textMyAccount;
	}
	else
	{
		$loginLink = keyAction.equals.actionDisplayUserLoginPage.'" id="loginlink" title="'.tooltipLoginRegister.quoteBracket.textLogin.nbsp.slash.nbsp.textRegister;
	}
	$accountLinks .= '<a href="'.scriptName.questionMark.$loginLink.aTagEnd.divTagEnd.newline;
}

////////////////////////////////////////////////////////////////////////////////

$pageHeader =
	'<div id="header">'.newline.
	$accountLinks.
	tableTag.trTag.newline.
	'<td id="headerImage"><img src="resources/logo.gif" width="67" height="67" alt=""/>'.tdTagEnd.newline.
	'<td id="headerTitle"><h1><a href="'.scriptName.quoteBracket.$pageTitle.aTagEnd.h1TagEnd.tdTagEnd.newline.
	trTagEnd.tableTagEnd.newline;

if ( isNotEmpty( $buttonBarUser ) || isNotEmpty( $buttonBarAdmin ) )
{
	$pageHeader .=
		'<table id="buttonbar">'.trTag.newline;

	if ( isNotEmpty( $buttonBarUser ) ) $pageHeader .=
		tdTag.newline.
		$buttonBarUser.
		tdTagEnd.newline;

	if ( isNotEmpty( $buttonBarAdmin ) ) $pageHeader .=
		'<td id="adminbuttons">'.newline.
		$buttonBarAdmin.
		tdTagEnd.newline;

	$pageHeader .=
		trTagEnd.tableTagEnd.newline;
}

$pageHeader .=
	divTagEnd.doubleNewline;

$pageContent =
	$pageHeader.$pageContent;

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function userHasAccess( $user )
{
	return isNotEmpty( $user ) || cookieExists( cookieUserAccess );
}

////////////////////////////////////////////////////////////////////////////////

function validateUser( $userUsername, $userPassword, $userPasswordConfirmation, $userEmail )
{
	require( 'resources/include.validator.php' );

	$validators['u'] = new ValidateUser( $userUsername );
	$validators['p'] = new ValidatePassword( $userPassword, $userPasswordConfirmation );
	if ( $userEmail != emptyString ) $validators['e'] = new ValidateEmail( $userEmail );

	$errors = array();
	foreach ( $validators as $validator ) if ( !$validator->isValid() ) while ( $error = $validator->getError() ) $errors[] = $error;

	if ( strcasecmp( $userUsername, $userPassword ) === 0 ) $errors[] = errorPasswordSameAsUsername;	//	ASCII-only

	return $errors;
}

////////////////////////////////////////////////////////////////////////////////

function isAdmin( $user )
{
	return ( $user && $user->isAdmin() );
}

////////////////////////////////////////////////////////////////////////////////

function idTableSchema()
{
	return fieldMostRecentId.space.'INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT';
}

////////////////////////////////////////////////////////////////////////////////

function userTableSchema()
{
	return PhotoUser::schema();
}

////////////////////////////////////////////////////////////////////////////////

function albumTableSchema()
{
	return Album::schema();
}

////////////////////////////////////////////////////////////////////////////////

function imageTableSchema()
{
	return Image::schema();
}

////////////////////////////////////////////////////////////////////////////////

function versionTableSchema()
{
	return Version::schema();
}

////////////////////////////////////////////////////////////////////////////////

function versionLabelTableSchema()
{
	return VersionLabels::schema();
}

////////////////////////////////////////////////////////////////////////////////

function deleteIdTable( $databaseConnection )
{
	return deleteTable( idTableName, $databaseConnection );
}

////////////////////////////////////////////////////////////////////////////////

function createIdTable( $databaseConnection )
{
	return createTable( idTableName, idTableSchema(), $databaseConnection );
}

////////////////////////////////////////////////////////////////////////////////

function deleteUserTable( $databaseConnection )
{
	return deleteTable( userTableName, $databaseConnection );
}

////////////////////////////////////////////////////////////////////////////////

function createUserTable( $databaseConnection )
{
	return createTable( userTableName, userTableSchema(), $databaseConnection );
}

////////////////////////////////////////////////////////////////////////////////

function deleteUser( $user, $databaseConnection )
{
	assert( 'isValidUser( $user )' );
	assert( '$user->isNotAdmin()' );

	$userId = $user->userId();

	$query =
		'DELETE '.
		versionLabelTableName.
		' FROM '.
		userTableName.' LEFT JOIN '.( albumTableName.' LEFT JOIN '.versionLabelTableName.' ON '.albumTableName.dot.fieldAlbumId.equals.versionLabelTableName.dot.fieldAlbumId ).' ON '.userTableName.dot.fieldUserId.equals.albumTableName.dot.fieldUserId.
		' WHERE '.
		userTableName.dot.fieldUserId.equals.singleQuote.$userId.singleQuote
		;

	$result = executeQuery( $query, 'Unable to delete version labels using query '.$query, $databaseConnection );
	assert( '$result != false' );

	if ( $result == false ) return $result;

	$query =
		'DELETE '.
		userTableName.comma.
		albumTableName.comma.
		imageTableName.comma.
		versionTableName.
		' FROM '.
		userTableName.' LEFT JOIN '.( albumTableName.' LEFT JOIN '.( imageTableName.' LEFT JOIN '.versionTableName.' ON '.imageTableName.dot.fieldImageId.equals.versionTableName.dot.fieldImageId ).' ON '.albumTableName.dot.fieldAlbumId.equals.imageTableName.dot.fieldAlbumId ).' ON '.userTableName.dot.fieldUserId.equals.albumTableName.dot.fieldUserId.
		' WHERE '.
		userTableName.dot.fieldUserId.equals.singleQuote.$userId.singleQuote
		;

	$result = executeQuery( $query, 'Unable to delete user '.$userId.' using query '.$query, $databaseConnection );
	assert( '$result != false' );

	if ( $result == false ) return $result;

	$result = deletePath( rootDirectoryPath.$user->userDirectoryName() );	//	ASCII-only
	assert( '$result === true' );

	return $result;
}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function deleteAlbumTable( $databaseConnection )
{
	return deleteTable( albumTableName, $databaseConnection );
}

////////////////////////////////////////////////////////////////////////////////

function createAlbumTable( $databaseConnection )
{
	return createTable( albumTableName, albumTableSchema(), $databaseConnection );
}

////////////////////////////////////////////////////////////////////////////////

function deleteAlbum( $user, $album, $databaseConnection )
{
	assert( 'isValidUser( $user )' );
	assert( 'isValidAlbum( $album )' );
	assert( '$album->userId() === $user->userId()' );

	if ( isNotValidUser( $user ) ) return false;
	if ( isNotValidAlbum( $album ) ) return false;
	if ( $album->userId() !== $user->userId() ) return false;

	$albumId = $album->albumId();

	$query =
		'DELETE '.
		versionLabelTableName.
		' FROM '.
		albumTableName.' LEFT JOIN '.versionLabelTableName.' ON '.albumTableName.dot.fieldAlbumId.equals.versionLabelTableName.dot.fieldAlbumId.
		' WHERE '.
		albumTableName.dot.fieldAlbumId.equals.singleQuote.$albumId.singleQuote
		;

	$result = executeQuery( $query, 'Unable to delete version labels using query '.$query, $databaseConnection );
	assert( '$result != false' );

	if ( $result == false ) return $result;

	$query =
		'DELETE '.
		albumTableName.comma.
		imageTableName.comma.
		versionTableName.
		' FROM '.
		albumTableName.' LEFT JOIN '.( imageTableName.' LEFT JOIN '.versionTableName.' ON '.imageTableName.dot.fieldImageId.equals.versionTableName.dot.fieldImageId ).' ON '.albumTableName.dot.fieldAlbumId.equals.imageTableName.dot.fieldAlbumId.
		' WHERE '.
		albumTableName.dot.fieldAlbumId.equals.singleQuote.$albumId.singleQuote
		;

	$result = executeQuery( $query, 'Unable to delete album using query '.$query, $databaseConnection );
	assert( '$result != false' );

	if ( $result == false ) return $result;

	$result = deletePath( rootDirectoryPath.$user->userDirectoryName().$album->albumDirectoryNameOld() );
	assert( '$result != false' );

	return $result;
}

////////////////////////////////////////////////////////////////////////////////

function albums( $targetUserId, $albumCategory, $albumCriteria, $publishedOnly, $databaseConnection )
{
	assert( 'isNonEmptyString( $targetUserId )' );
	assert( 'isNonEmptyString( $albumCategory )' );
	assert( 'isNonEmptyString( $albumCriteria )' );
	assert( 'isString( $publishedOnly )' );

	if ( $albumCategory === albumCategoryPeriod )
	{
		if ( $albumCriteria === albumCriteriaPeriodAll )
		{
			return executeQuery( 'SELECT '.fieldAlbumId.comma.fieldAlbumTimestampCreation.comma.fieldAlbumDescription.comma.fieldAlbumTitle.comma.fieldAlbumNumber.comma.fieldUserId.' FROM '.albumTableName.' WHERE '.fieldUserId.equals.singleQuote.$targetUserId.singleQuote.$publishedOnly.' ORDER BY '.fieldAlbumTimestampCreation.' DESC', 'Could not retrieve "'.$albumCriteria.'" from table "'.albumTableName.doubleQuote, $databaseConnection );
		}
		else if ( $albumCriteria === albumCriteriaPeriodRecent )
		{
			return executeQuery( 'SELECT '.fieldAlbumId.comma.fieldAlbumTimestampCreation.comma.fieldAlbumDescription.comma.fieldAlbumTitle.comma.fieldAlbumNumber.comma.fieldUserId.' FROM '.albumTableName.' WHERE '.fieldUserId.equals.singleQuote.$targetUserId.singleQuote.$publishedOnly.' AND ( DATEDIFF(NOW(),'.fieldAlbumTimestampCreation.')<33 OR DATEDIFF(NOW(),'.fieldAlbumTimestampPublication.')<33 ) ORDER BY '.fieldAlbumTimestampCreation.' DESC', 'Could not retrieve "'.$albumCriteria.'" from table "'.albumTableName.doubleQuote, $databaseConnection );
		}
		else
		{
			assert( '(int)$albumCriteria == $albumCriteria' );
			return executeQuery( 'SELECT '.fieldAlbumId.comma.fieldAlbumTimestampCreation.comma.fieldAlbumDescription.comma.fieldAlbumTitle.comma.fieldAlbumNumber.comma.fieldUserId.' FROM '.albumTableName.' WHERE '.fieldUserId.equals.singleQuote.$targetUserId.singleQuote.$publishedOnly.' AND LEFT('.fieldAlbumTimestampCreation.',4)='.$albumCriteria.' ORDER BY '.fieldAlbumTimestampCreation.' DESC', 'Could not retrieve "'.$albumCriteria.'" from table "'.albumTableName.doubleQuote, $databaseConnection );
		}
	}
	else if ( $albumCategory === albumCategoryTag )
	{
			$albumCriteria = htmlspecialchars_decode( $albumCriteria );
			return executeQuery( 'SELECT '.fieldAlbumId.comma.fieldAlbumTimestampCreation.comma.fieldAlbumDescription.comma.fieldAlbumTitle.comma.fieldAlbumNumber.comma.fieldUserId.' FROM '.albumTableName.' WHERE '.fieldUserId.equals.singleQuote.$targetUserId.singleQuote.$publishedOnly.' AND ( '.fieldAlbumTags.' LIKE '.singleQuote.$albumCriteria.singleQuote.' OR '.fieldAlbumTags.' LIKE '.singleQuote.$albumCriteria.';%'.singleQuote.' OR '.fieldAlbumTags.' LIKE '.singleQuote.'%;'.$albumCriteria.';%'.singleQuote.' OR '.fieldAlbumTags.' LIKE '.singleQuote.'%;'.$albumCriteria.singleQuote.' ) ORDER BY '.fieldAlbumTimestampCreation.' DESC', 'Could not retrieve "'.$albumCriteria.'" from table "'.albumTableName.doubleQuote, $databaseConnection );
	}
	else assert( 'false' );
}

////////////////////////////////////////////////////////////////////////////////

function albumTable( $user, $targetUserId, $albums, $tableId )
{
	assert( 'isNotEmpty( $albums )' );

	if ( rowCount( $albums ) === 0 ) return emptyString;

	$isOwner = ( isNotEmpty( $user ) && $user->userId() === $targetUserId );

	$accesskey = accesskeyAlbumEdit;
	$result = '<table id="'.$tableId.'" class="albums">'.newline;
	while ( $row = nextRow( $albums ) )
	{
		$albumId = $row[fieldAlbumId];
		$albumTimestampCreation = $row[fieldAlbumTimestampCreation];
		$albumTitle = $row[fieldAlbumTitle];
		$albumDescription = $row[fieldAlbumDescription];
		$albumNumber = $row[fieldAlbumNumber];
		$userId = $row[fieldUserId];

		$albumCreationDate = formatAlbumDate( $albumTimestampCreation );
		$albumTitle = formatAlbumTitle( $albumTitle );
		$albumDescription = formatAlbumDescription( $albumDescription );

		$albumTitle = h3Tag.$albumCreationDate.h3TagEnd.h2Tag.'<a href="'.scriptName.questionMark.keyUserId.equals.$userId.amp.keyAlbumNumber.equals.$albumNumber.quoteBracket.$albumTitle.aTagEnd.h2TagEnd;

		$result .=
			newline.'<tr id="album'.$albumId.quoteBracket.newline.
			'<td class="albumTitle">'.$albumTitle.'</td>'.newline.
			'<td class="albumDescription">'.newline.
			$albumDescription.newline.
			tdTagEnd.newline;

		if ( $isOwner )
		{
			$parameters = array( keyAlbumId => $albumId );
			$result .=
				'<td class="albumButtons">'.newline.
				button( actionDisplayAlbumEditPage, buttonAlbumEdit, tooltipAlbumEdit, scriptName, emptyString, $accesskey, $parameters ).
				button( actionDisplayAlbumDeletePage, buttonAlbumDelete, tooltipAlbumDelete, scriptName, emptyString, emptyString, $parameters ).
				button( actionDisplayAlbumUploadPage, buttonAlbumUpload, tooltipAlbumUpload, scriptName, emptyString, emptyString, $parameters ).
				tdTagEnd.newline;
			$accesskey = emptyString;
		}

		$result .= trTagEnd.newline;
	}
	$result .= newline.tableTagEnd.newline;

	return $result;
}

////////////////////////////////////////////////////////////////////////////////

function formatAlbumTimestamp( $albumTimestamp, $forEditing = false )
{
	return timestampString( $albumTimestamp );
}

////////////////////////////////////////////////////////////////////////////////

function formatAlbumDate( $albumTimestamp, $forEditing = false )
{
	assert( '$albumTimestamp !== null' );

	if ( is_int( $albumTimestamp ) ) $albumTimestamp = formatAlbumTimestamp( $albumTimestamp, $forEditing );

	return dateString( $albumTimestamp );
}

////////////////////////////////////////////////////////////////////////////////

function formatAlbumTitle( $albumTitle, $forEditing = false )
{
	if ( $albumTitle === emptyString ) return emptyString;

	if ( $forEditing ) return htmlspecialchars( $albumTitle );

	$albumTitle = SmartyPants( $albumTitle, smartyPantsAttributes );

	return $albumTitle;
}

////////////////////////////////////////////////////////////////////////////////

function formatAlbumDescription( $albumDescription, $forEditing = false )
{
	if ( $albumDescription === emptyString ) return emptyString;

	if ( $forEditing ) return htmlspecialchars( $albumDescription );

	$albumDescription = SmartyPants( $albumDescription, smartyPantsAttributes );
	$albumDescription = Markdown( $albumDescription );
	$albumDescription = preg_replace( linkPattern, linkReplacement, $albumDescription );	//	ASCII-only

	return $albumDescription;
}

////////////////////////////////////////////////////////////////////////////////

function formatAlbumTags( $targetUserId, $albumTags, $forEditing = false )
{
	if ( $albumTags === emptyString ) return emptyString;

	$albumTagsArray = explode( semicolon, $albumTags );

	if ( !$forEditing ) return albumTags( $targetUserId, $albumTagsArray, albumCategoryTag, emptyString, true );

	$first = true;
	$albumTags = emptyString;
	foreach ( $albumTagsArray as $albumTag )
	{
		$first ? $first = false : $albumTags .= semicolon.space;
		$albumTags .= htmlspecialchars( $albumTag );
	}

	return $albumTags;
}

////////////////////////////////////////////////////////////////////////////////

function formatAlbumPublish( $albumPublish, $forEditing = false )
{
}

////////////////////////////////////////////////////////////////////////////////

function albumTag( $targetUserId, $albumTag, $key, $currentTag, $isLast )
{
	assert( 'isString( $targetUserId )' );
	assert( 'isNonEmptyString( $albumTag )' );
	assert( 'isNonEmptyString( $key )' );
	assert( 'isString( $currentTag )' );
	assert( 'isBool( $isLast )' );

	$keyIsNotCurrentTag = ( strcmp( $albumTag, $currentTag ) !== 0 );	//	ASCII-only

	$albumTag = htmlspecialchars( $albumTag );

	$result = liTag;

	if ( $keyIsNotCurrentTag ) $result .= '<a href="'.scriptName.questionMark.keyUserId.equals.$targetUserId.amp.$key.equals.urlencode( $albumTag ).quoteBracket;

	$result .= SmartyPants( $albumTag, smartyPantsAttributes );

	if ( $keyIsNotCurrentTag ) $result .= aTagEnd;

	if ( !$isLast ) $result .= longSeparator;

	$result .= liTagEnd.newline;

	return $result;
}

////////////////////////////////////////////////////////////////////////////////

function albumTags( $targetUserId, $albumTagsArray, $key, $currentTag, $isLast )
{
	assert( 'isNotNull( $albumTagsArray )' );
	assert( 'isArray( $albumTagsArray )' );
	assert( 'isNonEmptyString( $key )' );
	assert( 'isString( $key )' );

	$albumTags = emptyString;
	$i = 0;
	$count = count( $albumTagsArray );
	foreach ( $albumTagsArray as $albumTag )
	{
		$albumTags .= albumTag( $targetUserId, $albumTag, $key, $currentTag, $isLast && ( ++$i === $count ) );
	}

	return $albumTags;
}

////////////////////////////////////////////////////////////////////////////////

function allAlbumTags( $targetUserId, $albumCriteria, $publishedOnly, $databaseConnection )
{
	assert( 'isNonEmptyIntString( $targetUserId )' );
	assert( 'isString( $publishedOnly )' );

	$result = executeQuery( 'SELECT '.fieldAlbumTimestampCreation.' FROM '.albumTableName.' WHERE '.fieldUserId.equals.singleQuote.$targetUserId.singleQuote.$publishedOnly.' ORDER BY '.fieldAlbumTimestampCreation.' DESC', 'Could not retrieve album timestamps from table "'.albumTableName.doubleQuote, $databaseConnection );
	assert( '$result != false' );

	if ( !$result ) return false;

	$albumYearsArray = array();
	while ( $row = nextRow( $result ) )
	{
		$albumTimestampCreation = $row[fieldAlbumTimestampCreation];
		assert( '$albumTimestampCreation !== null' );

		$albumYear = yearString( $albumTimestampCreation );
		if ( array_search( $albumYear, $albumYearsArray ) === false ) $albumYearsArray[] = $albumYear;
	}

	$result = rsort( $albumYearsArray );
	assert( '$result' );

	$albumYears = emptyString;
	foreach( $albumYearsArray as $albumYear ) $albumYears .= $albumYear;

	$result = executeQuery( 'SELECT '.fieldAlbumTags.' FROM '.albumTableName.' WHERE '.fieldUserId.equals.singleQuote.$targetUserId.singleQuote.$publishedOnly, 'Could not retrieve album tags from table "'.albumTableName.doubleQuote, $databaseConnection );
	assert( '$result != false' );

	if ( !$result ) return false;

	$albumTagsArray = array();
	while ( $row = nextRow( $result ) )
	{
		$albumTags = $row[fieldAlbumTags];
		if ( $albumTags != emptyString ) $albumTagsArray = array_merge( $albumTagsArray, explode( semicolon, $albumTags ) );
	}

	$albumTagsArray = array_iunique( $albumTagsArray );

	$result = natsort( $albumTagsArray );

	$currentTag = $albumCriteria;

	return
		'<ol class="albumTags">'.newline.
		albumTag( $targetUserId, albumCriteriaPeriodAll, albumCategoryPeriod, $currentTag, false ).
		albumTag( $targetUserId, albumCriteriaPeriodRecent, albumCategoryPeriod, $currentTag, isEmpty( $albumTagsArray ) && isEmpty( $albumYearsArray ) ).
		albumTags( $targetUserId, $albumYearsArray, albumCategoryPeriod, $currentTag, isEmpty( $albumTagsArray ) ).
		albumTags( $targetUserId, $albumTagsArray, albumCategoryTag, $currentTag, true ).
		olTagEnd;
}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function deleteImageTable( $databaseConnection )
{
	return deleteTable( imageTableName, $databaseConnection );
}

////////////////////////////////////////////////////////////////////////////////

function createImageTable( $databaseConnection )
{
	return createTable( imageTableName, imageTableSchema(), $databaseConnection );
}

////////////////////////////////////////////////////////////////////////////////

function deleteImage( $user, $album, $image, $databaseConnection )
{
	assert( 'isValidUser( $user )' );
	assert( 'isValidAlbum( $album )' );
	assert( 'isValidImage( $image )' );
	assert( '$album->userId() === $user->userId()' );
	assert( '$image->albumId() === $album->albumId()' );

	if ( isNotValidUser( $user ) ) return false;
	if ( isNotValidAlbum( $album ) ) return false;
	if ( isNotValidImage( $image ) ) return false;
	if ( $album->userId() !== $user->userId() ) return false;
	if ( $image->albumId() !== $album->albumId() ) return false;

	$imageId = $image->imageId();

	$query =
		'DELETE '.
		imageTableName.comma.
		versionTableName.
		' FROM '.
		imageTableName.' LEFT JOIN '.versionTableName.' ON '.imageTableName.dot.fieldImageId.equals.versionTableName.dot.fieldImageId.
		' WHERE '.
		imageTableName.dot.fieldImageId.equals.singleQuote.$imageId.singleQuote
		;

	$result = executeQuery( $query, 'Unable to delete image using query '.$query, $databaseConnection );
	assert( '$result != false' );

	return $result;
}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function deleteVersionTable( $databaseConnection )
{
	return deleteTable( versionTableName, $databaseConnection );
}

////////////////////////////////////////////////////////////////////////////////

function createVersionTable( $databaseConnection )
{
	return createTable( versionTableName, versionTableSchema(), $databaseConnection );
}

////////////////////////////////////////////////////////////////////////////////

function addVersion( $version, $databaseConnection )
{
	assert( 'isValidVersion( $version )' );

	if ( isNotValidVersion( $version ) ) return false;

	$result = executeQuery( 'INSERT INTO '.versionTableName.' SET '.$version->toSchemaString(), 'Could not insert new version into table "'.versionTableName.doubleQuote, $databaseConnection );
	assert( '$result != false' );
	if ( $result !== true ) return $result;

	$versionId = mysql_insert_id( $databaseConnection );
	assert( 'isNonEmptyInt( $versionId )' );
	if ( !isNonEmptyInt( $versionId ) ) return false;

	$version->setVersionId( $versionId );

	return $version;
}

////////////////////////////////////////////////////////////////////////////////
/*
function updateVersion( $version, $databaseConnection )
{
	assert( 'isValidVersion( $version )' );
	assert( '$version->isModified()' );

	if ( isNotValidVersion( $version ) ) return false;

	return executeQuery( 'UPDATE '.versionTableName.' SET '.$version->toSchemaString().' WHERE '.fieldVersionId.equals.singleQuote.$version->versionId().singleQuote, 'Could not update version "'.$version->versionId().'" in table "'.versionTableName.doubleQuote, $databaseConnection );
}*/

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function deleteVersionLabelTable( $databaseConnection )
{
	return deleteTable( versionLabelTableName, $databaseConnection );
}

////////////////////////////////////////////////////////////////////////////////

function createVersionLabelTable( $databaseConnection )
{
	return createTable( versionLabelTableName, versionLabelTableSchema(), $databaseConnection );
}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function processActionAdminResetDatabase( &$action, &$user, $databaseConnection )
{
	assert( '$action === actionAdminResetDatabase' );
	assert( 'isValidUser( $user )' );
	assert( '$user->isAdmin()' );

	$action = actionDefault;

	if ( isNotValidUser( $user ) ) return;
	if ( $user->isNotAdmin() ) return;

	$action = actionDisplayAdminPage;

	deleteDatabase( photoDatabaseName, $databaseConnection );
	createDatabase( photoDatabaseName, $databaseConnection );
	openDatabase( photoDatabaseName, $databaseConnection );

	createIdTable( $databaseConnection );
	createUserTable( $databaseConnection );
	createAlbumTable( $databaseConnection );
	createImageTable( $databaseConnection );
	createVersionTable( $databaseConnection );
	createVersionLabelTable( $databaseConnection );

	processActionUserLogout( $action, $user );
}

////////////////////////////////////////////////////////////////////////////////

function processActionAdminResetTableId( &$action, &$user, $databaseConnection )
{
	assert( '$action === actionAdminResetTableId' );
	assert( 'isValidUser( $user )' );
	assert( '$user->isAdmin()' );

	$action = actionDefault;

	if ( isNotValidUser( $user ) ) return;
	if ( $user->isNotAdmin() ) return;

	$action = actionDisplayAdminPage;

	deleteIdTable( $databaseConnection );
	createIdTable( $databaseConnection );
}

////////////////////////////////////////////////////////////////////////////////

function processActionAdminResetTableUser( &$action, &$user, $databaseConnection )
{
	assert( '$action === actionAdminResetTableUser' );
	assert( 'isValidUser( $user )' );
	assert( '$user->isAdmin()' );

	$action = actionDefault;

	if ( isNotValidUser( $user ) ) return;
	if ( $user->isNotAdmin() ) return;

	$action = actionDisplayAdminPage;

	deleteUserTable( $databaseConnection );
	createUserTable( $databaseConnection );

	processActionUserLogout( $action, $user );
}

////////////////////////////////////////////////////////////////////////////////

function processActionAdminResetTableAlbum( &$action, $user, $databaseConnection )
{
	assert( '$action === actionAdminResetTableAlbum' );
	assert( 'isValidUser( $user )' );
	assert( '$user->isAdmin()' );

	$action = actionDefault;

	if ( isNotValidUser( $user ) ) return;
	if ( $user->isNotAdmin() ) return;

	$action = actionDisplayAdminPage;

	deleteAlbumTable( $databaseConnection );
	createAlbumTable( $databaseConnection );
}

////////////////////////////////////////////////////////////////////////////////

function processActionAdminResetTableImage( &$action, $user, $databaseConnection )
{
	assert( '$action === actionAdminResetTableImage' );
	assert( 'isValidUser( $user )' );
	assert( '$user->isAdmin()' );

	$action = actionDefault;

	if ( isNotValidUser( $user ) ) return;
	if ( $user->isNotAdmin() ) return;

	$action = actionDisplayAdminPage;

	deleteImageTable( $databaseConnection );
	createImageTable( $databaseConnection );
}

////////////////////////////////////////////////////////////////////////////////

function processActionAdminResetTableVersion( &$action, $user, $databaseConnection )
{
	assert( '$action === actionAdminResetTableVersion' );
	assert( 'isValidUser( $user )' );
	assert( '$user->isAdmin()' );

	$action = actionDefault;

	if ( isNotValidUser( $user ) ) return;
	if ( $user->isNotAdmin() ) return;

	$action = actionDisplayAdminPage;

	deleteVersionTable( $databaseConnection );
	createVersionTable( $databaseConnection );
}

////////////////////////////////////////////////////////////////////////////////

function processActionAdminResetTableVersionLabel( &$action, $user, $databaseConnection )
{
	assert( '$action === actionAdminResetTableVersionLabel' );
	assert( 'isValidUser( $user )' );
	assert( '$user->isAdmin()' );

//	require( includePath.'include.fsitem.php' );
	require( includePath.'include.versionlabel.php' );

	$action = actionDefault;

	if ( isNotValidUser( $user ) ) return;
	if ( $user->isNotAdmin() ) return;

	$action = actionDisplayAdminPage;

	deleteVersionLabelTable( $databaseConnection );
	createVersionLabelTable( $databaseConnection );
}

////////////////////////////////////////////////////////////////////////////////

function processActionUserAccess( &$action, &$pageContent, $databaseConnection )
{
	assert( '$action === actionUserAccess' );

	$password = parameter( keyAccessPassword, $databaseConnection );

	if ( stripos( $password, passwordAccess ) !== false )	//	ASCII-only
	{
		setCookieEternal( cookieUserAccess, 'true' );
		$action = actionDefault;
	}
	else
	{
		$pageContent .= pErrorTag.errorIncorrectPassword.pTagEnd.doubleNewline;
		$action = actionDisplayUserAccessPage;
	}
}

////////////////////////////////////////////////////////////////////////////////

function processActionAdminUserDelete( &$action, $user, $databaseConnection )
{
	assert( '$action === actionAdminUserDelete' );
	assert( 'isValidUser( $user )' );
	assert( '$user->isAdmin()' );

	$action = actionDefault;

	if ( isNotValidUser( $user ) ) return;
	if ( $user->isNotAdmin() ) return;

	$targetUserId = parameter( keyUserId, $databaseConnection );
	assert( 'isNonEmptyIntString( $targetUserId )' );

	if ( !isNonEmptyIntString( $targetUserId ) ) return;

	$targetUser = User::userByUserId( $targetUserId, $databaseConnection );
	assert( 'isValidUser( $targetUser )' );

	if ( isNotValidUser( $targetUser ) ) return;

	if ( $targetUser->isAdmin() ) return;

	$action = actionDisplayAdminPage;

	deleteUser( $targetUser, $databaseConnection );
}

////////////////////////////////////////////////////////////////////////////////

function processActionUserLogin( &$action, &$user, &$pageContent, $databaseConnection )
{
	assert( '$action === actionUserLogin' );
	assert( 'isEmpty( $user )' );
	assert( 'isString( $pageContent )' );

	$username = parameter( fieldUserName, $databaseConnection );
	$password = parameter( fieldUserPassword, $databaseConnection );

	if ( isNotEmpty( $username ) || isNotEmpty( $password ) )
	{
		$passwordConfirmation = $password;
		$userEmail = emptyString;

		$errors = validateUser( $username, $password, $passwordConfirmation, $userEmail );
		if ( count( $errors ) === 0 )
		{
			$user = User::userByUserId( $username, $databaseConnection );

			if ( isValidUser( $user ) )
			{
				if ( $user->userPassword() === password( $password ) )
				{
					$result = setCookieEternal( cookieUserUserId, $user->userId() );
					assert( '$result === true' );

					$user->setUserIpAddressLoginLast( clientIpAddress );
					$user->setUserTimestampLoginLast( date( timestampFormat ) );

					$action = actionDefault;

					return;
				}
			}
		}

		$pageContent .= pErrorTag.errorIncorrectPassword.pTagEnd.doubleNewline;
	}

	$action = actionDisplayUserLoginPage;
}

////////////////////////////////////////////////////////////////////////////////

function processActionUserLogout( &$action, &$user )
{
	assert( '$action === actionUserLogout' );
	assert( 'isValidUser( $user ) || cookieDoesNotExist( cookieUserUserId )' );

	if ( $user !== null )
	{
		$user = null;
		$result = deleteCookie( cookieUserUserId );
		assert( '$result === true' );
	}

	$action = actionDefault;
}

////////////////////////////////////////////////////////////////////////////////

function processActionUserRegister( &$action, &$user, &$pageContent, $databaseConnection )
{
	assert( '$action === actionUserRegister' );
	assert( 'isEmpty( $user )' );
	assert( 'isString( $pageContent )' );

	$action = actionDisplayUserLoginPage;

	$username = parameter( fieldUserName, $databaseConnection );
	$password = parameter( fieldUserPassword, $databaseConnection );

	if ( isNotEmpty( $username ) || isNotEmpty( $password ) )
	{
		$passwordConfirmation = $password;
		$userEmail = emptyString;

		$errors = validateUser( $username, $password, $passwordConfirmation, $userEmail );

		if ( count( $errors ) === 0 )
		{
			if ( isNotNumeric( $username ) )
			{
				$user = User::userByUserId( $username, $databaseConnection );

				if ( isEmpty( $user ) )
				{
					$user = addUser( $username, $password, $databaseConnection );
					assert( 'isValidUser( $user )' );

					$result = setCookieEternal( cookieUserUserId, $user->userId() );
					assert( '$result === true' );

					$action = actionDisplayUserUpdatePage;
				}
				else
				{
					$errors[] = errorUsernameAlreadyTaken;
					$user = null;
				}
			}
			else
			{
				$errors[] = errorUsernameIsNumeric;
				$user = null;
			}
		}

		$pageContent .= formatErrors( $errors );
	}
}

////////////////////////////////////////////////////////////////////////////////

function processActionUserUpdate( &$action, $user, &$pageContent, $databaseConnection )
{
	assert( '$action === actionUserUpdate' );
	assert( 'isValidUser( $user )' );

	$action = actionDefault;

	if ( isNotValidUser( $user ) ) return;

	$action = actionDisplayUserUpdatePage;

	$userPassword = parameter( fieldUserPassword, $databaseConnection );

	if ( isNotEmpty( $userPassword ) && ( $user->userPassword() === password( $userPassword ) ) )
	{
		$userName = $user->userName();
		$userPasswordConfirmation = $userPassword;
		$userEmailAddress = parameter( fieldUserEmail, $databaseConnection );

		$errors = validateUser( $userName, $userPassword, $userPasswordConfirmation, $userEmailAddress );

		if ( count( $errors ) === 0 )
		{
			$user->setUserEmailAddress( $userEmailAddress );
			$action = actionDefault;
		}
	}
	else
	{
		$errors[] = errorIncorrectPassword;
	}

	$pageContent .= formatErrors( $errors );
}

////////////////////////////////////////////////////////////////////////////////

function processActionAlbumCreate( &$action, $user, &$album, $databaseConnection )
{
	assert( '$action === actionAlbumCreate' );
	assert( 'isValidUser( $user )' );
	assert( 'isEmpty( $album )' );

	if ( isNotValidUser( $user ) ) return;

	$album = $user->addAlbum( $databaseConnection );

	$action = actionDisplayAlbumEditPage;
}

////////////////////////////////////////////////////////////////////////////////

function processActionAlbumDelete( &$action, $user, $databaseConnection )
{
	assert( '$action === actionAlbumDelete' );
	assert( 'isValidUser( $user )' );

	$action = actionDisplayAlbumSummariesPage;

	if ( isNotValidUser( $user ) ) return;

	$albumId = parameter( keyAlbumId, $databaseConnection );
	assert( 'isNonEmptyIntString( $albumId )' );

	if ( isNonEmptyIntString( $albumId ) )
	{
		$album = Album::albumByAlbumId( $albumId, $databaseConnection );
		assert( 'isValidAlbum( $album )' );
		assert( '$album->userId() === $user->userId()' );

		if ( isValidAlbum( $album ) )
		{
			if ( $album->userId() === $user->userId() )
			{
				deleteAlbum( $user, $album, $databaseConnection );
			}
		}
	}
}

////////////////////////////////////////////////////////////////////////////////

function processActionAlbumUpdate( &$action, $user, &$album, &$pageContent, $databaseConnection )
{
	//	Note that this function can be fed user-generated input, so heavy
	//	sanitization is required.

	//	Preconditions:
	assert( 'isValidUser( $user )' );
	assert( 'isEmpty( $album )' );

	//	If we weren't given a valid user, bug out:
	if ( isNotValidUser( $user ) ) return;

	//	Extract the album id from the "hidden" form field:
	$albumId = parameter( keyAlbumId, $databaseConnection );
	assert( 'isNonEmptyIntString( $albumId )' );

	//	If we weren't given an integer string, bug out:
	if ( !isNonEmptyIntString( $albumId ) ) return false;

	//	Load the album:
	$album = Album::albumByAlbumId( $albumId, $databaseConnection );
	assert( 'isValidAlbum( $album )' );
	assert( '$album->userId() === $user->userId()' );

	//	If we couldn't find the correct album, bug out:
	if ( isNotValidAlbum( $album ) ) return false;

	//	If the album doesn't belong to this user, bug out:
	if ( $album->userId() !== $user->userId() ) return false;

	//	First, extract the user-supplied input from each of the form fields:
	$albumTimestampCreation = parameter( fieldAlbumTimestampCreation, $databaseConnection );
	$albumTimestampPublication = parameter( fieldAlbumTimestampPublication, $databaseConnection );
	$albumTitle = parameter( fieldAlbumTitle, $databaseConnection );
	$albumDescription = parameter( fieldAlbumDescription, $databaseConnection );
	$albumTags = parameter( fieldAlbumTags, $databaseConnection );
	$albumDoPublish = parameterExists( fieldAlbumDoPublish );

	//	Next, check the input data types from a programmatic point of view; note
	//	that the type of the user-supplied inputs have already been checked:
	assert( 'isNonEmptyIntString( $albumId )' );
	assert( 'isBool( $albumDoPublish )' );
	assert( '( $albumDoPublish === true ) || ( $albumDoPublish === false )' );

	//	Now, double-check the types of the user-supplied inputs when assertions
	//	have been turned off. If they fail the check, it may that someone is
	//	trying to game the $_POST data, so we silently fail so as to not give up
	//	any hints as to what we're expecting to a would-be attacker:
	if ( !isNonEmptyIntString( $albumId ) ) return false;
	if ( isNotString( $albumTimestampCreation ) ) return false;
	if ( isNotString( $albumTimestampPublication ) ) return false;
	if ( isNotString( $albumTitle ) ) return false;
	if ( isNotString( $albumDescription ) ) return false;
	if ( isNotString( $albumTags ) ) return false;
	if ( isNotBool( $albumDoPublish ) ) return false;

	//	Next, check for absurdly long strings, another sign of possible attack:
	if ( strlen( $albumTimestampCreation ) > limitFieldAlbumTimestamp ) return false;		//	ASCII-only
	if ( strlen( $albumTimestampPublication ) > limitFieldAlbumTimestamp ) return false;	//	ASCII-only
	if ( strlen( $albumTitle ) > limitFieldAlbumTitle ) return false;						//	ASCII-only
	if ( strlen( $albumDescription ) > limitFieldAlbumDescription ) return false;			//	ASCII-only
	if ( strlen( $albumTags ) > limitFieldAlbumTags ) return false;							//	ASCII-only

	//	Now, check for missing-but-required data in a more user-friendly way:
	$errors = array();
	if ( isEmpty( $albumId ) ) $errors[] = errorAlbumMissingAlbumId;
	if ( isEmpty( $albumTimestampCreation ) ) $errors[] = errorAlbumMissingTimestampCreation;
	if ( isEmpty( $albumTimestampPublication ) ) $errors[] = errorAlbumMissingTimestampPublication;
	if ( isEmpty( $albumTitle ) ) $errors[] = errorAlbumMissingAlbumTitle;

	//	Only continue if the error count is 0:
	if ( count( $errors ) === 0 )
	{
		//	Lastly, clean up any data and put it in a database-friendly format; note
		//	that this precludes the user from using the string "0" as either the
		//	album description or the tag list, but I think that's a limitation we
		//	can all live with:
		if ( isEmpty( $albumDescription ) ) $albumDescription = emptyString;
		if ( isEmpty( $albumTags ) ) $albumTags = emptyString;
		$albumDoPublish = ( $albumDoPublish ? one : zero );

		//	Convert the given timestamps from strings to integers:
		$albumTimestampCreation = strtotime( $albumTimestampCreation );
		$albumTimestampPublication = strtotime( $albumTimestampPublication );

		//	If the string can't be parsed, return an error:
		if ( !$albumTimestampCreation ) $errors[] = errorAlbumInvalidTimestampFormatCreation;
		if ( !$albumTimestampPublication ) $errors[] = errorAlbumInvalidTimestampFormatCreation;

		if ( isEmpty( $albumTimestampCreation ) ) $errors[] = errorAlbumInvalidTimestampCreation;
		if ( isEmpty( $albumTimestampPublication ) ) $errors[] = errorAlbumInvalidTimestampPublication;

		//	Only continue if the error count is 0:
		if ( count( $errors ) === 0 )
		{
			//	Now, reformat the timestamp into proper canonical form:
			$albumTimestampCreation = timestampString( $albumTimestampCreation );
			$albumTimestampPublication = timestampString( $albumTimestampPublication );

			//	If, for some reason, it couldn't be done
			if ( !$albumTimestampCreation ) $errors[] = errorAlbumInvalidTimestampCreation;
			if ( !$albumTimestampPublication ) $errors[] = errorAlbumInvalidTimestampPublication;

			//	You know the drill:
			if ( count( $errors ) === 0 )
			{
				//	Strip any potential escaped slashes:
				$albumTitle = stripcslashes( $albumTitle );
				$albumDescription = stripcslashes( $albumDescription );
				$albumTags = stripcslashes( $albumTags );

				//	Replace all forms of unwanted whitespace with semicolons; tags are separated by semicolons:
				$whitespace = array( carriageReturn.newline, carriageReturn, newline, tab, space.space, semicolon.space );
				$albumTags = str_replace( $whitespace, semicolon, $albumTags );	//	ASCII-only

				//	Replace repeating runs of semicolon with a single semicolon:
				$albumTags = preg_replace( '{(;)\1+}', '$1', $albumTags );	//	ASCII-only

				//	Trim any semicolon from the front and back of the string:
				$albumTags = trim( $albumTags, semicolon );	//	ASCII-only

				//	Now, remove any duplicates and sort them:
				$albumTags = explode( semicolon, $albumTags );
				$albumTags = array_iunique( $albumTags );
				natsort( $albumTags );
				$albumTags = implode( semicolon, $albumTags );

				//	Grab the current timestamp:
				$albumTimestampModification = date( timestampFormat );

				//	Stuff all of this into the album:
				$album->setAlbumTimestampCreation( $albumTimestampCreation );
				$album->setAlbumTimestampModification( $albumTimestampModification );
				$album->setAlbumTimestampPublication( $albumTimestampPublication );
				$album->setAlbumTitle( $albumTitle, $user, $databaseConnection );
				$album->setAlbumDescription( $albumDescription );
				$album->setAlbumTags( $albumTags );
				$album->setAlbumDoPublish( $albumDoPublish );

				//	Store the changes; we could perhaps wait until the
				//	Object::storeAll() function executes at the very end of
				//	the script, but this would require us removing an
				//	important safety check assertion elsewhere. This should
				//	have little practical effect on performance but increases
				//	safety a great deal, so it seems a reasonable tradeoff:
				Object::storeAll( $databaseConnection );
			}
		}
	}

	//	Signal that we're now going to show the updated album to the user:
	$action = actionDisplayAlbumPage;

	//	Force the display of any errors:
	if ( count( $errors ) !== 0 )
	{
		$pageContent .= formatErrors( $errors );
		$action = actionDisplayAlbumEditPage;
	}
}

////////////////////////////////////////////////////////////////////////////////

function processActionAlbumUpload( &$action, $user, &$album, &$pageContent, $databaseConnection )
{
	assert( '$action == actionAlbumUpload' );
	assert( 'isValidUser( $user )' );
	assert( 'isEmpty( $album )' );
	assert( 'isString( $pageContent )' );

	$action = actionDefault;

	if ( isNotValidUser( $user ) ) return;

	$albumId = parameter( keyAlbumId, $databaseConnection );
	assert( 'isNonEmptyIntString( $albumId )' );

	if ( !isNonEmptyIntString( $albumId ) ) return;

	$album = Album::albumByAlbumId( $albumId, $databaseConnection );
	assert( 'isValidAlbum( $album )' );
	assert( '$album->userId() === $user->userId()' );

	if ( isNotValidAlbum( $album ) ) return;
	if ( $album->userId() !== $user->userId() ) return;

	$albumDoResize = parameterExists( fieldAlbumDoResize );
	assert( '( $albumDoResize === true ) || ( $albumDoResize === false )' );

	if ( isNotBool( $albumDoResize ) ) return false;

	$albumDoResize = ( $albumDoResize ? one : zero );

	$album->setAlbumDoResize( $albumDoResize );

	$action = actionDisplayAlbumUploadPage;

	$result = Uploader::upload( $user, $album, $databaseConnection );

	if ( isNonEmptyString( $result ) )
	{
		$pageContent .=
			pErrorTag.
			$result.
			pTagEnd;
	}
	else
	{
		$pageContent .=
			pErrorTag.
			uploadAlbumImagesSuccess.
			pTagEnd;
	}
}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function displayUserAccessPage( &$pageContent )
{
	assert( 'isString( $pageContent )' );

	$pageContent .=
		'<div id="userAccessPage">'.doubleNewline.

		pTag.newline.
		messageAccess1.newline.
		pTagEnd.doubleNewline.

		'<form method="post" action="'.scriptName.quoteBracket.newline.
		divTag.newline.
		'<label for="password">'.textPassword.labelTagEnd.newline.
		'<input  id="password" type="text" name="'.keyAccessPassword.'" accesskey="'.accesskeyPassword.'" tabindex="1"/>'.newline.
		'<button id="submit" type="submit" value="'.buttonSubmit.'" name="'.actionUserAccess.'" accesskey="'.accesskeySubmit.'" tabindex="2" class="button">'.buttonSubmit.buttonTagEnd.newline.
		'<input  type="hidden" name="'.keyAction.'" value="'.actionUserAccess.quoteSlashBracket.newline.
		divTagEnd.newline.
		formTagEnd.doubleNewline.

		pTag.newline.
		messageAccess2.newline.
		pTagEnd.doubleNewline.

		divTagEnd.newline;
}

////////////////////////////////////////////////////////////////////////////////

function displayAdminPage( &$pageContent, $user, $databaseConnection )
{
	assert( 'isString( $pageContent )' );
	assert( 'isValidUser( $user )' );
	assert( '$user->isAdmin()' );

	if ( isNotValidUser( $user ) ) return;
	if ( $user->isNotAdmin() ) return;

	$users = executeQuery( 'SELECT '.fieldUserId.comma.fieldUserName.' FROM '.userTableName.' ORDER BY '.fieldUserName, 'Could not retrieve users from table "'.userTableName.doubleQuote, $databaseConnection );
	assert( '$users != false' );

	if ( !$users ) return;

	$phrase1 = liTag.aTag.scriptName.questionMark.keyAction.equals.actionDisplayAdminUserPage.amp.keyUserId.equals;
	$phrase2 = nbsp.leftParenthesis;
	$phrase3 = rightParenthesis.aTagEnd.liTagEnd.newline;

	$parameters = array();
	$pageContent .=
		'<div id="adminPage">'.doubleNewline.
		'<div id="adminbuttons">'.newline.
		button( actionAdminResetDatabase, buttonResetDatabase, tooltipResetDatabase, scriptName, emptyString, emptyString, $parameters ).
		button( actionAdminResetTableId, buttonResetTableId, tooltipResetTableId, scriptName, emptyString, emptyString, $parameters ).
		button( actionAdminResetTableUser, buttonResetTableUser, tooltipResetTableUser, scriptName, emptyString, emptyString, $parameters ).
		button( actionAdminResetTableAlbum, buttonResetTableAlbum, tooltipResetTableAlbum, scriptName, emptyString, emptyString, $parameters ).
		button( actionAdminResetTableImage, buttonResetTableImage, tooltipResetTableImage, scriptName, emptyString, emptyString, $parameters ).
		button( actionAdminResetTableVersion, buttonResetTableVersion, tooltipResetTableVersion, scriptName, emptyString, emptyString, $parameters ).
		button( actionAdminResetTableVersionLabel, buttonResetTableVersionLabel, tooltipResetTableVersionLabel, scriptName, emptyString, emptyString, $parameters ).
		divTagEnd.doubleNewline.

		olTag.newline;

	while ( $row = nextRow( $users ) )
	{
		$userId = $row[fieldUserId];
		$userName = strtolower( $row[fieldUserName] );	//	ASCII-only

		$pageContent .=
			$phrase1.$userId.quoteBracket.$userName.$phrase2.$userId.$phrase3;
	}

	$pageContent .=
		olTagEnd.doubleNewline.
		divTagEnd.newline;
}

////////////////////////////////////////////////////////////////////////////////

function displayAdminTestPage( &$pageContent, $user, $databaseConnection )
{
	assert( 'isString( $pageContent )' );
	assert( 'isValidUser( $user )' );
	assert( '$user->isAdmin()' );

	if ( isNotValidUser( $user ) ) return;
	if ( $user->isNotAdmin() ) return;

	$pageContent .=
		'<div id="adminTestPage">'.doubleNewline;

	$address = 'Minneapolis, MN, USA';

	$pageContent .= pTag.geocodeUsingOpenStreetMap( urlencode( $address ) ).pTagEnd.doubleNewline;
	$pageContent .= pTag.reverseGeocodeUsingOpenStreetMap( 44.96766, -93.21132 ).pTagEnd.doubleNewline;
	$pageContent .= pTag.geocodeUsingGoogleMaps( urlencode( $address ) ).pTagEnd.doubleNewline;
	$pageContent .= pTag.reverseGeocodeUsingGoogleMaps( 44.96766, -93.21132 ).pTagEnd.doubleNewline;

	$pageContent .=
		divTagEnd.newline;
}

////////////////////////////////////////////////////////////////////////////////

function displayAdminUserPage( &$pageContent, $user, $databaseConnection )
{
	assert( 'isString( $pageContent )' );
	assert( 'isValidUser( $user )' );
	assert( '$user->isAdmin()' );

	if ( isNotValidUser( $user ) ) return;
	if ( $user->isNotAdmin() ) return;

	$targetUserId = parameter( keyUserId, $databaseConnection );
	assert( 'isNonEmptyIntString( $targetUserId )' );

	if ( !isNonEmptyIntString( $targetUserId ) ) return;

	$targetUser = User::userByUserId( $targetUserId, $databaseConnection );
	assert( 'isValidUser( $targetUser )' );

	if ( isNotValidUser( $targetUser ) ) return;

	$deleteButton = emptyString;
	if ( $targetUser->isNotAdmin() ) $deleteButton =
		button( actionAdminUserDelete, buttonDelete, emptyString, scriptName, 1, emptyString, array( keyUserId => $targetUser->userId() ) );

	$pageContent .=
		'<div id="userViewPage">'.doubleNewline.

		tableTag.trTag.newline.
		tdTag.newline.
		'<table class="user">'.newline.
		$targetUser->toHtmlString().newline.
		tableTagEnd.newline.
		tdTagEnd.newline.
		tdTag.newline.
		$deleteButton.
		tdTagEnd.newline.
		trTagEnd.tableTagEnd.doubleNewline.

		divTagEnd.newline;

}

////////////////////////////////////////////////////////////////////////////////

function displayUserLoginPage( &$pageContent, $databaseConnection )
{
	assert( 'isString( $pageContent )' );

	$username = parameter( fieldUserName, $databaseConnection );
	assert( 'isString( $username )' );

	$pageContent .=
		'<div id="userLoginPage">'.doubleNewline.

		'<form method="post" action="'.scriptName.quoteBracket.newline.
		divTag.doubleNewline.

		'<div class="field">'.newline.
		'<label for="username" class="label">'.labelUsername.labelTagEnd.newline.
		'<input id="username" type="text" name="'.fieldUserName.'" value="'.$username.'" accesskey="'.accesskeyUsername.'" tabindex="1"/>'.newline.
		divTagEnd.doubleNewline.

		'<div class="field">'.newline.
		'<label for="password" class="label">'.labelPassword.labelTagEnd.newline.
		'<input id="password" type="password" name="'.fieldUserPassword.'" accesskey="'.accesskeyPassword.'" tabindex="2"/>'.doubleNewline.
		'<div id="buttons">'.newline.
		'<button type="submit" name="'.actionUserLogin.'" value="'.textLogin.'" tabindex="3" accesskey="'.accesskeyLogin.'" class="button">'.textLogin.buttonTagEnd.space.textOr.newline.
		'<button type="submit" name="'.actionUserRegister.'" value="'.textRegister.'" tabindex="4" accesskey="'.accesskeyRegister.'" class="button">'.textRegister.buttonTagEnd.newline.
		divTagEnd.newline.
		divTagEnd.doubleNewline.

		divTagEnd.newline.
		formTagEnd.doubleNewline.

		divTagEnd.newline;
}

////////////////////////////////////////////////////////////////////////////////

function displayUserUpdatePage( &$pageContent, $user, $databaseConnection )
{
	assert( 'isValidUser( $user )' );

	if ( isNotValidUser( $user ) ) return;

	$pageContent .=
		'<div id="userUpdatePage">'.doubleNewline.

		'<form method="post" action="'.scriptName.quoteBracket.newline.
		divTag.doubleNewline.

		'<div class="field">'.newline.
		'<label for="username" class="label">'.labelUserName.labelTagEnd.newline.
		'<span id="username">'.$user->userName().spanTagEnd.newline.
		divTagEnd.doubleNewline.

		'<div class="field">'.newline.
		'<label for="password" class="label">'.labelPassword.labelTagEnd.newline.
		'<input id="password" type="password" name="'.fieldUserPassword.'" accesskey="'.accesskeyPassword.'" tabindex="1"/>'.newline.
		divTagEnd.doubleNewline.

		'<div class="field">'.newline.
		'<label for="email" class="label"><span class="accesskey">'.labelEmail.labelTagEnd.newline.
		'<input id="email" type="text" name="'.fieldUserEmailAddress.'" accesskey="'.accesskeyEmailAddress.'" value="'.$user->userEmailAddress().'" tabindex="2"/>'.doubleNewline.

		'<input type="hidden" value="'.$user->userId().'" name="'.keyUserId.quoteSlashBracket.newline.
		'<input type="hidden" name="'.keyAction.'" value="'.actionUserUpdate.quoteSlashBracket.newline.
		'<button id="update" type="submit" name="'.actionUserUpdate.'" value="'.buttonSave.'" accesskey="'.accesskeySave.'" tabindex="3" class="button">'.buttonSave.buttonTagEnd.newline.
		button( actionDefault, buttonCancel, emptyString, scriptName, 4, emptyString, array() ).
		divTagEnd.doubleNewline.
		divTagEnd.newline.
		formTagEnd.doubleNewline.

		divTagEnd.newline;

}

////////////////////////////////////////////////////////////////////////////////

function displayAlbumSummariesPage( &$pageContent, $user, $databaseConnection )
{
	assert( 'isString( $pageContent )' );
	assert( 'isEmpty( $user ) || $user->isValid()' );

	$targetUserId = parameter( keyUserId, $databaseConnection, isNotEmpty( $user ) ? $user->userId() : one );

	$albumCategory = parameterExists( albumCategoryTag ) ? albumCategoryTag : albumCategoryPeriod;
	$albumCriteria = parameter( $albumCategory, $databaseConnection, albumCriteriaPeriodAll );

	if ( isNotEmpty( $user ) && $user->userId() === $targetUserId )
	{
		$publishedOnly = emptyString;
	}
	else
	{
		$publishedOnly = ' AND '.fieldAlbumDoPublish.equals.one;
	}

	$albums = albums( $targetUserId, $albumCategory, $albumCriteria, $publishedOnly, $databaseConnection );

	if ( isNotEmpty( $user ) && $targetUserId !== $user->userId() )
	{
		$targetUser = User::userByUserId( $targetUserId, $databaseConnection );

		if ( isEmpty( $targetUser ) ) return;

		$owners = h2Tag.textPhotosBy.$targetUser->userName().h2TagEnd.doubleNewline;
	}
	else
	{
		$owners = emptyString;
	}

	$pageContent .=
		'<div id="albumSummariesPage">'.doubleNewline.
		$owners.
		allAlbumTags( $targetUserId, $albumCriteria, $publishedOnly, $databaseConnection ).doubleNewline.
		albumTable( $user, $targetUserId, $albums, 'albums' ).newline.
		divTagEnd.newline;
}

////////////////////////////////////////////////////////////////////////////////

function displayAlbumDeletePage( &$pageContent, $user, &$buttonBarUser, $databaseConnection )
{
	assert( 'parameterExists( keyAlbumId )' );

	$albumId = parameter( keyAlbumId, $databaseConnection );
	assert( 'isNonEmptyIntString( $albumId )' );

	if ( !isNonEmptyIntString( $albumId ) )
	{
		return;
	}

	$album = Album::albumByAlbumId( $albumId, $databaseConnection );
	assert( 'isValidAlbum( $album )' );

	if ( isNotValidAlbum( $album ) ) return;

	$userId = $album->userId();
	assert( '$userId === $user->userId()' );

	if ( $userId !== $user->userId() ) return;

	$albumNumber = $album->albumNumber();
	$albumTitle = formatAlbumTitle( $album->albumTitle() );
	$albumDescription = formatAlbumDescription( $album->albumDescription() );

	$pageContent .=
		'<div id="albumDeletePage">'.doubleNewline.

		'<p class="question">'.displayAlbumDeletePageConfirmationDeleteAlbum.pTagEnd.doubleNewline.
		button( actionDisplayAlbumPage, buttonNo, emptyString, scriptName.questionMark.keyUserId.equals.$userId.amp.keyAlbumNumber.equals.$albumNumber, 1, emptyString, array() ).newline.
		button( actionAlbumDelete, buttonYes, emptyString, scriptName, 2, emptyString, array( keyAlbumId => $albumId ) ).newline.

		'<div class="albumSummary">'.newline.
		'<h3 class="albumTimestampCreation">'.$album->albumTimestampCreation().h3TagEnd.newline.
		'<h2 class="albumTitle">'.$albumTitle.h2TagEnd.newline.
		$albumDescription.
		divTagEnd.doubleNewline.
		divTagEnd.newline;

	$parameters = array( keyAlbumId => $albumId );
	$buttonBarUser .=
		button( actionDisplayAlbumEditPage, buttonAlbumEdit, tooltipAlbumEdit, scriptName, emptyString, emptyString, array() ).
		button( actionDisplayAlbumPage, buttonAlbumView, tooltipAlbumDelete, scriptName.questionMark.keyUserId.equals.$userId.amp.keyAlbumNumber.equals.$albumNumber, emptyString, emptyString, $parameters ).
		button( actionDisplayAlbumUploadPage, buttonAlbumUpload, tooltipAlbumUpload, scriptName, emptyString, emptyString, $parameters );
}

////////////////////////////////////////////////////////////////////////////////

function displayAlbumEditPage( &$pageContent, $user, $album, &$buttonBarUser, $databaseConnection )
{
	assert( 'isString( $pageContent )' );
	assert( 'isValidUser( $user )' );
	assert( 'isEmpty( $album ) || ( $album->isValid() && ( $album->userId() === $user->userId() ) )' );
	assert( 'isString( $buttonBarUser )' );

	if ( isEmpty( $album ) )
	{
		$albumId = parameter( keyAlbumId, $databaseConnection );
		assert( 'isNonEmptyIntString( $albumId )' );

		if ( !isNonEmptyIntString( $albumId ) ) return;

		$album = Album::albumByAlbumId( $albumId, $databaseConnection );
		assert( 'isValidAlbum( $album )' );

		if ( isNotValidAlbum( $album ) ) return;
	}
	else
	{
		$albumId = $album->albumId();
	}

	if ( $album->isNotValid() ) return;
	assert( '$album->userId() === $user->userId()' );

	$userId = $album->userId();

	if ( $userId != $user->userId() ) return;

	$albumNumber = $album->albumNumber();
	$albumTitle = formatAlbumTitle( $album->albumTitle(), true );
	$albumDescription = formatAlbumDescription( $album->albumDescription(), true );
	$albumTags = formatAlbumTags( $userId, $album->albumTags(), true );
	$albumDoPublish = ( $album->albumDoPublish() ? 'checked="checked" ' : emptyString );

	$pageContent .=
		'<div id="albumEditPage">'.doubleNewline.
		'<form method="post" action="'.scriptName.questionMark.keyUserId.equals.$userId.amp.keyAlbumNumber.equals.$albumNumber.quoteBracket.newline.
		'<div class="fields">'.doubleNewline.

		'<label for="'.fieldAlbumTimestampCreation.quoteBracket.labelAlbumTimestampCreation.labelTagEnd.newline.
		'<input tabindex="1" class="input" type="text" value="'.$album->albumTimestampCreation().'" name="'.fieldAlbumTimestampCreation.'" accesskey="'.accesskeyAlbumTimestampCreation.quoteSlashBracket.doubleNewline.

		'<label for="'.fieldAlbumTimestampPublication.quoteBracket.labelAlbumTimestampPublication.labelTagEnd.newline.
		'<input tabindex="2" class="input" type="text" value="'.$album->albumTimestampPublication().'" name="'.fieldAlbumTimestampPublication.'" accesskey="'.accesskeyAlbumTimestampPublication.quoteSlashBracket.doubleNewline.

		'<label for="'.fieldAlbumTitle.quoteBracket.labelAlbumTitle.labelTagEnd.newline.
		'<input tabindex="3" id="'.fieldAlbumTitle.'" class="input" type="text" value="'.$albumTitle.'" name="'.fieldAlbumTitle.'" accesskey="'.accesskeyAlbumTitle.quoteSlashBracket.doubleNewline.

		'<label for="'.fieldAlbumDescription.quoteBracket.labelAlbumDescription.labelTagEnd.newline.
		'<textarea tabindex="4" id="'.fieldAlbumDescription.'" name="'.fieldAlbumDescription.'" accesskey="'.accesskeyAlbumDescription.quoteBracket.
		$albumDescription.
		textareaTagEnd.doubleNewline.

		'<label id="'.fieldAlbumTags.'Label" for="'.fieldAlbumTags.quoteBracket.labelAlbumTags.'<span id="'.fieldAlbumTags.'Hint'.quoteBracket.hintAlbumTags.spanTagEnd.labelTagEnd.newline.
		'<textarea tabindex="5" id="'.fieldAlbumTags.'" name="'.fieldAlbumTags.'" accesskey="'.accesskeyAlbumTags.quoteBracket.
		$albumTags.
		textareaTagEnd.doubleNewline.

		divTagEnd.doubleNewline.

		'<div class="checkboxes">'.newline.
		'<label for="'.fieldAlbumDoPublish.quoteBracket.labelAlbumDoPublish.labelTagEnd.newline.
		'<input tabindex="6" class="input" id="'.fieldAlbumDoPublish.'" type="checkbox" '.$albumDoPublish.'name="'.fieldAlbumDoPublish.'" accesskey="'.accesskeyAlbumDoPublish.quoteSlashBracket.newline.
		divTagEnd.doubleNewline.

		'<div id="buttons">'.newline.
		'<input type="hidden" value="'.$album->albumId().'" name="'.keyAlbumId.quoteSlashBracket.doubleNewline.
		'<input type="hidden" name="'.keyAction.'" value="'.actionAlbumUpdate.quoteSlashBracket.newline.
		'<button tabindex="7" class="button" type="submit" value="'.buttonSave.'" accesskey="'.accesskeySave.quoteBracket.buttonSave.buttonTagEnd.newline.
		divTagEnd.doubleNewline.
		formTagEnd.newline.

		'<form class="button" method="post" action="'.scriptName.questionMark.keyUserId.equals.$userId.amp.keyAlbumNumber.equals.$albumNumber.quoteBracket.newline.
		button( actionDisplayAlbumPage, buttonCancel, emptyString, scriptName.questionMark.keyUserId.equals.$userId.amp.keyAlbumNumber.equals.$albumNumber, 8, emptyString, array() ).
		divTagEnd.doubleNewline.

		formTagEnd.doubleNewline.
		divTagEnd.newline;

	$parameters = array( keyAlbumId => $albumId );
	$buttonBarUser .=
		button( actionDisplayAlbumPage, buttonAlbumView, tooltipAlbumView, scriptName.questionMark.keyUserId.equals.$userId.amp.keyAlbumNumber.equals.$albumNumber, emptyString, accesskeyAlbumView, array() ).
		button( actionDisplayAlbumDeletePage, buttonAlbumDelete, tooltipAlbumDelete, scriptName, emptyString, emptyString, $parameters ).
		button( actionDisplayAlbumUploadPage, buttonAlbumUpload, tooltipAlbumUpload, scriptName, emptyString, emptyString, $parameters );
}

////////////////////////////////////////////////////////////////////////////////

function displayAlbumUploadPage( &$pageContent, &$buttonBarUser, $user, &$album, $databaseConnection  )
{
	assert( 'isString( $pageContent )' );
	assert( 'isString( $buttonBarUser )' );
	assert( 'isValidUser( $user )' );
	assert( 'isEmpty( $album ) || $album->isValid()' );

	if ( isNotValidUser( $user ) ) return;
	if ( isNotEmpty( $album ) && $album->isNotValid() ) return;

	if ( isEmpty( $album ) )
	{
		$albumId = parameter( keyAlbumId, $databaseConnection );
		assert( 'isNonEmptyIntString( $albumId )' );

		if ( !isNonEmptyIntString( $albumId ) ) return;

		$album = Album::albumByAlbumId( $albumId, $databaseConnection );
		assert( 'isValidAlbum( $album )' );

		if ( isNotValidAlbum( $album ) ) return;
	}
	else
	{
		$albumId = $album->albumId();
		assert( '$albumId === parameter( keyAlbumId, $databaseConnection )' );
	}

	$userId = $album->userId();
	$albumNumber = $album->albumNumber();
	$albumDoResize = ( $album->albumDoResize() ? 'checked="checked" ' : emptyString );

	$pageContent .=
		'<div id="albumUploadPage">'.doubleNewline.
		'<form method="post" action="'.scriptName.doubleQuote.space.'enctype="multipart/form-data'.quoteBracket.doubleNewline.

		'<div class="checkboxes">'.newline.
		'<label for="'.fieldAlbumDoResize.quoteBracket.labelAlbumDoResize.labelTagEnd.newline.
		'<input class="input" id="'.fieldAlbumDoResize.'" type="checkbox" '.$albumDoResize.'name="'.fieldAlbumDoResize.'" tabindex="1" accesskey="'.accesskeyAlbumDoResize.quoteSlashBracket.newline.
		divTagEnd.doubleNewline.

		'<div class="fields">'.newline.
		'<label for="'.fieldAlbumPhotoBundle.quoteBracket.labelFile.labelTagEnd.newline.
		'<input class="input" type="file" id="'.fieldAlbumPhotoBundle.'" name="'.fieldAlbumPhotoBundle.'" tabindex="2" accesskey="'.accesskeyFile.quoteSlashBracket.newline.
		divTagEnd.doubleNewline.

		'<div id="buttons">'.newline.
		'<input type="hidden" name="'.keyAlbumId.'" value="'.$albumId.quoteSlashBracket.newline.
		'<input type="hidden" name="'.keyAction.'" value="'.actionAlbumUpload.quoteSlashBracket.newline.
		'<button class="button" type="submit" tabindex="2" accesskey="'.accesskeyAlbumUpload.'" value="'.buttonAlbumUpload.quoteBracket.buttonAlbumUpload.buttonTagEnd.newline.
	//	button( actionDisplayAlbumPage, buttonCancel, emptyString, scriptName.questionMark.keyUserId.equals.$userId.amp.keyAlbumNumber.equals.$album->albumNumber(), 3, emptyString, array() ).
		divTagEnd.doubleNewline.

		formTagEnd.doubleNewline.
		helpAlbumUpload.doubleNewline.
		divTagEnd.newline;

	$parameters = array( keyAlbumId => $albumId );
	$buttonBarUser .=
		button( actionDisplayAlbumPage, buttonAlbumView, tooltipAlbumView, scriptName.questionMark.keyUserId.equals.$userId.amp.keyAlbumNumber.equals.$albumNumber, emptyString, emptyString, array() ).
		button( actionDisplayAlbumDeletePage, buttonAlbumDelete, tooltipAlbumDelete, scriptName, emptyString, emptyString, $parameters );
}

////////////////////////////////////////////////////////////////////////////////

function displayAlbumPage( &$pageContent, &$pageStyle, &$buttonBarUser, $user, $album, $databaseConnection )
{
	assert( 'isString( $pageContent )' );
	assert( 'isString( $pageStyle )' );
	assert( 'isString( $buttonBarUser )' );
	assert( 'isEmpty( $user ) || $user->isValid()' );
	assert( 'isEmpty( $album ) || ( isNotEmpty( $user ) && $album->isValid() && $album->userId() == $user->userId() )' );

	if ( isEmpty( $album ) )
	{
		if ( parameterExists( keyUserId ) && parameterExists( keyAlbumNumber ) )
		{
			$targetUserId = userIdParameter( $databaseConnection );
			$albumNumber = parameter( keyAlbumNumber, $databaseConnection );

			if ( isPositiveIntString( $albumNumber ) )
			{
				$album = Album::albumByAlbumNumber( $targetUserId, $albumNumber, $databaseConnection );
			}
		}
	}

	if ( isEmpty( $album ) )
	{
		return;
	}

	if ( $album->albumDoNotPublish() && ( isEmpty( $user ) || ( $user->userId() !== $album->userId() ) ) )
	{
		return;
	}

	$albumId = $album->albumId();
	$albumTimestampCreation = $album->albumTimestampCreation();
	$albumTimestampModification = $album->albumTimestampModification();
	$albumTimestampPublication = $album->albumTimestampPublication();
	$albumTitle = formatAlbumTitle( $album->albumTitle() );
	$albumDescription = formatAlbumDescription( $album->albumDescription() );
	$albumTags = formatAlbumTags( $album->userId(), $album->albumTags() );

	$timestampDisplay = $user ? $user->userShowDetailedTimestamps() : cookie( cookieShowDetailedTimestamps, displayNone );

	$pageStyle .= '#timestamps { display:'.$timestampDisplay.'; }'.newline;

	$buttonLabel = ( $timestampDisplay !== displayNone ? buttonDetailsHide : buttonDetailsShow );

	$pageContent .=
		'<div id="albumSummaryPage">'.doubleNewline.
		'<div class="albumSummary">'.newline.
		'<h3 id="albumDate">'.formatAlbumDate( $albumTimestampCreation ).h3TagEnd.
		'<button id="showHideButton" class="button" type="button" value="'.$buttonLabel.'" onclick="toggleVisibility( \'timestamps\', \'showHideButton\', \''.$timestampDisplay.'\', \''.displayBlock.'\', \''.displayNone.'\', \''.buttonDetailsHide.'\', \''.buttonDetailsShow.'\', \''.fieldUserShowDetailedTimestamps.'\' )">'.$buttonLabel.buttonTagEnd.newline.
		'<table id="timestamps">'.newline.
		'<tr id="'.fieldAlbumTimestampCreation.'"><td class="label">'.textAlbumCreated.'</td><td class="timestamp">'.$albumTimestampCreation.'</td></tr>'.newline.
		'<tr id="'.fieldAlbumTimestampPublication.'"><td class="label">'.textAlbumPublished.'</td><td class="timestamp">'.$albumTimestampPublication.'</td></tr>'.newline.
		'<tr id="'.fieldAlbumTimestampModification.'"><td class="label">'.textAlbumModified.'</td><td class="timestamp">'.$albumTimestampModification.'</td></tr>'.newline.
		'</table>'.newline.
		'<h2 class="albumTitle">'.$albumTitle.h2TagEnd.newline.
		$albumDescription.
		divTagEnd.doubleNewline;

	if ( $albumTags != emptyString ) $pageContent .=
		'<h3 class="albumTags">'.textTags.colon.h3TagEnd.newline.
		'<ol class="albumTags">'.newline.
		$albumTags.
		olTagEnd.doubleNewline;

	$pageContent .=
		'<div class="albumThumbnails">'.newline;

	$doDisplayImageTitles = true;
	$doDisplayImageNumbers = true;
	$doDisplayImageDescriptions = true;

	$imageURL = scriptURL.questionMark.keyUserId.equals.$album->userId().amp.keyAlbumNumber.equals.$album->albumNumber().amp.keyImageNumber.equals;
	$versionURL = scriptURL.questionMark.keyAction.equals.actionDisplayVersion.amp.keyVersionId.equals;
	$albumImages = $album->albumImages( $databaseConnection );
	$albumThumbnails = $album->albumThumbnails( $databaseConnection );
	foreach ( $albumThumbnails as $albumThumbnail )
	{
		$image = $albumImages[$albumThumbnail->imageId()];
		$imageNumber = $image->imageNumber();
		$imageTitle = $image->imageTitle();
		$imageDescription = $image->imageDescription();
		$imageLink = $imageURL.$imageNumber;

		$imageNumber = sprintf( imageNumberFormat, $imageNumber );

		$albumThumbnailURL = $versionURL.$albumThumbnail->versionId();
		$albumThumbnailWidth = $albumThumbnail->versionWidth();
		$albumThumbnailHeight = $albumThumbnail->versionHeight();
		$albumThumbnailAlt = $imageNumber.space.$imageTitle;
		$albumThumbnailTitle = emptyString;

		if ( $doDisplayImageTitles )
		{
			if ( $doDisplayImageNumbers ) $albumThumbnailTitle .= $imageNumber.space;
			$albumThumbnailTitle .= $imageTitle;
			if ( $doDisplayImageDescriptions && ( $imageDescription !== emptyString ) ) $albumThumbnailTitle .= space.mdash.space.$imageDescription;
			$albumThumbnailTitle = '" title="'.$albumThumbnailTitle;
		}

		$pageContent .=
			aTag.$imageLink.quoteBracket.
			'<img src="'.$albumThumbnailURL.'" width="'.$albumThumbnailWidth.'" height="'.$albumThumbnailHeight.'" alt="'.$albumThumbnailAlt.$albumThumbnailTitle.quoteSlashBracket.
			aTagEnd.newline;
	}
	$pageContent .=
		divTagEnd.newline;

	$pageContent .=
		divTagEnd.newline;

	$parameters = array( keyAlbumId => $albumId );
	if ( $user && ( $user->userId() === $album->userId() ) ) $buttonBarUser .=
		button( actionDisplayAlbumEditPage,   buttonAlbumEdit,   tooltipAlbumEdit,   scriptName, emptyString, accesskeyAlbumEdit, $parameters ).
		button( actionDisplayAlbumDeletePage, buttonAlbumDelete, tooltipAlbumDelete, scriptName, emptyString, emptyString, $parameters ).
		button( actionDisplayAlbumUploadPage, buttonAlbumUpload, tooltipAlbumUpload, scriptName, emptyString, emptyString, $parameters );
}

////////////////////////////////////////////////////////////////////////////////

function displayImageEditPage( &$pageContent, &$pageStyle, &$pageHead, &$buttonBarUser, $user, $album, $image, $databaseConnection )
{
	assert( 'isString( $pageContent )' );
	assert( 'isString( $pageStyle )' );
	assert( 'isString( $buttonBarUser )' );
	assert( 'isEmpty( $user ) || $user->isValid()' );
	assert( 'isEmpty( $album ) || $album->isValid()' );
	assert( 'isEmpty( $image ) || $image->isValid()' );
	assert( 'parameterExists( keyAlbumNumber ) || $album->isValid()' );
	assert( 'parameterExists( keyImageNumber )' );

	if ( isEmpty( $album ) )
	{
		if ( parameterExists( keyUserId ) && parameterExists( keyAlbumNumber ) )
		{
			$targetUserId = userIdParameter( $databaseConnection );
			$albumNumber = parameter( keyAlbumNumber, $databaseConnection );

			if ( isPositiveIntString( $albumNumber ) )
			{
				$album = Album::albumByAlbumNumber( $targetUserId, $albumNumber, $databaseConnection );
			}
		}
	}

	if ( isEmpty( $album ) )
	{
		return;
	}

	if ( $album->albumDoNotPublish() && ( isEmpty( $user ) || ( $user->userId() !== $album->userId() ) ) )
	{
		return;
	}

	$pageHead .=
		'<script type="text/javascript" src="https://www.google.com/jsapi?key=ABQIAAAAZKdxPrvMqNHUbKKEPkYtOBSH76WEOdvCJjsinN-k7V7-zUaZ0hTzbU8eSVvaHEcDTSic-UygNOxzew" ></script>'.newline.
		'<script type="text/javascript">google.load("jquery","1");google.load("jqueryui","1");google.load("webfont","1");</script>'.newline.
		'<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>'.newline.
		'<script type="text/javascript" src="'.resourcesPath.'include.albumEditImagesPage.js"></script>'.newline.
		'<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet"/>'.newline.
		emptyString;

	define( 'imageThumbnailsControlPair', tab.tab.tab.'<div class="imageThumbnailsControlPair">' );
	define( 'sphericalUnits', deg );
	define( 'latitudeUnits', sphericalUnits );
	define( 'longitudeUnits', sphericalUnits );
	define( 'altitudeUnits', 'ft' );
	define( 'headingUnits', sphericalUnits );

	$pageContent .=
		'<div id="albumEditImagesPage">'.doubleNewline.

		tab.'<div id="imageDataGroupWrapper">'.newline.
		tab.tab.'<div id="imageDataGroup">'.newline.
		tab.tab.tab.'<div id="imageTitleField">'.newline.
		tab.tab.tab.tab.'<label id="imageTitleLabel" for="imageTitleControl">'.labelImageTitle.labelTagEnd.newline.
		tab.tab.tab.tab.'<input id="imageTitleControl" type="text" value="" accesskey="'.accesskeyImageTitle.quoteSlashBracket.newline.
		tab.tab.tab.divTagEnd.newline.
		tab.tab.tab.'<div id="imageDescriptionField">'.newline.
		tab.tab.tab.tab.'<label id="imageDescriptionLabel" for="imageDescriptionControl">'.labelImageDescription.labelTagEnd.newline.
		tab.tab.tab.tab.'<textarea id="imageDescriptionControl" accesskey="'.accesskeyImageDescription.quoteBracket.textareaTagEnd.newline.
		tab.tab.tab.divTagEnd.newline.
		tab.tab.tab.'<div id="imageRemainderGroup">'.newline.
		tab.tab.tab.tab.'<div id="imageTagsField">'.newline.
		tab.tab.tab.tab.tab.'<label id="imageTagsLabel" for="imageTagsControl">'.labelImageTags.'<span id="'.fieldImageTags.'Hint'.quoteBracket.hintImageTags.spanTagEnd.labelTagEnd.newline.
		tab.tab.tab.tab.tab.'<textarea id="imageTagsControl" accesskey="'.accesskeyImageTags.quoteBracket.textareaTagEnd.newline.
		tab.tab.tab.tab.divTagEnd.newline.
		tab.tab.tab.tab.'<div id="imageRatingsGroup">'.newline.
		tab.tab.tab.tab.tab.'<div id="imageRatingField">'.newline.
		tab.tab.tab.tab.tab.tab.'<label id="imageRatingLabel">'.labelImageRating.labelTagEnd.newline.
		tab.tab.tab.tab.tab.tab.'<div id="imageRatingControl1" class="imageRating" accesskey="'.accesskeyImageRating1.'" tabindex="0" title="'.tooltipImageRating1.'">'.star.divTagEnd.newline.
		tab.tab.tab.tab.tab.tab.'<div id="imageRatingControl2" class="imageRating" accesskey="'.accesskeyImageRating2.'" tabindex="0" title="'.tooltipImageRating2.'">'.star.divTagEnd.newline.
		tab.tab.tab.tab.tab.tab.'<div id="imageRatingControl3" class="imageRating" accesskey="'.accesskeyImageRating3.'" tabindex="0" title="'.tooltipImageRating3.'">'.star.divTagEnd.newline.
		tab.tab.tab.tab.tab.tab.'<div id="imageRatingControl4" class="imageRating" accesskey="'.accesskeyImageRating4.'" tabindex="0" title="'.tooltipImageRating4.'">'.star.divTagEnd.newline.
		tab.tab.tab.tab.tab.tab.'<div id="imageRatingControl5" class="imageRating" accesskey="'.accesskeyImageRating5.'" tabindex="0" title="'.tooltipImageRating5.'">'.star.divTagEnd.newline.
		tab.tab.tab.tab.tab.divTagEnd.newline.
		tab.tab.tab.tab.tab.'<div id="albumThumbnailField">'.newline.
		tab.tab.tab.tab.tab.tab.'<input id="albumThumbnailControl" type="checkbox" checked="checked" value="false" accesskey="'.accesskeyImageAlbumThumbnail.quoteSlashBracket.newline.
		tab.tab.tab.tab.tab.tab.'<label id="albumThumbnailLabel" for="albumThumbnailControl">'.labelImageAlbumThumbnail.labelTagEnd.newline.
		tab.tab.tab.tab.tab.divTagEnd.newline.
		tab.tab.tab.tab.divTagEnd.newline.
		tab.tab.tab.tab.'<div id="imagePhotographerField">'.newline.
		tab.tab.tab.tab.tab.'<label id="imagePhotographerLabel" for="imagePhotographerControl">'.labelImagePhotographer.labelTagEnd.newline.
		tab.tab.tab.tab.tab.'<input id="imagePhotographerControl" type="text" value="" accesskey="'.accesskeyImagePhotographer.quoteSlashBracket.newline.
		tab.tab.tab.tab.divTagEnd.newline.
		tab.tab.tab.tab.'<div id="imageTimestampCreationField">'.newline.
		tab.tab.tab.tab.tab.'<label id="imageTimestampCreationLabel" for="imageTimestampCreationControl">'.labelImageTimestampCreation.labelTagEnd.newline.
		tab.tab.tab.tab.tab.'<input id="imageTimestampCreationControl" type="text" value="" accesskey="'.accesskeyImageTimestampCreation.quoteSlashBracket.newline.
		tab.tab.tab.tab.divTagEnd.newline.
		tab.tab.tab.tab.'<div id="imageAddressGroup">'.newline.
		tab.tab.tab.tab.tab.'<div id="imageAddressField">'.newline.
		tab.tab.tab.tab.tab.tab.'<label id="imageAddressLabel" for="imageAddressControl">'.labelImageAddress.labelTagEnd.newline.
		tab.tab.tab.tab.tab.tab.'<input id="imageAddressControl" type="text" value="" accesskey="'.accesskeyImageAddress.quoteSlashBracket.newline.
		tab.tab.tab.tab.tab.tab.'<div id="imageAddressButtonGroup">'.newline.
		tab.tab.tab.tab.tab.tab.tab.'<button id="imageAddressButtonStandardize" type="button" title="'.tooltipStandardizeLocation.'">'.buttonStandardize.buttonTagEnd.newline.
		tab.tab.tab.tab.tab.tab.tab.'<button id="imageAddressButtonApplyToAll" type="button" title="'.tooltipApplyLocationToAll.'">'.buttonApplyToAll.buttonTagEnd.newline.
		tab.tab.tab.tab.tab.tab.divTagEnd.newline.
		tab.tab.tab.tab.tab.divTagEnd.newline.
		tab.tab.tab.tab.divTagEnd.newline.
		tab.tab.tab.tab.'<div id="imageLocationGroup">'.newline.
		tab.tab.tab.tab.tab.'<div id="imageLatitudeField" class="imageCoordinate">'.newline.
		tab.tab.tab.tab.tab.tab.'<label id="imageLatitudeLabel" for="imageLatitudeControl">'.labelImageLatitude.'<span id="latitudeUnits" class="units">'.latitudeUnits.spanTagEnd.labelTagEnd.newline.
		tab.tab.tab.tab.tab.tab.'<input id="imageLatitudeControl" type="text" value="" accesskey="'.accesskeyImageLatitude.quoteSlashBracket.newline.
		tab.tab.tab.tab.tab.divTagEnd.newline.
		tab.tab.tab.tab.tab.'<div id="imageLongitudeField" class="imageCoordinate">'.newline.
		tab.tab.tab.tab.tab.tab.'<label id="imageLongitudeLabel" for="imageLongitudeControl">'.labelImageLongitude.'<span id="longitudeUnits" class="units">'.longitudeUnits.spanTagEnd.labelTagEnd.newline.
		tab.tab.tab.tab.tab.tab.'<input id="imageLongitudeControl" type="text" value="" accesskey="'.accesskeyImageLongitude.quoteSlashBracket.newline.
		tab.tab.tab.tab.tab.divTagEnd.newline.
		tab.tab.tab.tab.tab.'<div id="imageAltitudeField" class="imageCoordinate">'.newline.
		tab.tab.tab.tab.tab.tab.'<label id="imageAltitudeLabel" for="imageAltitudeControl">'.labelImageAltitude.'<span id="altitudeUnits" class="units">'.altitudeUnits.spanTagEnd.labelTagEnd.newline.
		tab.tab.tab.tab.tab.tab.'<input id="imageAltitudeControl" type="text" value="" accesskey="'.accesskeyImageAltitude.quoteSlashBracket.newline.
		tab.tab.tab.tab.tab.divTagEnd.newline.
		tab.tab.tab.tab.tab.'<div id="imageHeadingField" class="imageCoordinate">'.newline.
		tab.tab.tab.tab.tab.tab.'<label id="imageHeadingLabel" for="imageHeadingControl">'.labelImageHeading.'<span id="headingUnits" class="units">'.headingUnits.spanTagEnd.labelTagEnd.newline.
		tab.tab.tab.tab.tab.tab.'<input id="imageHeadingControl" type="text" value="" accesskey="'.accesskeyImageHeading.quoteSlashBracket.newline.
		tab.tab.tab.tab.tab.divTagEnd.newline.
		tab.tab.tab.tab.divTagEnd.newline.
		tab.tab.tab.divTagEnd.newline.
		tab.tab.divTagEnd.newline.
		tab.divTagEnd.doubleNewline.

		tab.'<div id="imageThumbnailsGroup">'.newline.
		tab.tab.'<label id="imageThumbnailsLabel">'.labelImageList.labelTagEnd.newline.
		tab.tab.'<div id="imageThumbnailsList" accesskey="'.accesskeyImageList.quoteBracket.newline.
		tab.tab.divTagEnd.newline.
		tab.tab.'<div id="imageThumbnailsControlsGroup">'.newline.
					imageThumbnailsControlPair.newline.
		tab.tab.tab.tab.'<button id="imageThumbnailsControlMoveUp" class="imageThumbnailButton" type="button" value="'.buttonImageMoveUp.'" title="'.tooltipImageMoveUp.quoteBracket.buttonImageMoveUp.buttonTagEnd.newline.
		tab.tab.tab.tab.'<button id="imageThumbnailsControlMoveDown" class="imageThumbnailButton" type="button" value="'.buttonImageMoveDown.'" title="'.tooltipImageMoveDown.quoteBracket.buttonImageMoveDown.buttonTagEnd.newline.
		tab.tab.tab.divTagEnd.newline.
					imageThumbnailsControlPair.newline.
		tab.tab.tab.tab.'<button id="imageThumbnailsControlRotateLeft" class="imageThumbnailButton" type="button" value="'.buttonImageRotateLeft.'" title="'.tooltipImageRotateLeft.quoteBracket.buttonImageRotateLeft.buttonTagEnd.newline.
		tab.tab.tab.tab.'<button id="imageThumbnailsControlRotateRight" class="imageThumbnailButton" type="button" value="'.buttonImageRotateRight.'" title="'.tooltipImageRotateRight.quoteBracket.buttonImageRotateRight.buttonTagEnd.newline.
		tab.tab.tab.divTagEnd.newline.
					imageThumbnailsControlPair.newline.
		tab.tab.tab.tab.'<button id="imageThumbnailsControlAdd" class="imageThumbnailButton" type="button" value="'.buttonImageAdd.'" title="'.tooltipImageAdd.quoteBracket.buttonImageAdd.buttonTagEnd.newline.
		tab.tab.tab.tab.'<button id="imageThumbnailsControlDelete" class="imageThumbnailButton" type="button" value="'.buttonImageDelete.'" title="'.tooltipImageDelete.quoteBracket.buttonImageDelete.buttonTagEnd.newline.
		tab.tab.tab.divTagEnd.newline.
		tab.tab.divTagEnd.newline.
		tab.divTagEnd.doubleNewline.

		tab.'<div id="imageExifGroup">'.newline.
		tab.tab.'<div id="imageExifSummaryGroup">'.newline.
		tab.tab.tab.'<label id="imageExifSummaryLabel">'.'<a href="">'.labelExifSummary.space.'<span id="imageExifSummaryToggle" class="imageExifToggle">'.triangleDown.spanTagEnd.aTagEnd.labelTagEnd.newline.
		tab.tab.tab.'<div id="imageExifSummaryControl">'.divTagEnd.newline.
		tab.tab.divTagEnd.newline.
		tab.tab.'<div id="imageExifCompleteGroup">'.newline.
		tab.tab.tab.'<label id="imageExifCompleteLabel">'.'<a href="">'.labelExifComplete.space.'<span id="imageExifCompleteToggle" class="imageExifToggle">'.triangleRight.spanTagEnd.aTagEnd.labelTagEnd.newline.
		tab.tab.tab.'<div id="imageExifCompleteControl">'.divTagEnd.newline.
		tab.tab.divTagEnd.newline.
		tab.tab.'<div id="imageMapControl">'.newline.
		tab.tab.divTagEnd.newline.
		tab.divTagEnd.doubleNewline.

		tab.'<div id="parameters">'.newline.
		tab.tab.'<div id="server">'.newline.
		tab.tab.tab.scriptDirectory.newline.
		tab.tab.divTagEnd.newline.
		tab.tab.'<div id="albumId">'.newline.
		tab.tab.tab.$album->albumId().newline.
		tab.tab.divTagEnd.newline.
		tab.divTagEnd.doubleNewline.

/*		tab.'<div id="dialogConfirmationAddress" title="44 Clarence Photos">'.newline.
		tab.tab.'These coordinates do not match any known address. Erase current address?'.newline.
		tab.divTagEnd.doubleNewline.*/

		divTagEnd;

	$parameters = array( keyAlbumId => $album->albumId() );
	$buttonBarUser .=
		button( actionDisplayAlbumEditPage,   buttonAlbumEdit,   tooltipAlbumEdit,   scriptName, emptyString, accesskeyAlbumEdit, $parameters ).
		button( actionDisplayAlbumDeletePage, buttonAlbumDelete, tooltipAlbumDelete, scriptName, emptyString, emptyString, $parameters ).
		button( actionDisplayAlbumUploadPage, buttonAlbumUpload, tooltipAlbumUpload, scriptName, emptyString, emptyString, $parameters ).
		button( actionDisplayImageEditPage,   buttonImageUpdate, tooltipImageUpdate, scriptName, emptyString, emptyString, parameters() );
}

////////////////////////////////////////////////////////////////////////////////

function displayVersion( &$pageContent, $user, $databaseConnection )
{
	assert( 'isString( $pageContent )' );
	assert( 'isEmpty( $user ) || $user->isValid()' );

	if ( parameterExists( keyVersionId ) )
	{
		$versionId = parameter( keyVersionId, $databaseConnection );

		$version = Version::versionByVersionId( $versionId, $databaseConnection );
		assert( 'isValidVersion( $version )' );
	}
	else
	{
		$targetUserId = parameter( keyUserId, $databaseConnection );
		$albumNumber = parameter( keyAlbumNumber, $databaseConnection );
		$imageNumber = parameter( keyImageNumber, $databaseConnection );
		$versionNumber = parameter( keyVersionNumber, $databaseConnection );

		$version = Version::versionByVersionNumber( $targetUserId, $albumNumber, $imageNumber, $versionNumber, $databaseConnection );
		assert( 'isValidVersion( $version )' );
	}

	if ( isNotValidVersion( $version ) ) return;

	$degrees = parameter( keyVersionRotationAngle, $databaseConnection, '0' );

	$versionFilePath = $version->versionFilePath( $databaseConnection );
	$versionFileName = basename( $versionFilePath );	//	ASCII-only?

	header( 'Content-Type: image/jpeg' );
//	header( 'Content-Disposition: inline; filename="'.$versionFileName.doubleQuote );

	if ( $degrees !== 0 )
	{
		$originalImage = imagecreatefromjpeg( $versionFilePath );
		assert( 'isNonEmptyResource( $originalImage )' );

		$rotatedImage = imagerotate( $originalImage, $degrees, 0 );
		assert( 'isNonEmptyResource( $rotatedImage )' );

		imagejpeg( $rotatedImage );
	}
	else
	{
		readfile( $versionFilePath );
	}
}

////////////////////////////////////////////////////////////////////////////////

function getAlbumXml( $user, $databaseConnection )
{
	$albumId = parameter( keyAlbumId, $databaseConnection );

	if ( !isNonEmptyIntString( $albumId ) ) exit;

	$album = Album::albumByAlbumId( $albumId, $databaseConnection );
	assert( isValidAlbum( $album ) );

	if ( isNotValidAlbum( $album ) ) exit;

	if ( $user->userId() !== $album->userId() )
	{
		exit;
	}

	header( 'Content-type: text/xml' );

	$versionURL = scriptURL.questionMark.keyAction.equals.actionDisplayVersion.amp.keyVersionId.equals;
	$albumImages = $album->albumImages( $databaseConnection );
	$albumThumbnails = $album->albumThumbnails( $databaseConnection );
	$xml =
		'<?xml version="1.0" encoding="UTF-8"?>'.newline.
		'<album>'.newline;
	foreach ( $albumThumbnails as $albumThumbnail )
	{
		$image = $albumImages[$albumThumbnail->imageId()];

		$imageElement = new SimpleXMLElement( '<image>'.newline.htmlspecialchars_decode( $image->toXmlString() ).'</image>'.newline );
		$imageExifElement = ( isset( $imageElement->imageExif ) ? $imageElement->imageExif : $imageElement->addChild( 'imageExif' ) );
		$imageExifSummaryElement = $imageElement->addChild( 'imageExifSummary' );

		if ( $imageExifElement->count() )
		{
			unset( $imageExifElement->FileName );	//	Don't include the filename tag for file system security reasons.

			if ( isset( $imageExifElement->Make ) )
			{
				$element = $imageExifSummaryElement->addChild( $imageExifElement->Make->getName() );
				$element->addChild( exifLabel, exifLabelMake );
				$element->addChild( exifValue, $imageExifElement->Make );
			}

			if ( isset( $imageExifElement->Model ) )
			{
				$element = $imageExifSummaryElement->addChild( $imageExifElement->Model->getName() );
				$element->addChild( exifLabel, exifLabelModel );
				$element->addChild( exifValue, $imageExifElement->Model );
			}

			if ( isset( $imageExifElement->FNumber ) )
			{
				$element = $imageExifSummaryElement->addChild( $imageExifElement->FNumber->getName() );
				$element->addChild( exifLabel, exifLabelFNumber );
				$element->addChild( exifValue, 'f/'.(double)$imageExifElement->FNumber );
			}

			if ( isset( $imageExifElement->ISOSpeedRatings ) )
			{
				$element = $imageExifSummaryElement->addChild( $imageExifElement->ISOSpeedRatings->getName() );
				$element->addChild( exifLabel, exifLabelISOSpeedRatings );
				$element->addChild( exifValue, $imageExifElement->ISOSpeedRatings );
			}

			if ( isset( $imageExifElement->ExposureTime ) )
			{
				$element = $imageExifSummaryElement->addChild( $imageExifElement->ExposureTime->getName() );
				$element->addChild( exifLabel, exifLabelExposureTime );
				$element->addChild( exifValue, $imageExifElement->ExposureTime );
			}

			if ( isset( $imageExifElement->ExposureBiasValue ) )
			{
				$exposureBias = (double)$imageExifElement->ExposureBiasValue;
				if ( $exposureBias != 0 )
				{
					$element = $imageExifSummaryElement->addChild( $imageExifElement->ExposureBiasValue->getName() );
					$element->addChild( exifLabel, exifLabelExposureBiasValue );
					$element->addChild( exifValue, (double)$imageExifElement->ExposureBiasValue.' EV' );
				}
			}

			if ( isset( $imageExifElement->ExposureProgram ) )
			{
				$exposureProgram = (int)$imageExifElement->ExposureProgram;
				switch ( $exposureProgram )
				{
					case 1: $exposureProgram = exifValueExposureProgramManual; break;
					case 2: $exposureProgram = exifValueExposureProgramProgram; break;
					case 3: $exposureProgram = exifValueExposureProgramAperturePriority; break;
					case 4: $exposureProgram = exifValueExposureProgramShutterPriority; break;
					case 5: $exposureProgram = exifValueExposureProgramCreative; break;
					case 6: $exposureProgram = exifValueExposureProgramAction; break;
					case 7: $exposureProgram = exifValueExposureProgramPortrait; break;
					case 8: $exposureProgram = exifValueExposureProgramLandscape; break;
				}
				if ( isNotInt( $exposureProgram ) )
				{
					$element = $imageExifSummaryElement->addChild( $imageExifElement->ExposureProgram->getName() );
					$element->addChild( exifLabel, exifLabelExposureProgram );
					$element->addChild( exifValue, $exposureProgram );
				}
			}

			if ( isset( $imageExifElement->LightSource ) )
			{
				$lightSource = (int)$imageExifElement->LightSource;
				switch ( $lightSource )
				{
					case 1: $lightSource = exifValueLightSourceDaylight; break;
					case 2: $lightSource = exifValueLightSourceFluorescent; break;
					case 3: $lightSource = exifValueLightSourceTungsten; break;
					case 4: $lightSource = exifValueLightSourceFlash; break;
					case 9: $lightSource = exifValueLightSourceFineWeather; break;
					case 10: $lightSource = exifValueLightSourceCloudy; break;
					case 11: $lightSource = exifValueLightSourceShade; break;
					case 12: $lightSource = exifValueLightSourceFluorescentDaylight; break;
					case 13: $lightSource = exifValueLightSourceFluorescentWarm; break;
					case 14: $lightSource = exifValueLightSourceFluorescentCool; break;
					case 15: $lightSource = exifValueLightSourceFluorescentWhite; break;
					case 17: $lightSource = exifValueLightSourceStandardLightA; break;
					case 18: $lightSource = exifValueLightSourceStandardLightB; break;
					case 19: $lightSource = exifValueLightSourceStandardLightC; break;
					case 20: $lightSource = exifValueLightSourceD55; break;
					case 21: $lightSource = exifValueLightSourceD65; break;
					case 22: $lightSource = exifValueLightSourceD75; break;
					case 23: $lightSource = exifValueLightSourceD50; break;
					case 24: $lightSource = exifValueLightSourceISOStudioTungsten; break;
					case 255: $lightSource = exifValueLightSourceOther; break;
				}
				if ( isNotInt( $lightSource ) )
				{
					$element = $imageExifSummaryElement->addChild( $imageExifElement->LightSource->getName() );
					$element->addChild( exifLabel, exifLabelLightSource );
					$element->addChild( exifValue, $lightSource );
				}
			}

			if ( isset( $imageExifElement->MeteringMode ) )
			{
				$meteringMode = (int)$imageExifElement->MeteringMode;
				switch ( $meteringMode )
				{
					case 1: $meteringMode = exifValueMeteringModeAverage; break;
					case 2: $meteringMode = exifValueMeteringModeCenterWeightedAverage; break;
					case 3: $meteringMode = exifValueMeteringModeSpot; break;
					case 4: $meteringMode = exifValueMeteringModeMultiSpot; break;
					case 5: $meteringMode = exifValueMeteringModeMultiSegment; break;
					case 6: $meteringMode = exifValueMeteringModePartial; break;
					case 255: $meteringMode = exifValueMeteringModeOther; break;
				}
				if ( isNotInt( $meteringMode ) )
				{
					$element = $imageExifSummaryElement->addChild( $imageExifElement->MeteringMode->getName() );
					$element->addChild( exifLabel, exifLabelMeteringMode );
					$element->addChild( exifValue, $meteringMode );
				}
			}

			if ( isset( $imageExifElement->Flash ) )
			{
				$flash = (int)$imageExifElement->Flash;
				switch ( $flash )
				{
					case 0x0: $flash = exifValueFlashNoFlash; break;
					case 0x1: $flash = exifValueFlashFired; break;
					case 0x5: $flash = exifValueFlashFiredReturnNotDetected; break;
					case 0x7: $flash = exifValueFlashFiredReturnDetected; break;
					case 0x8: $flash = exifValueFlashOnDidNotFire; break;
					case 0x9: $flash = exifValueFlashOnFired; break;
					case 0xd: $flash = exifValueFlashOnReturnNotDetected; break;
					case 0xf: $flash = exifValueFlashReturnDetected; break;
					case 0x10: $flash = exifValueFlashOff; break;
					case 0x14: $flash = exifValueFlashOff; break;
					case 0x18: $flash = exifValueFlashAutoDidNotFire; break;
					case 0x19: $flash = exifValueFlashAutoFired; break;
					case 0x1d: $flash = exifValueFlashAutoFiredReturnNotDetected; break;
					case 0x1f: $flash = exifValueFlashAutoFiredReturnDetected; break;
					case 0x20: $flash = exifValueFlashNoFlashFunction; break;
					case 0x30: $flash = exifValueFlashNoFlashFunction; break;
					case 0x41: $flash = exifValueFlashFiredRedEyeReduction; break;
					case 0x45: $flash = exifValueFlashFiredRedEyeReductionReturnNotDetected; break;
					case 0x47: $flash = exifValueFlashFiredRedEyeReductionReturnDetected; break;
					case 0x49: $flash = exifValueFlashOnRedEyeReduction; break;
					case 0x4d: $flash = exifValueFlashOnRedEyeReductionReturnNotDetected; break;
					case 0x4f: $flash = exifValueFlashOnRedEyeReductionReturnDetected; break;
					case 0x50: $flash = exifValueFlashOff; break;
					case 0x58: $flash = exifValueFlashAutoDidNotFire; break;
					case 0x59: $flash = exifValueFlashAutoFiredRedEyeReduction; break;
					case 0x5d: $flash = exifValueFlashAutoFiredRedEyeReductionReturnNotDetected; break;
					case 0x5f: $flash = exifValueFlashAutoFiredRedEyeReductionReturnDetected; break;
				}
				$element = $imageExifSummaryElement->addChild( $imageExifElement->Flash->getName() );
				$element->addChild( exifLabel, exifLabelFlash );
				$element->addChild( exifValue, $flash );
			}

			if ( isset( $imageExifElement->FocalLength ) )
			{
				$element = $imageExifSummaryElement->addChild( $imageExifElement->FocalLength->getName() );
				$element->addChild( exifLabel, exifLabelFocalLength );
				$element->addChild( exifValue, (double)$imageExifElement->FocalLength.' mm' );
			}

			if ( isset( $imageExifElement->SubjectDistance ) && isNotEmpty( $imageExifElement->SubjectDistance ) )
			{
				$element = $imageExifSummaryElement->addChild( $imageExifElement->SubjectDistance->getName() );
				$element->addChild( exifLabel, exifLabelSubjectDistance );
				$element->addChild( exifValue, (double)$imageExifElement->SubjectDistance.' m' );
			}

			if ( isset( $imageExifElement->DateTimeOriginal ) )
			{
				$element = $imageExifSummaryElement->addChild( $imageExifElement->DateTimeOriginal->getName() );
				$element->addChild( exifLabel, exifLabelDateTimeOriginal );
				$element->addChild( exifValue, $imageExifElement->DateTimeOriginal );
			}

			if ( isset( $imageExifElement->DateTime ) )
			{
				$element = $imageExifSummaryElement->addChild( $imageExifElement->DateTime->getName() );
				$element->addChild( exifLabel, exifLabelDateTime );
				$element->addChild( exifValue, $imageExifElement->DateTime );
			}

			if ( isset( $imageExifElement->Copyright ) )
			{
				$element = $imageExifSummaryElement->addChild( $imageExifElement->Copyright->getName() );
				$element->addChild( exifLabel, exifLabelCopyright );
				$element->addChild( exifValue, $imageExifElement->Copyright );
			}
		}
		else
		{
			$imageExifElement->addChild( messageExifUnavailable );
		}

		if ( $imageExifSummaryElement->count() === 0 )
		{
			$element = $imageExifSummaryElement->addChild( messageExifUnavailable );
			$element->addChild( exifLabel, messageExifUnavailable );
			$element->addChild( exifValue );
		}

		$imageVersionThumbnailId = $albumThumbnail->versionId();
		$imageVersionThumbnailWidth = $albumThumbnail->versionWidth();
		$imageVersionThumbnailHeight = $albumThumbnail->versionHeight();

		$imageElement->addChild( 'imageThumbnailVersionId', $imageVersionThumbnailId );
		$imageElement->addChild( 'imageThumbnailVersionWidth', $imageVersionThumbnailWidth );
		$imageElement->addChild( 'imageThumbnailVersionHeight', $imageVersionThumbnailHeight );

		$xml .= dom_import_simplexml( $imageElement )->ownerDocument->saveHTML();
	}
	$xml .=
		'</album>';

	echo $xml; exit;
}

////////////////////////////////////////////////////////////////////////////////

function setAlbumXml( $user, $databaseConnection )
{
	//	Extract the album id parameter:
	$albumId = parameter( keyAlbumId, $databaseConnection );
//	print_r( $_POST ); exit;

	//	If we don't have a valid album id, bug out:
	if ( !isNonEmptyIntString( $albumId ) ) exit;

	//	Fetch the album associated with this id:
	$album = Album::albumByAlbumId( $albumId, $databaseConnection );
	assert( isValidAlbum( $album ) );

	//	If no such album exists, bug out:
	if ( isNotValidAlbum( $album ) ) exit;

	//	If the current user isn't the owner of the album, bug out:
	if ( $user->userId() !== $album->userId() )
	{
		exit;
	}

	//	Extract the XML string:
	$albumXml = $_POST['xml'];
	if ( isEmptyString( $albumXml ) ) exit;

	//	Convert the data string into an XML object:
	$albumXml = simplexml_load_string( $albumXml, 'SimpleXMLElement', LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_COMPACT );
	if ( $albumXml === false )
	{
		echo saxErrorCorrupt.'1'; exit;
	}

	//	Extract the id of the image to be used as the album's thumbnail;
	//	if one has been selected:
	$albumThumbnailImageId = sanitizedString( (string)$albumXml->albumThumbnailImageId, $databaseConnection );
	if ( $albumThumbnailImageId !== 'undefined' )
	{
		if ( isNotPositiveIntString( $albumThumbnailImageId ) )
		{
			echo saxErrorCorrupt.'2'; exit;
		}
	}
	else
	{
		$albumThumbnailImageId = emptyString;
	}

	//	Process each of the images and store them in an array:
	$imageNumber = 1;
	foreach ( $albumXml->image as $imageXml )
	{
		//	Extract the image id:
		$imageId = sanitizedString( (string)$imageXml->imageId, $databaseConnection );
		assert( 'isPositiveIntString( $imageId )' );
		if ( isNotPositiveIntString( $imageId ) ) exit;

		//	Fetch the image associated with this image id:
		$image = Image::imageByImageId( $imageId, $databaseConnection );
		assert( 'isValidImage( $image )' );
		if ( isNotValidImage( $image ) ) exit;

		//	Make sure the album id matches:
		$imageAlbumId = $image->albumId();
		assert( '$imageAlbumId === $albumId' );
		if ( $imageAlbumId !== $albumId ) exit;

		//	Extract the rest of the passed information from the XML object:
		$imageTitle = sanitizedString( (string)$imageXml->imageTitle, $databaseConnection );
		$imageDescription = sanitizedString( (string)$imageXml->imageDescription, $databaseConnection );
		$imageTags = sanitizedString( (string)$imageXml->imageTags, $databaseConnection );
		$imagePhotographer = sanitizedString( (string)$imageXml->imagePhotographer, $databaseConnection );
		$imageTimestamp = sanitizedString( (string)$imageXml->imageTimestamp, $databaseConnection );
		$imageAddress = sanitizedString( (string)$imageXml->imageAddress, $databaseConnection );
		$imageLatitude = sanitizedString( (string)$imageXml->imageLatitude, $databaseConnection );
		$imageLongitude = sanitizedString( (string)$imageXml->imageLongitude, $databaseConnection );
		$imageAltitude = sanitizedString( (string)$imageXml->imageAltitude, $databaseConnection );
		$imageHeading = sanitizedString( (string)$imageXml->imageHeading, $databaseConnection );

		//	Do some sanity checking on the extracted data:
		assert( 'isEmptyString( $imageTimestamp ) || ( timestampString( strtotime( $imageTimestamp ) ) === $imageTimestamp )' );
		if ( isNotEmptyString( $imageTimestamp ) && ( timestampString( strtotime( $imageTimestamp ) ) !== $imageTimestamp ) ) exit;

		assert( 'isEmptyString( $imageLatitude ) || isNumericString( $imageLatitude )' );
		if ( isNotEmptyString( $imageLatitude ) && isNotNumericString( $imageLatitude ) ) exit;

		assert( 'isEmptyString( $imageLongitude ) || isNumericString( $imageLongitude )' );
		if ( isNotEmptyString( $imageLongitude ) && isNotNumericString( $imageLongitude ) ) exit;

		assert( 'isEmptyString( $imageAltitude ) || isNumericString( $imageAltitude )' );
		if ( isNotEmptyString( $imageAltitude ) && isNotNumericString( $imageAltitude ) ) exit;

		assert( 'isEmptyString( $imageHeading ) || isNumericString( $imageHeading )' );
		if ( isNotEmptyString( $imageHeading ) && isNotNumericString( $imageHeading ) ) exit;

		//	Increment the image number:
		$imageNumber++;
	}

	//	Flag success and return:
	echo 'success'; exit;
}

////////////////////////////////////////////////////////////////////////////////

function getTimestampXml( $databaseConnection )
{
	$string = parameter( keyTimestamp, $databaseConnection );

	if ( isEmptyString( $string ) ) exit;

	$timestamp = strtotime( $string );

	echo ( isPositiveInt( $timestamp ) ? timestampString( $timestamp ) : emptyString );

	exit;
}

////////////////////////////////////////////////////////////////////////////////

function getLatitudeOrLongitude( $databaseConnection )
{
	//	Extract the value:
	$string = parameter( keyLatitudeOrLongitude, $databaseConnection );

	//	First, strip all the whitespace from the string; I'm assuming the
	//	only whitespace that will normally make it into a string is spaces;
	//	anything else would likely be considered malicious. Note that we're
	//	taking the opportunity to remove any seconds designations as well:
	$string = str_replace( array( space, '"', '″' ), emptyString, $string );

	//	If it's empty, bug out:
	if ( isEmptyString( $string ) ) exit;

	//	Extract the direction multiplier (NSEW), which will be either the
	//	last character in the string (most commonly) or the first character
	//	in the string. It might also be written as a '+' or '-' sign. If no
	//	direction is found, assume it to be positive. I've tried to order
	//	cases from most common to least common:
	$lastCharacter = lastCharacter( $string );
	if ( ( $lastCharacter === 'N' ) || ( $lastCharacter === 'n' ) || ( $lastCharacter === 'E' ) || ( $lastCharacter === 'e' ) )
	{
		$multiplier = +1.0;
		$string = substr( $string, 0, strlen( $string ) - 1 );
	}
	else if ( ( $lastCharacter === 'S' ) || ( $lastCharacter === 's' ) || ( $lastCharacter === 'W' ) || ( $lastCharacter === 'w' ) )
	{
		$multiplier = -1.0;
		$string = substr( $string, 0, strlen( $string ) - 1 );
	}
	else
	{
		$firstCharacter = $string[0];
		if ( isNumericString( $firstCharacter ) )
		{
			$multiplier = +1.0;
		}
		else if ( $firstCharacter === minus )
		{
			$multiplier = -1.0;
		}
		else if ( ( $firstCharacter === 'N' ) || ( $firstCharacter === 'n' ) || ( $firstCharacter === 'E' ) || ( $firstCharacter === 'e' ) )
		{
			$multiplier = +1.0;
			$string = substr( $string, 1 );
		}
		else if ( ( $firstCharacter === 'S' ) || ( $firstCharacter === 's' ) || ( $firstCharacter === 'W' ) || ( $firstCharacter === 'w' ) )
		{
			$multiplier = -1.0;
			$string = substr( $string, 1 );
		}
		else if ( $firstCharacter === plus )
		{
			$multiplier = +1.0;
		}
		else
		{
			exit;
		}
	}
	assert( '( $multiplier === +1.0 ) || ( $multiplier === -1.0 )' );

	//	At this point we have the multiplier and the string, stripped of any NSEW desgination.
	//	Replace all degree, minute and second designations with colons:
	$string = str_replace( array( '°', '"', "'", '′', 'deg', 'DEG', 'Deg', 'd', 'D' ), colon, $string );

	//	Now we have the string in the form: "xx:xx:xx.xxx", so snap it at colons:
	$array = explode( colon, $string );

	//	Convert each array element to its proper numeric representations;
	//	note that case fallthrough is important for this to work:
	$minutes = 0.0;
	$seconds = 0.0;
	switch ( count( $array ) )
	{
		case 3:
		{
			$seconds = $array[2];
			if ( isNonEmptyString( $seconds ) && isNotNumericString( $seconds ) ) exit;
			$seconds = (double)$seconds;
			if ( $seconds < 0.0 ) exit;
			if ( $seconds >= 60.0 ) exit;
		}
		case 2:
		{
			$minutes = $array[1];
			if ( isNonEmptyString( $minutes ) && isNotNumericString( $minutes ) ) exit;
			$minutes = (double)$minutes;
			if ( $minutes < 0.0 ) exit;
			if ( $minutes >= 60.0 ) exit;
		}
		case 1:
		{
			$degrees = $array[0];
			if ( isNotNumericString( $degrees ) ) exit;
			$degrees = (double)$degrees;
			assert( '$degrees >= 0.0' );	//	Taken care of by our +/- multiplier logic
			if ( $degrees >= 180.0 ) exit;	//	Note that this should be limited to +/- 90 for latitude, but at the moment we don't distinguish!
			break;
		}
		default:
		{
			exit;
		}
	}

	//	Return the decimal result:
	echo $multiplier * $degrees + ( $minutes / 60.0 ) + ( $seconds / 3600.0 );

	//	Bug out of the script:
	exit;
}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function button( $buttonAction, $buttonName, $buttonTooltip, $formAction, $tabIndex, $accessKey, $parameters )
{
	assert( 'isNonEmptyString( $buttonAction )' );
	assert( 'isNonEmptyString( $buttonName )' );
	assert( 'isString( $buttonTooltip )' );
	assert( 'isNonEmptyString( $formAction )' );
	assert( 'isString( $tabIndex ) || isInt( $tabIndex )' );
	assert( 'isString( $accessKey )' );
	assert( 'isArray( $parameters )' );

	$buttonForm =
		'<form class="button" method="post" action="'.$formAction.quoteBracket.newline.
		divTag.newline.
		'<input type="hidden" name="'.keyAction.'" value="'.$buttonAction.quoteSlashBracket.newline;

	foreach ( $parameters as $parameterName => $parameterValue ) $buttonForm .=
		'<input type="hidden" name="'.$parameterName.'" value="'.$parameterValue.quoteSlashBracket.newline;

	$buttonForm .=
		'<button type="submit" class="button" value="'.$buttonName.'" id="'.$buttonAction.doubleQuote;

	if ( isNotEmpty( $buttonTooltip ) ) $buttonForm .=
		' title="'.$buttonTooltip.doubleQuote;

	if ( isNotEmpty( $accessKey ) ) $buttonForm .=
		' accesskey="'.$accessKey.doubleQuote;

	if ( isNotEmpty( $tabIndex ) ) $buttonForm .=
		' tabIndex="'.$tabIndex.doubleQuote;

	$buttonForm .=
		rightBracket.newline.
		$buttonName.newline.
		buttonTagEnd.newline.
		divTagEnd.newline.
		formTagEnd.newline;

	return $buttonForm;
}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function array_value( $array, $key, $defaultValue = null )
{
	assert( 'isArray( $array )' );

	return array_key_exists( $key, $array ) ? $array[$key] : $defaultValue;
}

////////////////////////////////////////////////////////////////////////////////

function array_valuei( $array, $key, $defaultValue = null )
{
	assert( 'isArray( $array )' );
	assert( 'isNonEmptyString( $key )' );

	if ( array_key_exists( $key, $array ) ) return $array[$key];

	foreach ( $array as $k => $v )
	{
		if ( strcasecmp( $k, $key ) === 0 ) return $v;	//	ASCII-only
	}

	return $defaultValue;
}

////////////////////////////////////////////////////////////////////////////////

function array_iunique( $array )
{
	assert( 'isArray( $array )' );

	return array_intersect_key( $array, array_unique( array_map( 'strtolower', $array ) ) );	//	ASCII-only
}

////////////////////////////////////////////////////////////////////////////////

function in_arrayi( $needle, $haystack )
{
	return in_array( strtolower( $needle ), array_map( 'strtolower', $haystack ) );	//	ASCII-only
}

////////////////////////////////////////////////////////////////////////////////

function array_isearch( $str, $array )
{
	foreach ( $array as $k => $v )
	{
		if ( strcasecmp( $str, $v ) == 0 ) return $k;	//	ASCII-only
	}

	return false;
}

////////////////////////////////////////////////////////////////////////////////

function median( $array, $arrayCount )
{
	assert( 'isArray( $array )' );
	assert( 'isNonEmptyInt( $arrayCount )' );
	assert( '$arrayCount > 0' );
	assert( 'isNumeric( $array[0] )' );

	$result = sort( $array );
	assert( '$result === true' );

	$middle = ( $arrayCount / 2 );

	if ( $arrayCount % 2 === 0 )
	{
		return ( $array[$middle-1] + $array[$middle] ) / 2;
	}
	else
	{
		return $array[$middle];
	}
}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function userIdParameter( $databaseConnection, $defaultValue = emptyString )
{
	$userId = parameter( keyUserId, $databaseConnection, $defaultValue );
	assert( 'isString( $userId )' );

	if ( isEmpty( $userId ) ) return $userId;

	if ( isNonEmptyIntString( $userId ) ) return $userId;

	$user = User::userByUserId( $userId, $databaseConnection );
	assert( 'isValidUser( $user )' );

	if ( isNotValidUser( $user ) ) return emptyString;

	return $user->userId();
}

////////////////////////////////////////////////////////////////////////////////

function getFileIdAndTitle( $fileLabel, &$fileId, &$fileTitle )
{
	assert( 'isNonEmptyString( $fileLabel )' );

	$fileLabelArray = explode( space, $fileLabel, 2 );

	if ( count( $fileLabelArray ) > 1 )
	{
		if ( is_numeric( $fileLabelArray[0] ) )
		{
			$fileId = (int)$fileLabelArray[0];
			if ( $fileId === 0 ) $fileId = emptyString;
			$fileTitle = $fileLabelArray[1];
		}
		else
		{
			$fileId = emptyString;
			$fileTitle = $fileLabel;
		}
	}
	else
	{
		if ( is_numeric( $fileLabelArray[0] ) )
		{
			$fileId = (int)$fileLabelArray[0];
			if ( $fileId === 0 ) $fileId = emptyString;
			$fileTitle = emptyString;
		}
		else
		{
			$fileId = emptyString;
			$fileTitle = $fileLabel;
		}
	}

	return true;
}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function formatErrors( $errors )
{
	if ( $errors === null ) return emptyString;

	assert( 'is_array( $errors )' );

	$errorCount = count( $errors );
	if ( $errorCount === 0 ) return emptyString;

	$errorContent = emptyString;

	if ( $errorCount === 1 )
	{
		$errorContent .= pErrorTag.$errors[0].pTagEnd;
	}
	else
	{
		$errorContent .= pErrorTag.'Errors:'.pTagEnd.newline.'<ul class="errors">'.newline;
		foreach( $errors as $error ) $errorContent .= liTag.$error.liTagEnd.newline;
		$errorContent .= ulTagEnd;
	}

	return $errorContent.doubleNewline;
}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function password( $password )
{
	assert( 'isNonEmptyString( $password )' );

	return md5( $password.saltPassword );
}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function setAlbumDirectoryPermissions( $albumDirectoryPath )
{
	return setDirectoryPermissions( $albumDirectoryPath, permissionsDirectoryAlbum );
}

////////////////////////////////////////////////////////////////////////////////

function canonicalFileName( $userId, $albumNumber, $imageNumber, $versionNumber, $imageTitle, $imageExtension )
{
	assert( 'isNonEmptyIntString( $userId )' );
	assert( 'isNonEmptyIntString( $userId )' );
	assert( 'isNonEmptyIntString( $userId )' );
	assert( 'isNonEmptyIntString( $userId )' );
	assert( 'isNonEmptyString( $imageTitle )' );
	assert( 'isNonEmptyString( $imageExtension )' );
	assert( 'strtolower( $imageExtension ) === $imageExtension' );

	$userId = sprintf( userIdFormat, $userId );	//	ASCII-only
	$albumNumber = sprintf( albumNumberFormat, $albumNumber );	//	ASCII-only
	$imageNumber = sprintf( imageNumberFormat, $imageNumber );	//	ASCII-only
	$versionNumber = sprintf( versionNumberFormat, $versionNumber );	//	ASCII-only

	return keyUserId.$userId.keyAlbumNumber.$albumNumber.keyVersionNumber.$versionNumber.keyImageNumber.$imageNumber.space.$imageTitle.dot.$imageExtension;
}

////////////////////////////////////////////////////////////////////////////////

function parseFileName( $fileName, &$userId, &$albumNumber, &$imageNumber, &$versionNumber, &$fileTitle, &$fileExtension )
{
	assert( 'isNonEmptyString( $fileName )' );

	$userId = $albumNumber = $imageNumber = $versionNumber = emptyString;

	$fileTitle = pathinfo( $fileName, PATHINFO_FILENAME );	//	ASCII-only?

	$fileExtension = strtolower( pathinfo( $fileName, PATHINFO_EXTENSION ) );	//	ASCII-only

	$tokens = explode( space, $fileTitle, 2 );
	assert( 'isNonEmptyArray( $tokens )' );

	if ( isNonEmptyIntString( $tokens[0] ) )
	{
		$imageNumber = (int)$tokens[0];
		$fileTitle = ( count( $tokens ) == 1 ? emptyString : $tokens[1] );
		return;
	}

	$token = $tokens[0];
	$userId = canonicalValue( $token, keyUserId );
	$albumNumber = canonicalValue( $token, keyAlbumNumber );
	$imageNumber = canonicalValue( $token, keyImageNumber );
	$versionNumber = canonicalValue( $token, keyVersionNumber );

	if ( isNotEmpty( $userId ) || isNotEmpty( $albumNumber ) || isNotEmpty( $imageNumber ) || isNotEmpty( $versionNumber ) )
	{
		$fileTitle = $tokens[1];
	}
}

////////////////////////////////////////////////////////////////////////////////

function canonicalValue( $string, $key )
{
	assert( 'isNonEmptyString( $string )' );
	assert( 'isNonEmptyString( $key )' );

	$start = stripos( $string, $key );	//	ASCII-only
	if ( $start === false ) return emptyString;
	assert( 'isInt( $start )' );

	$start += strlen( $key );	//	ASCII-only
	assert( 'isNonEmptyInt( $start )' );

	$valueLength = strspn( $string, digits, $start );	//	ASCII-only
	assert( 'isInt( $valueLength )' );

	if ( $valueLength === 0 ) return emptyString;

	$valueString = substr( $string, $start, $valueLength );	//	ASCII-only
	assert( 'isString( $valueString )' );

	$value = (int)$valueString;
	if ( $value == 0 ) return emptyString;

	return $value;
}

////////////////////////////////////////////////////////////////////////////////
/*
$versionLabels = array(
	'01' => directoryNameThumbnail,
	'02' => directoryNameOriginal,
	'03' => directoryNameOriginals,
	'04' => directoryNameUnedited,
	'05' => directoryNameEdited,
	'06' => directoryNameLarge,
	'07' => directoryNameMedium,
	'08' => directoryNameSmall,
	'09' => directoryNameDNG,
	'10' => directoryNameCR2,
	'11' => directoryNameRAW
);

function versionNumber( $user, $album, $image, $versionLabel )
{
	assert( 'isValidUser( $user )' );
	assert( 'isValidAlbum( $album )' );
	assert( 'isValidImage( $image )' );
	assert( 'isNonEmptyString( $versionLabel )' );

	$versionNumber = array_isearch( $versionLabel, $versionLabels );
	assert( 'isNotEmpty( $versionNumber )' );

	return $versionNumber;
}*/

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

Object::storeAll( $databaseConnection );

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

require( templatePath );

//session_write_close();

echo '<!-- Executed in ' .( microtime( true ) - $startTime ). ' seconds. -->';

if ( isNotEmpty( $log ) ) echo doubleNewline.'<!--'.doubleNewline.$log.doubleNewline.'-->'.doubleNewline;

/*

udarin+a0001+i001 Flower In Bloom.jpg

u00001+a0001+i001 Flower In Bloom.jpg

u0001a0001i0001 Flower In Bloom.jpg

darin 0001 001 Flower In Bloom.jpg
debbie 0001 001 Flower In Bloom.jpg

00001 0001 001 Flower In Bloom.jpg
00002 0005 001 Hedgie.jpg

u00001 a0001 i001 Flower In Bloom.jpg
u00002 a0005 i004 Hedgie.

u00001a0001i001 Flower In Bloom.jpg
u00002a0005i004 Hedgie.jpg

u1a1i1 Flower In Bloom.jpg
u2a5i4 Hedgie.jpg

u=darin a=0001 i=001 Flower In Bloom.jpg

udarin a0001 i001 Flower In Bloom.jpg
udebbie a0005 i004 Hedgie.jpg

ii023456789 Hedgie.jpg

u-darin  a-0001 i-001 Flower In Bloom.jpg
u-debbie a-0005 i-004 Hedgie

u001a0001v01i001 Flower In Bloom.jpg
u002a0005v02i005 Hedgie.dng

u001a0001v01 001 Flower In Bloom.jpg
u002a0005v02 005 Hedgie.dng

u001-a0001-v01-i001 Flower In Bloom.jpg
u002-a0005-v02-i005 Hedgie.dng

u001 a0001 v01 i001 Flower In Bloom.jpg
u002 a0005 v02 i005 Hedgie.dng

*/
?>