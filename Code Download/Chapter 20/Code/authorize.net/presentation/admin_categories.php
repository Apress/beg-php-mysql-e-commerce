<?php
// Class that deals with categories admin
class AdminCategories
{
  // Public variables available in smarty template
  public $mCategoriesCount;
  public $mCategories;
  public $mErrorMessage;
  public $mEditItem;
  public $mDepartmentId;
  public $mDepartmentName;
  public $mLinkToDepartmentsAdmin;
  public $mLinkToDepartmentCategoriesAdmin;

  // Private members
  private $_mAction;
  private $_mActionedCategoryId;

  // Class constructor
  public function __construct()
  {
    if (isset ($_GET['DepartmentId']))
      $this->mDepartmentId = (int)$_GET['DepartmentId'];
    else
      trigger_error('DepartmentId not set');

    $department_details = Catalog::GetDepartmentDetails($this->mDepartmentId);
    $this->mDepartmentName = $department_details['name'];

    foreach ($_POST as $key => $value)
      // If a submit button was clicked ...
      if (substr($key, 0, 6) == 'submit')
      {
        /* Get the position of the last '_' underscore from submit
           button name e.g strtpos('submit_edit_cat_1', '_') is 16 */
        $last_underscore = strrpos($key, '_');

        /* Get the scope of submit button
           (e.g  'edit_cat' from 'submit_edit_cat_1') */
        $this->_mAction = substr($key, strlen('submit_'),
                                $last_underscore - strlen('submit_'));

        /* Get the category id targeted by submit button
           (the number at the end of submit button name)
           e.g '1' from 'submit_edit_cat_1' */
        $this->_mActionedCategoryId = (int)substr($key, $last_underscore + 1);

        break;
      }

    $this->mLinkToDepartmentsAdmin = Link::ToDepartmentsAdmin();

    $this->mLinkToDepartmentCategoriesAdmin =
      Link::ToDepartmentCategoriesAdmin($this->mDepartmentId);
  }

  public function init()
  {
    // If adding a new category ...
    if ($this->_mAction == 'add_cat')
    {
      $category_name = $_POST['category_name'];
      $category_description = $_POST['category_description'];

      if ($category_name == null)
        $this->mErrorMessage = 'Category name is empty';

      if ($this->mErrorMessage == null)
      {
        Catalog::AddCategory($this->mDepartmentId, $category_name,
                             $category_description);

        header('Location: ' .
               htmlspecialchars_decode(
                 $this->mLinkToDepartmentCategoriesAdmin));
      }
    }

    // If editing an existing category ...
    if ($this->_mAction == 'edit_cat')
    {
      $this->mEditItem = $this->_mActionedCategoryId;
    }

    // If updating a category ...
    if ($this->_mAction == 'update_cat')
    {
      $category_name = $_POST['name'];
      $category_description = $_POST['description'];

      if ($category_name == null)
        $this->mErrorMessage = 'Category name is empty';

      if ($this->mErrorMessage == null)
      {
        Catalog::UpdateCategory($this->_mActionedCategoryId, $category_name,
                                $category_description);

        header('Location: ' .
               htmlspecialchars_decode(
                 $this->mLinkToDepartmentCategoriesAdmin));
      }
    }

    // If deleting a category ...
    if ($this->_mAction == 'delete_cat')
    {
      $status = Catalog::DeleteCategory($this->_mActionedCategoryId);

      if ($status < 0)
        $this->mErrorMessage = 'Category not empty';
      else
        header('Location: ' .
               htmlspecialchars_decode(
                 $this->mLinkToDepartmentCategoriesAdmin));
    }

    // If editing category's products ...
    if ($this->_mAction == 'edit_prod')
    {
      header('Location: ' .
             htmlspecialchars_decode(
               Link::ToCategoryProductsAdmin($this->mDepartmentId,
                                             $this->_mActionedCategoryId)));

      exit();
    }

    // Load the list of categories
    $this->mCategories =
      Catalog::GetDepartmentCategories($this->mDepartmentId);
    $this->mCategoriesCount = count($this->mCategories);
  }
}
?>
