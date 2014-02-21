<?php
/**
 *	Brewery
 *
 *	Brewery is an object oriented, agile and scalable RESTful web applications framweork built for PHP 5.4 and later, which focuses on structured and enterprise-worthy application development for cloud based services and applications.
 *
 *	@link http://brewphp.org
 *
 *	@author Robin Grass <hej@carbin.se>
 *
 *	@license http://opensource.org/licenses/LGPL-2.1 The GNU Lesser General Public License, version 2.1
 */



/**
 *	url
 *
 *	Global alias function for {{@see \Brewery\asset}}.
 *
 *	@return string
 */
function asset($asset, $isPublic = true) {

	return \Brewery\asset($asset, $isPublic);

}

/**
 *	url
 *
 *	Global alias function for {{@see \Brewery\url}}.
 *
 *	@return string
 */
function url() {

	return \Brewery\url();

}

/**
 *	uri
 *
 *	Global alias function for {{@see \Brewery\uri}}.
 *
 *	@param string $uri Unresolved URI.
 *
 *	@return string
 */
function uri($uri = null) {

	return \Brewery\uri($uri);

}

/**
 *	segment
 *
 *	Global alias function for {{@see \Brewery\segment}}.
 *
 *	@param int $index Segment index.
 *
 *	@return string
 */
function segment($index) {

	return \Brewery\segment($index);

}