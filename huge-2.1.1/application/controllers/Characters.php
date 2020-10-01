<?php

/**
 * Class Overview
 * This controller shows the (public) account data of one or all user(s)
 */
class Characters extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    function __construct()
    {
        parent::__construct();
		Auth::handleLogin();
    }

    /**
     * This method controls what happens when you move to /overview/index in your app.
     * Shows a list of all users.
     */
    function index()
    {
        $overview_model = $this->loadModel('Overview');

		if(isset($_COOKIE["Char_id"]))
		{
		$this->view->render('Characters/Character');
		}
		Else
		{
        $this->view->render('Characters/Character_Select');
		}
    }

    /**
     * This method controls what happens when you move to /overview/showuserprofile in your app.
     * Shows the (public) details of the selected user.
     * @param $user_id int id the the user
     */
	 
	 function Character()
    {
		if(isset($_POST["ID"]))
		{
		setcookie("Char_id", $_POST["ID"], NULL, "/","", 0);
		$this->view->render('Characters/Character');
		}
		if(isset($_COOKIE["Char_id"]))
		{
        $this->view->render('Characters/Character');
		}
		Else
		{
        $this->view->render('Characters/index');
		}
    }
	
	function Inventory()
    {
        $this->view->render('Characters/Inventory');
    }
	
	function Item()
    {
        $this->view->render('Characters/Item');
    }
	
	function Character_Select()
    {
        $this->view->render('Characters/Character_Select');
    }
	
	function Race_Feature()
    {
        $this->view->render('Characters/Race_Feature');
    }
	
	function Class_Feature()
    {
        $this->view->render('Characters/Class_Feature');
    }
	
		function Path_Feature()
    {
        $this->view->render('Characters/Path_Feature');
    }
	
	function Equip()
    {
        $this->view->render('Characters/Equip',true);
    }
	
	function Unequip()
    {
        $this->view->render('Characters/Unequip',true);
    }
	
    function showUserProfile($user_id)
    {
        if (isset($user_id)) {
            $overview_model = $this->loadModel('Overview');
            $this->view->user = $overview_model->getUserProfile($user_id);
            $this->view->render('Characters/showuserprofile');
        } else {
            header('location: ' . URL);
        }
    }
}
