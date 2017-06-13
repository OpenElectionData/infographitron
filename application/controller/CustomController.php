<?php

/**
 * This controller shows the custom infographic creator.
 */
class CustomController extends Controller
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
        // Load existing infographic information if we have it either by loading the URL, ID or default template
        $url = $_SERVER['QUERY_STRING'];
        $query = explode("&",$url, 2);
        $processedQuery = CustomModel::processCustomInfographic($query);

        $this->View->render('custom/index', array(
                'action' => CustomModel::determineAction($query),
                'query' => CustomModel::processCustomInfographic($processedQuery),
                'step' => CustomModel::trackStepProgress($processedQuery),
                'backgrounds' => InfographicModel::generateBackgrounds(),
                'graphics' => InfographicModel::generateGraphics($getInfo = true),
                'fonts' => InfographicModel::generateFonts()
            )
        );
    }

    /**
     * This method controls what happens when you move to /dashboard/create in your app.
     * Creates a new note. This is usually the target of form submit actions.
     * POST request.
     */
    public function create()
    {
        $lastID = CustomModel::createInfographic(Request::post('url'), Request::post('f_n'));
        Redirect::to('custom?id='.$lastID);
    }

    /**
     * This method controls what happens when you edit an infographic.
     * Edits an infographic (performs the editing after form submit).
     * POST request.
     */
    public function edit()
    {
        CustomModel::updateInfographic(Request::post('id'), Request::post('url'), Request::post('f_n'));
        Redirect::to('custom?id='.Request::post('id'));
    }

    /**
     * This method controls what happens when you edit an infographic.
     * Edits an infographic (performs the editing after form submit).
     * POST request.
     */
    public function updateURL()
    {
        
    }

    /**
     * This method controls what happens when you move to /custom/duplicate in your app.
     * Duplicates an existing infographic
     * POST request.
     */
    public function duplicate()
    {
        $lastID = CustomModel::createInfographic(Request::post('url'), Request::post('f_n'));
        Redirect::to('custom?id='.$lastID);
    }

    /**
     * This method controls what happens when you move to /custom/delete?id=X in your app.
     * Deletes an infographic
     * @param int $note_id id of the note
     */
    public function delete()
    {
        CustomModel::deleteInfographic(Request::get('id'));
        Redirect::to('profile/infographics');
    }

    /**
     * This method controls what happens when you move to /custom/default
     * Saves infographic information as the default template to be used
     * POST request.
     */
    public function saveDefault()
    {
        CustomModel::saveDefaultTemplate(Request::post('url'));
        Redirect::to('custom?id='.Request::post('id'));
    }

    /**
     * This method controls what happens when you move to /custom/default
     * Saves infographic information as the default template to be used
     * POST request.
     */
    public function deleteDefault()
    {
        CustomModel::deleteDefaultTemplate();
        Redirect::to('login/showProfile');
    }

}
