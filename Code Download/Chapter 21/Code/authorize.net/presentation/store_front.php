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
  // Define the template file for the login or logged cell
  public $mLoginOrLoggedCell = 'customer_login.tpl';
  // Controls the visibility of the shop navigation (departments, etc)
  public $mHideBoxes = false;
  // Page title
  public $mPageTitle;
  // PayPal continue shopping link
  public $mPayPalContinueShoppingLink;

  // Class constructor
  public function __construct()
  {
    $is_https = false;

    // Is the page being accessed through an HTTPS connection?
    if (getenv('HTTPS') == 'on')
      $is_https = true;

    // Use HTTPS when accessing sensitive pages
    if ($this->_IsSensitivePage() && $is_https == false &&
        USE_SSL != 'no')
    {
      $redirect_to =
        Link::Build(str_replace(VIRTUAL_LOCATION, '', getenv('REQUEST_URI')),
                    'https');

      header ('Location: '. $redirect_to);

      exit();
    }

    // Don't use HTTPS for nonsensitive pages
    if (!$this->_IsSensitivePage() && $is_https == true)
    {
      $redirect_to =
        Link::Build(str_replace(VIRTUAL_LOCATION, '', getenv('REQUEST_URI')));

      header ('Location: '. $redirect_to);

      exit();
    }

    $this->mSiteUrl = Link::Build('');
  }

  // Initialize presentation object
  public function init()
  {
    $_SESSION['link_to_store_front'] =
      Link::Build(str_replace(VIRTUAL_LOCATION, '', getenv('REQUEST_URI')));

    // Build the "continue shopping" link
    if (!isset ($_GET['CartAction']) && !isset($_GET['Logout']) &&
        !isset($_GET['RegisterCustomer']) &&
        !isset($_GET['AddressDetails']) &&
        !isset($_GET['CreditCardDetails']) &&
        !isset($_GET['AccountDetails']) &&
        !isset($_GET['Checkout']))
      $_SESSION['link_to_last_page_loaded'] = $_SESSION['link_to_store_front'];

    // Build the "cancel" link for customer details pages
    if (!isset($_GET['Logout']) &&
        !isset($_GET['RegisterCustomer']) &&
        !isset($_GET['AddressDetails']) &&
        !isset($_GET['CreditCardDetails']) &&
        !isset($_GET['AccountDetails']))
      $_SESSION['customer_cancel_link'] = $_SESSION['link_to_store_front'];

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

    if (Customer::IsAuthenticated())
      $this->mLoginOrLoggedCell = 'customer_logged.tpl';

    if (isset ($_GET['RegisterCustomer']) ||
        isset ($_GET['AccountDetails']))
      $this->mContentsCell = 'customer_details.tpl';
    elseif (isset ($_GET['AddressDetails'])) 
      $this->mContentsCell = 'customer_address.tpl';
    elseif (isset ($_GET['CreditCardDetails']))
      $this->mContentsCell = 'customer_credit_card.tpl';

    if (isset ($_GET['Checkout']))
    {
      if (Customer::IsAuthenticated())
        $this->mContentsCell = 'checkout_info.tpl';
      else
        $this->mContentsCell = 'checkout_not_logged.tpl';

      $this->mHideBoxes = true;
    }

    if (isset($_GET['OrderDone']))
      $this->mContentsCell = 'order_done.tpl';
    elseif (isset($_GET['OrderError']))
      $this->mContentsCell = 'order_error.tpl';

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

  // Visiting a sensitive page?
  private function _IsSensitivePage()
  {
    if (isset($_GET['RegisterCustomer']) ||
        isset($_GET['AccountDetails']) ||
        isset($_GET['CreditCardDetails']) ||
        isset($_GET['AddressDetails']) ||
        isset($_GET['Checkout']) ||
        isset($_POST['Login']))
      return true;

    return false;
  }
}
?>
