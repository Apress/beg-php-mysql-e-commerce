<?php
class StoreFront
{
  public $mSiteUrl;
  // Define the template file for the page contents
  public $mContentsCell = 'first_page_contents.tpl';
  // Define the template file for the categories cell
  public $mCategoriesCell = 'blank.tpl';
  // Define the template file for the cart summary cell
  public $mCartSummaryCell = 'blank.tpl';
  // Page title
  public $mPageTitle;
  // PayPal continue shopping link
  public $mPayPalContinueShoppingLink;

  // Class constructor
  public function __construct()
  {
    $this->mSiteUrl = Link::Build('');
  }

  // Initialize presentation object
  public function init()
  {
    $_SESSION['link_to_store_front'] =
      Link::Build(str_replace(VIRTUAL_LOCATION, '', getenv('REQUEST_URI')));

    // Build the "continue shopping" link
    if (!isset ($_GET['CartAction']))
      $_SESSION['link_to_last_page_loaded'] = $_SESSION['link_to_store_front'];

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

    // Load search result page if we're searching the catalog
    elseif (isset ($_GET['SearchResults']))
      $this->mContentsCell = 'search_results.tpl';

    // Load shopping cart or cart summary template
    if (isset ($_GET['CartAction']))
      $this->mContentsCell = 'cart_details.tpl';
    else
      $this->mCartSummaryCell = 'cart_summary.tpl';

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
    elseif (isset ($_GET['SearchResults']))
    {
      $page_title  = 'TShirtShop: "';

      // Display the search string
      $page_title .= trim(str_replace('-', ' ', $_GET['SearchString'])) . '" (';

      // Display "all-words search " or "any-words search"
      $all_words = isset ($_GET['AllWords']) ? $_GET['AllWords'] : 'off';

      $page_title .= (($all_words == 'on') ? 'all' : 'any') . '-words search';

      // Display page number
      if (isset ($_GET['Page']) && ((int)$_GET['Page']) > 1)
        $page_title .= ', page ' . ((int)$_GET['Page']);

      $page_title .= ')';
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
