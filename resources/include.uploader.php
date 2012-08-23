<?php

////////////////////////////////////////////////////////////////////////////////

//namespace org\givingtree\uploader;

////////////////////////////////////////////////////////////////////////////////

require( 'include.uploader.'.language.'.php' );

////////////////////////////////////////////////////////////////////////////////

class Uploader
{
	public static function upload( $user, $album, $databaseConnection )
	{
		//	Note that this function can be fed user-generated input, so heavy
		//	sanitization is required.

		////////////////////////////////////////////////////////////////////////////
		//	Preconditions. Note that we do nothing directly with $databaseConnection
		//	other than pass it to other functions, so there's no need to check it:
		assert( 'isValidUser( $user )' );
		assert( 'isValidAlbum( $album )' );
		assert( '$album->userId() === $user->userId()' );

		if ( isNotValidUser( $user ) ) return false;
		if ( isNotValidAlbum( $album ) ) return false;
		if ( $album->userId() !== $user->userId() ) return false;

		////////////////////////////////////////////////////////////////////////////
		//	Sanitize the input data

		//	If for some reason the $_FILES global variable isn't set properly, fail:
		if ( !isset( $_FILES ) ) return uploadAlbumImagesErrorNoPhotosUploaded.uploadAlbumImagesErrorMissingFileInformation.uploadAlbumImagesErrorMissingFileInformation01;
		if ( !isset( $_FILES[fieldAlbumPhotoBundle] ) ) return uploadAlbumImagesErrorNoPhotosUploaded.uploadAlbumImagesErrorMissingFileInformation.uploadAlbumImagesErrorMissingFileInformation02;
		if ( isEmpty( $_FILES[fieldAlbumPhotoBundle] ) ) return uploadAlbumImagesErrorNoPhotosUploaded.uploadAlbumImagesErrorMissingFileInformation.uploadAlbumImagesErrorMissingFileInformation03;

		//	Create a shortcut to the field:
		$zipFile = $_FILES[fieldAlbumPhotoBundle];

		//	Check the validity of the data:
		if ( !isset( $zipFile['name'] ) ) return uploadAlbumImagesErrorNoPhotosUploaded.uploadAlbumImagesErrorMissingFileInformation.uploadAlbumImagesErrorMissingFileInformation04;
		if ( !isset( $zipFile['tmp_name'] ) ) return uploadAlbumImagesErrorNoPhotosUploaded.uploadAlbumImagesErrorMissingFileInformation.uploadAlbumImagesErrorMissingFileInformation05;
		if ( !isset( $zipFile['type'] ) ) return uploadAlbumImagesErrorNoPhotosUploaded.uploadAlbumImagesErrorMissingFileInformation.uploadAlbumImagesErrorMissingFileInformation06;
		if ( !isset( $zipFile['error'] ) ) return uploadAlbumImagesErrorNoPhotosUploaded.uploadAlbumImagesErrorMissingFileInformation.uploadAlbumImagesErrorMissingFileInformation07;

		//	Create shortcuts to the fields:
		$originalFileName = $zipFile['name'];
		$temporaryFilePath = $zipFile['tmp_name'];
		$mimeType = $zipFile['type'];
		$fileError = $zipFile['error'];

		//	Check the validity of the original filename:
		if ( $originalFileName == emptyString ) return uploadAlbumImagesErrorNoPhotosUploaded.uploadAlbumImagesErrorMissingFileName;

		//	Sanitize the original file name, just in case:
		$originalFileName = sanitizedFileName( $originalFileName, $databaseConnection );

		//	If the end result is empty, someone was probably trying to game the
		//	system because it means the string was all "weird" characters:
		if ( $originalFileName == emptyString ) return false;

		//	Check the validity of the fields:
		if ( $temporaryFilePath == emptyString ) return uploadAlbumImagesErrorNoPhotosUploaded.uploadAlbumImagesErrorMissingTemporaryFilePath;
		if ( $fileError !== 0 ) return uploadAlbumImagesErrorNoPhotosUploaded.uploadAlbumImagesErrorUnknownError.$fileError;

		//	Signal that we're going to allow trusted files:
		$trusted = true;

		//	Check the validity of the MIME content type:
		$mimeTypes = array( 'application/zip' );
		$mimeTypes[] = 'application/x-zip';
		$fileTypes = explode( space, fileTypesBasic );
		if ( $trusted )
		{
			$fileTypes = array_merge( $fileTypes, explode( space, fileTypesTrusted ) );
			$mimeTypes[] = 'application/octet-stream';
		}
		$albumFileFilter = emptyString;
		foreach ( $fileTypes as $fileType )
		{
			$mimeTypes[] = 'image/'.$fileType;

			if ( isNotEmpty( $albumFileFilter ) ) $albumFileFilter .= space;

			$albumFileFilter .= asterisk.dot.$fileType.space.asterisk.dot.strtoupper( $fileType );	//	ASCII-only
		}

		//	If $mimeType happens to be a quoted string for some reason, strip off the quotes:
		if ( $mimeType[0] === '"' ) $mimeType = substr( $mimeType, 1, strlen( $mimeType ) - 2 );

		if ( array_search( $mimeType, $mimeTypes, true ) === false ) return uploadAlbumImagesErrorNoPhotosUploaded.uploadAlbumImagesErrorIncorrectType.commentTag.'MIME Type'.equals.$mimeType.commentTagEnd;

		////////////////////////////////////////////////////////////////////////////
		//	Define the path to the user's directory:

		//	Get the path to the user's directory:
		$userDirectoryPath = rootDirectoryPath.$user->userDirectoryName();

		////////////////////////////////////////////////////////////////////////////
		//	Unzip the file

		//	Create a temporary file in our root with no special prefix:
		$temporaryDirectoryPath = tempnam( $userDirectoryPath, emptyString );
		assert( 'isNotEmpty( $temporaryDirectoryPath )' );

		//	What we -really- want is a directory, not a file, so delete the newly-
		//	created file and create a directory with the same name in its place:
		$result = unlink( $temporaryDirectoryPath );
		assert( '$result != false' );

		//	Ensure that it's a path, not just a name:
		$temporaryDirectoryPath .= pathSeparator;

		//	Create the temporary directory:
		$result = createDirectory( $temporaryDirectoryPath, permissionsDirectoryAlbum );
		assert( '$result != false' );

		//	Move the uploaded file into the given directory:
		$result = move_uploaded_file( $temporaryFilePath, $temporaryDirectoryPath.$originalFileName );
		assert( '$result != false' );

		if ( ( $mimeType === 'application/zip' ) || ( $mimeType == 'application/x-zip' ) )
		{
			//	String together all the components of the external unzip command:
			$unzipCommand = 'unzip'.space.singleQuote.$temporaryDirectoryPath.$originalFileName.singleQuote.space.$albumFileFilter.space.'-d'.space.singleQuote.$temporaryDirectoryPath.singleQuote.space.'-x _*';

			//	Execute the external unzip command:
			exec( $unzipCommand );

			//	Delete the temporary file:
			$result = unlink( $temporaryDirectoryPath.$originalFileName );
			assert( '$result != false' );
		}

		//	Set the permissions for the newly-created
		//	directory and its newly-unzipped contents:
		$result = setAlbumDirectoryPermissions( $temporaryDirectoryPath );
		assert( '$result != false' );

		////////////////////////////////////////////////////////////////////////////
		//	Read the temporary directory structure into memory.

		//	Grab our album and user ids:
		$userId = $album->userId();
		$albumId = $album->albumId();
		$albumNumber = $album->albumNumber();

		//	Create a directory structure in memory:
		$temporaryDirectory = new DirectoryItem( $userId, $albumId, $albumNumber, $temporaryDirectoryPath, $originalFileName, true, $trusted, 2, $databaseConnection );

		//	If all we're left with is an empty directory, someone's probably trying
		//	to game the system, so fail silently:
		if ( $temporaryDirectory->isEmpty() )
		{
			//	Delete the (empty) temporary directory:
			$result = deletePath( $temporaryDirectoryPath );
			assert( '$result === true' );

			//	Bug out:
			return false;
		}

		//	Shift the contents up a level, if necessary:
		$temporaryDirectory->shiftUp();

		//	Delete sub-subdirectories; i.e., only allow nesting to a depth of 1:
		$temporaryDirectory->deleteNestedSubdirectories( 1 );

		//	Move any loose files into their proper subdirectories:
		$temporaryDirectory->organizeLooseFiles();

		//	Find the "master" temporary subdirectory:
		$masterSubdirectory = $temporaryDirectory->masterSubdirectory();
		assert( '$masterSubdirectory !== false' );

		//	Make sure the filenames in the temporary directory all match each other:
		$temporaryDirectory->synchronizeSubdirectories( $masterSubdirectory );

		////////////////////////////////////////////////////////////////////////////
		//	Get the list of version labels associated with this album.

		//	Create the version labels object:
		$versionLabels = new VersionLabels;

		$versionLabels->create( $albumId );

		$result = executeQuery( 'SELECT * FROM '.versionLabelTableName.' WHERE '.fieldAlbumId.equals.singleQuote.$albumId.singleQuote, 'Could not retrieve version labels for album '.$albumId, $databaseConnection );
		assert( '$result != false' );

		$versionLabels->fromSchemaArray( $result );

		////////////////////////////////////////////////////////////////////////////
		//	Read the album directory structure into memory.

		//	Create the full path to the album directory:
		$albumDirectoryPath = realPath( $userDirectoryPath.$album->albumDirectoryNameOld().pathSeparator );
		assert( '$albumDirectoryPath !== false' );
		$albumDirectoryPath .= pathSeparator;

		//	Create the root directory item:
		$albumDirectory = new DirectoryItem( $userId, $albumId, $albumNumber, $albumDirectoryPath, $album->albumDirectoryNameOld(), false, $trusted, 1, $databaseConnection );

		//	Load all of the version objects associated with this album into an array:
		$versions = Version::versionsByAlbumId( $albumId, $databaseConnection );

		//	Populate the root album directory object with the various file item objects organized into their proper subdirectories:
		$images = array();
		foreach ( $versions as $version )
		{
			//	Create an empty file item:
			$fileItem = new FileItem;

			//	Read it from the schema array:
			$fileItem->fromVersion( $version, $databaseConnection );

			//	Extract its image number:
			$imageNumber = $fileItem->imageNumber();
			assert( 'isPositiveInt( $imageNumber )' );

			//	Add it to the mapping of image numbers to image ids:
			if ( !isset( $images[$imageNumber] ) )
			{
				$imageId = $fileItem->imageId();
				assert( 'isPositiveInt( $imageId )' );

				$images[$imageNumber] = $imageId;
			}

			//	Get the version number associated with this version:
			$versionNumber = $fileItem->versionNumber();

			//	Get the name of the subdirectory associated with this version number:
			$versionSubdirectoryName = $versionLabels->versionDirectoryName( $versionNumber );
			assert( 'isNonEmptyString( $versionSubdirectoryName )' );

			//	Get this subdirectory object, if it exists:
			$versionSubdirectory = $albumDirectory->subdirectory( $versionSubdirectoryName );

			//	If it doesn't exist, create it:
			if ( $versionSubdirectory === false )
			{
				$versionSubdirectory = $albumDirectory->addSubdirectory( $versionSubdirectoryName );
			}

			//	Add the version to the subdirectory:
			$versionSubdirectory->addFile( $fileItem );
		}

		////////////////////////////////////////////////////////////////////////////
		//	Merge the temporary directory into the album directory.

		//	Synchronize the temporary directory names against those in the album directory:
//		print_r( $masterSubdirectory );
//		print_r( $temporaryDirectory );
		$temporaryDirectory->synchronizeToDestination( $masterSubdirectory, $albumDirectory );
//		print_r( $temporaryDirectory );

		//	Merge the contents temporary directory into those of the album directory:
		$newImageNumbers = array();
		$newImageTitles = array();
		$updatedImageNumbers = array();
		$newFileItems = array();
		$updatedFileItems = array();
		$temporaryDirectory->mergeIntoDirectory( $userId, $albumNumber, $albumDirectory, $albumDirectoryPath, permissionsDirectoryAlbum, $newImageNumbers, $newImageTitles, $updatedImageNumbers, $newFileItems, $updatedFileItems, $versionLabels );

		//	Delete the temporary directory:
		$result = deletePath( $temporaryDirectoryPath );
		assert( '$result === true' );

		////////////////////////////////////////////////////////////////////////////
		//	Process new images

		foreach ( $newImageNumbers as $newImageKey => $newImageNumber )
		{
			assert( 'isPositiveInt( $newImageNumber )' );

			$image = $album->addImage( (string)$newImageNumber, $newImageTitles[$newImageKey], $databaseConnection );
			assert( 'isValidImage( $image )' );

			$images[$newImageNumber] = $image->imageId();
		}

		////////////////////////////////////////////////////////////////////////////
		//	Resize thumbnails and others

		//	Do we need to do the default resizing logic? Note that we -always-
		//	create thumbnails, regardless of what $albumDoResize says:
		$albumDoResize =  ( $album->albumDoResize() === one );
		assert( 'isBool( $albumDoResize )' );

		//	Create the path to the various directories:
		$directoryPathThumbnail = $albumDirectoryPath.directoryNameThumbnail.pathSeparator;
		if ( $albumDoResize )
		{
			$directoryPathLarge  = $albumDirectoryPath.directoryNameLarge.pathSeparator;
			$directoryPathMedium = $albumDirectoryPath.directoryNameMedium.pathSeparator;
			$directoryPathSmall  = $albumDirectoryPath.directoryNameSmall.pathSeparator;
		}

		//	Attempt to get the various subdirectories:
		$subdirectoryThumbnail = $albumDirectory->subdirectory( directoryNameThumbnail );
		if ( $albumDoResize )
		{
			$subdirectoryLarge  = $albumDirectory->subdirectory( directoryNameLarge );
			$subdirectoryMedium = $albumDirectory->subdirectory( directoryNameMedium );
			$subdirectorySmall  = $albumDirectory->subdirectory( directoryNameSmall );
		}

		//	If these subdirectories don't yet exist, create them:
		$subdirectoryThumbnail = Uploader::createSubdirectory( $albumDirectory, $subdirectoryThumbnail, directoryNameThumbnail, $directoryPathThumbnail );
		if ( $albumDoResize )
		{
			$subdirectoryLarge  = Uploader::createSubdirectory( $albumDirectory, $subdirectoryLarge, directoryNameLarge, $directoryPathLarge );
			$subdirectoryMedium = Uploader::createSubdirectory( $albumDirectory, $subdirectoryMedium, directoryNameMedium, $directoryPathMedium );
			$subdirectorySmall  = Uploader::createSubdirectory( $albumDirectory, $subdirectorySmall, directoryNameSmall, $directoryPathSmall );
		}

		//	Define the various resolutions:
		$sizeThumbnail = sizeDefaultThumbnail;
		$qualityThumbnail = qualityDefaultThumbnail;
		if ( $albumDoResize )
		{
			$sizeLarge  = sizeDefaultLarge;
			$sizeMedium = sizeDefaultMedium;
			$sizeSmall  = sizeDefaultSmall;

			$qualityLarge  = qualityDefaultLarge;
			$qualityMedium = qualityDefaultMedium;
			$qualitySmall  = qualityDefaultSmall;
		}

		//	Get the version numbers associated with each label:
		$versionNumberThumbnail = $versionLabels->versionNumber( directoryNameThumbnail );
		if ( $albumDoResize )
		{
			$versionNumberLarge  = $versionLabels->versionNumber( directoryNameLarge );
			$versionNumberMedium = $versionLabels->versionNumber( directoryNameMedium );
			$versionNumberSmall  = $versionLabels->versionNumber( directoryNameSmall );
		}

		//	For each of the newly-updated files, update the corresponding thumbnail:
		foreach ( $updatedImageNumbers as $imageNumber )
		{
			//	Get the master file associated with this image number:
			$masterFile = $albumDirectory->masterFile( $imageNumber );

			//	If we were able to retrive a master file, continue:
			if ( isNotNull( $masterFile ) )
			{
				assert( '$imageNumber === $masterFile->imageNumber()' );

				//	Extract some information from the master file:
				$masterFileTitle = $masterFile->fileTitle();
				assert( 'isNonEmptyString( $masterFileTitle )' );

				$masterFileExtension = $masterFile->fileExtension();
				assert( 'isNonEmptyString( $masterFileExtension )' );

				$masterFilePath = $masterFile->filePath();
				assert( 'isFilePath( $masterFilePath )' );

				//	Generate canonical file names:
				$fileNameThumbnail = canonicalFileName( $userId, $albumNumber, $imageNumber, $versionNumberThumbnail, $masterFileTitle, fileTypeThumbnail );
				if ( $albumDoResize )
				{
					$fileNameLarge  = canonicalFileName( $userId, $albumNumber, $imageNumber, $versionNumberLarge,  $masterFileTitle, fileTypeLarge );
					$fileNameMedium = canonicalFileName( $userId, $albumNumber, $imageNumber, $versionNumberMedium, $masterFileTitle, fileTypeMedium );
					$fileNameSmall  = canonicalFileName( $userId, $albumNumber, $imageNumber, $versionNumberSmall,  $masterFileTitle, fileTypeSmall );
				}

				//	Generate canonical file paths:
				$filePathThumbnail = $directoryPathThumbnail.$fileNameThumbnail;
				if ( $albumDoResize )
				{
					$filePathLarge  = $directoryPathLarge.$fileNameLarge;
					$filePathMedium = $directoryPathMedium.$fileNameMedium;
					$filePathSmall  = $directoryPathSmall.$fileNameSmall;
				}

				//	Create or update the applicable versions:
				Uploader::updateVersionFile( $userId, $albumId, $albumNumber, $imageNumber, $subdirectoryThumbnail, $masterFileTitle, $masterFileExtension, $masterFilePath, $filePathThumbnail, $sizeThumbnail, $qualityThumbnail, $versionNumberThumbnail, $images, $updatedFileItems, $newFileItems );

				if ( $albumDoResize )
				{
					Uploader::updateVersionFile( $userId, $albumId, $albumNumber, $imageNumber, $subdirectoryLarge,  $masterFileTitle, $masterFileExtension, $masterFilePath, $filePathLarge,  $sizeLarge,  $qualityLarge,  $versionNumberLarge,  $images, $updatedFileItems, $newFileItems );
					Uploader::updateVersionFile( $userId, $albumId, $albumNumber, $imageNumber, $subdirectoryMedium, $masterFileTitle, $masterFileExtension, $masterFilePath, $filePathMedium, $sizeMedium, $qualityMedium, $versionNumberMedium, $images, $updatedFileItems, $newFileItems );
					Uploader::updateVersionFile( $userId, $albumId, $albumNumber, $imageNumber, $subdirectorySmall,  $masterFileTitle, $masterFileExtension, $masterFilePath, $filePathSmall,  $sizeSmall,  $qualitySmall,  $versionNumberSmall,  $images, $updatedFileItems, $newFileItems );
				}
			}
		}

		////////////////////////////////////////////////////////////////////////////
		//	Process new file items

		$updatedImages = array();

		foreach ( $newFileItems as $fileItem )
		{
			$imageNumber = $fileItem->imageNumber();
			assert( 'isPositiveInt( $imageNumber )' );

			$imageId = $images[$imageNumber];
			assert( 'isPositiveIntString( $imageId ) || isPositiveInt( $imageId )' );

			$updatedImages[] = $imageId;

			$image = Image::imageByImageId( $imageId, $databaseConnection );
			assert( 'isValidImage( $image )' );

			$versionNumber = $fileItem->versionNumber();
			assert( 'isPositiveIntString( $versionNumber )' );

			$versionLabel = $versionLabels->versionDirectoryName( $versionNumber );
			assert( 'isNonEmptyString( $versionLabel )' );

			$imageWidth = $fileItem->imageWidth();
			assert( 'isInt( $imageWidth )' );
			assert( '$imageWidth > -1' );

			$imageHeight = $fileItem->imageHeight();
			assert( 'isInt( $imageHeight )' );
			assert( '$imageHeight > -1' );

			$fileBytes = $fileItem->fileBytes();
			assert( 'isInt( $fileBytes )' );
			assert( '$fileBytes > -1' );

			$fileExtension = $fileItem->fileExtension();
			assert( 'isNonEmptyString( $fileExtension )' );

			$version = $image->addVersion( $versionNumber, $versionLabel, (string)$imageWidth, (string)$imageHeight, (string)$fileBytes, $fileExtension, $databaseConnection );
			assert( 'isValidVersion( $version )' );

			$versionExif = emptyString;
			$versionExifCount = 0;
			Uploader::readExif( $version->versionFilePath( $databaseConnection ), $versionExif, $versionExifCount, $databaseConnection );

			$versionIptc = emptyString;
			$versionIptcCount = 0;
			Uploader::readIptc( $version->versionFilePath( $databaseConnection ), $versionIptc, $versionIptcCount, $databaseConnection );

			//	Add the IPTC data to the EXIF data. I don't know if this is
			//	the right approach or whether IPTC should be in its own
			//	separate database field, but for the moment I'm treating
			//	it all as "metadata":
			$versionExif .= $versionIptc;
			$versionExifCount += $versionIptcCount;

/*			$versionXmp = emptyString;
			$versionXmpCount = 0;
			Uploader::readXmp( $version->versionFilePath( $databaseConnection ), $versionXmp, $versionXmpCount, $databaseConnection );*/
			//	Note: I'm not doing anything with XMP data at the moment.

			//	Get the image's existing EXIF data:
			$imageExif = $image->imageExif();

			//	Convert the image EXIF data into an XML data structure:
			try
			{
				$useInternalErrors = libxml_use_internal_errors( true );
				$imageExifXml = new SimpleXMLElement( '<exif>'.$imageExif.'</exif>' );
				libxml_use_internal_errors( $useInternalErrors );
				$imageExifCount = $imageExifXml->count();
			}
			catch ( Exception $e )
			{
				$imageExifCount = 0;
			}

			//	If the number of entries in the version's exif data exceeds the
			//	number of entries in the current image exif data, replace the
			//	current image exif with the version's exif. Another approach
			//	would be to try to merge the two in case some tags exist in one
			//	but not the other, and allow the new version exif to win any
			//	conflicts, but that seems like overkill (at least for now). In
			//	any case, my current approach definitely has room for error and
			//	could potentially be improved at some future point if necessary:
			if ( $versionExifCount > $imageExifCount )
			{
				//	Replace the image's exif data with the version's exif data:
				$image->setImageExif( $versionExif );

				////////////////////////////////////////////////////////////////

				//	Find the contents of any ImageDescription tag and use it
				//	as the image's caption:
				$versionDescription = xmlStringValue( 'ImageDescription', $versionExif );

				//	If the version description isn't empty...
				if ( isNonEmptyString( $versionDescription ) )
				{
					//	Get the existing image description:
					$imageDescription = $image->imageDescription();

					//	If the version's description is longer than the
					//	current image description use it instead. Potentially
					//	error-prone, but probably better than no check at all:
					if ( strlen( $versionDescription ) > strlen( $imageDescription ) ) $image->setImageDescription( $versionDescription );
				}

				////////////////////////////////////////////////////////////////

				//	Try to extract the address:
				$address = emptyString;
				$city = xmlStringValue( 'IPTCCity', $versionExif );
				if ( isNonEmptyString( $city ) )
				{
					$address = $city;
				}

				$state = xmlStringValue( 'IPTCStateProvince', $versionExif );
				if ( isNonEmptyString( $state ) )
				{
					if ( isNonEmptyString( $address ) ) $address .= comma.space;
					$address .= $state;
				}

				$country = xmlStringValue( 'IPTCCountry', $versionExif );
				if ( isNonEmptyString( $country ) )
				{
					if ( isNonEmptyString( $address ) ) $address .= comma.space;
					$address .= $country;
				}

				//	If we have a non-empty address, set it:
				if ( isNonEmptyString( $address ) )
				{
					$image->setImageAddress( $address );
				}

				////////////////////////////////////////////////////////////////

				//	Try to set the GPS data if it exists:
				$latitudeHemisphere = trim( xmlStringValue( 'GPSLatitudeRef', $versionExif ) );
				$latitude = emptyString;
				if ( isNonEmptyString( $latitudeHemisphere ) )
				{
					$latitude = xmlStringValue( 'GPSLatitude', $versionExif );
					$latitude = Uploader::gpsDecimalString( $latitudeHemisphere, $latitude );
					$image->setImageLatitude( $latitude );
				}

				$longitudeHemisphere = trim( xmlStringValue( 'GPSLongitudeRef', $versionExif ) );
				$longitude = emptyString;
				if ( isNonEmptyString( $longitudeHemisphere ) )
				{
					$longitude = xmlStringValue( 'GPSLongitude', $versionExif );
					$longitude = Uploader::gpsDecimalString( $longitudeHemisphere, $longitude );
					$image->setImageLongitude( $longitude );
				}

				$altitude = xmlStringValue( 'GPSAltitude', $versionExif );
				if ( isNonEmptyString( $altitude ) )
				{
					$altitude = Uploader::gpsDoubleString( $altitude );
					$image->setImageAltitude( $altitude );
				}

				$heading = xmlStringValue( 'GPSImgDirection', $versionExif );
				if ( isNonEmptyString( $heading ) )
				{
					$heading = Uploader::gpsDoubleString( $heading );
					$image->setImageHeading( $heading );
				}

				////////////////////////////////////////////////////////////////

				//	Assume we want to enable geocoding and reverse geocoding:
				$geocode = true;

				//	If we didn't enable geocoding, do nothing:
				if ( !$geocode )
				{
					//	Do nothing.
				}
				//	If we have an address, set it; in addition, if the latitude
				//	and longitude have not both been set, then attempt to look
				//	those up:
				else if ( isNonEmptyString( $address ) )
				{
					//	If the latitude and longitude have not both been set
					//	then attempt to look them up:
					if ( isEmptyString( $latitude ) && isEmptyString( $longitude ) )
					{
						//	Look up the location of this address:
						for ( $i = 0; $i < 5; $i++ )
						{
							//	Get the location:
							$location = geocode( $address );

							//	If we were successful, bug out:
							if ( $location !== false ) break;

							//	Sleep half a second before trying again:
							usleep( 500000 );
						}

						//	If it was successful, extract the coordinates:
						if ( $location !== false )
						{
							//	Extract the coordinates:
							$latitude = $location->latitude();
							assert( 'isNumericString( $latitude )' );

							$longitude = $location->longitude();
							assert( 'isNumericString( $longitude )' );

							//	Store the coordinates:
							$image->setImageLatitude( $latitude );
							$image->setImageLongitude( $longitude );

							//	Now try to get the elevation using this:
						}
					}
				}
				//	If we have location coordinates but no address, attempt to
				//	look up the address using those coordinates:
				else
				{
					//	If the latitude and longitude have both been set then attempt
					//	to look up the address associated with those coordinates:
					if ( isNumericString( $latitude ) && isNumericString( $longitude ) )
					{
						//	Look up the address of this location:
						for ( $i = 0; $i < 5; $i++ )
						{
							//	Get the location:
							$location = reverseGeocode( $latitude, $longitude );

							//	If we were successful, bug out:
							if ( $location !== false ) break;

							//	Sleep half a second before trying again:
							usleep( 500000 );
						}

						//	If it was successful, extract the address:
						if ( $location !== false )
						{
							//	Extract the address:
							$address = $location->address();
							assert( 'isString( $address )' );

							//	Store the address:
							$image->setImageAddress( $address );

							//	Now try to get the elevation using this:
						}

					}
				}

				////////////////////////////////////////////////////////////////

				//	Try to set the timestamp if it exists:
				$timestampString = xmlStringValue( 'DateTimeOriginal', $versionExif, xmlStringValue( 'IPTCDateCreated', $versionExif ).space.xmlStringValue( 'IPTCTimeCreated', $versionExif ) );
				if ( isNonEmptyString( $timestampString ) )
				{
					$timestamp = strtotime( $timestampString );
					if ( $timestamp != false )
					{
						assert( 'isPositiveInt( $timestamp )' );

						$timestampString = timestampString( $timestamp );
						assert( 'isNonEmptyString( $timestampString )' );

						$image->setImageTimestamp( $timestampString );
					}
				}

				////////////////////////////////////////////////////////////////

				//	Try to set the photographer using the Artist tag, if it
				//	exists, else try the CameraOwnerName tag, else try the
				//	IPTCCreator tag, then the IPTCProvider tag:
				$photographer = xmlStringValue( 'Artist', $versionExif, xmlStringValue( 'CameraOwnerName', $versionExif, xmlStringValue( 'IPTCCreator', $versionExif, xmlStringValue( 'IPTCProvider', $versionExif ) ) ) );
				if ( isNonEmptyString( $photographer ) )
				{
					$image->setImagePhotographer( $photographer );	
				}

				////////////////////////////////////////////////////////////////

				//	Try to set the keywords:
				$keywords = xmlStringValue( 'IPTCKeywords' , $versionExif );
				if ( isNonEmptyString( $keywords ) )
				{
					$image->setImageTags( $keywords );
				}
			}

			$fileItem->setImageId( (int)$imageId );
			$fileItem->setVersionId( (int)$version->versionId() );
		}

		////////////////////////////////////////////////////////////////////////////
		//	Process updated file items

		foreach ( $updatedFileItems as $fileItem )
		{
			$versionId = $fileItem->versionId();
			assert( 'isPositiveInt( $versionId )' );

			$version = Version::versionByVersionId( $versionId, $databaseConnection );
			assert( 'isValidVersion( $version )' );
			assert( '$version->versionId() == $fileItem->versionId()' );
			assert( '$version->imageId() == $fileItem->imageId()' );
			assert( '$version->versionNumber() === $fileItem->versionNumber()' );

			$imageWidth = $fileItem->imageWidth();
			assert( 'isInt( $imageWidth )' );
			assert( '$imageWidth > -1' );

			$imageHeight = $fileItem->imageHeight();
			assert( 'isInt( $imageHeight )' );
			assert( '$imageHeight > -1' );

			$fileBytes = $fileItem->fileBytes();
			assert( 'isInt( $fileBytes )' );
			assert( '$fileBytes > -1' );

			$fileExtension = $fileItem->fileExtension();
			assert( 'isNonEmptyString( $fileExtension )' );

			$version->setVersionResolution( $imageWidth, $imageHeight );
			$version->setVersionFileBytes( $fileBytes );
			$version->setVersionFileExtension( $fileExtension );

			$updatedImages[] = $version->imageId();
		}

		//	Update the list of version labels:
		$updatedVersionLabels = $versionLabels->updatedVersionLabels();
		if ( $updatedVersionLabels !== emptyString )
		{
			$result = executeQuery( 'INSERT INTO '.versionLabelTableName.space.$updatedVersionLabels, 'Could not add new version labels for album '.$albumId, $databaseConnection );
			assert( '$result != false' );
		}

		////////////////////////////////////////////////////////////////////////////
		//	Process updated images

		//	Make sure we start with an up-to-date database:
		Object::storeAll( $databaseConnection );

		//	Ensure the list of updated images is unique; note that these are image ids, not image objects:
		$updatedImages = array_unique( $updatedImages, SORT_NUMERIC );

		//	Process each image:
		foreach( $updatedImages as $imageId )
		{
			//	Get the image object:
			$image = Image::imageByImageId( $imageId, $databaseConnection );

			//	Force it to determine its new magnified version:
			$image->setImageVersionIdMagnified( $databaseConnection );
		}

		//	Signal success:
		return true;
	}

