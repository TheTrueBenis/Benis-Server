<?php

/**
 * Class Dashboard
 * This is a demo controller that simply shows an area that is only visible for the logged in user
 * because of Auth::handleLogin(); in line 19.
 */
class Dashboard extends Controller
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
		$MAP_WIDTH = 20;
		$MAP_HEIGHT = 20;
		$HEX_HEIGHT = 72;
		$HEX_SCALED_HEIGHT = $HEX_HEIGHT * 1.0;
		$HEX_SIDE = $HEX_SCALED_HEIGHT / 2;
		$servername = "localhost";
		$username = "DungeonsAndDrago";
		$password = "9616347";
		$dbname = "DungeonsAndDragons";
        $this->view->render('dashboard/index');
    }
}
