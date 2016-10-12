<?php
// Class that supports departments admin functionality
class AdminDepartments
{
  // Public variables available in smarty template
  public $mDepartmentsCount;
  public $mDepartments;
  public $mErrorMessage;
  public $mEditItem;
  public $mLinkToDepartmentsAdmin;

 // Private members
  private $_mAction;
  private $_mActionedDepartmentId;

  // Class constructor
  public function __construct()
  {
    // Parse the list with posted variables
    foreach ($_POST as $key => $value)
      // If a submit button was clicked ...
      if (substr($key, 0, 6) == 'submit')
      {
        /* Get the position of the last '_' underscore from submit
           button name e.g strtpos('submit_edit_dept_1', '_') is 17 */
        $last_underscore = strrpos($key, '_');

        /* Get the scope of submit button
           (e.g  'edit_dep' from 'submit_edit_dept_1') */
        $this->_mAction = substr($key, strlen('submit_'),
                                 $last_underscore - strlen('submit_'));

        /* Get the department id targeted by submit button
           (the number at the end of submit button name)
           e.g '1' from 'submit_edit_dept_1' */
        $this->_mActionedDepartmentId = substr($key, $last_underscore + 1);

        break;
      }

    $this->mLinkToDepartmentsAdmin = Link::ToDepartmentsAdmin();
  }

  public function init()
  {
    // If adding a new department ...
    if ($this->_mAction == 'add_dept')
    {
      $department_name = $_POST['department_name'];
      $department_description = $_POST['department_description'];

      if ($department_name == null)
        $this->mErrorMessage = 'Department name required';

      if ($this->mErrorMessage == null)
      {
        Catalog::AddDepartment($department_name, $department_description);

        header('Location: ' . $this->mLinkToDepartmentsAdmin);
      }
    }

    // If editing an existing department ...
    if ($this->_mAction == 'edit_dept')
      $this->mEditItem = $this->_mActionedDepartmentId;

    // If updating a department ...
    if ($this->_mAction == 'update_dept')
    {
      $department_name = $_POST['name'];
      $department_description = $_POST['description'];

      if ($department_name == null)
        $this->mErrorMessage = 'Department name required';

      if ($this->mErrorMessage == null)
      {
        Catalog::UpdateDepartment($this->_mActionedDepartmentId,
                                  $department_name, $department_description);

        header('Location: ' . $this->mLinkToDepartmentsAdmin);
      }
    }

    // If deleting a department ...
    if ($this->_mAction == 'delete_dept')
    {
      $status = Catalog::DeleteDepartment($this->_mActionedDepartmentId);

      if ($status < 0)
        $this->mErrorMessage = 'Department not empty';
      else
        header('Location: ' . $this->mLinkToDepartmentsAdmin);
    }

    // If editing department's categories ...
    if ($this->_mAction == 'edit_cat')
    {
      header('Location: ' .
             htmlspecialchars_decode(
               Link::ToDepartmentCategoriesAdmin(
                 $this->_mActionedDepartmentId)));

      exit();
    }

    // Load the list of departments
    $this->mDepartments = Catalog::GetDepartmentsWithDescriptions();
    $this->mDepartmentsCount = count($this->mDepartments);
  }
}
?>
