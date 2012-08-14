
var $photoDimensions = new Array();
//var $verticalMargin = 0.9;

var $isZoomed = false;

////////////////////////////////////////////////////////////////////////////////

function zoom( $photoId, $zoomFactor )
{
	$photo = document.getElementById( 'photo' + $photoId );
	$photoWidth = $photo.width;
	$photoHeight = $photo.height;

	if ( $photoDimensions.length < $photoId )
	{
		$photoDimensions[$photoId] = [$photoWidth, $photoHeight];
	}
	else
	{
		if ( !$photoDimensions[$photoId] )
		{
			$photoDimensions[$photoId] = [$photoWidth, $photoHeight];
		}
	}

	if ( $zoomFactor > 0 )
	{
		$photo.width = ( $photoWidth * $zoomFactor );
		$photo.height = ( $photoHeight * $zoomFactor );
	}
	else
	{
		$photo.width = $photoDimensions[$photoId][0];
		$photo.height = $photoDimensions[$photoId][1];
	}

	if ( $zoomFactor == 0.0 )
	{
		$photo.title = 'Click to fit to window';
		$isZoomed = false;

	}
	else
	{
		$photo.title = 'Click to restore';
		$isZoomed = true;
	}
}

////////////////////////////////////////////////////////////////////////////////

function expandOrContract( $photoId )
{
	if ( $isZoomed )
	{
		zoom( $photoId, 0 );
	}
	else
	{
		zoomToBrowser( $photoId );
	}
}

////////////////////////////////////////////////////////////////////////////////

function zoomToBrowser( $photoId )
{
	$photoString = ( 'photo' + $photoId );
	$photo = document.getElementById( $photoString );
	$photoWidth = $photo.width;
	$photoHeight = $photo.height;

	$navbarPosition = getAnchorPosition( 'navbar' );
	$photoPosition = getAnchorPosition( $photoString );

	$browserWidth = ( window.innerWidth ? window.innerWidth : document.body.offsetWidth ) - ( $photoPosition.x * 2 ) - 20;
//	$browserHeight = ( window.innerHeight ? window.innerHeight : document.body.offsetHeight ) - $photoPosition.y - 40;
	$browserHeight = ( window.innerHeight ? window.innerHeight : document.body.offsetHeight ) - 55;

	$verticalZoomFactor = ( $browserHeight / $photoHeight );
	$horizontalZoomFactor = ( $browserWidth / $photoWidth );

	$zoomFactor = ( $verticalZoomFactor < $horizontalZoomFactor ? $verticalZoomFactor : $horizontalZoomFactor );

	if ( $zoomFactor == 1.0 ) return;

	zoom( $photoId, $zoomFactor );

	isZoomedToBrowser = true;

	window.scrollTo( 0, $navbarPosition.y );
}

////////////////////////////////////////////////////////////////////////////////

function zoomOnLoad()
{
	if ( !document.getElementsByTagName )
	{
		return;
	}

	$images = document.getElementsByTagName( 'img' );
	for ( i = 0; i < $images.length; i++)
	{
		$image = $images[i];
		$photoId = $image.getAttribute( 'id' );
		if ( $photoId == 'photo1' )
		{
			zoomToBrowser( 1 );
		}
	}
}
//window.onload = zoomOnLoad;

////////////////////////////////////////////////////////////////////////////////

function getAnchorPosition(anchorname) {
	// This function will return an Object with x and y properties
	var useWindow=false;
	var coordinates=new Object();
	var x=0,y=0;
	// Browser capability sniffing
	var use_gebi=false, use_css=false, use_layers=false;
	if (document.getElementById) { use_gebi=true; }
	else if (document.all) { use_css=true; }
	else if (document.layers) { use_layers=true; }
	// Logic to find position
 	if (use_gebi && document.all) {
		x=AnchorPosition_getPageOffsetLeft(document.all[anchorname]);
		y=AnchorPosition_getPageOffsetTop(document.all[anchorname]);
		}
	else if (use_gebi) {
		var o=document.getElementById(anchorname);
		x=AnchorPosition_getPageOffsetLeft(o);
		y=AnchorPosition_getPageOffsetTop(o);
		}
 	else if (use_css) {
		x=AnchorPosition_getPageOffsetLeft(document.all[anchorname]);
		y=AnchorPosition_getPageOffsetTop(document.all[anchorname]);
		}
	else if (use_layers) {
		var found=0;
		for (var i=0; i<document.anchors.length; i++) {
			if (document.anchors[i].id==anchorname) { found=1; break; }
			}
		if (found==0) {
			coordinates.x=0; coordinates.y=0; return coordinates;
			}
		x=document.anchors[i].x;
		y=document.anchors[i].y;
		}
	else {
		coordinates.x=0; coordinates.y=0; return coordinates;
		}
	coordinates.x=x;
	coordinates.y=y;
	return coordinates;
	}


// Functions for IE to get position of an object
function AnchorPosition_getPageOffsetLeft (el) {
	var ol=el.offsetLeft;
	while ((el=el.offsetParent) != null) { ol += el.offsetLeft; }
	return ol;
	}
function AnchorPosition_getWindowOffsetLeft (el) {
	return AnchorPosition_getPageOffsetLeft(el)-document.body.scrollLeft;
	}
