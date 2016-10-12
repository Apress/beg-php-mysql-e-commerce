<?php
// Business tier class for the shopping cart
class ShoppingCart
{
  // Stores the visitor's Cart ID
  private static $_mCartId;

  // Private constructor to prevent direct creation of object
  private function __construct()
  {
  }

  /* This will be called by GetCartId to ensure we have the
     visitor's cart ID in the visitor's session in case
     $_mCartID has no value set */
  public static function SetCartId()
  {
    // If the cart ID hasn't already been set ...
    if (self::$_mCartId == '')
    {
      // If the visitor's cart ID is in the session, get it from there
      if (isset ($_SESSION['cart_id']))
      {
        self::$_mCartId = $_SESSION['cart_id'];
      }
      // If not, check whether the cart ID was saved as a cookie
      elseif (isset ($_COOKIE['cart_id']))
      {
        // Save the cart ID from the cookie
        self::$_mCartId = $_COOKIE['cart_id'];
        $_SESSION['cart_id'] = self::$_mCartId;

        // Regenerate cookie to be valid for 7 days (604800 seconds)
        setcookie('cart_id', self::$_mCartId, time() + 604800);
      }
      else
      {
        /* Generate cart id and save it to the $_mCartId class member,
           the session and a cookie (on subsequent requests $_mCartId
           will be populated from the session) */
        self::$_mCartId = md5(uniqid(rand(), true));

        // Store cart id in session
        $_SESSION['cart_id'] = self::$_mCartId;

        // Cookie will be valid for 7 days (604800 seconds)
        setcookie('cart_id', self::$_mCartId, time() + 604800);
      }
    }
  }

  // Returns the current visitor's card id
  public static function GetCartId()
  {
    // Ensure we have a cart id for the current visitor
    if (!isset (self::$_mCartId))
      self::SetCartId();

    return self::$_mCartId;
  }

  // Adds product to the shopping cart
  public static function AddProduct($productId, $attributes)
  {
    // Build SQL query
    $sql = 'CALL shopping_cart_add_product(
                   :cart_id, :product_id, :attributes)';

    /* Build the parameters array and here is the call to md5()
       that will create the value of item_id */
    $params = array (':cart_id' => self::GetCartId(),
                     ':product_id' => $productId,
                     ':attributes' => $attributes);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Updates the shopping cart with new product quantities
  public static function Update($itemId, $quantity)
  {
    // Build SQL query
    $sql = 'CALL shopping_cart_update(:item_id, :quantity)';

    // Build the parameters array
    $params = array (':item_id' => $itemId,
                     ':quantity' => $quantity);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Removes product from shopping cart
  public static function RemoveProduct($itemId)
  {
    // Build SQL query
    $sql = 'CALL shopping_cart_remove_product(:item_id)';

    // Build the parameters array
    $params = array (':item_id' => $itemId);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Gets shopping cart products
  public static function GetCartProducts($cartProductsType)
  {
    $sql = '';
    // If retrieving "active" shopping cart products ...
    if ($cartProductsType == GET_CART_PRODUCTS)
    {
      // Build SQL query
      $sql = 'CALL shopping_cart_get_products(:cart_id)';
    }
    // If retrieving products saved for later ...
    elseif ($cartProductsType == GET_CART_SAVED_PRODUCTS)
    {
      // Build SQL query
      $sql = 'CALL shopping_cart_get_saved_products(:cart_id)';
    }
    else
      trigger_error($cartProductsType. ' value unknown', E_USER_ERROR);

    // Build the parameters array
    $params = array (':cart_id' => self::GetCartId());

    // Execute the query and return the results
    return DatabaseHandler::GetAll($sql, $params);
  }

  /* Gets total amount of shopping cart products before tax and/or
     shipping charges (not including the ones that are being
     saved for later) */
  public static function GetTotalAmount()
  {
    // Build SQL query
    $sql = 'CALL shopping_cart_get_total_amount(:cart_id)';

    // Build the parameters array
    $params = array (':cart_id' => self::GetCartId());

    // Execute the query and return the results
    return DatabaseHandler::GetOne($sql, $params);
  }

  // Save product to the Save for Later list
  public static function SaveProductForLater($itemId)
  {
    // Build SQL query
    $sql = 'CALL shopping_cart_save_product_for_later(:item_id)';

    // Build the parameters array
    $params = array (':item_id' => $itemId);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Get product from the Save for Later list back to the cart
  public static function MoveProductToCart($itemId)
  {
    // Build SQL query
    $sql = 'CALL shopping_cart_move_product_to_cart(:item_id)';

    // Build the parameters array
    $params = array (':item_id' => $itemId);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Count old shopping carts
  public static function CountOldShoppingCarts($days)
  {
    // Build SQL query
    $sql = 'CALL shopping_cart_count_old_carts(:days)';

    // Build the parameters array
    $params = array (':days' => $days);

    // Execute the query and return the results
    return DatabaseHandler::GetOne($sql, $params);
  }

  // Deletes old shopping carts
  public static function DeleteOldShoppingCarts($days)
  {
    // Build SQL query
    $sql = 'CALL shopping_cart_delete_old_carts(:days)';

    // Build the parameters array
    $params = array (':days' => $days);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Create a new order
  public static function CreateOrder($customerId, $shippingId, $taxId)
  {
    // Build SQL query
    $sql = 'CALL shopping_cart_create_order(:cart_id, :customer_id,
                   :shipping_id, :tax_id)';

    // Build the parameters array
    $params = array (':cart_id' => self::GetCartId(),
                     ':customer_id' => $customerId,
                     ':shipping_id' => $shippingId,
                     ':tax_id' => $taxId);

    // Execute the query and return the results
    return DatabaseHandler::GetOne($sql, $params);
  }

  // Get product recommendations for the shopping cart
  public static function GetRecommendations()
  {
    // Build the SQL query
    $sql = 'CALL shopping_cart_get_recommendations(
                   :cart_id, :short_product_description_length)';

    // Build the parameters array
    $params = array (':cart_id' => self::GetCartId(),
                     ':short_product_description_length' =>
                       SHORT_PRODUCT_DESCRIPTION_LENGTH);

    // Execute the query and return the results
    return DatabaseHandler::GetAll($sql, $params);
  }
}
?>
