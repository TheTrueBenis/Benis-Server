<?php

/**
 * Class Hex_Map
 * This is a demo controller that simply shows an area that is only visible for the logged in user
 * because of Auth::handleLogin(); in line 19.
 */
class Hex_Map extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    function __construct()
    {
        parent::__construct();

        // this controller should only be visible/usable by logged in users, so we put login-check here
        Auth::handleLogin();
    }

    /**
     * This method controls what happens when you move to /dashboard/index in your app.
     */
    function index()
    {
		//setcookie("Char_id", "123456", NULL, "/","", 0);
        $this->view->render('Hex_Map/index');
    }
	function Hex_alter()
    {
		//setcookie("Char_id", "123456", NULL, "/","", 0);
        $this->view->render('Hex_Map/Hex_alter');
    }
	function Hex_alter_desc()
    {
		//setcookie("Char_id", "123456", NULL, "/","", 0);
        $this->view->render('Hex_Map/Hex_alter_desc');
    }
}
