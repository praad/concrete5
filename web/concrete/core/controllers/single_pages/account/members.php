<?
defined('C5_EXECUTE') or die("Access Denied.");
class Concrete5_Controller_Page_Account_Members extends PageController {
	
	public function view() {
		$this->redirect('/account/members/directory');
	}

}