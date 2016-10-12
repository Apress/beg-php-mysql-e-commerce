<?php
// Class that supports the checkout page
class CheckoutInfo
{
  // Public attributes
  public $mCartItems;
  public $mTotalAmount;
  public $mCreditCardNote;
  public $mOrderButtonVisible;
  public $mNoShippingAddress = 'no';
  public $mNoCreditCard = 'no';
  public $mPlainCreditCard;
  public $mShippingRegion;
  public $mLinkToCheckout;
  public $mLinkToCart;
  public $mLinkToContinueShopping;

  // Class constructor
  public function __construct()
  {
    $this->mLinkToCheckout = Link::ToCheckout();
    $this->mLinkToCart = Link::ToCart();
    $this->mLinkToContinueShopping = $_SESSION['link_to_last_page_loaded'];
  }

  public function init()
  {
    // Set members for use in the Smarty template
    $this->mCartItems = ShoppingCart::GetCartProducts(GET_CART_PRODUCTS);
    $this->mTotalAmount = ShoppingCart::GetTotalAmount();
    $this->mCustomerData = Customer::Get();

    // If the Place Order button was clicked, save the order to database ...
    if(isset ($_POST['place_order']))
    {
      // Create the order and get the order ID
      $order_id = ShoppingCart::CreateOrder();

      // This will contain the PayPal link
      $redirect =
        PAYPAL_URL . '&item_name=TShirtShop Order ' .
        urlencode('#') . $order_id .
        '&item_number=' . $order_id .
        '&amount=' . $this->mTotalAmount .
        '&currency_code=' . PAYPAL_CURRENCY_CODE .
        '&return=' . PAYPAL_RETURN_URL .
        '&cancel_return=' . PAYPAL_CANCEL_RETURN_URL;

      // Redirection to the payment page
      header('Location: ' . $redirect);

      exit();
    }

    // We allow placing orders only if we have complete customer details
    if (empty ($this->mCustomerData['credit_card']))
    {
      $this->mOrderButtonVisible = 'disabled="disabled"';
      $this->mNoCreditCard = 'yes';
    }
    else
    {
      $this->mPlainCreditCard = Customer::DecryptCreditCard(
                                  $this->mCustomerData['credit_card']);

      $this->mCreditCardNote = 'Credit card to use: ' .
                               $this->mPlainCreditCard['card_type'] .
                               '<br />Card number: ' .
                               $this->mPlainCreditCard['card_number_x'];
    }

    if (empty ($this->mCustomerData['address_1']))
    {
      $this->mOrderButtonVisible = 'disabled="disabled"';
      $this->mNoShippingAddress = 'yes';
    }
    else
    {
      $shipping_regions = Customer::GetShippingRegions();

      foreach ($shipping_regions as $item)
        if ($item['shipping_region_id'] ==
            $this->mCustomerData['shipping_region_id'])
          $this->mShippingRegion = $item['shipping_region'];
    }
  }
}
?>
