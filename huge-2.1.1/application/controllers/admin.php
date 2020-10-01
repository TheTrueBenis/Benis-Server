<?php

/**
 * Class Hex_Map
 * This is a demo controller that simply shows an area that is only visible for the logged in user
 * because of Auth::handleLogin(); in line 19.
 */
class admin extends Controller
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
        $this->view->render('admin/index');
    }
	function users()
    {
		$admin_model = $this->loadModel('Admin');
        $this->view->users = $admin_model->getAllUsersProfiles();
        $this->view->render('admin/users');
    }
	function showUserProfile($user_id)
    {
        if (isset($user_id)) {
            $admin_model = $this->loadModel('Admin');
            $this->view->user = $admin_model->getUserProfile($user_id);
            $this->view->render('admin/showuserprofile');
        } else {
            header('location: ' . URL);
        }
    }
	function characters()
    {
        $admin_model = $this->loadModel('Admin');
        $this->view->characters = $admin_model->getAllCharacters();
        $this->view->render('admin/characters');
    }
}
