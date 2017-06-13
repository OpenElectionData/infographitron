<?php

/**
 * This controller shows the infographic.
 */
class InfographicController extends Controller
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
     * Generate Infographic
     */
    public function showInfographic()
    {
        $url = $_SERVER['QUERY_STRING'];
        $query = explode("&",$url, 2);

        InfographicModel::generateAndShowInfographic($query[1]);
    }

    /**
     * Download infographics in zip file
     */
    public function zip()
    {
        $filename = Request::get('filename');
        $firstline = Request::get('firstline');

        InfographicModel::zipInfographics($filename, $firstline);
    }
}
