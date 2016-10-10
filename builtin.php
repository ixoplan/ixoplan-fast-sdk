<?php
/**
 * This is a mock class to help with DL function declarations. It is never used.
 */


/**
 * Returns the single value for the request parameter with the given name.
 *
 * @param string $name
 *
 * @return string|null
 */
function getRequestParameter($name) {
}

/**
 * Returns the array value for the request parameter with the given name.
 *
 * @param string $name
 *
 * @return array|null
 */
function getRequestParameterList($name) {
}

/**
 * Escapes text for HTML.
 *
 * @param string $text text to escape
 *
 * @return string
 */
function html($text) {
}

/**
 * Escapes text for XML.
 *
 * @param string $text text to escape
 *
 * @return string
 */
function xml($text) {
}

/**
 * Escapes text or data for JavaScript (JSON).
 *
 * @param mixed $data text to escape
 *
 * @return string
 */
function js($data) {
}

/**
 * Gets a flat list of all pages under a certain vhost. This can be used to generate an XML sitemap.
 *
 * Example Output:
 *	Array(
 *		["/"] => Object(
 *			["pageUrl"] => "http://www.myhost.com/en",
 *			["pagePath"]=> "/en/"
 * 		),
 *		["/welcome"] => Object(
 *			["pageUrl"] => "http://www.myhost.com/en/welcome"
 *			["pagePath"]=> "/en/welcome"
 *		)
 *	)
 *
 * @param string $vhost	 defaults to the current vhost (if available)
 * @param string $lang   defaults to the current language (if available)
 * @param string $layout defaults to the current layout (if available)
 * @param string $scheme defaults to the current request scheme (if available, otherwise it's 'http')
 *
 * @return array|null
 */
function getAllPages($vhost = null, $lang = null, $layout = null, $scheme = null) {
}

/**
 * Returns a list of all supported language codes
 *
 * @return array
 */
function getAllLanguages() {
}

/**
 * Returns the current language.
 *
 * @return string
 */
function getCurrentLanguage() {
}

/**
 * Returns a given meta value or all meta values for the given page in the given language.
 *
 * @param null|string $meta key to resolve. the full meta array is returned is this is null
 * @param null|string $lang defaults to the current language
 * @param null|string $pagePath defaults to the current page path
 * @param null|string $layout defaults to the current layout or "default" if the current layout is not set
 *
 * @return string|array
 */
function getMeta($meta = null, $lang = null, $pagePath = null, $layout = null) {
}

/**
 * Overwrite metadata value for the current page render.
 *
 * @param string      $meta
 * @param string      $value
 * @param null|string $lang defaults to current language
 *
 * @return boolean
 */
function setMeta($meta, $value, $lang = null) {
}

/**
 * Gets the name of the vhost for the current request.
 *
 * @return string
 */
function getVhost() {
}

/**
 * Returns the FQDN this page was called with.
 *
 * @return string
 */
function getFQDN() {
}

/**
 * Returns the content of the page in the sitelayout.
 *
 * @return string
 */
function getContent() {
}

/**
 * Gets the current page's link in the specified language
 *
 * @param null|string $lang defaults to current language
 *
 * @return string
 */
function getCurrentPageLink($lang = null) {
}

/**
 * Gets the current page's path (formerly known as pageId)
 *
 * @return string
 */
function getCurrentPagePath() {
}

/**
 * Returns a link to a static resource
 *
 * @param string $resource
 *
 * @return string
 */
function getStaticLink($resource) {
}

/**
 * Returns an url => title array of breadcrumbs to the specified page.
 * Calling this function is only valid in the context of a page.
 * For error pages, etc. this function returns null.
 *
 * @param null|string $page   defaults to current page
 * @param null|string $lang   defaults to current language
 * @param null|string $layout defaults to current layout
 *
 * @return null|array
 */
function getBreadcrumb($page = null, $lang = null, $layout = null) {
}

/**
 * Send email
 *
 * @param $from string          sender
 * @param $to string | array    recipient
 * @param $subject string
 * @param $content string | array message content
 * @param $options array        options like cc, bcc,
 *
 * The message plain text content if a string is supplied.
 * For an array value:
 * $content["plain"]: plain text body
 * $content["html"]: html formatted body.
 *
 * @return boolean              Success true
 */
function sendMail($from, $to, $subject, $content, $options = null) {

}

/**
 * Returns the list of files and directories located in the given directory.
 *
 * Example Output:
 *	Array(
 *		["logo.png"] => Object(
 *			["type"] => "file",
 *			["size"]=> 4511, // File Size in Bytes
 *			["modified"] => 1402490337821 //  Elapsed milliseconds since 1970-01-01T00:00:00Z
 * 		),
 *		["some directory"] => Object(
 *			["type"] => "directory"
 *		)
 *	)
 *
 * @param $path string
 *
 * @return array|null
 */
function listDirectory($path) {
}


/**
 * Performs a permanent or temporary redirect to the page path specified via $page.
 * $page is a relative path to a local page on the site (e.g. "/about/legal").
 *
 * @param $page string redirect target page
 * @param string $lang langauge of the target page
 * @param $permanently null|boolean permanent redirect (Status Code 301 - "Moved Permanently") if true, temporary redirect (Status Code 302 - "Found") otherwise
 */
function redirectToPage($page, $lang = null, $permanently=false) {
}

/**
 * Performs a permanent or temporary redirect to the location specified via $location.
 *
 * @param $location string redirect target location
 * @param $permanently null|boolean permanent redirect (Status Code 301 - "Moved Permanently") if true, temporary redirect (Status Code 302 - "Found") otherwise
 */
function redirectTo($location, $permanently=false) {
}

/**
 * Returns geo information data based on an ip address
 *
 * Example Output:
 * object(stdClass) (7) {
 *   ["location"]=>
 *   object(stdClass) (3) {
 *     ["latitude"]=>
 *     float(48.2)
 *     ["longitude"]=>
 *     float(16.3667)
 *     ["timezone"]=>
 *     unicode(13) "Europe/Vienna"
 *   }
 *   ["city"]=>
 *   object(stdClass) (1) {
 *     ["name"]=>
 *     unicode(6) "Vienna"
 *   }
 *   ["subdivisions"]=>
 *   array(1) {
 *     [0]=>
 *     object(stdClass) (2) {
 *       ["name"]=>
 *       unicode(6) "Vienna"
 *       ["iso_code"]=>
 *       unicode(1) "9"
 *     }
 *   }
 *   ["country"]=>
 *   object(stdClass) (2) {
 *     ["name"]=>
 *     unicode(7) "Austria"
 *     ["iso_code"]=>
 *     unicode(2) "AT"
 *   }
 *   ["registered_country"]=>
 *   object(stdClass) (2) {
 *     ["name"]=>
 *     unicode(7) "Austria"
 *     ["iso_code"]=>
 *     unicode(2) "AT"
 *   }
 *   ["represented_country"]=>
 *   object(stdClass) (3) {
 *     ["name"]=>
 *     unicode(0) ""
 *     ["iso_code"]=>
 *     unicode(0) ""
 *     ["type"]=>
 *     unicode(0) ""
 *   }
 *   ["continent"]=>
 *   object(stdClass) (2) {
 *     ["name"]=>
 *     unicode(6) "Europe"
 *     ["code"]=>
 *     unicode(2) "EU"
 *   }
 * }
 *
 * @param string $remote remote ip for which the geo information should be retrieved. defaults to the request remote if $remote = null
 *
 * @return object|null
 */
function geoInfo($remote = null){
}

