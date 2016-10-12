<?php
// Manages the departments list
class DepartmentsList
{
  /* Public variables available in departments_list.tpl Smarty template */
  public $mSelectedDepartment = 0;
  public $mDepartments;

  // Constructor reads query string parameter
  public function __construct()
  {
    /* If DepartmentId exists in the query string, we're visiting a
       department */
    if (isset ($_GET['DepartmentId']))
      $this->mSelectedDepartment = (int)$_GET['DepartmentId'];
    elseif (isset($_GET['ProductId']) &&
            isset($_SESSION['link_to_continue_shopping']))
    {
      $continue_shopping =
        Link::QueryStringToArray($_SESSION['link_to_continue_shopping']);

      if (array_key_exists('DepartmentId', $continue_shopping))
        $this->mSelectedDepartment =
          (int)$continue_shopping['DepartmentId'];

    }
  }

  /* Calls business tier method to read departments list and create
     their links */
  public function init()
  {
    // Get the list of departments from the business tier
    $this->mDepartments = Catalog::GetDepartments();

    // Create the department links
    for ($i = 0; $i < count($this->mDepartments); $i++)
      $this->mDepartments[$i]['link_to_department'] =
        Link::ToDepartment($this->mDepartments[$i]['department_id']);
  }
}
?>
