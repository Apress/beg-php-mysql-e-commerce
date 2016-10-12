<?php
// Deals with retrieving department details
class Department
{
  // Public variables for the smarty template
  public $mName;
  public $mDescription;
  public $mEditActionTarget;
  public $mEditAction;
  public $mEditButtonCaption;
  public $mShowEditButton;

  // Private members
  private $_mDepartmentId;
  private $_mCategoryId;

  // Class constructor
  public function __construct()
  {
    // We need to have DepartmentId in the query string
    if (isset ($_GET['DepartmentId']))
      $this->_mDepartmentId = (int)$_GET['DepartmentId'];
    else
      trigger_error('DepartmentId not set');

    /* If CategoryId is in the query string we save it 
       (casting it to integer to protect against invalid values) */
    if (isset ($_GET['CategoryId']))
      $this->_mCategoryId = (int)$_GET['CategoryId'];

    // Show Edit button if the user is administrator
    if (!(isset ($_SESSION['admin_logged'])) ||
        $_SESSION['admin_logged'] != true)
      $this->mShowEditButton = false;
    else
      $this->mShowEditButton = true;
  }

  public function init()
  {
    // If visiting a department ...
    $department_details =
      Catalog::GetDepartmentDetails($this->_mDepartmentId);

    $this->mName = $department_details['name'];
    $this->mDescription = $department_details['description'];

    // If visiting a category ...
    if (isset ($this->_mCategoryId))
    {
      $category_details =
        Catalog::GetCategoryDetails($this->_mCategoryId);

      $this->mName = $this->mName . ' &raquo; ' .
                     $category_details['name'];
      $this->mDescription = $category_details['description'];

      $this->mEditActionTarget =
        Link::ToDepartmentCategoriesAdmin($this->_mDepartmentId);
      $this->mEditAction = 'edit_cat_' . $this->_mCategoryId;
      $this->mEditButtonCaption = 'Edit Category Details';
    }
    else
    {
      $this->mEditActionTarget = Link::ToDepartmentsAdmin();
      $this->mEditAction = 'edit_dept_' . $this->_mDepartmentId;
      $this->mEditButtonCaption = 'Edit Department Details';
    }
  }
}
?>
