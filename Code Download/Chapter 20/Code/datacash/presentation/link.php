<?php
class Link
{
  public static function Build($link, $type = 'http')
  {
    $base = (($type == 'http' || USE_SSL == 'no') ? 'http://' : 'https://') .
            getenv('SERVER_NAME');

    // If HTTP_SERVER_PORT is defined and different than default
    if (defined('HTTP_SERVER_PORT') && HTTP_SERVER_PORT != '80' &&
        strpos($base, 'https') === false)
    {
      // Append server port
      $base .= ':' . HTTP_SERVER_PORT;
    }

    $link = $base . VIRTUAL_LOCATION . $link;

    // Escape html
    return htmlspecialchars($link, ENT_QUOTES);
  }

  public static function ToDepartment($departmentId, $page = 1)
  {
    $link = self::CleanUrlText(Catalog::GetDepartmentName($departmentId)) .
            '-d' . $departmentId . '/';

    if ($page > 1)
      $link .= 'page-' . $page . '/';

    return self::Build($link);
  }

  public static function ToCategory($departmentId, $categoryId, $page = 1)
  {
    $link = self::CleanUrlText(Catalog::GetDepartmentName($departmentId)) .
            '-d' . $departmentId . '/' .
            self::CleanUrlText(Catalog::GetCategoryName($categoryId)) .
            '-c' . $categoryId . '/';

    if ($page > 1)
      $link .= 'page-' . $page . '/';

    return self::Build($link);
  }

  public static function ToProduct($productId)
  {
    $link = self::CleanUrlText(Catalog::GetProductName($productId)) .
            '-p' . $productId . '/';

    return self::Build($link);
  }

  public static function ToIndex($page = 1)
  {
    $link = '';

    if ($page > 1)
      $link .= 'page-' . $page . '/';

    return self::Build($link);
  }

  public static function QueryStringToArray($queryString)
  {
    $result = array();

    if ($queryString != '')
    {
      $elements = explode('&', $queryString);

      foreach($elements as $key => $value)
      {
        $element = explode('=', $value);
        $result[urldecode($element[0])] =
          isset($element[1]) ? urldecode($element[1]) : '';
      }
    }

    return $result;
  }

  // Prepares a string to be included in an URL
  public static function CleanUrlText($string)
  {
    // Remove all characters that aren't a-z, 0-9, dash, underscore or space
    $not_acceptable_characters_regex = '#[^-a-zA-Z0-9_ ]#';
    $string = preg_replace($not_acceptable_characters_regex, '', $string);

    // Remove all leading and trailing spaces
    $string = trim($string);

    // Change all dashes, underscores and spaces to dashes
    $string = preg_replace('#[-_ ]+#', '-', $string);

    // Return the modified string
    return strtolower($string);
  }

  // Redirects to proper URL if not already there
  public static function CheckRequest()
  {
    $proper_url = '';

    if (isset ($_GET['Search']) || isset($_GET['SearchResults']) ||
        isset ($_GET['CartAction']) || isset ($_GET['AjaxRequest']) ||
        isset ($_POST['Login']) || isset ($_GET['Logout']) ||
        isset ($_GET['RegisterCustomer']) ||
        isset ($_GET['AddressDetails']) ||
        isset ($_GET['CreditCardDetails']) ||
        isset ($_GET['AccountDetails']) || isset ($_GET['Checkout']) ||
        isset ($_GET['OrderDone']) || isset ($_GET['OrderError']))
    {
      return ;
    }
    // Obtain proper URL for category pages
    elseif (isset ($_GET['DepartmentId']) && isset ($_GET['CategoryId']))
    {
      if (isset ($_GET['Page']))
        $proper_url = self::ToCategory($_GET['DepartmentId'],
                        $_GET['CategoryId'], $_GET['Page']);
      else
        $proper_url = self::ToCategory($_GET['DepartmentId'],
                                       $_GET['CategoryId']);
    }
    // Obtain proper URL for department pages
    elseif (isset ($_GET['DepartmentId']))
    {
      if (isset ($_GET['Page']))
        $proper_url = self::ToDepartment($_GET['DepartmentId'],
                                         $_GET['Page']);
      else
        $proper_url = self::ToDepartment($_GET['DepartmentId']);
    }
    // Obtain proper URL for product pages
    elseif (isset ($_GET['ProductId']))
    {
      $proper_url = self::ToProduct($_GET['ProductId']);
    }
    // Obtain proper URL for the home page
    else
    {
       if (isset($_GET['Page']))
         $proper_url = self::ToIndex($_GET['Page']);
       else
         $proper_url = self::ToIndex();
    }

    /* Remove the virtual location from the requested URL
       so we can compare paths */
    $requested_url = self::Build(str_replace(VIRTUAL_LOCATION, '',
                                             $_SERVER['REQUEST_URI']));

    // 404 redirect if the requested product, category or department doesn’t exist
    if (strstr($proper_url, '/-'))
    {
      // Clean output buffer
      ob_clean();

      // Load the 404 page
      include '404.php';

      // Clear the output buffer and stop execution
      flush(); 
      ob_flush(); 
      ob_end_clean(); 
      exit();
    }

    // 301 redirect to the proper URL if necessary
    if ($requested_url != $proper_url)
    {
      // Clean output buffer
      ob_clean();

      // Redirect 301 
      header('HTTP/1.1 301 Moved Permanently');
      header('Location: ' . $proper_url);

      // Clear the output buffer and stop execution
      flush();
      ob_flush();
      ob_end_clean();
      exit();
    }
  }

