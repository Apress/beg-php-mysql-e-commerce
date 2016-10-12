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
    // Create "Continue Shopping" link for the PayPal shopping cart
    if (!isset ($_GET['AddProduct']))
    {
      /* Store the current request needed for the paypal
         continue shopping functionality */
      $_SESSION['paypal_continue_shopping'] =
        Link::Build(str_replace(VIRTUAL_LOCATION, '',
                                $_SERVER['REQUEST_URI']));

      $this->mPayPalContinueShoppingLink =
        $_SESSION['paypal_continue_shopping'];
    }
    // If Add to Cart was clicked, prepare PayPal variables
    else
    {
      // Clean output buffer
      ob_clean();

      $product_id = 0;

      // Get the product ID to be added to cart
      if (isset ($_GET['AddProduct']))
        $product_id = (int)$_GET['AddProduct'];
      else
        trigger_error('AddProduct not set');

      $selected_attribute_groups = array ();
      $selected_attribute_values = array ();

      // Get selected product attributes if any
      foreach ($_POST as $key => $value)
      {
        // If there are fields starting with "attr_" in the POST array
        if (substr($key, 0, 5) == 'attr_')
        {
          // Get the selected attribute name and value
          $selected_attribute_groups[] = substr($key, strlen('attr_'));
          $selected_attribute_values[] = $_POST[$key];
        }
      }

      // Get product info
      $product = Catalog::GetProductDetails($product_id);

      // Build the PayPal url to add the product to cart
      $paypal_url = PAYPAL_URL . '?cmd=_cart&business=' . PAYPAL_EMAIL .
                    '&item_name=' . rawurlencode($product['name']);

      if (count($selected_attribute_groups) > 0)
        $paypal_url .= '&on0=' . implode('/', $selected_attribute_groups) .
                       '&os0=' . implode('/', $selected_attribute_values);

      $paypal_url .=
        '&amount=' . ($product['discounted_price'] == 0 ?
                      $product['price'] : $product['discounted_price']) .
        '&currency_code=' . PAYPAL_CURRENCY_CODE . '&add=1' .
        '&shopping_url=' .
          rawurlencode($_SESSION['paypal_continue_shopping']) .
        '&return=' . rawurlencode(PAYPAL_RETURN_URL) .
        '&cancel_return=' . rawurlencode(PAYPAL_CANCEL_RETURN_URL);

      // Redirect to the PayPal cart page
      header('HTTP/1.1 302 Found');
      header('Location: ' . $paypal_url);

      // Clear the output buffer and stop execution
      flush();
      ob_flush();
      ob_end_clean();
      exit();
    }

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
