<?php

class Text
{
    private static $texts;

    public static function get($key)
    {
	    // if not $key
	    if (!$key) {
		    return null;
	    }

	    // load config file (this is only done once per application lifecycle)
        if (!self::$texts) {

        	// Load in our default language file and our specific language file if necessary
        	self::$texts = require('../application/lang/'.Config::get('DEFAULT_LANGUAGE').'.php');
        	if(Session::get("user_lang") != Config::get('DEFAULT_LANGUAGE')) {
				if (file_exists('../application/lang/'.Session::get("user_lang").'.php')) {
				    self::$texts = require('../application/lang/'.Session::get("user_lang").'.php');
				}
			}
        }

	    // check if array key exists
	    if (!array_key_exists($key, self::$texts)) {
		    return null;
	    }

        return self::$texts[$key];
    }

}