  // Create link to the search page
  public static function ToSearch()
  {
    return self::Build('index.php?Search');
  }

  // Create link to a search results page
  public static function ToSearchResults($searchString, $allWords,
                                         $page = 1)
  {
    $link = 'search-results/find';

    if (empty($searchString))
      $link .= '/';
    else
      $link .= '-' . self::CleanUrlText($searchString) . '/';

    $link .= 'all-words-' . $allWords . '/';

    if ($page > 1)
      $link .= 'page-' . $page . '/';

    return self::Build($link);
  }

  // Create an Add to Cart link
  public static function ToAddProduct($productId)
  {
    return self::Build('index.php?AddProduct=' . $productId);
  }

  // Create link to admin page
  public static function ToAdmin($params = '')
  {
    $link = 'admin.php';

    if ($params != '')
      $link .= '?' . $params;

    return self::Build($link, 'https');
  }

  // Create logout link
  public static function ToLogout()
  {
    return self::ToAdmin('Page=Logout');
  }

  // Create link to the departments administration page
  public static function ToDepartmentsAdmin()
  {
    return self::ToAdmin('Page=Departments');
  }

  // Create link to the categories administration page
  public static function ToDepartmentCategoriesAdmin($departmentId)
  {
    $link = 'Page=Categories&DepartmentId=' . $departmentId;

    return self::ToAdmin($link);
  }

  // Create link to the attributes administration page
  public static function ToAttributesAdmin()
  {
    return self::ToAdmin('Page=Attributes');
  }

  // Create link to the attribute values administration page
  public static function ToAttributeValuesAdmin($attributeId)
  {
    $link = 'Page=AttributeValues&AttributeId=' . $attributeId;

    return self::ToAdmin($link);
  }

  // Create link to a products administration page
  public static function ToCategoryProductsAdmin($departmentId, $categoryId)
  {
    $link = 'Page=Products&DepartmentId=' . $departmentId .
            '&CategoryId=' . $categoryId;
  
    return self::ToAdmin($link);
  }

  // Create link to product details administration page
  public static function ToProductAdmin($departmentId, $categoryId, $productId)
  {
    $link = 'Page=ProductDetails&DepartmentId=' . $departmentId .
            '&CategoryId=' . $categoryId . '&ProductId=' . $productId;

    return self::ToAdmin($link);
  }

  // Create a shopping cart link
  public static function ToCart($action = 0, $target = null)
  {
    $link = '';

    switch ($action)
    {
      case ADD_PRODUCT:
        $link = 'index.php?CartAction=' . ADD_PRODUCT . '&ItemId=' . $target;
        break;
      case REMOVE_PRODUCT:
        $link = 'index.php?CartAction=' .
                REMOVE_PRODUCT . '&ItemId=' . $target;
        break;
      case UPDATE_PRODUCTS_QUANTITIES:
        $link = 'index.php?CartAction=' . UPDATE_PRODUCTS_QUANTITIES;
        break;
      case SAVE_PRODUCT_FOR_LATER:
        $link = 'index.php?CartAction=' .
                SAVE_PRODUCT_FOR_LATER . '&ItemId=' . $target;
        break;
      case MOVE_PRODUCT_TO_CART:
        $link = 'index.php?CartAction=' .
                MOVE_PRODUCT_TO_CART . '&ItemId=' . $target;
        break;
      default:
        $link = 'cart-details/';
    }

    return self::Build($link);
  }

  // Create link to shopping carts administration page
  public static function ToCartsAdmin()
  {
    return self::ToAdmin('Page=Carts');
  }

  // Create link to orders administration page
  public static function ToOrdersAdmin()
  {
    return self::ToAdmin('Page=Orders');
  }

  // Create link to the order details administration page
  public static function ToOrderDetailsAdmin($orderId)
  {
    $link = 'Page=OrderDetails&OrderId=' . $orderId;

    return self::ToAdmin($link);
  }

  // Creates a link to the register customer page
  public static function ToRegisterCustomer()
  {
    return self::Build('register-customer/', 'https');
  }

  // Creates a link to the update customer account details page
  public static function ToAccountDetails()
  {
    return self::Build('account-details/', 'https');
  }

  // Creates a link to the update customer credit card details page
  public static function ToCreditCardDetails()
  {
    return self::Build('credit-card-details/', 'https');
  }

  // Creates a link to the update customer address details page
  public static function ToAddressDetails()
  {
    return self::Build('address-details/', 'https');
  }

  // Creates a link to the checkout page
  public static function ToCheckout()
  {
    return self::Build('checkout/', 'https');
  }

  // Creates a link to the order done page
  public static function ToOrderDone()
  {
    return self::Build('order-done/');
  }

  // Creates a link to the order error page
  public static function ToOrderError()
  {
    return self::Build('order-error/');
  }
}
?>
