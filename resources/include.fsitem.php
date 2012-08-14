<?php

////////////////////////////////////////////////////////////////////////////////

//namespace org\givingtree\fsitems;

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

class FileItem
{
	private $imageId;
	private $imageNumber;

	private $versionId;
	private $versionNumber;

	private $imageWidth;
	private $imageHeight;
	private $imageResolution;

	private $filePath;
	private $fileTitle;
	private $fileExtension;
	private $fileBytes;

	private $isModified = false;

	////////////////////////////////////////////////////////////////////////////

	public function isValid()
	{
		assert( 'isEmpty( $this->imageId )     || isPositiveInt( $this->imageId )' );
		assert( 'isEmpty( $this->imageNumber ) || isPositiveInt( $this->imageNumber )' );

		assert( 'isEmpty( $this->versionId )     || isPositiveInt( $this->versionId )' );
		assert( 'isEmpty( $this->versionNumber ) || isPositiveIntString( $this->versionNumber )' );

		assert( 'isEmpty( $this->imageWidth )      || ( isInt( $this->imageWidth )      && ( $this->imageWidth > -1 ) )' );
		assert( 'isEmpty( $this->imageHeight )     || ( isInt( $this->imageHeight )     && ( $this->imageHeight > -1 ) )' );
		assert( 'isEmpty( $this->imageResolution ) || ( isInt( $this->imageResolution ) && ( $this->imageResolution > -1 ) )' );

		assert( 'isNonEmptyString( $this->filePath )' );
		assert( 'isNonEmptyString( $this->fileTitle )' );
		assert( 'isNonEmptyString( $this->fileExtension )' );

		return true;
	}

	////////////////////////////////////////////////////////////////////////////

	public function fromVersion( $version, $databaseConnection )
	{
		assert( 'isValidVersion( $version )' );

		$image = $version->image();

		$this->imageId = (int)$image->imageId();
		$this->imageNumber = (int)$image->imageNumber();

		$this->versionId = (int)$version->versionId();
		$this->versionNumber = $version->versionNumber();

		$this->imageWidth = (int)$version->versionWidth();
		$this->imageHeight = (int)$version->versionHeight();
		$this->imageResolution = $version->versionResolution();

		$this->filePath = $version->versionFilePath( $databaseConnection );
		$this->fileTitle = $image->imageTitle();
		$this->fileExtension = $version->versionFileExtension();
		$this->fileBytes = $version->versionFileBytes();

		assert( '$this->isValid()' );
	}

	////////////////////////////////////////////////////////////////////////////