	////////////////////////////////////////////////////////////////////////////////

	private static function createSubdirectory( $directory, $subdirectory, $subdirectoryName, $subdirectoryPath )
	{
		//	Preconditions:
		assert( 'isValidObject( $directory )' );
		assert( '$directory != $subdirectory' );
		assert( '( $subdirectory === false ) || isValidObject( $subdirectory )' );
		assert( 'isNonEmptyString( $subdirectoryName )' );
		assert( 'isDirectoryPath( $subdirectoryPath )' );

		//	If it already exists, bug out:
		if ( $subdirectory !== false ) return $subdirectory;

		//	Create the subdirectory in the file system:
		$result = createDirectory( $subdirectoryPath, permissionsDirectoryAlbum );
		assert( '$result === true' );

		//	Create the directory in the virtual file system:
		$subdirectory = $directory->addSubdirectory( $subdirectoryName );
		assert( '$directory->isValid()' );
		assert( '$subdirectory->isValid()' );

		//	Return the new subdirectory:
		return $subdirectory;
	}

	////////////////////////////////////////////////////////////////////////////////

	private static function updateVersionFile( $userId, $albumId, $albumNumber, $imageNumber, $subdirectory, $masterFileTitle, $masterFileExtension, $masterFilePath, $newFilePath, $size, $quality, $versionNumber, &$images, &$updatedVersions, &$newVersions )
	{
		assert( 'isPositiveInt( $imageNumber )' );
		assert( 'isValidObject( $subdirectory )' );
		assert( 'isNonEmptyArray( $images )' );
		assert( 'isArray( $updatedVersions )' );
		assert( 'isArray( $newVersions )' );

		if ( $newFilePath === $masterFilePath ) return;

		$newImageSize = Uploader::resizeImage( $masterFilePath, $newFilePath, $size, $quality );
		assert( 'isNotEmpty( $newImageSize )' );

		$newImageWidth = $newImageSize[0];
		assert( 'isInt( $newImageWidth )' );
		assert( '$newImageWidth > 0' );

		$newImageHeight = $newImageSize[1];
		assert( 'isInt( $newImageHeight )' );
		assert( '$newImageHeight > 0' );

		$imageFileKey = $subdirectory->fileKey( $imageNumber );
		$newImageExists = ( $imageFileKey !== false );

		if ( $newImageExists )
		{
			$imageFile = $subdirectory->file( $imageFileKey );
			assert( '$imageFile->isValid()' );

			$imageFile->updateFileBytes();
			$imageFile->setImageResolution( $newImageWidth, $newImageHeight );

			if ( $imageFile->isModified() && array_search( $imageFile, $updatedVersions ) === false ) $updatedVersions[] = $imageFile;
		}
		else
		{
			$imageId = $images[$imageNumber];
			$imageFile = new FileItem;
			$imageFile->createFileItem( $imageNumber, $newFilePath, $masterFileTitle, fileTypeThumbnail );
			$imageFile->setVersionNumber( $versionNumber );
			$subdirectory->addFile( $imageFile );

			$imageFile->updateFileBytes();
			$imageFile->setImageResolution( $newImageWidth, $newImageHeight );

			$newVersions[] = $imageFile;
		}
	}

