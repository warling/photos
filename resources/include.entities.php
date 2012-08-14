<?php

////////////////////////////////////////////////////////////////////////////////

//namespace org\givingtree\entities;

////////////////////////////////////////////////////////////////////////////////

define( 'nbsp', '&nbsp;' );		//	Full-width joiner; space with prevents word breaks in the strings it connects; non-breaking space
define( 'nnbsp', '&#8239;' );	//	Half-width joiner; space with prevents word breaks in the strings it connects; narrow non-breaking space
define( 'zwj', '&#8205;' );		//	Zero-width joiner; prevents word breaks in the strings it connects
define( 'zwnj', '&#8204;' );	//	Zero-width non-joiner; indicates possible word-break points
define( 'thinsp', '&thinsp;' );	//	Thin space; thinner than a space
define( 'hairsp', '&#8202;' );	//	Hair space; thinner than a thin space
define( 'middot', '&middot;' );
define( 'amp', '&amp;' );
define( 'lt', '&lt;' );
define( 'gt', '&gt;' );
define( 'rsquo', '&rsquo;' );
define( 'lsquo', '&lsquo;' );
define( 'rdquo', '&rdquo;' );
define( 'ldquo', '&ldquo;' );
define( 'mdash', zwj.hairsp.zwj.'&mdash;'.hairsp );
define( 'ndash', zwj.thinsp.zwj.'&ndash;'.thinsp );
define( 'star', '&#9733;' );
define( 'deg', '&deg;' );
define( 'triangleDown', '&#x25bc;' );
define( 'triangleRight', '&#x25ba;' );

////////////////////////////////////////////////////////////////////////////////

?>