<?php

/**
 * This is the "base controller class". All other "real" controllers extend this class.
 * Whenever a controller is created, we also
 * 1. initialize a session
 * 2. check if the user is not logged in anymore (session timeout) but has a cookie
 */
class Controller
{
    /** @var View View The view object */
    public $View;

    /**
     * Construct the (base) controller. This happens when a real controller is constructed, like in
     * the constructor of IndexController when it says: parent::__construct();
     */
    function __construct()
    {
        // always initialize a session
        Session::init();

        // user is not logged in but has remember-me-cookie ? then try to login with cookie ("remember me" feature)
        if (!Session::userIsLoggedIn() AND Request::cookie('remember_me')) {
            header('location: ' . Config::get('URL') . 'login/loginWithCookie');
        }

        // Get the language
        if (empty(Session::get("user_lang"))) {
            if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                // Save the first two letters to the session
                Session::set("user_lang", strtolower(trim(strip_tags(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2)))));
            }
        }

        // create a view object to be able to use it inside a controller, like $this->View->render();
        $this->View = new View();
    }
}