	////////////////////////////////////////////////////////////////////////////////

	private static function resizeImage( $originalFilePath, $newFilePath, $newSize /*pixels*/, $quality /* 0-100 */ )
	{
		assert( 'isNonEmptyString( $originalFilePath )' );
		assert( 'isFilePath( $originalFilePath )' );
		assert( 'isNonEmptyString( $newFilePath )' );
		assert( 'isFilePath( $newFilePath )' );
		assert( 'strcmp( $originalFilePath, $newFilePath ) !== 0' );	//	ASCII-only
		assert( 'isFile( $originalFilePath )' );
		assert( 'isFilePath( $newFilePath )' );
		assert( 'isPositiveInt( $newSize )' );
		assert( '$newSize > 1' );
		assert( 'isPositiveInt( $quality )' );
		assert( '$quality > 0' );
		assert( '$quality < 101' );

		$fileType = pathinfo( $originalFilePath, PATHINFO_EXTENSION );
		assert( 'isNonEmptyString( $fileType )' );
		assert( '$fileType === strtolower( $fileType )' );	//	ASCII-only

		switch ( $fileType )
		{
			case 'jpg':
			case 'jpeg': $originalImage = imagecreatefromjpeg( $originalFilePath ); break;
			case 'gif':  $originalImage = imagecreatefromgif( $originalFilePath ); break;
			case 'png':  $originalImage = imagecreatefrompng( $originalFilePath ); break;
			default:
			{
				assert( 'false' );
				return false;
			}
		}
		assert( 'isNotEmpty( $originalImage )' );

		$originalWidth  = imagesx( $originalImage );
		assert(	'isNotEmpty( $originalWidth )' );
		assert( 'isInt( $originalWidth )' );

		$originalHeight = imagesy( $originalImage );
		assert(	'isNotEmpty( $originalHeight )' );
		assert( 'isInt( $originalHeight )' );

		if ( $originalHeight > $originalWidth )
		{
			$newHeight = $newSize;
			$newWidth = (int)floor( $originalWidth * ( $newHeight / $originalHeight ) );
		}
		else
		{
			$newWidth = $newSize;
			$newHeight = (int)floor( $originalHeight * ( $newWidth / $originalWidth ) );
		}
		assert( 'isInt( $newWidth )' );
		assert( 'isInt( $newHeight )' );

		$newImage = imagecreatetruecolor( $newWidth, $newHeight );
		assert( 'isNotEmpty( $newImage )' );

		if ( ( $fileType === 'gif' ) || ( $fileType === 'png' ) )
		{
			$result = imagealphablending( $newImage, false );
			assert( '$result === true' );

			$result = imagesavealpha( $newImage, true ) ;
			assert( '$result === true' );

			$transparentColor = imagecolorallocatealpha( $newImage, 255, 255, 255, 127 );
			assert( '$transparentColor !== false' );

			$result = imagefilledrectangle( $newImage, 0, 0, $newWidth, $newHeight, $transparentColor );
			assert( '$result === true' );
		}

		$result = imagecopyresampled( $newImage, $originalImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight );
		assert( '$result === true' );

		$result = imagejpeg( $newImage, $newFilePath, $quality );
		assert( '$result === true' );

		return ( $result === true ? array( $newWidth, $newHeight ) : $result );
	}

