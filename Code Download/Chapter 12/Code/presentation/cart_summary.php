<?php
// Class that deals with managing the shopping cart summary
class CartSummary
{
  // Public variables to be used in Smarty template
  public $mTotalAmount;
  public $mItems;
  public $mLinkToCartDetails;
  public $mEmptyCart;

  // Class constructor
  public function __construct()
  {
    /* Calculate the total amount for the shopping cart
       before applicable taxes and/or shipping charges */
    $this->mTotalAmount = ShoppingCart::GetTotalAmount();

    // Get shopping cart products
    $this->mItems = ShoppingCart::GetCartProducts(GET_CART_PRODUCTS);

    if (empty($this->mItems))
      $this->mEmptyCart = true;
    else
      $this->mEmptyCart = false;

    $this->mLinkToCartDetails = Link::ToCart();
  }
}
?>
