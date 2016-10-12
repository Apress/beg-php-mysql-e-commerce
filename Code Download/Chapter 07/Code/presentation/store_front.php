<?php
class StoreFront
{
  public $mSiteUrl;
  // Define the template file for the page contents
  public $mContentsCell = 'first_page_contents.tpl';
  // Define the template file for the categories cell
  public $mCategoriesCell = 'blank.tpl';
  // Page title
  public $mPageTitle;

  // Class constructor
  public function __construct()
  {
    $this->mSiteUrl = Link::Build('');
  }

  // Initialize presentation object
  public function init()
  {
    // Load department details if visiting a department
    if (isset ($_GET['DepartmentId']))
    {
      $this->mContentsCell = 'department.tpl';
      $this->mCategoriesCell = 'categories_list.tpl';
    }
    elseif (isset($_GET['ProductId']) && 
            isset($_SESSION['link_to_continue_shopping']) &&
            strpos($_SESSION['link_to_continue_shopping'], 'DepartmentId', 0)
            !== false)
    {
      $this->mCategoriesCell = 'categories_list.tpl';
    }

    // Load product details page if visiting a product
    if (isset ($_GET['ProductId']))
      $this->mContentsCell = 'product.tpl';

    // Load the page title
    $this->mPageTitle = $this->_GetPageTitle();
  }

  // Returns the page title
  private function _GetPageTitle()
  {
    $page_title = 'TShirtShop: ' .
      'Demo Product Catalog from Beginning PHP and MySQL E-Commerce';

    if (isset ($_GET['DepartmentId']) && isset ($_GET['CategoryId']))
    {
      $page_title = 'TShirtShop: ' .
        Catalog::GetDepartmentName($_GET['DepartmentId']) . ' - ' .
        Catalog::GetCategoryName($_GET['CategoryId']);

      if (isset ($_GET['Page']) && ((int)$_GET['Page']) > 1)
        $page_title .= ' - Page ' . ((int)$_GET['Page']);
    }
    elseif (isset ($_GET['DepartmentId']))
    {
      $page_title = 'TShirtShop: ' .
        Catalog::GetDepartmentName($_GET['DepartmentId']);

      if (isset ($_GET['Page']) && ((int)$_GET['Page']) > 1)
        $page_title .= ' - Page ' . ((int)$_GET['Page']);
    }
    elseif (isset ($_GET['ProductId']))
    {
      $page_title = 'TShirtShop: ' .
        Catalog::GetProductName($_GET['ProductId']);
    }
    else
    {
      if (isset ($_GET['Page']) && ((int)$_GET['Page']) > 1)
        $page_title .= ' - Page ' . ((int)$_GET['Page']);
    }

    return $page_title;
  }
}
?>