	////////////////////////////////////////////////////////////////////////////////

	private static function readExif( $filePath, &$xml, &$count, $databaseConnection )
	{
		assert( 'isFile( $filePath )' );

		$exifData = array();
		if ( $exif = exif_read_data( $filePath, null, true ) )
		{
			foreach ( $exif as $key => $section )
			{
				foreach ( $section as $name => $value )
				{
					//	Sanitize the tag name:
					$name = sanitizedString( (string)$name, $databaseConnection );

					//	Map raw hexidecimal tag ids to tag names:
					$matches = array();
					$nameArray = preg_match( '/0x[0-9A-F]*/i', $name, $matches );
					assert( '( count( $matches ) === 0 ) || ( count( $matches ) === 1 )' );
					if ( count( $matches ) === 1 )
					{
						//	Extract the hexidecimal tag id:
						$name = $matches[0];
						assert( isNonEmptyString( $name ) );
						assert( strlen( $name ) > 1 );

						//	Hex tag is begin with '0x':
						$name = substr( $name, 2 );

						//	Ensure that all hex values are lowercase:
						$name = strtolower( $name );

						//	Map hex tag ids to tag names:
						switch ( $name )
						{
							case 'a430': $name = 'CameraOwnerName'; break;
							case 'a431': $name = 'SerialNumber'; break;
							case 'a432': $name = 'LensInfo'; break;
							case 'a433': $name = 'LensMake'; break;
							case 'a434': $name = 'LensModel'; break;
							case 'a435': $name = 'LensSerialNumber'; break;
						}
					}

					//	Linearize array values:
					if ( isArray( $value ) ) $value = implode( newline, $value );

					//	Sanitize the value:
					$value = sanitizedString( (string)$value, $databaseConnection );

					//	Add the tag key, value pair to the exif array:
					if ( isNonEmptyString( $name ) && isNonEmptyString( $value ) && ctype_print( $name ) && ctype_print( $value ) && isNotNumeric( $name ) ) $exifData[$name] = $value;
				}
			}
		}

		uksort( $exifData, 'strnatcasecmp' );

		$xml = emptyString;
		foreach ( $exifData as $key => $value ) $xml .=
				leftBracket.$key.rightBracket.newline.
				$value.newline.
				leftBracket.slash.$key.rightBracket.newline;

		$count = count( $exifData );

		return true;
	}

