<?php

class Icons
{
    private static $icons;

    public static function get($key)
    {
	    // if not $key
	    if (!$key) {
		    return null;
	    }

	    // load config file (this is only done once per application lifecycle)
        if (!self::$icons) {

        	// Load in our default language file and our specific language file if necessary
        	self::$icons = require('../application/config/icons.php');
        }

	    // check if array key exists
	    if (!array_key_exists($key, self::$icons)) {
		    return null;
	    }

        return self::$icons[$key];
    }

}
