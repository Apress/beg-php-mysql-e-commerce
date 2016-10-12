<?php
class StoreAdmin
{
  public $mSiteUrl;
  // Define the template file for the page menu
  public $mMenuCell = 'blank.tpl';
  // Define the template file for the page contents
  public $mContentsCell = 'blank.tpl';

  // Class constructor
  public function __construct()
  {
    $this->mSiteUrl = Link::Build('', 'https');

    // Enforce page to be accessed through HTTPS if USE_SSL is on
    if (USE_SSL == 'yes' && getenv('HTTPS') != 'on')
    {
      header ('Location: https://' . getenv('SERVER_NAME') .
              getenv('REQUEST_URI'));

      exit();
    }
  }

  public function init()
  {
    // If admin is not logged in, load the admin_login template
    if (!(isset ($_SESSION['admin_logged'])) ||
        $_SESSION['admin_logged'] != true)
      $this->mContentsCell = 'admin_login.tpl';
    else
    {
      // If admin is logged in, load the admin menu page
      $this->mMenuCell = 'admin_menu.tpl';

      // If logging out ...
      if (isset ($_GET['Page']) && ($_GET['Page'] == 'Logout'))
      {
        unset($_SESSION['admin_logged']);
        header('Location: ' . Link::ToAdmin());
  
        exit();
      }

      // If Page is not explicitly set, assume the Departments page
      $admin_page = isset ($_GET['Page']) ? $_GET['Page'] : 'Departments';

      // Choose what admin page to load ...
      if ($admin_page == 'Departments')
        $this->mContentsCell = 'admin_departments.tpl';
      elseif ($admin_page == 'Categories')
        $this->mContentsCell = 'admin_categories.tpl';
    }
  }
}
?>