	////////////////////////////////////////////////////////////////////////////////

	private static function gpsDecimalString( $hemisphere, $value )
	{
		assert( '( $hemisphere === emptyString ) || ( $hemisphere === "N" ) || ( $hemisphere === "S" ) || ( $hemisphere === "E" ) || ( $hemisphere === "W" )' );

		$valueArray = explode( '\\n', $value );
		$valueArrayCount = count( $valueArray );
		assert( '( $valueArrayCount > -1 ) && ( $valueArrayCount < 4 )' );

		$degrees = ( $valueArrayCount > 0 ? Uploader::gpsDoubleString( $valueArray[0] ) : 0.0 );
		$minutes = ( $valueArrayCount > 1 ? Uploader::gpsDoubleString( $valueArray[1] ) : 0.0 );
		$seconds = ( $valueArrayCount > 2 ? Uploader::gpsDoubleString( $valueArray[2] ) : 0.0 );

		$flip =  ( ( ( $hemisphere === 'W' ) || ( $hemisphere === 'S' ) ) ? -1.0 : 1.0 );

		return (string)( $flip * ( $degrees + ( $minutes / 60.0 ) + ( $seconds / 3600.0 ) ) );
	}

	////////////////////////////////////////////////////////////////////////////////

	private static function gpsDoubleString( $value )
	{
		assert( 'isString( $value )' );

		$parts = explode( slash, $value);
		assert( 'isArray( $parts )' );

		$parts = array_map( 'trim', $parts );

		$partsCount = count( $parts );
		assert( '( $partsCount > -1 ) && ( $partsCount < 3 )' );

		if ( $partsCount === 0 ) return 0.0;

		assert( 'isNonEmptyNumericString( $parts[0] )' );
		if ( $partsCount === 1 ) return (double)$parts[0];

		assert( 'isNonEmptyNumericString( $parts[1] )' );
		assert( '(double)$parts[1] != 0' );
		return (string)( (double)$parts[0] / (double)$parts[1] );
	}

