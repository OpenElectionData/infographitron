<?php

/**
 * This controller shows the batch upload.
 */
class BatchController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();

        // this entire controller should only be visible/usable by logged in users, so we put authentication-check here
        // Auth::checkAuthentication();
    }

    /**
     * This method controls what happens when you move to /dashboard/index in your app.
     */
    public function index()
    {
        $this->View->render('batch/upload');
    }

    /**
     * This method controls what happens when you move to /dashboard/upload in your app.
     */
    public function upload()
    {
        $this->View->render('batch/upload');
    }

    /**
     * This method controls what happens when you move to /dashboard/feed in your app.
     */
    public function feed()
    {
        $this->View->render('batch/feed');
    }
}
