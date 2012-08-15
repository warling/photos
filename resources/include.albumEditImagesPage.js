
/*
var debug = true;

if ( debug )
{
	function assert( e )
	{
		try
		{
			if ( !( typeof e === 'string' ? eval( e ) : e ) ) throw e;
		}
		catch ( e )
		{
			alert( 'assertion failed: ' + e );
		}
	}
}
else
{
	function assert( e )
	{
	}
}*/

$.fn.toEm = function(settings){
	settings = jQuery.extend({
		scope: 'body'
	}, settings);
	var that = parseInt(this[0],10);
	var scopeTest = jQuery('<div style="display: none; font-size: 1em; margin: 0; padding:0; height: auto; line-height: 1; border:0;">&nbsp;</div>').appendTo(settings.scope);
	var scopeVal = scopeTest.height();
	scopeTest.remove();
	return (that / scopeVal).toFixed(8) + 'em';
};


$.fn.toPx = function(settings){
	settings = jQuery.extend({
		scope: 'body'
	}, settings);
	var that = parseFloat(this[0]);
	var scopeTest = jQuery('<div style="display: none; font-size: 1em; margin: 0; padding:0; height: auto; line-height: 1; border:0;">&nbsp;</div>').appendTo(settings.scope);
	var scopeVal = scopeTest.height();
	scopeTest.remove();
	return Math.round(that * scopeVal) + 'px';
};


function isNumeric( n )
{
  return !isNaN( parseFloat( n ) ) && isFinite( n );
}

////////////////////////////////////////////////////////////////////////////////