	////////////////////////////////////////////////////////////////////////////////

	private static function iptcValue( $array, $key )
	{
		//	NB: This is not designed to deal with entries containing arrays of
		//	values, such as supp_categories. These will be caught by assertion
		//	and you can modify the function as needed. However, these types of
		//	data are not needed at this time.

		assert( 'isArray( $array )' );
		assert( 'isNonEmptyString( $key )' );

		if ( array_key_exists( $key, $array ) )
		{
			$subarray = $array[$key];
			assert( 'isNonEmptyArray( $subarray )' );
			assert( 'count( $subarray ) === 1' );	//	> 1 => array of values

			return $subarray[0];	//	Return the first value in the array only
		}

		return emptyString;
	}

	////////////////////////////////////////////////////////////////////////////////

	private static function iptcValues( $array, $key )
	{
		assert( 'isArray( $array )' );
		assert( 'isNonEmptyString( $key )' );

		if ( array_key_exists( $key, $array ) )
		{
			$subarray = $array[$key];
			assert( 'isNonEmptyArray( $subarray )' );
			assert( 'count( $subarray ) > 0' );

			return $subarray;
		}

		return array();
	}

	////////////////////////////////////////////////////////////////////////////////

	private static function readIptc( $filePath, &$xml, &$count, $databaseConnection )
	{
		assert( 'isFile( $filePath )' );

		$iptcData = array();

		$imageSize = getimagesize( $filePath, $imageInfo );
		if ( isset( $imageInfo['APP13'] ) )
		{
			$iptc = iptcparse( $imageInfo['APP13'] );

			if ( isArray( $iptc ) )
			{
				$title = Uploader::iptcValue( $iptc, '2#005' );
				if ( isNonEmptyString( $title ) )
				{
					$xml .= '<IPTCTitle>'.$title.'</IPTCTitle>';
					$count++;
				}

				$headline = Uploader::iptcValue( $iptc, '2#105' );
				if ( isNonEmptyString( $headline ) )
				{
					$xml .= '<IPTCHeadline>'.$headline.'</IPTCHeadline>';
					$count++;
				}

				$caption = Uploader::iptcValue( $iptc, '2#120' );
				if ( isNonEmptyString( $caption ) )
				{
					$xml .= '<IPTCCaption>'.$caption.'</IPTCCaption>';
					$count++;
				}

				$keywords = Uploader::iptcValues( $iptc, '2#025' );
				if ( isNonEmptyArray( $keywords ) )
				{
					$xml .= '<IPTCKeywords>'.implode( comma.space, $keywords ).'</IPTCKeywords>';
					$count++;
				}

				$urgency = Uploader::iptcValue( $iptc, '2#010' );
				if ( isNonEmptyString( $urgency ) )
				{
					$xml .= '<IPTCUrgency>'.$urgency.'</IPTCUrgency>';
					$count++;
				}

				$creator = Uploader::iptcValue( $iptc, '2#080' );
				if ( isNonEmptyString( $creator ) )
				{
					$xml .= '<IPTCCreator>'.$creator.'</IPTCCreator>';
					$count++;
				}

				$provider = Uploader::iptcValue( $iptc, '2#110' );
				if ( isNonEmptyString( $provider ) )
				{
					$xml .= '<IPTCProvider>'.$provider.'</IPTCProvider>';
					$count++;
				}

				$dateCreated = Uploader::iptcValue( $iptc, '2#055' );
				if ( isNonEmptyString( $dateCreated ) )
				{
					$xml .= '<IPTCDateCreated>'.$dateCreated.'</IPTCDateCreated>';
					$count++;
				}

				$timeCreated = Uploader::iptcValue( $iptc, '2#060' );
				if ( isNonEmptyString( $timeCreated ) )
				{
					$xml .= '<IPTCTimeCreated>'.$timeCreated.'</IPTCTimeCreated>';
					$count++;
				}

				$city = Uploader::iptcValue( $iptc, '2#090' );
				if ( isNonEmptyString( $city ) )
				{
					$xml .= '<IPTCCity>'.$city.'</IPTCCity>';
					$count++;
				}

				$state = Uploader::iptcValue( $iptc, '2#095' );
				if ( isNonEmptyString( $state ) )
				{
					$xml .= '<IPTCStateProvince>'.$state.'</IPTCStateProvince>';
					$count++;
				}

				$country = Uploader::iptcValue( $iptc, '2#101' );
				if ( isNonEmptyString( $country ) )
				{
					$xml .= '<IPTCCountry>'.$country.'</IPTCCountry>';
					$count++;
				}

				$copyrightNotice = Uploader::iptcValue( $iptc, '2#116' );
				if ( isNonEmptyString( $copyrightNotice ) )
				{
					$xml .= '<IPTCCopyrightNotice>'.$copyrightNotice.'</IPTCCopyrightNotice>';
					$count++;
				}
			}
		}

		return true;
	}

	////////////////////////////////////////////////////////////////////////////////

	private static function readXmp( $filePath, &$xml, &$count, $databaseConnection )
	{
		$chunkSize = 1024; // bytes

		$filePointer = fopen( $filePath, 'r' );
		assert( '$filePointer !== false ' );

		$foundStart = false;
		while ( ( $chunk = fread( $filePointer, $chunkSize ) ) !== false )
		{
			if ( ( $index = strpos( $chunk, '<x:xmpmeta' ) ) !== false )
			{
				$buffer .= substr( $chunk, index );
				$foundStart = true;
			}
			elseif ( ( $index = strpos( $chunk, '</x:xmpmeta>' ) ) !== false )
			{
				$buffer .= substr( $chunk, 0, $index + 12 );
				break;
			}
			elseif ( $foundStart )
			{
				$buffer .= $chunk;
			}
			else
			{
				break;
			}
		}

		$result = fclose( $filePointer );
		assert( '$result === true' );

		return true;
	}
}

////////////////////////////////////////////////////////////////////////////////

?>