	public function createFileItem( $imageNumber, $filePath, $fileTitle, $fileExtension )
	{
		assert( 'isEmpty( $imageNumber ) || isPositiveInt( $imageNumber )' );

		assert( 'isNonEmptyString( $filePath )' );
		assert( 'isNonEmptyString( $fileTitle )' );
		assert( 'isNonEmptyString( $fileExtension )' );

		$this->imageNumber = $imageNumber;

		$this->filePath = $filePath;
		$this->fileTitle = $fileTitle;
		$this->fileExtension = $fileExtension;
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

	////////////////////////////////////////////////////////////////////////////

	public function imageId()
	{
		return $this->imageId;
	}

	////////////////////

	public function setImageId( $imageId )
	{
		assert( 'isPositiveInt( $imageId )' );

		if ( $imageId !== $this->imageId )
		{
			$this->imageId = $imageId;

			$this->isModified = true;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function imageNumber()
	{
		return $this->imageNumber;
	}

	////////////////////

	public function setImageNumber( $imageNumber )
	{
		assert( 'isPositiveInt( $imageNumber )' );

		if ( $imageNumber !== $this->imageNumber )
		{
			$this->imageNumber = $imageNumber;

			$this->isModified = true;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function versionId()
	{
		return $this->versionId;
	}

	////////////////////

	public function setVersionId( $versionId )
	{
		assert( 'isPositiveInt( $versionId )' );

		if ( $versionId !== $this->versionId )
		{
			$this->versionId = $versionId;

			$this->isModified = true;

			assert( '$this->isValid()' );
		}
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
			$this->versionNumber = $versionNumber;

			$this->isModified = true;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function imageWidth()
	{
		return $this->imageWidth;
	}

	////////////////////

	public function imageHeight()
	{
		return $this->imageHeight;
	}

	////////////////////

	public function imageResolution()
	{
		return $this->imageResolution;
	}

	////////////////////

	public function setImageResolution( $imageWidth, $imageHeight )
	{
		assert( 'isInt( $imageWidth )' );
		assert( 'isInt( $imageHeight )' );
		assert( '$imageWidth > -1' );
		assert( '$imageHeight > -1' );

		if ( ( $imageWidth !== $this->imageWidth ) || ( $imageHeight !== $this->imageHeight ) )
		{
			$this->imageWidth = $imageWidth;
			$this->imageHeight = $imageHeight;

			$this->imageResolution = ( $imageWidth * $imageHeight );

			$this->isModified = true;

			assert( '$this->isValid()' );
		}

		return $this->imageResolution;
	}

	////////////////////

	public function updateImageResolution()
	{
		$imageWidth = 0;
		$imageHeight = 0;
		if ( strpos( fileTypesRaw, $this->fileExtension() ) === false )	//	ASCII-only
		{
			$imageSizeArray = getimagesize( $this->filePath() );
			assert( '$imageSizeArray !== false' );
			assert( 'is_numeric( $imageSizeArray[0] )' );
			assert( 'is_numeric( $imageSizeArray[1] )' );

			if ( $imageSizeArray !== false )
			{
				$imageWidth = $imageSizeArray[0];
				$imageHeight = $imageSizeArray[1];
			}
		}

		return $this->setImageResolution( $imageWidth, $imageHeight );
	}

	////////////////////////////////////////////////////////////////////////////

	public function filePath()
	{
		return $this->filePath;
	}

	////////////////////

	public function setFilePath( $filePath )
	{
		assert( 'isNonEmptyString( $filePath )' );

		if ( $filePath !== $this->filePath )
		{
			$this->filePath = $filePath;

			$this->isModified = true;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function fileTitle()
	{
		return $this->fileTitle;
	}

	////////////////////

	public function setFileTitle( $fileTitle )
	{
		assert( 'isNonEmptyString( $fileTitle )' );

		if ( $fileTitle !== $this->fileTitle )
		{
			$this->fileTitle = $fileTitle;

			$this->isModified = true;

			assert( '$this->isValid()' );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function fileExtension()
	{
		return $this->fileExtension;
	}

	////////////////////////////////////////////////////////////////////////////

	public function fileBytes()
	{
		return $this->fileBytes;
	}

	////////////////////

	public function updateFileBytes()
	{
		$fileBytes = filesize( $this->filePath() );
		assert( '$fileBytes !== false' );
		assert( '$fileBytes > 0' );

		if ( $fileBytes === false ) $fileBytes = 0;


		if ( $fileBytes !== $this->fileBytes )
		{
			$this->fileBytes = $fileBytes;

			$this->isModified = true;

			assert( '$this->isValid()' );
		}

		return $fileBytes;
	}
}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

class DirectoryItem
{
	private $userId;
	private $albumId;
	private $albumNumber;
	private $directoryName;
	private $subdirectories;
	private $files;
	private $isTrusted;

	public function __construct( $userId, $albumId, $albumNumber, $directoryPath, $directoryName, $doParse, $isTrusted, $maximumDepth, $databaseConnection )
	{
		assert( 'isPositiveIntString( $userId )' );
		assert( 'isPositiveIntString( $albumNumber )' );
		assert( 'isDirectoryPath( $directoryPath )' );
		assert( '$directoryPath[0] != dot' );
		assert( '$directoryPath !== rootDirectoryPath' );
		assert( 'isBool( $doParse )' );
		assert( '( $doParse === false ) || isDirectory( $directoryPath )' );
		assert( 'isBool( $isTrusted )' );
		assert( 'isInt( $maximumDepth )' );
		assert( '$maximumDepth > -1' );

		$this->userId = $userId;
		$this->albumId = $albumId;
		$this->albumNumber = $albumNumber;
		$this->directoryName = $directoryName;
		$this->isTrusted = $isTrusted;

		$subdirectories = &$this->subdirectories;
		$files = &$this->files;

		$subdirectories = array();
		$files = array();

		if ( $doParse === false ) return;

		$itemNames = opendir( $directoryPath );
		while ( ( $itemName = readdir( $itemNames ) ) !== false )
		{
			assert( 'isNonEmptyString( $itemName )' );

			if ( $itemName[0] === dot ) continue;

			$itemPath = $directoryPath.$itemName;

			$itemName = sanitizedFileName( $itemName, $databaseConnection );

			if ( !isNonEmptyString( $itemName ) ) continue;

			if ( is_dir( $itemPath ) )
			{
				if ( $maximumDepth > 0 ) $subdirectories[$itemName] = new DirectoryItem( $this->userId, $this->albumId, $this->albumNumber, $itemPath.pathSeparator, $itemName, true, $isTrusted, $maximumDepth - 1, $databaseConnection );
			}
			else
			{
				parseFileName( $itemName, $userId, $albumNumber, $fileNumber, $versionNumber, $fileTitle, $fileExtension );

//				echo '$itemName = '.$itemName.newline.'$userId = '.$userId.newline.'$albumNumber = '.$albumNumber.newline.'$fileNumber = '.$fileNumber.newline.'$versionNumber = '.$versionNumber.newline.'$fileTitle = '.$fileTitle.newline.'$fileExtension = '.$fileExtension; exit;

				if ( ( $fileNumber === emptyString ) && ( $fileTitle === emptyString ) ) continue;
				if ( $fileExtension === emptyString ) continue;
				if ( strpos( $isTrusted ? fileTypesTrusted : fileTypesBasic, $fileExtension ) === false ) continue;

				if ( $fileTitle === emptyString )
				{
					$fileTitle = (string)$fileNumber;
					$fileNumber = emptyString;
//					echo '$itemName = '.$itemName.newline.'$userId = '.$userId.newline.'$albumNumber = '.$albumNumber.newline.'$fileNumber = '.$fileNumber.newline.'$versionNumber = '.$versionNumber.newline.'$fileTitle = '.$fileTitle.newline.'$fileExtension = '.$fileExtension; exit;
				}

				$fileItem = new FileItem;
				$fileItem->createFileItem( $fileNumber, $itemPath, $fileTitle, $fileExtension );
				$files[] = $fileItem;
			}
		}

		assert( '$this->isValid()' );
	}

	////////////////////////////////////////////////////////////////////////////

	public function isValid()
	{
		assert( 'isPositiveIntString( $this->userId )' );
		assert( 'isPositiveIntString( $this->albumId )' );
		assert( 'isPositiveIntString( $this->albumNumber )' );
		assert( 'isNonEmptyString( $this->directoryName )' );

		$subdirectories = &$this->subdirectories;
		assert( 'isArray( $subdirectories )' );
		foreach ( $subdirectories as $subdirectoryName => $subdirectory )
		{
			assert( '$subdirectory->isValid()' );
			assert( '$subdirectoryName === $subdirectory->directoryName' );
		}

		$files = &$this->files;
		assert( 'isArray( $files )' );
		foreach ( $files as $fileKey => $file )
		{
			assert( '$file->isValid()' );
		}

		return true;
	}

	////////////////////////////////////////////////////////////////////////////

	public function __toString()
	{
		return $this->directoryName;
	}

	////////////////////////////////////////////////////////////////////////////

	private function subdirectoryCount()
	{
		return count( $this->subdirectories );
	}

	////////////////////////////////////////////////////////////////////////////

	private function fileCount()
	{
		return count( $this->files );
	}

	////////////////////////////////////////////////////////////////////////////

	private function count()
	{
		return ( $this->subdirectoryCount() + $this->fileCount() );
	}

	////////////////////////////////////////////////////////////////////////////

	public function isEmpty()
	{
		return ( $this->count() === 0 );
	}

	////////////////////////////////////////////////////////////////////////////

	public function subdirectory( $subdirectoryName )
	{
		assert( 'isNonEmptyString( $subdirectoryName )' );
		assert( 'isNotDirectoryPath( $subdirectoryName )' );
		assert( '$this->isValid()' );

		return array_key_exists( $subdirectoryName, $this->subdirectories ) ? $this->subdirectories[$subdirectoryName] : false;
	}

	////////////////////////////////////////////////////////////////////////////

	public function addSubdirectory( $subdirectoryName )
	{
		assert( 'isNonEmptyString( $subdirectoryName )' );
		assert( 'isNotDirectoryPath( $subdirectoryName )' );
		assert( '$this->isValid()' );

		$this->subdirectories[$subdirectoryName] = $subdirectory = new DirectoryItem( $this->userId, $this->albumId, $this->albumNumber, $subdirectoryName.pathSeparator, $subdirectoryName, false, $this->isTrusted, 1, null );

		return $subdirectory;
	}

	////////////////////////////////////////////////////////////////////////////

	public function file( $fileKey )
	{
		assert( 'isInt( $fileKey )' );
		assert( '$fileKey > -1' );

		return $this->files[$fileKey];
	}

	////////////////////////////////////////////////////////////////////////////

	public function addFile( $file )
	{
		assert( 'isNotNull( $file )' );
		assert( 'is_a( $file, \'FileItem\' )' );
		assert( '$this->isValid()' );

		$this->files[] = $file;

		return $file;
	}

	////////////////////////////////////////////////////////////////////////////

	public function fileKey( $fileNumber )
	{
		assert( 'isPositiveInt( $fileNumber )' );
		assert( '$this->isValid()' );

		$files = &$this->files;
		foreach ( $files as $fileKey => $file ) if ( $file->imageNumber() === $fileNumber ) return $fileKey;

		return false;
	}

	////////////////////////////////////////////////////////////////////////////

	public function shiftUp()
	{
		if ( ( $this->subdirectoryCount() !== 1 ) || ( $this->fileCount() !== 0 ) ) return;

		foreach ( $this->subdirectories as $subdirectory )
		{
			$this->subdirectories = $subdirectory->subdirectories;
			$this->files = $subdirectory->files;
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function deleteNestedSubdirectories( $maximumDepth )
	{
		assert( 'isInt( $maximumDepth )' );
		assert( '$maximumDepth > -1' );

		$subdirectories = &$this->subdirectories;
		foreach ( $subdirectories as $subdirectoryName => $subdirectory )
		{
			if ( $maximumDepth === 0 )
			{
				unset( $subdirectories[$subdirectoryName] );
			}
			else
			{
				$subdirectory->deleteNestedSubdirectories( $maximumDepth - 1 );
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function organizeLooseFiles()
	{
		$files = &$this->files;
		foreach ( $files as $fileKey => $file )
		{
			assert( 'isInt( $fileKey )' );

			$fileExtension = $file->fileExtension();

			$fileTypeIsBasic = ( strpos( fileTypesBasic, $fileExtension ) !== false );	//	ASCII-only

			$subdirectoryName = ( $fileTypeIsBasic ? directoryNameFull : strtoupper( $fileExtension ) );	//	ASCII-only

			$subdirectory = $this->subdirectory( $subdirectoryName );

			if ( $subdirectory === false ) $subdirectory = $this->subdirectories[$subdirectoryName] = new DirectoryItem( $this->userId, $this->albumId, $this->albumNumber, $subdirectoryName.pathSeparator, $subdirectoryName, false, true, 1, null );

			$subdirectory->files[$fileKey] = $file;

			unset( $this->files[$fileKey] );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function masterSubdirectory()
	{
		////////////////////////////////////////////////////////////////////////////
		//	Determine which is the "master" subdirectory on which all other
		//	titles and numbers will be based. First, find the subdirectory with
		//	the most files. If one subdirectory has more files than the others,
		//	assume that's the master subdirectory. If multiple subdirectories
		//	have equal numbers of files, look at the resolution of each file
		//	and assume the subdirectory with the highest median resolution is
		//	the master. If multiple	subdirectories have equal resolutions, look
		//	at the size of each file and assume the subdirectory with the
		//	highest median size is the master. If all else is equal go by the
		//	name of the subdirectory. Barring any obvious candidate, just pick
		//	the first in the list of candidates.

		//	Look for candidates based purely on image count:
		$masterSubdirectoryCandidates = array();
		$masterSubdirectoryFileCount = 0;
		$subdirectories = &$this->subdirectories;
		foreach ( $subdirectories as $subdirectoryName => $subdirectory )
		{
			//	How many files does this subdirectory contain? (Because we
			//	sanitized the directory structure early, we -know- that these
			//	subdirectories contain no subdirectories of their own, and that
			//	each of its files is a legitimate image file.)
			$fileCount = $subdirectory->fileCount();
			assert( '$fileCount > 0' );

			//	Trap any that are greater than or equal to:
			if ( $fileCount < $masterSubdirectoryFileCount ) continue;

			if ( $fileCount > $masterSubdirectoryFileCount )
			{
				$masterSubdirectoryCandidates = array();
				$masterSubdirectoryCandidates[] = $subdirectory;
				$masterSubdirectoryFileCount = $fileCount;
			}
			else
			{
				$masterSubdirectoryCandidates[] = $subdirectory;
			}
		}

		//	Either we have no subdirectories or they don't contain files:
		$masterSubdirectoryCandidatesCount = count( $masterSubdirectoryCandidates );
		if ( $masterSubdirectoryCandidatesCount == 0 ) return false;

		//	If we found exactly one candidate then we're done:
		if ( count( $masterSubdirectoryCandidates ) === 1 ) return $masterSubdirectoryCandidates[0];

		//	Otherwise, look for candidates based on numbered file count:
		$newMasterSubdirectoryCandidates = array();
		$masterSubdirectoryFileNumberCount = 0;
		foreach ( $masterSubdirectoryCandidates as $subdirectory )
		{
			$fileNumberCount = 0;
			foreach ( $subdirectory->files as $file ) if ( isNotEmpty( $file->imageNumber( ) ) ) $fileNumberCount++;

			//	Trap any that are greater than or equal to:
			if ( $fileNumberCount < $masterSubdirectoryFileNumberCount ) continue;

			if ( $fileNumberCount > $masterSubdirectoryFileNumberCount )
			{
				$newMasterSubdirectoryCandidates = array();
				$newMasterSubdirectoryCandidates[] = $subdirectory;
				$masterSubdirectoryFileNumberCount = $fileNumberCount;
			}
			else
			{
				$newMasterSubdirectoryCandidates[] = $subdirectory;
			}
		}
		assert( 'count( $newMasterSubdirectoryCandidates ) > 0' );
		if ( count( $newMasterSubdirectoryCandidates ) === 1 ) return $newMasterSubdirectoryCandidates[0];

		//	Look for candidates based on median resolution:
		$masterSubdirectoryCandidates = $newMasterSubdirectoryCandidates;
		$newMasterSubdirectoryCandidates = array();
		$masterSubdirectoryMedianResolution = 0;
		foreach ( $masterSubdirectoryCandidates as $subdirectory )
		{
			$fileResolutions = array();
			foreach ( $subdirectory->files as $file )
			{
				//	Cache the file resolution so we don't have to look it up
				//	again, since this is an extremely expensive operation:
				$fileResolution = $file->updateImageResolution();

				//	If the resolution is invalid, don't bother counting it:
				if ( $fileResolution === 0 ) continue;

				//	Add it to the list of known file resolutions:
				$fileResolutions[] = $fileResolution;
			}

			//	How many valid files did we have?
			$fileResolutionsCount = count( $fileResolutions );

			//	Compute the median file resolution:
			$medianFileResolution = ( $fileResolutionsCount > 0 ? median( $fileResolutions, $fileResolutionsCount ) : 0 );

			//	Trap any that are greater than or equal to:
			if ( $medianFileResolution < $masterSubdirectoryFileNumberCount ) continue;

			if ( $medianFileResolution > $masterSubdirectoryFileNumberCount )
			{
				$newMasterSubdirectoryCandidates = array();
				$newMasterSubdirectoryCandidates[] = $subdirectory;
				$masterSubdirectoryMedianResolution = $medianFileResolution;
			}
			else
			{
				$newMasterSubdirectoryCandidates[] = $subdirectory;
			}
		}
		assert( 'count( $newMasterSubdirectoryCandidates ) > 0' );
		if ( count( $newMasterSubdirectoryCandidates ) === 1 ) return $newMasterSubdirectoryCandidates[0];

		//	Look for candidates based on median file size:
		$masterSubdirectoryCandidates = $newMasterSubdirectoryCandidates;
		$newMasterSubdirectoryCandidates = array();
		$masterSubdirectoryMedianBytes = 0;
		foreach ( $masterSubdirectoryCandidates as $subdirectory )
		{
			//	Find the size of each file in the subdirectory:
			$fileBytes = array();
			foreach ( $subdirectory->files as $file ) $fileBytes[] = $file->updateFileBytes();

			//	How many valid files did we have?
			$fileBytesCount = count( $fileBytes );

			//	Compute the median file bytes:
			$medianFileBytes = ( $fileBytesCount > 0 ? median( $fileBytes, $fileBytesCount ) : 0 );

			//	Trap any that are greater than or equal to:
			if ( $medianFileBytes < $masterSubdirectoryFileNumberCount ) continue;

			if ( $medianFileBytes > $masterSubdirectoryFileNumberCount )
			{
				$newMasterSubdirectoryCandidates = array();
				$newMasterSubdirectoryCandidates[] = $subdirectory;
				$masterSubdirectoryMedianBytes = $medianFileBytes;
			}
			else
			{
				$newMasterSubdirectoryCandidates[] = $subdirectory;
			}
		}
		assert( 'count( $newMasterSubdirectoryCandidates ) > 0' );
		if ( count( $newMasterSubdirectoryCandidates ) === 1 ) return $newMasterSubdirectoryCandidates[0];

		//	Look for candidates based on name:
		$masterSubdirectoryCandidateNames = array( directoryNameCR2, directoryNameRAW, directoryNameDNG, directoryNameOriginal, directoryNameOriginals, directoryNameUnedited, directoryNameUncropped, directoryNameFull, directoryNameLarge, directoryNameMedium, directoryNameSmall, directoryNameEdited );
		$masterSubdirectoryCandidates = $newMasterSubdirectoryCandidates;
		$newMasterSubdirectoryCandidates = array();
		foreach ( $masterSubdirectoryCandidates as $subdirectory )
		{
			//	Get just the name:
			$versionDirectoryName = $versionDirectory->directoryName;

			//	If it's in the list, we're done:
			if ( in_arrayi( $subdirectory->directoryName, $masterSubdirectoryCandidateNames ) ) $newMasterSubdirectoryCandidates[] = $subdirectory;
		}
		assert( 'count( $newMasterSubdirectoryCandidates ) > 0' );
		if ( count( $newMasterSubdirectoryCandidates ) === 1 ) return $newMasterSubdirectoryCandidates[0];

		//	If all else fails, simply return the first one:
		return $newMasterSubdirectoryCandidates[0];
	}

	////////////////////////////////////////////////////////////////////////////

	public function synchronizeSubdirectories( $masterSubdirectory )
	{
		assert( '$masterSubdirectory->isValid()' );

		$masterFileNumbers = array();
		$masterFileTitles = array();
		$masterFiles = &$masterSubdirectory->files;
		foreach ( $masterFiles as $masterFile )
		{
			$masterFileNumber = $masterFile->imageNumber();
//			echo '$masterFileNumber = '.$masterFileNumber.newline;
			assert( 'isEmptyString( $masterFileNumber ) || isPositiveInt( $masterFileNumber )' );

			$masterFileTitle = $masterFile->fileTitle();
			assert( 'isNonEmptyString( $masterFileTitle )' );

			$masterFileLabels[] = ( $masterFileNumber.space.$masterFileTitle );
			$masterFileNumbers[] = $masterFileNumber;
			$masterFileTitles[] = $masterFileTitle;
		}

		$subdirectories = &$this->subdirectories;
		foreach ( $subdirectories as $subdirectory )
		{
			if ( $subdirectory === $masterSubdirectory ) continue;

			$subdirectory->synchronizeFiles( $masterFileLabels, $masterFileNumbers, $masterFileTitles );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	private function synchronizeFiles( $masterFileLabels, $masterFileNumbers, $masterFileTitles )
	{
		assert( 'isNonEmptyArray( $masterFileLabels )' );
		assert( 'isNonEmptyArray( $masterFileNumbers )' );
		assert( 'isNonEmptyArray( $masterFileTitles )' );
		assert( 'count( $masterFileLabels ) === count( $masterFileNumbers )' );
		assert( 'count( $masterFileLabels ) === count( $masterFileTitles )' );

/*		$full = ( $this->directoryName == 'Full' );
		if ( $full )
		{
			echo '$masterFileLabels:'.newline; print_r( $masterFileLabels );
			echo '$masterFileNumbers:'.newline; print_r( $masterFileNumbers );
			echo '$masterFileTitles:'.newline; print_r( $masterFileTitles );
		}*/

//		$files = &$this->files;
		$files = &$this->files;
		$newFiles = array();
		foreach ( $files as $fileKey => $file )
		{
			$masterKey = $this->masterKey( $file, $masterFileLabels, $masterFileNumbers, $masterFileTitles );
//			if ( $full ) { echo '$masterKey = '.$masterKey.' for $fileKey = '.$fileKey.' and $file = ';print_r( $file ); }

			if ( $masterKey === false )
			{
//				unset( $files[$fileKey] );
//				unset( $this->files[$fileKey] );
				continue;
			}

			$file->setImageNumber( $masterFileNumbers[$masterKey] );
			$file->setfileTitle( $masterFileTitles[$masterKey] );
//			if ( $full ) { echo '$files before unset = '; print_r( $files ); }
//			if ( $full ) { echo '$this->files before unset = '; print_r( $this->files ); }
//			unset( $this->files[$fileKey] );
//			$this->files[$masterKey] = $file;
			$newFiles[$masterKey] = $file;
//			if ( $full ) { echo '$this->files after set = '; print_r( $this->files ); }
//			if ( $full ) { echo '$files after unset = '; print_r( $files ); }

			unset( $masterFileLabels[$masterKey] );
			unset( $masterFileNumbers[$masterKey] );
			unset( $masterFileTitles[$masterKey] );
		}
		$this->files = $newFiles;
//		if ( $full ) { echo '$newFiles = ';print_r( $newFiles ); }
	}

	////////////////////////////////////////////////////////////////////////////

	private function masterKey( $file, $masterFileLabels, $masterFileNumbers, $masterFileTitles )
	{
		assert( 'isArray( $masterFileLabels )' );
		assert( 'isArray( $masterFileNumbers )' );
		assert( 'isArray( $masterFileTitles )' );
		assert( 'count( $masterFileLabels ) === count( $masterFileNumbers )' );
		assert( 'count( $masterFileLabels ) === count( $masterFileTitles )' );

		//	Find the matching master key using a variety of methods...

		$fileNumber = $file->imageNumber();
		$fileTitle = $file->fileTitle();

		$fileLabel = $fileNumber.space.$fileTitle;

/*		$full = ( $this->directoryName == 'Full' );
		if ( $full )
		{
			echo 'Full $fileNUmber = '.$fileNumber.newline;
			echo 'Full $fileTitle = '.$fileTitle.newline;
			echo 'Full $fileLabel = '.$fileLabel.newline;
		}*/

		//	1. Case-sensitive search of labels:
		$masterKey = array_search( $fileLabel, $masterFileLabels );	//	ASCII-only?
		if ( $masterKey === false )
		{
			//	2. Case-INsensitive search of labels:
			$masterKey = array_isearch( $fileLabel, $masterFileLabels );
			if ( $masterKey === false )
			{
				//	3.	Case-sensitive search of titles:
				$masterKey = array_search( $fileTitle, $masterFileTitles );	//	ASCII-only?
				if ( $masterKey === false )
				{
					//	4. Case-INsensitve search of titles:
					$masterKey = array_isearch( $fileTitle, $masterFileTitles );
					if ( ( $masterKey === false ) && ( $fileNumber !== emptyString ) && ( $fileTitle === emptyString ) )
					{
						//	5. Search image numbers:
						$masterKey = array_search( $fileNumber, $masterFileNumbers );
//						if ( $full ) echo 'punted on $fileNumber'.newline;
					}
					else
					{
//						if ( $full ) echo 'matched nocase $fileTitle'.newline;
					}
				}
				else
				{
//					if ( $full ) echo 'matched case $fileTitle'.newline;
				}
			}
			else
			{
//				if ( $full ) echo 'matched nocase $fileLabel'.newline;
			}
		}
		else
		{
//			if ( $full ) echo 'matched case $fileLabel'.newline;
		}
//		if ( $full ) echo '$masterKey = '.$masterKey.doubleNewline;

		//	Return the master key, if any:
		return $masterKey;
	}

	////////////////////////////////////////////////////////////////////////////

	public function synchronizeToDestination( $masterSubdirectory, $destinationDirectory )
	{
		assert( '$masterSubdirectory->isValid()' );

		//	Assemble the list of files in the destination:
		$destinationFileLabels = array();
		$destinationFileNumbers = array();
		$destinationFileTitles = array();
		$destinationSubdirectories = &$destinationDirectory->subdirectories;
		foreach ( $destinationSubdirectories as $destinationSubdirectory )
		{
			$destinationSubdirectoryFiles = &$destinationSubdirectory->files;
			foreach ( $destinationSubdirectoryFiles as $destinationFile )
			{
				$destinationFileNumber = $destinationFile->imageNumber();
				$destinationFileTitle = $destinationFile->fileTitle();

				$destinationFileLabels[$destinationFileNumber] = $destinationFileNumber.space.$destinationFileTitle;
				$destinationFileNumbers[$destinationFileNumber] = $destinationFileNumber;
				$destinationFileTitles[$destinationFileNumber] = $destinationFileTitle;
			}
		}

//		echo '$destinationFileLabels ='.newline;print_r( $destinationFileLabels );
//		echo '$destinationFileNumbers ='.newline;print_r( $destinationFileNumbers );
//		echo '$destinationFileTitles ='.newline;print_r( $destinationFileTitles );

		//	Process each of the files in the master subdirectory:
		$unmatchedFiles = array();
		$masterFiles = &$masterSubdirectory->files;
//		echo '$masterFiles ='.newline;print_r( $masterFiles );
		foreach ( $masterFiles as $masterKey => $masterFile )
		{
//			echo '$masterKey => $masterFile = '.$masterKey.' => ';print_r( $masterFile );
			//	Find the matching destination key:
			$destinationKey = $this->masterKey( $masterFile, $destinationFileLabels, $destinationFileNumbers, $destinationFileTitles );
//			echo '$destinationKey = '.$destinationKey.newline;

			//	If this file doesn't yet exist in the list of destination files, cache it:
			if ( $destinationKey === false )
			{
				$unmatchedFiles[$masterKey] = $masterFile->fileTitle();
//				echo 'Unmatched'.newline;
				continue;
			}

//			echo '$masterFile->setImageNumber = '.$destinationFileNumbers[$destinationKey].newline;
//			echo '$masterFile->setfileTitle = '.$destinationFileTitles[$destinationKey].newline;

			//	If the file label has changed, rename it:
			$masterFile->setImageNumber( $destinationFileNumbers[$destinationKey] );
			$masterFile->setfileTitle( $destinationFileTitles[$destinationKey] );

			//	Remove these keys to ensure no more matches are found:
			unset( $destinationFileLabels[$destinationKey] );
			unset( $destinationFileNumbers[$destinationKey] );
			unset( $destinationFileTitles[$destinationKey] );
		}

//		echo 'new $destinationFileLabels ='.newline;print_r( $destinationFileLabels );
//		echo 'new $destinationFileNumbers ='.newline;print_r( $destinationFileNumbers );
//		echo 'new $destinationFileTitles ='.newline;print_r( $destinationFileTitles );

		//	Sort any unmatched files. Currently this is done by name; ideally
		//	this should be done by timestamp extracted from embedded EXIF data:
//		echo 'pre-sort $unmatchedFiles ='.newline;print_r( $unmatchedFiles );
		$result = natcasesort( $unmatchedFiles );	//	ASCII-only
//		echo 'post-sort $unmatchedFiles ='.newline;print_r( $unmatchedFiles );
		assert( '$result === true' );

		//	What's the next highest number?
		$nextDestinationFileNumber = ( count( $destinationFileNumbers ) + 1 );
//		echo 'nextDestinationFileNumber = '.$nextDestinationFileNumber.doubleNewline;

		//	Rename each one:
		foreach ( $unmatchedFiles as $fileKey => $fileTitle )
		{
			//	Rename:
			$this->renameSynchronizedFiles( $fileKey, $nextDestinationFileNumber, $fileTitle );

			//	Increment the "next number" counter:
			$nextDestinationFileNumber++;
		}
	}

	////////////////////////////////////////////////////////////////////////////

	private function renameSynchronizedFiles( $fileKey, $newFileNumber, $newFileTitle )
	{
		assert( 'isInt( $fileKey )' );
		assert( '$fileKey > -1' );
		assert( 'isInt( $newFileNumber )' );
		assert( '$newFileNumber > 0' );
		assert( 'isString( $newFileTitle )' );

//		echo '$fileKey = '.$fileKey.newline;
//		echo '$newFileNumber = '.$newFileNumber.newline;
//		echo '$newFileTitle = '.$newFileTitle.newline;

		$subdirectories = &$this->subdirectories;
		foreach ( $subdirectories as $subdirectory )
		{
			if ( isset( $subdirectory->files[$fileKey] ) )
			{
				$file = $subdirectory->files[$fileKey];
//				echo '$file before'.newline;print_r( $file );
				$file->setImageNumber( $newFileNumber );
				$file->setfileTitle( $newFileTitle );
//				echo '$file after'.newline;print_r( $file );echo doubleNewline;
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function mergeIntoDirectory( $userId, $albumNumber, $destinationDirectory, $destinationDirectoryPath, $octalPermissionsFlag, &$newFileNumbers, &$newFileTitles, &$updatedFileNumbers, &$newFiles, &$updatedFiles, &$versionLabels )
	{
		assert( 'isNonEmptyString( $userId )' );
		assert( 'isNonEmptyString( $albumNumber )' );
		assert( '$destinationDirectory->isValid()' );
		assert( 'isDirectoryPath( $destinationDirectoryPath )' );
		assert( '$destinationDirectoryPath != rootDirectoryPath' );
		assert( 'isArray( $newFileNumbers )' );
		assert( 'isArray( $newFileTitles )' );
		assert( 'isArray( $updatedFileNumbers )' );
		assert( 'isArray( $newFiles )' );
		assert( 'isArray( $updatedFiles )' );
		assert( 'isValidVersionLabel( $versionLabels )' );

		//	Protect ourself from really stupid mistakes:
		if ( $destinationDirectoryPath == rootDirectoryPath ) return false;

		//	If this directory doesn't yet exist, create it:
		if ( !file_exists( $destinationDirectoryPath ) )
		{
			$result = createDirectory( $destinationDirectoryPath, $octalPermissionsFlag );
			assert( '$result === true' );
		}

		//	Merge each of our subdirectories into the destination directory:
		$subdirectories = &$this->subdirectories;
		foreach ( $subdirectories as $subdirectory )
		{
			//	If the destination directory doesn't yet contain this subdirectory, add it:
			$subdirectoryName = $subdirectory->directoryName;
			$matchingDestinationSubdirectory = $destinationDirectory->subdirectory( $subdirectoryName );
			if ( $matchingDestinationSubdirectory === false )
			{
				$destinationDirectory->subdirectories[$subdirectoryName] = $matchingDestinationSubdirectory = new DirectoryItem( $this->userId, $this->albumId, $this->albumNumber, $destinationDirectoryPath.$subdirectoryName.pathSeparator, $subdirectoryName, false, true, 1, null );
			}

			//	Get the version number corresponding to this subdirectory:
			$versionNumber = $versionLabels->versionNumber( $subdirectoryName );
			assert( 'isPositiveIntString( $versionNumber )' );

			//	Merge this subdirectory into the corresponding destination subdirectory:
			$subdirectory->mergeIntoDirectory( $userId, $albumNumber, $matchingDestinationSubdirectory, $destinationDirectoryPath.$subdirectoryName.pathSeparator, $octalPermissionsFlag, $newFileNumbers, $newFileTitles, $updatedFileNumbers, $newFiles, $updatedFiles, $versionLabels );
		}

		//	Merge each of our files into the destination directory:
		$files = &$this->files;

		//	If we have at least one, fetch our version number:
		if ( count( $files ) > 0 )
		{
			$versionNumber = $versionLabels->versionNumber( $this->directoryName );
		}

		foreach ( $files as $file )
		{
			//	Extract some information:
			$fileNumber = $file->imageNumber();
			assert( 'isPositiveInt( $fileNumber )' );

			$fileTitle = $file->fileTitle();
			assert( 'isNonEmptyString( $fileTitle )' );

			$fileExtension = $file->fileExtension();
			assert( 'isNonEmptyString( $fileTitle )' );

			//	Signal that at least one version of this file is being updated, so we'll need to regenerate its thumbnail:
			if ( !in_array( $fileNumber, $updatedFileNumbers ) ) $updatedFileNumbers[] = $fileNumber;

			//	Find the same file object in the destination directory:
			$matchingDestinationFileKey = $destinationDirectory->fileKey( $fileNumber );

			//	If it doesn't yet exist in the destination directory, add it:
			$existingFilePath = emptyString;
			if ( $matchingDestinationFileKey === false )
			{
				//	Add the file to the directory:
				$destinationDirectory->files[] = $file;

				//	Add the file to the list of new files:
				$newFiles[] = $file;

				//	If this image number doesn't yet exist, add it:
				if ( !in_array( $fileNumber, $newFileNumbers ) )
				{
					$newFileNumbers[] = $fileNumber;
					$newFileTitles[] = $fileTitle;
				}
			}

			//	Else, move it:
			else
			{
				//	Grab the existing file:
				$existingFile = $destinationDirectory->files[$matchingDestinationFileKey];

				//	Grab the existing file's path:
				$existingFilePath = $existingFile->filePath();

				//	Copy some information from it:
				$file->setImageId( $existingFile->imageId() );
				$file->setVersionId( $existingFile->versionId() );

				//	Replace the in-memory object:
				$destinationDirectory->files[$matchingDestinationFileKey] = $file;

				//	Add the file to the list of updated files:
				$updatedFiles[] = $file;
			}

			//	Create the path to the file's new destination:
			$destinationFilePath = $destinationDirectoryPath.canonicalFileName( $userId, $albumNumber, $fileNumber, $versionNumber, $fileTitle, $fileExtension );

			//	Delete the existing file if the file names don't match exactly:
			if ( isNonEmptyString( $existingFilePath ) && ( $existingFilePath !== $destinationFilePath ) )
			{
				deletePath( $existingFilePath );
			}

			//	Move the new file:
			$result = rename( $file->filePath(), $destinationFilePath );
			assert( '$result === true' );

			//	Update the file's path, just for the sake of completeness:
			$file->setFilePath( $destinationFilePath );

			//	Make sure we've properly set the file size and resolution:
			if ( $file->fileBytes() === null )
			{
				$file->updateFileBytes();
			}

			if ( $file->imageResolution() === null )
			{
				$file->updateImageResolution();
			}

			//	Set the version number:
			$file->setVersionNumber( $versionNumber );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	public function masterFile( $fileNumber )
	{
		assert( 'isPositiveInt( $fileNumber )' );

		$masterSubdirectoryNames = array( directoryNameEdited, directoryNameCropped, directoryNameFull, directoryNameLarge, directoryNameOriginal, directoryNameOriginals, directoryNameUncropped, directoryNameUnedited );

		foreach ( $masterSubdirectoryNames as $masterSubdirectoryName )
		{
			$masterSubdirectory = $this->subdirectory( $masterSubdirectoryName );

			if ( $masterSubdirectory === false ) continue;

			$masterFileKey = $masterSubdirectory->fileKey( $fileNumber );

			if ( $masterFileKey !== false ) return $masterSubdirectory->files[$masterFileKey];
		}

		$masterFile = null;
		$this->getMasterFile( $fileNumber, $masterFile );

		return $masterFile;
	}

	////////////////////////////////////////////////////////////////////////////

	private function getMasterFile( $fileNumber, &$masterFile )
	{
		assert( 'isNull( $masterFile ) || isObject( $masterFile )' );

		foreach ( $this->subdirectories as &$subdirectory )
		{
			if ( $subdirectory->directoryName !== directoryNameThumbnail ) $subdirectory->getMasterFile( $fileNumber, $masterFile );
		}

		foreach ( $this->files as &$file )
		{
			if ( $file->imageNumber() === $fileNumber )
			{
				$fileTypeIsNotBasic = ( strpos( fileTypesBasic, $file->fileExtension() ) === false );	//	ASCII-only
				if ( $fileTypeIsNotBasic ) continue;
				if ( isNull( $masterFile ) || $file->imageResolution() > $masterFile->imageResolution() )
				{
					$masterFile = $file;
				}

				return;
			}
		}
	}
}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

?>