function AnchorPosition_getPageOffsetTop (el) {
	var ot=el.offsetTop;
	while((el=el.offsetParent) != null) { ot += el.offsetTop; }
	return ot;
	}
function AnchorPosition_getWindowOffsetTop (el) {
	return AnchorPosition_getPageOffsetTop(el)-document.body.scrollTop;
	}

function updateExternalLinks()
{
	if ( !document.getElementsByTagName )
	{
		return;
	}

	anchors = document.getElementsByTagName( "a" );

	for ( i = 0; i < anchors.length; i++)
	{
		anchor = anchors[i];
		href = anchor.getAttribute( "href" );
		if ( href )
		{
		    if ( false && href.substring( 0, 7 ) == "http://" )
		    {
			    anchor.title = href;
			    anchor.target = "_blank";
		    }
		    else
		    {
			    $rel = anchor.getAttribute( "rel" );
			    if ( $rel == "external" )
			    {
				    anchor.title = href;
				    anchor.target = "_blank";
			    }
			    else if ( $rel == "popup" )
			    {
				    anchor.target = "_blank";
			    }
			}
		}
	}
}

function setFocus()
{
	var element = document.getElementById( 'username' );
	if ( element && element.getAttribute( 'value' ) == '' )
	{
		element.focus();
		return;
	}

	element = document.getElementById( 'password' );
	if ( element ) element.focus();
}

var alwaysFit = false;
var nextImageFilePath = null;
var prevImageFilePath = null;
function onLoad()
{
	updateExternalLinks();
	if ( nextImageFilePath != null )
	{
		var home = new Image( 100,100 );
		home.src = nextImageFilePath;
	}
	if ( prevImageFilePath != null )
	{
		var home = new Image( 100,100 );
		home.src = prevImageFilePath;
	}
	if ( alwaysFit )
	{
	//	zoomOnLoad();
		zoomToBrowser(1);
	}
	setFocus();
}
window.onload = onLoad;

var popup = null;
var oldImageWidth;
var oldImageHeight;
function closePopup()
{
	if ( popup )
	{
		popup.close();
		delete popup;
		popup = null;
	}
}

function openPopup( imageURL, imageWidth, imageHeight, title, maximumImageWidth )
{
	//  If the popup window object is defined but its window has been closed by the user, make sure the corresponding object is deleted:
	if ( popup && popup.closed )
	{
 		closePopup();
	}

	//  If the popup window object doesn't exist, create it in the upper right-hand corner of the screen; else, resize by the difference in its image sizes:
	if ( !popup )
	{
	    var left = screen.availWidth - maximumImageWidth - 10;
		popup = window.open( "", "_blank", 'dependent,height=' + imageHeight + ',width=' + imageWidth + ',top=' + 0 + ',screenY=' + 0 + ',left=' + left + ',screenX=' + left );
	}
	else
	{
	    popup.resizeBy( imageWidth - oldImageWidth, imageHeight - oldImageHeight );
	}

	//  Rewrite the document contents:
	popup.document.open();
	popup.document.write( '<html><head><title>' + title + '</title></head><body style="margin:0em"><img src="' + imageURL + '" height="' + imageHeight+'" width="' + imageWidth + '"></body></html>' );
	popup.document.close();

	//  Ensure the window is visible:
	popup.focus();

	//  Store the image size so we can resize the window later on if necessary:
	oldImageWidth = imageWidth;
	oldImageHeight = imageHeight;
}

var slide = null;

function closeSlide()
{
	if ( slide )
	{
		slide.close();
		delete slide;
		slide = null;
	}
}

function openSlide( imageURL )
{
	//  If the slide window object is defined but its window has been closed by the user, make sure the corresponding object is deleted:
	if ( slide && slide.closed )
	{
 		closeSlide();
	}

	//  If the slide window object doesn't exist, create it in the upper right-hand corner of the screen; else, resize by the difference in its image sizes:
	if ( !slide )
	{
//		slide = window.open( imageURL, "", 'dependent,location,menubar,toolbar,status,height=' + screen.height + ',width=' + screen.width + ',top=' + 0 + ',screenY=' + 0 + ',left=' + 0 + ',screenX=' + 0 );
		slide = window.open( imageURL, "" );
	}

	//  Ensure the window is visible:
	slide.focus();
}

function maximize()
{
	window.moveTo(0,0);
	window.resizeTo( screen.width, screen.height );
}

function toggleVisibility( contentId, triggerId, initialStyle, visibleStyle, invisibleStyle, visibleTriggerText, invisibleTriggerText, cookieName )
{
	var style = document.getElementById( contentId ).style;
	const cookieExpiration = "; expires=Fri, 19 Oct 2020 12:00:00 UTC";

	if ( style.display == '' ) style.display = initialStyle;

	if ( style.display == invisibleStyle )
	{
		style.display = visibleStyle;
		document.getElementById( triggerId ).value = visibleTriggerText;
		document.getElementById( triggerId ).innerHTML = visibleTriggerText;
		document.cookie = cookieName + "=" + visibleStyle + cookieExpiration;
	}
	else
	{
		style.display = invisibleStyle;
		document.getElementById( triggerId ).value = invisibleTriggerText;
		document.getElementById( triggerId ).innerHTML = invisibleTriggerText;
		document.cookie = cookieName + "=" + invisibleStyle + cookieExpiration;
	}
}