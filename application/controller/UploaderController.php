<?php

/**
 * This controller shows the uploads section
 */
class UploaderController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();

        // this entire controller should only be visible/usable by logged in users, so we put authentication-check here
        Auth::checkAuthentication();
    }

    /**
     * This method controls what happens when you move to /dashboard/index in your app.
     */
    public function index()
    {
        // $this->View->render('uploads/backgrounds');
        $this->View->renderMulti(array(
            'uploads/tabs',
            'uploads/backgrounds',
            'uploads/uploadForm'
        ),
        array(
            'page' => 'backgrounds',
            'backgrounds' => InfographicModel::getBackgrounds()
        ));
    }

    /**
     * This method controls what happens when you move to /dashboard/index in your app.
     */
    public function backgrounds()
    {
        // $this->View->render('uploads/backgrounds');
        $this->View->renderMulti(array(
            'uploads/tabs',
            'uploads/backgrounds',
            'uploads/uploadForm'
        ),
        array(
            'page' => 'backgrounds',
            'backgrounds' => InfographicModel::getBackgrounds()
        ));
    }

    /**
     * This method controls what happens when you move to /dashboard/index in your app.
     */
    public function graphics()
    {
        $tags = require('../application/config/asset-tags.php');

        $this->View->renderMulti(array(
            'uploads/tabs',
            'uploads/graphics',
            'uploads/uploadForm'
        ),
        array(
            'page' => 'graphics',
            'graphics' => InfographicModel::getGraphics(),
            'tags' => $tags
        ));
    }

    /**
     * This method controls what happens when you move to /dashboard/index in your app.
     */
    public function fonts()
    {
        // $this->View->render('uploads/backgrounds');
        $this->View->renderMulti(array(
            'uploads/tabs',
            'uploads/fonts',
            'uploads/uploadForm'
        ),
        array(
            'page' => 'fonts',
            'fonts' => InfographicModel::getFonts()
        ));
    }

    /**
     * This method controls what happens when you upload an asset
     * POST request.
     */
    public function upload()
    {

        $fileArray = UploaderModel::reArrayFiles($_FILES['file']);

        foreach ($fileArray as $index => $file) {

            $filename = Request::post('filename')[$index];
            $permissions = Request::post('permissions')[$index];
            if(isset(Request::post('tags')[$index])) {
                $tags = Request::post('tags')[$index];
            }
            else {
                $tags = null;
            }
            $_FILES['file'] = $file;

            UploaderModel::uploadAsset($filename, Request::post('assetType'), $permissions, $tags, $_FILES['preview']);

        }
        
        Redirect::to('uploader/'.Request::post('assetType'));
    }

    /**
     * Delete an asset
     */
    public function delete() {
        UploaderModel::deleteAsset(Request::get('id'));
        Redirect::to('uploader/index');
    }
}
