{* checkout_info.tpl *}
{load_presentation_object filename="checkout_info" assign="obj"}
<form method="post" action="{$obj->mLinkToCheckout}">
  <h2>Your order consists of the following items:</h2>
  <table class="tss-table">
    <tr>
      <th>Product Name</th>
      <th>Price</th>
      <th>Quantity</th>
      <th>Subtotal</th>
    </tr>
  {section name=i loop=$obj->mCartItems}
    <tr>
      <td>{$obj->mCartItems[i].name} ({$obj->mCartItems[i].attributes})</td>
      <td>{$obj->mCartItems[i].price}</td>
      <td>{$obj->mCartItems[i].quantity}</td>
      <td>{$obj->mCartItems[i].subtotal}</td>
    </tr>
  {/section}
  </table>
  <p>Total amount: <font class="price">${$obj->mTotalAmount}</font></p>
  {if $obj->mNoCreditCard == 'yes'}
  <p class="error">No credit card details stored.</p>
  {else}
  <p>{$obj->mCreditCardNote}</p>
  {/if}
  {if $obj->mNoShippingAddress == 'yes'}
  <p class="error">Shipping address required to place order.</p>
  {else}
  <p>
    Shipping address: <br />
    &nbsp;{$obj->mCustomerData.address_1}<br />
    {if $obj->mCustomerData.address_2}
      &nbsp;{$obj->mCustomerData.address_2}<br />
    {/if}
    &nbsp;{$obj->mCustomerData.city}<br />
    &nbsp;{$obj->mCustomerData.region}<br />
    &nbsp;{$obj->mCustomerData.postal_code}<br />
    &nbsp;{$obj->mCustomerData.country}<br /><br />
    Shipping region: {$obj->mShippingRegion}
  </p>
  {/if}
  {if $obj->mNoCreditCard!= 'yes' && $obj->mNoShippingAddress != 'yes'}
  <p>
    Shipping type:
    <select name="shipping">
    {section name=i loop=$obj->mShippingInfo}
      <option value="{$obj->mShippingInfo[i].shipping_id}">
        {$obj->mShippingInfo[i].shipping_type}
      </option>
    {/section}
    </select>
  </p>
  {/if}
  <input type="submit" name="place_order" value="Place Order"
   {$obj->mOrderButtonVisible} /> |
  <a href="{$obj->mLinkToCart}">Edit Shopping Cart</a> |
  <a href="{$obj->mLinkToContinueShopping}">Continue Shopping</a>
</form>