var AlbumEditImagesPage = new function()
{
	////////////////////////////////////////////////////////////////////////////

	this.$body = undefined;
	this.$window = undefined;
	this.$albumEditImagesPage = undefined;
	this.$imageThumbnailsGroup = undefined;
	this.$imageThumbnailsList = undefined;
	this.$imageThumbnailsControlsGroup = undefined;
	this.$imageDataGroup = undefined;
	this.$imageTitleControl = undefined;
	this.$imageDescriptionControl = undefined;
	this.$imageTagsControl = undefined;
	this.$imageRatingControl1 = undefined;
	this.$imageRatingControl2 = undefined;
	this.$imageRatingControl3 = undefined;
	this.$imageRatingControl4 = undefined;
	this.$imageRatingControl5 = undefined;
	this.$albumThumbnailControl = undefined;
	this.$imagePhotographerControl = undefined;
	this.$imageTimestampCreationControl = undefined;
	this.$imageAddressControl = undefined;
	this.$imageAddressButtonGroup = undefined;
	this.$imageAddressButtonStandardize = undefined;
	this.$imageAddressButtonApplyToAll = undefined;
	this.$imageLatitudeControl = undefined;
	this.$imageLongitudeControl = undefined;
	this.$imageAltitudeControl = undefined;
	this.$imageHeadingControl = undefined;
	this.$imageExifGroup = undefined;
	this.$imageExifSummaryGroup = undefined;
	this.$imageExifSummaryControl = undefined;
	this.$imageExifCompleteGroup = undefined;
	this.$imageExifCompleteControl = undefined;
	this.$imageMapControl = undefined;
	this.$imageRemainderGroup = undefined;
	this.$imageMagnifiedContainer = undefined;
	this.$imageMagnifiedImage = undefined;
	this.$busyCursor = undefined;

	this.server = undefined;

	var geocoder = undefined;
	var elevator = undefined;
	var marker = undefined;

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	this.Keyboard = new function()
	{
		this.TAB = 9;
		this.CLEAR = 12;
		this.ENTER = 13;
		this.SHIFT = 16;
		this.CTRL = 17;
		this.SPACE = 32;
		this.PAGE_UP = 33;
		this.PAGE_DOWN = 34;
		this.END = 35;
		this.HOME = 36;
		this.ARROW_UP = 38;
		this.ARROW_DOWN = 40;
		this.ARROW_LEFT = 37;
		this.ARROW_RIGHT = 39;
		this.DELETE = 46;
		this.COMMAND = 224;		//	Firefox and Opera generate a single Command key; Webkit generates specific left and right command keys
		this.COMMAND_LEFT = 91;
		this.COMMAND_RIGHT = 93;
		this.PLUS = 61;
		this.MINUS = 45;
		this.MINUS_CTRL = 31;

		this.repeating = false;
		this.ctrlPressed = false;
		this.shiftPressed = false;
		this.pageUpPressed = false;
		this.pageDownPressed = false;
		this.arrowUpPressed = false;
		this.arrowDownPressed = false;
		this.arrowLeftPressed = false;
		this.arrowRightPressed = false;
		this.deletePressed = false;
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	this.ImageThumbnailsList = new function()
	{
		////////////////////////////////////////////////////////////////////////

		this.$mostRecentTarget = undefined;

		////////////////////////////////////////////////////////////////////////

		this.onFocusIn = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					//	Wrap the raw event target in a jQuery object:
					var $target = $( e.target );

					//	If the target has changed, un-highlight the old target:
					if ( ( $mostRecentTarget != undefined ) && ( $mostRecentTarget[0] != e.target ) )
					{
						$target.unbind( { 'focusout' : onFocusOut, 'keydown' : onKeyDown, 'keyup' : onKeyUp, 'keypress' : onKeyPress } );

						$mostRecentTarget.fadeTo( 'fast', 0.5 );

						$mostRecentTarget.removeClass( 'nofocus' );
					}

					//	If the target has changed, highlight the new target:
					if ( ( $mostRecentTarget == undefined ) || ( $mostRecentTarget[0] != e.target ) )
					{
						//	Wrap the raw Javascript element in jQuery element and store it:
						$mostRecentTarget = $target;

						//	Make it fully opaque:
						$target.fadeTo( 'fast', 1 );

						$target.addClass( 'nofocus' );

						//	Bind various keystroke handlers to it:
						$target.bind( { 'focusout' : onFocusOut, 'keydown' : onKeyDown, 'keyup' : onKeyUp, 'keypress' : onKeyPress } );

						//	Ensure the target has focus:
						$target.focus();

						if ( $imageDescriptionControl.attr( 'disabled' ) )
						{
							$imageDescriptionControl.removeAttr( 'disabled' );
							$imageTagsControl.removeAttr( 'disabled' );
							$imageAddressButtonStandardize.removeAttr( 'disabled' );
							$imageAddressButtonApplyToAll.removeAttr( 'disabled' );
							$( '#imageDataGroup input' ).removeAttr( 'disabled' );
							$( '#imageRatingField div' ).removeAttr( 'disabled' );
						}

						//	Grab the data associated with this image:
						var image = $target.data( 'image' );

						//	Transfer image data into the various fields:
						$imageTitleControl.val( image.imageTitle );
						$imageDescriptionControl.val( image.imageDescription );
						$imageTagsControl.val( image.imageTags );
						$imagePhotographerControl.val( image.imagePhotographer );
						$imageTimestampCreationControl.val( image.imageTimestampCreation );
						$imageAddressControl.val( image.imageAddress );
						$imageLatitudeControl.val( image.imageLatitude );
						$imageLongitudeControl.val( image.imageLongitude );
						$imageAltitudeControl.val( image.imageAltitude );
						$imageHeadingControl.val( image.imageHeading );

						//	Check or uncheck:
						$albumThumbnailControl.attr( 'checked', image.imageId == Album.albumThumbnailImageId );

						//	Set the rating; this is complicated, so we shunt it off into another function:
						ImageRatingControl.setRating( image.imageRating );

						//	If this is the first time we've displayed this photo,
						//	turn the raw EXIF XML data into HTML:
						if ( image.imageExifIsStillXml )
						{
							image.imageExifSummary = $( ImageExifControl.summaryXmlToHtml( image.imageExifSummary ) );
							image.imageExifComplete = $( ImageExifControl.completeXmlToHtml( image.imageExifComplete ) );
							image.imageExifIsStillXml = false;
						}

						//	Clear the existing data:
						$imageExifSummaryControl.empty();
						$imageExifCompleteControl.empty();

						//	Add the new data:
						$imageExifSummaryControl.append( image.imageExifSummary );
						$imageExifCompleteControl.append( image.imageExifComplete );

						$imageExifGroup.css( 'display', 'block' );

						//	Display the GPS location on the map:
						ImageMapControl.panTo( new google.maps.LatLng( image.imageLatitude, image.imageLongitude ) );

						//	Force into view:
						scrollIntoView( $target );
					}
				}
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.onFocusOut = function( e )
		{
			with ( AlbumEditImagesPage.Keyboard )
			{
				ctrlPressed = false;
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.onKeyDown = function( e )
		{
//			alert( e.which );

			with ( AlbumEditImagesPage.Keyboard )
			{
				//	Handle the Tab key:
				if ( e.which == TAB )
				{
					e.preventDefault();
				}

				//	Handle the Shift key going down:
				else if ( e.which == SHIFT )
				{
					shiftPressed = true;
				}

				//	Handle the Ctrl key going down:
				else if ( e.which == CTRL )
				{
					ctrlPressed = true;
				}

				//	Handle the Enter key:
				else if ( ( e.which == ENTER ) || ( e.which == SPACE ) )
				{
					if ( AlbumEditImagesPage.$imageMagnifiedContainer != undefined )
					{
						AlbumEditImagesPage.ImageThumbnailsList.removeImageMagnified( e );
					}
					else
					{
						AlbumEditImagesPage.ImageThumbnailsList.onDblClick( e );
					}
					e.stopImmediatePropagation();
				}

				//	Handle the Delete key:
				else if ( e.which == DELETE )
				{
					deletePressed = true;
					var $target = $( e.target );
					var $next = $target.next();
					if ( $next.length == 0 ) $next = $target.prev();
					$target.remove();
					if ( $next.length == 0 )
					{
						AlbumEditImagesPage.clearImageFields();
					}
					else
					{
						$next.focus();
					}
					e.preventDefault();
				}

				//	Handle Home:
				else if ( e.which == HOME )
				{
					e.preventDefault();
					$( e.target ).parent().children().first().focus();
				}

				//	Handle End:
				else if ( e.which == END )
				{
					e.preventDefault();
					$( e.target ).parent().children().last().focus();
				}

				//	Handle Page Up / Page Down:
				else if ( ( e.which == PAGE_UP || e.which == PAGE_DOWN ) )
				{
					e.preventDefault();

					//	Create some jQuery shortcuts:
					var $target = $( e.target );
					var $parent = $target.parent();
					var $siblings = $parent.children();

					var position = $target.position();

					var parentTop = $parent.position().top;

					//	Compute the number of visible rows:
					if ( e.which == PAGE_UP )
					{
						pageUpPressed = true;

						if ( position.top > ( parentTop + 2 ) )
						{
							while ( ( $target = $target.prev() ) && ( position = $target.position() ) && ( position.top > ( parentTop + 2 ) ) );
						}
						else
						{
							var height = $target.outerHeight( true );
							var parentHeight = $parent.height();
							while ( ( $target = $target.prev() ) && ( position = $target.position() ) && ( height < parentHeight ) ) height += $target.outerHeight( true );
						}
						if ( position == null ) $target = $siblings.first();
					}
					else
					{
						pageDownPressed = true;

						if ( ( position.top + $target.outerHeight( true ) ) < ( parentTop + parentHeight - 2 ) )
						{
							while ( ( $target = $target.next() ) && ( position = $target.position() ) && ( ( position.top + $target.outerHeight( true ) ) < ( parentTop + parentHeight - 2 ) ) );
						}
						else
						{
							var height = $target.outerHeight( true );
							var parentHeight = $parent.height();
							while ( ( $target = $target.next() ) && ( position = $target.position() ) && ( height < parentHeight ) ) height += $target.outerHeight( true );
						}
						if ( position == null ) $target = $siblings.last();
					}

					$target.focus();
				}

				//	Handle the up and down arrows:
				else if ( ( e.which == ARROW_UP ) || ( e.which == ARROW_DOWN ) )
				{
					e.preventDefault();

					arrowUpPressed = ( e.which == ARROW_UP );
					arrowDownPressed = !arrowUpPressed;

					var $target = $( e.target );

					if ( ctrlPressed )
					{
						AlbumEditImagesPage.ImageThumbnailsList.shift( $target, e.which );
					}
					else
					{
						( e.which == ARROW_UP ? $target.prev() : $target.next() ).focus();
					}
				}

				//	Handle the right arrow:
				else if ( ( e.which == ARROW_RIGHT ) && ctrlPressed )
				{
					e.preventDefault();

					arrowRightPressed = true;

					AlbumEditImagesPage.ImageThumbnailsList.rotateImageThumbnailRight( $( e.target ) );
				}

				//	Handle the right arrow:
				else if ( ( e.which == ARROW_LEFT ) && ctrlPressed )
				{
					e.preventDefault();

					arrowLeftPressed = true;

					AlbumEditImagesPage.ImageThumbnailsList.rotateImageThumbnailLeft( $( e.target ) );
				}
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.onKeyUp = function( e )
		{
			with ( AlbumEditImagesPage.Keyboard )
			{
//				alert( e.which );
				switch ( e.which )
				{
					case CTRL:			ctrlPressed = false; break;
					case SHIFT:			shiftPressed = false; break;
					case PAGE_UP:		pageUpPressed = false; break;
					case PAGE_DOWN:		pageDownPressed = false; break;
					case ARROW_UP:		arrowUpPressed = false; break;
					case ARROW_DOWN:	arrowDownPressed = false; break;
					case ARROW_LEFT:	arrowLeftPressed = false; break;
					case ARROW_RIGHT:	arrowRightPressed = false; break;
					case DELETE:		deletePressed = false; break;
				}

				repeating = false;
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.onKeyPress = function( e )
		{
			with ( AlbumEditImagesPage.Keyboard )
			{
				with ( AlbumEditImagesPage.ImageThumbnailsList )
				{
//					alert( e.which );
					if ( e.which == 0 )
					{
						if ( repeating )
						{
							if ( pageUpPressed || pageDownPressed )
							{
								e.which = ( pageUpPressed ? PAGE_UP : PAGE_DOWN );
								onKeyDown( e );
							}
							else if ( arrowUpPressed || arrowDownPressed )
							{
								e.which = ( arrowUpPressed ? ARROW_UP : ARROW_DOWN );
								onKeyDown( e );
							}
							else if ( arrowLeftPressed || arrowRightPressed )
							{
								e.which = ( arrowLeftPressed ? ARROW_LEFT : ARROW_RIGHT );
								onKeyDown( e );
							}
							else if ( deletePressed )
							{
								e.which = DELETE;
								onKeyDown( e );
							}
						}
						else
						{
							repeating = true;
						}
					}
				}
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.onDblClick = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					//	Wrap the event target in a jQuery selector object:
					var $target = $( e.target );

					//	Extract the image data from the target so
					//	we can get the magnified version id from it:
					var image = $target.data( 'image' );

					//	Create a container to hold both a semi-opaque background
					//	screen as well as the magnified image itself:
					$imageMagnifiedContainer = $( '<div id="imageMagnified"></div>' );
					var $imageMagnifiedBackground = $( '<div id="imageMagnifiedBackground"></div>' );
					$imageMagnifiedImage = $( '<img id="imageMagnifiedImage" src="' + server + '?d=v&vi=' + image.imageVersionIdMagnified + '&r=' + ( image.imageRotation * 90 ) + '" />' );

					//	Put the background screen and image inside the container:
					$imageMagnifiedContainer.append( $imageMagnifiedBackground );
					$imageMagnifiedContainer.append( $imageMagnifiedImage );

					//	Align left edge of the image with the right edge of the image list:
					$imageMagnifiedImage.css( { 'left' : $imageThumbnailsList.position().left + $imageThumbnailsList.width() } );

					//	Set the size of the background; this is also
					//	called whenever the window itself is resized:
					resizeImageMagnified();

					//	Attach window handlers such that any key
					//	or mouse click will close the magnified image:
					$( document.documentElement ).bind( { 'keydown' : ImageMagnified.onKeyDown, 'keyup' : ImageMagnified.onKeyUp, 'keypress' : ImageMagnified.onKeyPress, 'click' : ImageThumbnailsList.removeImageMagnified } );

					//	Attach window handlers to handle moving the image around the screen:
					$imageMagnifiedImage.bind( { 'mousedown' : ImageMagnified.onMouseDown, 'click' : ImageMagnified.onClick } );

					//	Ensure the user knows the image can be moved around the screen:
					$imageMagnifiedImage.css( 'cursor', 'move' );

					//	Add the container to the document:
					$albumEditImagesPage.append( $imageMagnifiedContainer );

					//	Set the focus:
					$imageMagnifiedImage.focus();
				}
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.rotateImageThumbnailLeft = function( $image )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					rotateImageThumbnail( $image, +1 );
				}
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.rotateImageThumbnailRight = function( $image )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					rotateImageThumbnail( $image, -1 );
				}
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.rotateImageThumbnail = function( $image, direction )
		{
			with ( AlbumEditImagesPage )
			{
				var image = $image.data( 'image' );

				var imageRotation = ( image.imageRotation += direction ) % 4;

				var imageThumbnailVersionId = image.imageThumbnailVersionId;
				var imageThumbnailVersionWidth = image.imageThumbnailVersionWidth;
				var imageThumbnailVersionHeight = image.imageThumbnailVersionHeight;

				image.imageThumbnailVersionWidth = imageThumbnailVersionHeight;
				image.imageThumbnailVersionHeight = imageThumbnailVersionWidth;

				var imageThumbnailURL = server + '?d=v&vi=' + imageThumbnailVersionId + '&r=' + ( imageRotation * 90 );

				$image.attr( { 'src' : imageThumbnailURL, 'width' : imageThumbnailVersionHeight, 'height' : imageThumbnailVersionWidth } );
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.removeImageMagnified = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					$( document.documentElement ).unbind( { 'keydown' : ImageMagnified.onKeyDown, 'keyup' : ImageMagnified.onKeyUp, 'keypress' : ImageMagnified.onKeyPress, 'click' : ImageThumbnailsList.removeImageMagnified } );

					$imageMagnifiedContainer.remove();
					$imageMagnifiedContainer = undefined;

					$imageMagnifiedImage = undefined;

					$mostRecentTarget.focus();
				}
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.resizeImageMagnified = function()
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					var $container = $imageMagnifiedContainer;
					var $background = $container.find( 'div' );

					//	Find out how big our window is:
					var windowWidth = $window.width() + 'px';
					var windowHeight = $window.height() + 'px';

					var windowSize = { 'width' : windowWidth, 'height' : windowHeight };

					$container.css( windowSize );
					$background.css( windowSize );
				}
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.scrollIntoView = function( $target )
		{
			//	Get the position of the element that will have focus:
			var position = $target.position();
			if ( position != null )
			{
				//	Get the target's parent:
				var $parent = $target.parent();

				//	Get the parent's vertical scrollbar position:
				var scrollTop = $parent.scrollTop();

				//	Compute the element's top relative to its parent:
				var top = ( position.top - $parent.position().top );

				//	Get the element's height:
				var height = $target.outerHeight( true );

				//	Compute the margin between elements; this is half the
				//	difference between the element's inner and outer heights:
				var margin = ( height - $target.height() ) / 2;

				//	If the top of the element lies above the viewport
				//	scroll the top edge of the element into view:
				if ( ( top - margin ) < 0 )
				{
					$parent.scrollTop( scrollTop + top + 1 );

					return;
				}

				//	Find the height the viewport:
				var parentHeight = $parent.height();

				//	If the bottom of the element lies below the viewport scroll
				//	the bottom edge of the element it into view:
				if ( ( top + height ) >= parentHeight )
				{
					$parent.scrollTop( scrollTop + top + height - parentHeight );
				}
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.shift = function( $target, direction )
		{
			with ( AlbumEditImagesPage )
			{
				with ( Keyboard )
				{
					if ( direction == ARROW_UP )
					{
						$target.after( $target.prev() );
					}
					else
					{
						$target.before( $target.next() );
					}
				}

				with ( ImageThumbnailsList )
				{
					scrollIntoView( $target );
				}
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	this.ImageThumbnailsControlMoveUp = new function()
	{
		this.onClick = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					if ( $mostRecentTarget != undefined )
					{
						shift( $mostRecentTarget, Keyboard.ARROW_UP );
					}
				}
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	this.ImageThumbnailsControlMoveDown = new function()
	{
		this.onClick = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					if ( $mostRecentTarget != undefined )
					{
						shift( $mostRecentTarget, Keyboard.ARROW_DOWN );
					}
				}
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	this.ImageThumbnailsControlRotateLeft = new function()
	{
		this.onClick = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					if ( $mostRecentTarget != undefined )
					{
						rotateImageThumbnailLeft( $mostRecentTarget );
					}
				}
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	this.ImageThumbnailsControlRotateRight = new function()
	{
		this.onClick = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					if ( $mostRecentTarget != undefined )
					{
						rotateImageThumbnailRight( $mostRecentTarget );
					}
				}
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	this.ImageThumbnailsControlDelete = new function()
	{
		this.onClick = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					if ( $mostRecentTarget != undefined )
					{
						e.target = $mostRecentTarget[0];
						e.which = Keyboard.DELETE;
						onKeyDown( e );
					}
				}
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	this.ImageTitleControl = new function()
	{
		this.onChange = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					if ( $mostRecentTarget != undefined )
					{
						var text = jQuery.trim( $imageTitleControl.val() );
						$mostRecentTarget.data( 'image' ).imageTitle = text;
						$mostRecentTarget.attr( 'title', text );
					}
				}
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	this.ImageDescriptionControl = new function()
	{
		this.onChange = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					if ( $mostRecentTarget != undefined )
					{
						$mostRecentTarget.data( 'image' ).imageDescription = jQuery.trim( $imageDescriptionControl.val() );
					}
				}
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	this.ImageRatingControl = new function()
	{
		this.onClick = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					if ( $mostRecentTarget != undefined )
					{
						var $target = $( e.target );
						var id = $target.attr( 'id' );
						var rating = id.charAt( id.length - 1 );
						var image = $mostRecentTarget.data( 'image' );
						if ( image.imageRating == rating ) rating = 0;
						image.imageRating = rating;
						ImageRatingControl.setRating( rating );
						$target.focus();
					}
				}
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.onKeyDown = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				if ( e.which == Keyboard.SPACE )
				{
					ImageRatingControl.onClick( e );
				}
				else if ( ( e.which == Keyboard.ARROW_RIGHT ) || ( e.which == Keyboard.ARROW_UP ) )
				{
					$next = $( e.target ).next();
					if ( $next.length == 0 ) return;
					e.target = $next[0];
					ImageRatingControl.onClick( e );
					$next.focus();
				}
				else if ( ( e.which == Keyboard.ARROW_LEFT ) || ( e.which == Keyboard.ARROW_DOWN ) )
				{
					$prev = $( e.target ).prev();
					if ( $prev.length == 0 ) return;
					e.target = $prev[0];
					ImageRatingControl.onClick( e );
					$prev.focus();
				}
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.setRating = function( rating )
		{
			for ( i = 0; i < 5; i++ )
			{
				var $element = $( '#imageRatingControl' + ( i + 1 ) );

				i < rating ? $element.addClass( 'rated' ) : $element.removeClass( 'rated' );
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	this.AlbumThumbnailControl = new function()
	{
		this.onChange = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					if ( $mostRecentTarget != undefined )
					{
						Album.albumThumbnailImageId = ( $( e.target ).attr( 'checked' ) == 'checked' ? $mostRecentTarget.data( 'image' ).imageId : undefined );
					}
				}
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	this.ImageTagsControl = new function()
	{
		this.onChange = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					if ( $mostRecentTarget != undefined )
					{
						$mostRecentTarget.data( 'image' ).imageTags = jQuery.trim( $imageTagsControl.val() );
					}
				}
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	this.ImagePhotographerControl = new function()
	{
		this.onChange = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					if ( $mostRecentTarget != undefined )
					{
						$mostRecentTarget.data( 'image' ).imagePhotographer = jQuery.trim( $imagePhotographerControl.val() );
					}
				}
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	this.ImageTimestampCreationControl = new function()
	{
		this.onChange = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					if ( $mostRecentTarget != undefined )
					{
						var value = $imageTimestampCreationControl.val();

						$.get( server + '?d=gtx&ts=' + value, ImageTimestampCreationControl.fromXml );
					}
				}
			}
		}

		this.fromXml = function( data )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					if ( $mostRecentTarget != undefined )
					{
						$mostRecentTarget.data( 'image' ).imageTimestampCreation = data;

						$imageTimestampCreationControl.val( data );
					}
				}
			}
		}

		this.xmlError = function( data )
		{
			alert( 'AJAX Failure' );
			alert( XMLHttpRequest.responseText );
			alert( textStatus );
			alert( errorThrow );
		}
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	this.ImageMapControl = new function()
	{
		this.panTo = function( location )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					var panned = false;

					if ( location !== null )
					{
						var latitude = location.lat();
						if ( isNumeric( latitude ) )
						{
							var longitude = location.lng();
							if ( isNumeric( longitude ) )
							{
								if ( ( latitude !== 0 ) && ( longitude !== 0 ) )
								{
									marker.setPosition( location );
									marker.setVisible( true );
									$imageMapControl.panTo( location );
									panned = true;
								}
							}
						}
					}

					if ( !panned ) marker.setVisible( false );

					return panned;
				}
			}
		}

		this.moveMarker = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					//	Get the new marker location:
					var location = marker.getPosition();

					//	Extract the coordinates:
					var latitude = location.lat();
					var longitude = location.lng();

					//	Store the coordinates in the image object:
					var $image = $mostRecentTarget.data( 'image' );

					$image.imageLatitude = latitude;
					$image.imageLongitude = longitude;

					//	Update the coordinate controls:
					$imageLatitudeControl.val( latitude );
					$imageLongitudeControl.val( longitude );

					//	Update the address and elevation at the new location:
					geocoder.geocode( { 'latLng' : location }, ImageLatitudeControl.onAddressReturn );
					elevator.getElevationForLocations( { 'locations' : [ location ] }, ImageLatitudeControl.onElevationReturn );

					//	Move the map to the new location:
					ImageMapControl.panTo( location );
				}
			}
		}

		this.deleteMarker = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					//	Clear the marker:
					marker.setVisible( false );

					//	Clear the coordinates in the image object:
					var $image = $mostRecentTarget.data( 'image' );

					$image.imageAddress = null;
					$image.imageLatitude = null;
					$image.imageLongitude = null;
					$image.imageAltitude = null;
					$image.imageHeading = null;

					//	Clear the coordinate controls:
					$imageAddressControl.val( null );
					$imageLatitudeControl.val( null );
					$imageLongitudeControl.val( null );
					$imageAltitudeControl.val( null );
					$imageHeadingControl.val( null );
				}
			}
		}

		this.addMarker = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					//	If the marker already exists, bug out:
					if ( marker.getVisible() ) return;

					//	Stop the event from propagating:
					e.stop();

					//	Get the new location:
					var location = e.latLng;

					//	Move the marker:
					marker.setPosition( location );

					//	Update the coordinates:
					ImageMapControl.moveMarker( e );

					//	Add the marker:
					marker.setVisible( true );
				}
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	this.ImageAddressControl = new function()
	{
		this.onChange = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					var address = jQuery.trim( $imageAddressControl.val() );

					$mostRecentTarget.data( 'image' ).imageAddress = address;

					geocoder.geocode( { 'address' : address }, ImageAddressControl.onLatLongReturn );
				}
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.onLatLongReturn = function( results, status )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					if ( status != google.maps.GeocoderStatus.OK )
					{
						marker.setVisible( false );
						return;
					}

					var location = results[0].geometry.location;

					var latitude = location.lat();
					var longitude = location.lng();

					var changed = false;

					var image = $mostRecentTarget.data( 'image' );

					if ( image.imageLatitude != latitude )
					{
						changed = true;
						$mostRecentTarget.data( 'image' ).imageLatitude = latitude;
						$imageLatitudeControl.val( latitude );
					}

					if ( image.imageLongitude != longitude )
					{
						changed = true;
						$mostRecentTarget.data( 'image' ).imageLongitude = longitude;
						$imageLongitudeControl.val( longitude );
					}

					if ( changed )
					{
						elevator.getElevationForLocations( { 'locations' : [ location ] }, ImageAddressControl.onElevationReturn );
						ImageMapControl.panTo( location );
					}
				}
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.onElevationReturn = function( results, status )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					if ( status != google.maps.ElevationStatus.OK ) return;

					var altitude = results[0].elevation;

					if ( $mostRecentTarget.data( 'image' ).imageAltitude == altitude ) return;

					$mostRecentTarget.data( 'image').imageAltitude = altitude;

					$imageAltitudeControl.val( altitude );
				}
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	this.ImageAddressButtonStandardize = new function()
	{
		this.onClick = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					var address = $mostRecentTarget.data( 'image' ).imageAddress;

					geocoder.geocode( { 'address' : address }, ImageAddressButtonStandardize.onLatLongReturn );
				}
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.onLatLongReturn = function( results, status )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					if ( status != google.maps.GeocoderStatus.OK )
					{
						marker.setVisible( false );
						return;
					}

					var location = results[0].geometry.location;

					geocoder.geocode( { 'latLng' : location }, ImageLatitudeControl.onAddressReturn );

					ImageAddressControl.onLatLongReturn( results, status );
				}
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	this.ImageAddressButtonApplyToAll = new function()
	{
		this.onClick = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					if ( $mostRecentTarget != undefined )
					{
						//	This could potentially be a lengthy process:
						BusyCursor.show();

						//	Extract the target's location information:
						var image = $mostRecentTarget.data( 'image' );
						var imageAddress = image.imageAddress;
						var imageLatitude = image.imageLatitude;
						var imageLongitude = image.imageLongitude;
						var imageAltitude = image.imageAltitude;
						var imageHeading = image.imageHeading;

						//	Get the set of all images:
						var $images = $imageThumbnailsList.find( 'img' );

						//	Copy the location information into each of them:
						$images.each( function( i )
						{
							var image = $( this ).data( 'image' );
							image.imageAddress = imageAddress;
							image.imageLatitude = imageLatitude;
							image.imageLongitude = imageLongitude;
							image.imageAltitude = imageAltitude;
							image.imageHeading = imageHeading;
						} );

						//	This could potentially be a lengthy process:
						BusyCursor.hide();
					}
				}
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	this.ImageLatitudeControl = new function()
	{
		this.onChange = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					var panned = false;

					if ( $mostRecentTarget != undefined )
					{
						var latitude = jQuery.trim( $imageLatitudeControl.val() );

						$mostRecentTarget.data( 'image' ).imageLatitude = latitude;

						if ( e != undefined )
						{
							var longitude = $imageLongitudeControl.val();

							if ( isNumeric( latitude ) && isNumeric( longitude ) )
							{
								var location = new google.maps.LatLng( latitude, longitude );

								geocoder.geocode( { 'latLng' : location }, ImageLatitudeControl.onAddressReturn );
								elevator.getElevationForLocations( { 'locations' : [ location ] }, ImageLatitudeControl.onElevationReturn );

								panned = ImageMapControl.panTo( location );
							}
						}
					}

					if ( !panned ) marker.setVisible( false );
				}
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.onAddressReturn = function( results, status )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					if ( status == google.maps.GeocoderStatus.OK )
					{
						address = results[0].formatted_address;

						if ( address != $mostRecentTarget.data( 'image' ).imageAddress )
						{
							$mostRecentTarget.data( 'image' ).imageAddress = address;

							$imageAddressControl.val( address );
						}
					}
					else
					{
						alert( status );
						if ( $imageAddressControl.val() != '' )
						{
							var latitude = $imageLatitudeControl.val();
							var longitude = $imageLongitudeControl.val();
							if ( isNumeric( latitude ) && isNumeric( longitude ) )
							{/*
								if ( confirm( 'These coordinates do not match any known address. Erase current address?' ) )
								{
									$imageAddressControl.val( '' );
								}*/
								$('#dialogConfirmationAddress').dialog( { buttons: { "Yes":function(){ }, "No":function(){ $(this).dialog("close"); } } } );
							}
						}
					}
				}
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.onElevationReturn = function( results, status )
		{
			with ( AlbumEditImagesPage )
			{
				if ( status != google.maps.ElevationStatus.OK ) return;

				$imageAltitudeControl.val( results[0].elevation );
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	this.ImageLongitudeControl = new function()
	{
		this.onChange = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					var panned = false;

					if ( $mostRecentTarget != undefined )
					{
						var longitude = jQuery.trim( $imageLongitudeControl.val() );

						$mostRecentTarget.data( 'image' ).imageLongitude = longitude;

						if ( e != undefined )
						{
							var latitude = $imageLatitudeControl.val();

							if ( isNumeric( latitude ) && isNumeric( longitude ) )
							{
								var location = new google.maps.LatLng( latitude, longitude );

								geocoder.geocode( { 'latLng' : location }, ImageLatitudeControl.onAddressReturn );
								elevator.getElevationForLocations( { 'locations' : [ location ] }, ImageLatitudeControl.onElevationReturn );

								panned = ImageMapControl.panTo( location );
							}
						}
					}

					if ( !panned ) marker.setVisible( false );
				}
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	this.ImageAltitudeControl = new function()
	{
		this.onChange = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					if ( $mostRecentTarget != undefined )
					{
						$mostRecentTarget.data( 'image' ).imageAltitude = jQuery.trim( $imageAltitudeControl.val() );
					}
				}
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	this.ImageHeadingControl = new function()
	{
		this.onChange = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageThumbnailsList )
				{
					if ( $mostRecentTarget != undefined )
					{
						$mostRecentTarget.data( 'image' ).imageAltitude = jQuery.trim( $imageHeadingControl.val() );
					}
				}
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	this.ImageExifControl = new function()
	{
		////////////////////////////////////////////////////////////////////////

		this.summaryXmlToHtml = function( xml )
		{
			with ( AlbumEditImagesPage )
			{
				var html = '';

				$( xml ).children().each( function() { $this = $( this ); html += ImageExifControl.formattedField( $this.find( 'label' ).text(), $this.find( 'value' ).text() ); } );

				if ( html != '' ) html = '<table>' + html + '</table>';

				return html;
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.completeXmlToHtml = function( xml )
		{
			with ( AlbumEditImagesPage )
			{
				var html = '';

				$( xml ).children().each( function() { html += ImageExifControl.formattedField( (this).nodeName, $( this ).text() ); } );

				if ( html != '' ) html = '<table>' + html + '</table>';

				return html;
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.formattedField = function( label, value )
		{
			return '<tr><td class="label">' + jQuery.trim( label ) + '</td><td class="value">' + jQuery.trim( value ) + '</td></tr>';
		}

		////////////////////////////////////////////////////////////////////////

		this.toggle = function( e, state )
		{
			//	Prevent any further processing of the event:
			e.preventDefault();

			//	Wrap the event's target in a jQuery object:
			var $target = $( e.target );

			//	Go up the list of ancestors to the first <div>; this should be our group div:
			var $parent = $target.closest( 'div' );

			//	Find the control within the group:
			var $control = $parent.find( 'div' );

			//	Find the span within the group:
			var $span = $parent.find( 'span' );

			//	Define the animation speed:
			var animationRate = 'fast';

			//	Show or hide the control as needed:
			if ( state == 0 )
			{
				//	Hide the control:
				$control.slideUp( animationRate );

				//	Toggle the triangle:
				$span.html( '&#x25ba;' );
			}
			else
			{
				//	Show the control:
				$control.slideDown( animationRate );

				//	Toggle the triangle:
				$span.html( '&#x25bc;' );
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.show = function( e )
		{
			AlbumEditImagesPage.ImageExifControl.toggle( e, 1 );
		}

		////////////////////////////////////////////////////////////////////////

		this.hide = function( e )
		{
			AlbumEditImagesPage.ImageExifControl.toggle( e, 0 );
		}
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	this.ImageMagnified = new function()
	{
		////////////////////////////////////////////////////////////////////////

		this.onMouseDown = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageMagnified )
				{
					e.preventDefault();
					e.stopImmediatePropagation();
					e.stopPropagation();

					var $target = $imageMagnifiedImage;

					$target.data( 'mouseDown', true );
					$target.data( 'clientX', e.clientX );
					$target.data( 'clientY', e.clientY );
					$target.data( 'originalClientX', e.clientX );
					$target.data( 'originalClientY', e.clientY );

					$target.mousemove( ImageMagnified.onMouseMove );
					$target.mouseup( ImageMagnified.onMouseUp );

					$( document.documentElement ).mousemove( ImageMagnified.onMouseMove );
				}
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.onMouseMove = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageMagnified )
				{
					var $target = $imageMagnifiedImage;

					if ( $target.data( 'mouseDown' ) !== true ) return;

					e.preventDefault();
					e.stopImmediatePropagation();
					e.stopPropagation();

					var deltaX = ( e.clientX - $target.data( 'clientX' ) );
					var deltaY = ( e.clientY - $target.data( 'clientY' ) );

					$target.data( 'clientX', e.clientX );
					$target.data( 'clientY', e.clientY );

					var left = $target.css( 'left' );
					var top = $target.css( 'top' );

					var leftLength = Math.max( 0, left.length - 2 );
					var topLength = Math.max( 0, top.length - 2 );

					left = left.substr( 0, leftLength );
					top = top.substr( 0, topLength );

					left = parseInt( left );
					top = parseInt( top );

					left += deltaX;
					top += deltaY;

					left += 'px';
					top += 'px';

					$target.css( { 'left' : left, 'top' : top } );
				}
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.onMouseUp = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageMagnified )
				{
					e.preventDefault();
					e.stopImmediatePropagation();
					e.stopPropagation();

					var $target = $imageMagnifiedImage;

					$target.removeData( 'mouseDown' );

					$target.unbind( 'mousemove', ImageMagnified.onMouseMove );
					$target.unbind( 'mouseup', ImageMagnified.onMouseUp );
					$( document.documentElement ).unbind( 'mousemove', ImageMagnified.onMouseMove );
				}
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.onClick = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageMagnified )
				{
					e.preventDefault();
					e.stopImmediatePropagation();
					e.stopPropagation();

					var $target = $imageMagnifiedImage;

					if ( ( e.clientX == $target.data( 'originalClientX' ) ) && ( e.clientY == $target.data( 'originalClientY' ) ) )
					{
						ImageThumbnailsList.removeImageMagnified();
					}
				}
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.onKeyDown = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageMagnified )
				{
//					e.preventDefault();											//	WebKit needs this commented out to generate the keypress event
					e.stopImmediatePropagation();
					e.stopPropagation();

					var $target = $imageMagnifiedImage;

					if ( ( e.which == Keyboard.CTRL ) || ( e.which == Keyboard.COMMAND ) || ( e.which == Keyboard.COMMAND_LEFT ) || ( e.which == Keyboard.COMMAND_RIGHT ) )
					{
						$target.data( 'ctrlPressed', true );
					}
					else if ( $target.data( 'ctrlPressed' ) != true )
					{
						ImageThumbnailsList.removeImageMagnified();
					}
				}
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.onKeyUp = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageMagnified )
				{
					e.preventDefault();
					e.stopImmediatePropagation();
					e.stopPropagation();

					var $target = $imageMagnifiedImage;

					if ( ( e.which == Keyboard.CTRL ) || ( e.which == Keyboard.COMMAND ) || ( e.which == Keyboard.COMMAND_LEFT ) || ( e.which == Keyboard.COMMAND_RIGHT ) )
					{
						$target.removeData( 'ctrlPressed' );
					}
				}
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.onKeyPress = function( e )
		{
			with ( AlbumEditImagesPage )
			{
				with ( ImageMagnified )
				{
					e.preventDefault();
					e.stopImmediatePropagation();
					e.stopPropagation();

					var $target = $imageMagnifiedImage;

					if ( ( e.which == Keyboard.PLUS ) || ( e.which == Keyboard.MINUS ) || ( e.which == Keyboard.MINUS_CTRL ) )
					{
						var scale = ( e.which == Keyboard.PLUS ? 1.25 : 0.8 );

						var oldWidth = $target.attr( 'width' );
//						var oldHeight = $target.attr( 'height' );

						var newWidth = ( oldWidth * scale );
//						var newHeight = ( oldHeight * scale );

						$target.attr( 'width', newWidth );
//						$target.attr( 'height', newHeight );	//	Images appear to scale uniformly by default in all browsers.
					}
				}
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	this.BusyCursor = new function()
	{
		this.show = function()
		{
			with ( AlbumEditImagesPage )
			{
				$busyCursor.show();
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.hide = function()
		{
			with ( AlbumEditImagesPage )
			{
				$busyCursor.hide();
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	this.Album = new function()
	{
		this.albumId = undefined;
		this.albumThumbnailImageId = undefined;

		////////////////////////////////////////////////////////////////////////

		this.fromXml = function( xml )
		{
			with ( AlbumEditImagesPage )
			{
				$( xml ).find( 'image' ).each( function() { Album.imageFromXml( $( this ) ); } );
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.imageFromXml = function( $image )
		{
			with ( AlbumEditImagesPage )
			{
				var imageId = jQuery.trim( $image.find( 'imageId' ).text() );
				var albumId = jQuery.trim( $image.find( 'albumId' ).text() );
				var imageNumber = jQuery.trim( $image.find( 'imageNumber' ).text() );

				var imageTitle = jQuery.trim( $image.find( 'imageTitle' ).text() );
				var imageDescription = jQuery.trim( $image.find( 'imageDescription' ).text() );
				var imageTags = jQuery.trim( $image.find( 'imageTags' ).text() );
				var imageRating = jQuery.trim( $image.find( 'imageRating' ).text() );
				var imagePhotographer = jQuery.trim( $image.find( 'imagePhotographer' ).text() );
				var imageTimestampCreation = jQuery.trim( $image.find( 'imageTimestamp' ).text() );
				var imageAddress = jQuery.trim( $image.find( 'imageAddress' ).text() );
				var imageLatitude = jQuery.trim( $image.find( 'imageLatitude' ).text() );
				var imageLongitude = jQuery.trim( $image.find( 'imageLongitude' ).text() );
				var imageAltitude = jQuery.trim( $image.find( 'imageAltitude' ).text() );
				var imageHeading = jQuery.trim( $image.find( 'imageHeading' ).text() );

				var imageExifSummary = $image.find( 'imageExifSummary' );
				var imageExifComplete = $image.find( 'imageExif' );

				var imageVersionIdMagnified = jQuery.trim( $image.find( 'imageVersionIdMagnified' ).text() );

				var imageThumbnailVersionId = jQuery.trim( $image.find( 'imageThumbnailVersionId' ).text() );
				var imageThumbnailVersionWidth = jQuery.trim( $image.find( 'imageThumbnailVersionWidth' ).text() );
				var imageThumbnailVersionHeight = jQuery.trim( $image.find( 'imageThumbnailVersionHeight' ).text() );

				var image = new Album.Image( imageId, albumId, imageNumber, imageTitle, imageDescription, imageTags, imageRating, imagePhotographer, imageTimestampCreation, imageAddress, imageLatitude, imageLongitude, imageAltitude, imageHeading, imageExifSummary, imageExifComplete, imageVersionIdMagnified, imageThumbnailVersionId, imageThumbnailVersionWidth, imageThumbnailVersionHeight );

				$image = $( '<img src="' + server + '?d=v&vi=' + imageThumbnailVersionId + '" width="' + imageThumbnailVersionWidth + '" height="' + imageThumbnailVersionHeight + '" tabindex="0" title="' + imageTitle + '" />' );

				$image.data( 'image', image );

				$imageThumbnailsList.append( $image );
			}
		}

		////////////////////////////////////////////////////////////////////////

		this.toXml = function()
		{
		}

		////////////////////////////////////////////////////////////////////////

		this.xmlError = function( XMLHttpRequest, textStatus, errorThrow )
		{
			alert( 'XML Failure' );
			alert( XMLHttpRequest.responseText );
			alert( textStatus );
			alert( errorThrow );
		}

		////////////////////////////////////////////////////////////////////////

		this.Image = function( imageId, albumId, imageNumber, imageTitle, imageDescription, imageTags, imageRating, imagePhotographer, imageTimestampCreation, imageAddress, imageLatitude, imageLongitude, imageAltitude, imageHeading, imageExifSummary, imageExifComplete, imageVersionIdMagnified, imageThumbnailVersionId, imageThumbnailVersionWidth, imageThumbnailVersionHeight )
		{
			this.imageId = imageId;
			this.albumId = albumId;
			this.imageNumber = imageNumber;

			this.imageTitle = imageTitle;
			this.imageDescription = imageDescription;
			this.imageTags = imageTags;
			this.imageRating = imageRating;
			this.imagePhotographer = imagePhotographer;
			this.imageTimestampCreation = imageTimestampCreation;
			this.imageAddress = imageAddress;
			this.imageLatitude = imageLatitude;
			this.imageLongitude = imageLongitude;
			this.imageAltitude = imageAltitude;
			this.imageHeading = imageHeading;

			this.imageExifSummary = imageExifSummary;
			this.imageExifComplete = imageExifComplete;
			this.imageExifIsStillXml = true;

			this.imageVersionIdMagnified = imageVersionIdMagnified;

			this.imageThumbnailVersionId = imageThumbnailVersionId;
			this.imageThumbnailVersionWidth = imageThumbnailVersionWidth;
			this.imageThumbnailVersionHeight = imageThumbnailVersionHeight;

			this.imageRotation = 0;
		}
	}

	////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////

	this.onResize = function()
	{
		with ( AlbumEditImagesPage )
		{
			with ( ImageThumbnailsList )
			{
				var windowWidth = $window.width();
				var windowHeight = $window.height();

				var imageThumbnailsListTop = $imageThumbnailsList.position().top;
				var imageThumbnailsControlsGroupHeight = $imageThumbnailsControlsGroup.outerHeight();

				var imageDescriptionControlTop = $imageDescriptionControl.position().top;
				var imageRemainderGroupHeight = $imageRemainderGroup.outerHeight( true );
				var imageTagsControlHeight = $imageTagsControl.innerHeight();

			//	var fontSize = parseInt( $body.css( 'font-size' ) );

				var imageDescriptionControlHeight = Math.max( windowHeight - imageDescriptionControlTop - imageRemainderGroupHeight - 2, imageTagsControlHeight );

				$imageDescriptionControl.height( imageDescriptionControlHeight );

				$imageThumbnailsList.height( Math.max( windowHeight - imageThumbnailsListTop - imageThumbnailsControlsGroupHeight - parseInt( $(0.5).toPx() ), 300 ) );

				var imageExifGroupHeight = ( windowHeight - $imageExifGroup.position().top );
				$imageExifGroup.height( imageExifGroupHeight );

				var imageDataGroupMinWidth = $imageDataGroup.css( 'min-width' );
				imageDataGroupMinWidth = imageDataGroupMinWidth.substr( 0, imageDataGroupMinWidth.length - 2 );
				imageDataGroupMinWidth = Math.max( 0, imageDataGroupMinWidth );
				var imageDataGroupWidth = Math.max( ( ( $window.width() - $imageThumbnailsGroup.offset().left - $imageThumbnailsGroup.width() - $imageExifGroup.width() - parseInt( $(3).toPx() ) ) ), imageDataGroupMinWidth );
				$imageDataGroup.width( imageDataGroupWidth );

				var imageAddressControlWidth = ( imageDataGroupWidth - $imageAddressButtonStandardize.width() - $imageAddressButtonApplyToAll.width() - parseFloat( $(3.5).toPx() ) );
				$( '#imageAddressControl' ).width( imageAddressControlWidth );

				if ( $imageMagnifiedContainer != undefined ) resizeImageMagnified();
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////

	this.clearImageFields = function()
	{
		with ( AlbumEditImagesPage )
		{
			$imageDescriptionControl.val( '' );
			$imageTagsControl.val( '' );
			$albumThumbnailControl.attr( 'checked', false );
			$( '#imageDataGroup input' ).val( '' );
			$( '#imageRatingField div' ).removeClass( 'rated' );

			$imageDescriptionControl.attr( 'disabled', true );
			$imageTagsControl.attr( 'disabled', true );
			$imageAddressButtonStandardize.attr( 'disabled', true );
			$imageAddressButtonApplyToAll.attr( 'disabled', true );
			$( '#imageDataGroup input' ).attr( 'disabled', true );
			$( '#imageRatingField div' ).attr( 'disabled', true );
		}
	}

	////////////////////////////////////////////////////////////////////////////

	this.onReady = function()
	{
		with ( AlbumEditImagesPage )
		{
			//	Define whether we need to time things:
			var doTime = false;

			//	Grab our starting timestamp:
			if ( doTime )
			{
				var t0 = new Date;
			}

			//	Grab the id of the album we're editing:
			Album.albumId = jQuery.trim( $( '#albumId' ).text() );
			if ( parseInt( Album.albumId ) != Album.albumId ) return;

			//	Grab the server's address:
			server = jQuery.trim( $( '#server' ).text() );

			//	Grab shortcuts to various elements that we'll need later on:
			$body = $( 'body' );
			$window = $( window );
			$albumEditImagesPage = $( '#albumEditImagesPage' );
			$imageDataGroup = $( '#imageDataGroup' );
			$imageThumbnailsGroup = $( '#imageThumbnailsGroup' );
			$imageThumbnailsList = $( '#imageThumbnailsList' );
			$imageThumbnailsControlsGroup = $( '#imageThumbnailsControlsGroup' );
			$imageTitleControl = $( '#imageTitleControl' );
			$imageDescriptionControl = $( '#imageDescriptionControl' );
			$imageRemainderGroup = $( '#imageRemainderGroup' );
			$imageTagsControl = $( '#imageTagsControl' );
			$imageRatingControl1 = $( '#imageRatingControl1' );
			$imageRatingControl2 = $( '#imageRatingControl2' );
			$imageRatingControl3 = $( '#imageRatingControl3' );
			$imageRatingControl4 = $( '#imageRatingControl4' );
			$imageRatingControl5 = $( '#imageRatingControl5' );
			$albumThumbnailControl = $( '#albumThumbnailControl' );
			$imagePhotographerControl = $( '#imagePhotographerControl' );
			$imageTimestampCreationControl = $( '#imageTimestampCreationControl' );
			$imageAddressControl = $( '#imageAddressControl' );
			$imageAddressButtonGroup = $( '#imageAddressButtonGroup' );
			$imageAddressButtonStandardize = $( '#imageAddressButtonStandardize' );
			$imageAddressButtonApplyToAll = $( '#imageAddressButtonApplyToAll' );
			$imageLatitudeControl = $( '#imageLatitudeControl' );
			$imageLongitudeControl = $( '#imageLongitudeControl' );
			$imageAltitudeControl = $( '#imageAltitudeControl' );
			$imageHeadingControl = $( '#imageHeadingControl' );
			$imageExifGroup = $( '#imageExifGroup' );
			$imageExifSummaryGroup = $( '#imageExifSummaryGroup' );
			$imageExifSummaryControl = $( '#imageExifSummaryControl' );
			$imageExifCompleteGroup = $( '#imageExifCompleteGroup' );
			$imageExifCompleteControl = $( '#imageExifCompleteControl' );
			$imageMapControl = $( '#imageMapControl' );

			//	Ensure that all of our fields are cleared of data:
			clearImageFields();

			//	Add the busy cursor:
			$busyCursor = $( '<img id="busyCursor" src="' + server + '/resources/busy.gif" />' );
			$( '#accountlinks' ).append( $busyCursor );

			var $document = $( document );
			$document.ajaxStart( BusyCursor.show );
			$document.ajaxStop( BusyCursor.hide );

			//	Set up the thumbnail control handlers:
			$( '#imageThumbnailsControlMoveUp' ).click( ImageThumbnailsControlMoveUp.onClick );
			$( '#imageThumbnailsControlMoveDown' ).click( ImageThumbnailsControlMoveDown.onClick );
			$( '#imageThumbnailsControlRotateLeft' ).click( ImageThumbnailsControlRotateLeft.onClick );
			$( '#imageThumbnailsControlRotateRight' ).click( ImageThumbnailsControlRotateRight.onClick );
			$( '#imageThumbnailsControlDelete' ).click( ImageThumbnailsControlDelete.onClick );

			//	Fetch the album data from the server:
			$.ajax( { async : false, dataType : 'xml', url: ( server + '?d=gax&ai=' + Album.albumId ), success : Album.fromXml, error : Album.xmlError } );

			//	Set up various handlers for the thumbnails:
			$imageThumbnailsList.find( 'img' ).bind( { 'focusin' : ImageThumbnailsList.onFocusIn, 'click' : ImageThumbnailsList.onFocusIn, 'dblclick' : ImageThumbnailsList.onDblClick } );

			//	Set up the various field handlers:
			$imageTitleControl.keyup( ImageTitleControl.onChange );
			$imageDescriptionControl.change( ImageDescriptionControl.onChange );
			$imageTagsControl.change( ImageTagsControl.onChange );
			$imageRatingControl1.click( ImageRatingControl.onClick ); $imageRatingControl1.keydown( ImageRatingControl.onKeyDown );
			$imageRatingControl2.click( ImageRatingControl.onClick ); $imageRatingControl2.keydown( ImageRatingControl.onKeyDown );
			$imageRatingControl3.click( ImageRatingControl.onClick ); $imageRatingControl3.keydown( ImageRatingControl.onKeyDown );
			$imageRatingControl4.click( ImageRatingControl.onClick ); $imageRatingControl4.keydown( ImageRatingControl.onKeyDown );
			$imageRatingControl5.click( ImageRatingControl.onClick ); $imageRatingControl5.keydown( ImageRatingControl.onKeyDown );
			$albumThumbnailControl.change( AlbumThumbnailControl.onChange );
			$imagePhotographerControl.change( ImagePhotographerControl.onChange );
			$imageTimestampCreationControl.change( ImageTimestampCreationControl.onChange );
			$imageAddressControl.change( ImageAddressControl.onChange );
			$imageAddressButtonStandardize.click( ImageAddressButtonStandardize.onClick );
			$imageAddressButtonApplyToAll.click( ImageAddressButtonApplyToAll.onClick );
			$imageLatitudeControl.change( ImageLatitudeControl.onChange );
			$imageLongitudeControl.change( ImageLongitudeControl.onChange );
			$imageAltitudeControl.change( ImageAltitudeControl.onChange );
			$imageHeadingControl.change( ImageHeadingControl.onChange );

			$( '#imageExifSummaryLabel' ).toggle( ImageExifControl.hide, ImageExifControl.show );
			$( '#imageExifCompleteLabel' ).toggle( ImageExifControl.show, ImageExifControl.hide );

			//	Create reusable mapping service objects:
			geocoder = new google.maps.Geocoder();
			elevator = new google.maps.ElevationService();

			//	Create the map object:
			var location = new google.maps.LatLng( -34.397, 150.644 );
			var myOptions = { zoom: 17, center: location, mapTypeId: google.maps.MapTypeId.ROADMAP };
			var map = new google.maps.Map( document.getElementById( 'imageMapControl' ), myOptions);
			$imageMapControl = map;

			var markerOptions = { draggable:true, map:map };
			marker = new google.maps.Marker( markerOptions );
			google.maps.event.addListener( marker, 'dragend', function() { ImageMapControl.moveMarker( marker.getPosition() ); } );
			google.maps.event.addListener( marker, 'rightclick', function() { ImageMapControl.deleteMarker(); } );
			google.maps.event.addListener( map, 'rightclick', function( e ) { ImageMapControl.addMarker( e ); } );

			//	Set up the resize handler:
			$window.resize( onResize );

			//	Force the controls to size themselves properly on initialization:
			onResize();

			if ( doTime )
			{
				//	Grab our ending timestamp:
				var t1 = new Date;

				//	Compute the elapsed time:
				alert( ( new Date().getTime() - t0.getTime() ) + ' ms' );
			}
		}
	}
}

$( document ).ready( AlbumEditImagesPage.onReady );